<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Shengfai\LaravelAdmin\Traits\Response;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;
use Symfony\Component\HttpFoundation\File\File as SFile;

/**
 * Handles all requests related to managing the data models.
 * Class AdminController
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年6月17日
 */
class AdminController extends Controller
{

    /**
     * The main view for any of the data models.
     *
     * @return Response
     */
    public function index($modelName)
    {
        // 列表排序默认处理
        if ($this->request->action === 'resort') {
            $config = app('itemconfig');
            return $this->resort($config->getDataModel());
        }
        
        // set the layout content and title
        return view('admin::grid');
    }

    /**
     * Gets the item create page / information.
     *
     * @param string $modelName
     */
    public function create(string $modelName)
    {
        return view('admin::form');
    }

    /**
     * Gets the item edit page / information.
     *
     * @param string $modelName
     * @param mixed $itemId
     */
    public function item($modelName, $itemId = 0)
    {
        $config = app('itemconfig');
        $fieldFactory = app('admin.field_factory');
        $actionFactory = app('admin.action_factory');
        $columnFactory = app('admin.column_factory');
        $actionPermissions = $actionFactory->getActionPermissions();
        $fields = $fieldFactory->getEditFields();
        
        // try to get the object
        $model = $config->getModel($itemId, $fields, $columnFactory->getIncludedColumns($fields));
        
        if ($model->exists) {
            $model = $config->updateModel($model, $fieldFactory, $actionFactory);
        }
        
        if ($this->request->acceptsHtml()) {
            $view = $this->request->action === 'edit' ? 'admin::form' : 'admin::detail';
            return view($view, [
                'itemId' => $itemId,
                'model' => $model
            ]);
        }
        
        // if it's ajax, we just return the item information as json
        $response = $actionPermissions['view'] ? response()->json($model) : response()->json([
            'success' => false,
            'errors' => 'You do not have permission to view this item'
        ]);
        
        // set the Vary : Accept header to avoid the browser caching the json response
        return $response->header('Vary', 'Accept');
    }

    /**
     * POST save method that accepts data via JSON POST and either saves an old item (if id is valid) or creates a new
     * one.
     *
     * @param string $modelName
     * @param int $id
     *
     * @return JSON
     */
    public function save($modelName, $id = false)
    {
        try {
            $config = app('itemconfig');
            
            $fieldFactory = app('admin.field_factory');
            $actionFactory = app('admin.action_factory');
            
            if (array_key_exists('form_request', $config->getOptions()) && $this->formRequestErrors !== null) {
                return response()->json([
                    'success' => false,
                    'errors' => $this->formRequestErrors
                ]);
            }
            
            if ($this->request->has('field')) {
                $request = $this->request;
                $request->offsetSet($request->field, $request->value);
                $save = $config->save($request, Arr::only($fieldFactory->getEditFields(), [
                    $this->request->field
                ]), $actionFactory->getActionPermissions(), $id);
            } else {
                $save = $config->save($this->request, $fieldFactory->getEditFields(), $actionFactory->getActionPermissions(), $id);
            }
            
            $columnFactory = app('admin.column_factory');
            $fields = $fieldFactory->getEditFields();
            $model = $config->getModel($id, $fields, $columnFactory->getIncludedColumns($fields));
            ActivityHandler::console()->performedOn($model)->log($id ? '更新' : '创建' . $config->getOption('single'));
            
            return $this->success();
        
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * POST delete method that accepts data via JSON POST and either saves an old.
     *
     * @param string $modelName
     *
     * @return JSON
     */
    public function delete($modelName)
    {
        $config = app('itemconfig');
        $actionFactory = app('admin.action_factory');
        $baseModel = $config->getDataModel();
        $errorResponse = [
            'success' => false,
            'error' => 'There was an error perform batch deletion. Please reload the page and try again.'
        ];
        
        // if don't have permission, send back request
        $permissions = $actionFactory->getActionPermissions();
        if (!$permissions['delete']) {
            return response()->json($errorResponse);
        }
        
        // request ids: 1,3,5
        $ids = explode(',', $this->request->id ?? $this->request->ids);
        
        // delete the model
        if ($baseModel::whereIn('id', $ids)->delete()) {
            return $this->success('数据删除成功！');
        } else {
            return $this->error('数据删除失败！');
        }
    }

    /**
     * POST method for handling custom model actions.
     *
     * @param string $modelName
     *
     * @return JSON
     */
    public function customModelAction($modelName)
    {
        $config = app('itemconfig');
        $actionFactory = app('admin.action_factory');
        $actionName = $this->request->input('action_name', false);
        $dataTable = app('admin.datatable');
        
        // get the sort options and filters
        $page = $this->request->input('page', 1);
        $sortOptions = $this->request->input('sortOptions', []);
        $filters = $this->request->input('filters', []);
        
        // get the prepared query options
        $prepared = $dataTable->prepareQuery(app('db'), $page, $sortOptions, $filters);
        
        // get the action and perform the custom action
        $action = $actionFactory->getByName($actionName, true);
        $result = $action->perform($prepared['query']);
        
        // if the result is a string, return that as an error.
        if (is_string($result)) {
            return response()->json(array(
                'success' => false,
                'error' => $result
            ));
        } // if it's falsy, return the standard error message
elseif (!$result) {
            $messages = $action->getOption('messages');
            
            return response()->json(array(
                'success' => false,
                'error' => $messages['error']
            ));
        } else {
            $response = array(
                'success' => true
            );
            
            // if it's a download response, flash the response to the session and return the download link
            if (is_a($result, 'Symfony\Component\HttpFoundation\BinaryFileResponse')) {
                $file = $result->getFile()->getRealPath();
                $headers = $result->headers->all();
                $this->session->put('administrator_download_response', array(
                    'file' => $file,
                    'headers' => $headers
                ));
                
                $response['download'] = route('admin.file_download');
            } // if it's a redirect, put the url into the redirect key so that javascript can transfer the user
elseif (is_a($result, '\Illuminate\Http\RedirectResponse')) {
                $response['redirect'] = $result->getTargetUrl();
            }
            
            return response()->json($response);
        }
    }

    /**
     * POST method for handling custom model item actions.
     *
     * @param string $modelName
     * @param int $id
     *
     * @return JSON
     */
    public function customModelItemAction($modelName, $id = null)
    {
        $config = app('itemconfig');
        $actionFactory = app('admin.action_factory');
        $model = $config->getDataModel();
        $model = $model::find($id);
        $actionName = $this->request->input('action_name', false);
        
        // get the action and perform the custom action
        $action = $actionFactory->getByName($actionName);
        $result = $action->perform($model);
        
        // override the config options so that we can get the latest
        app('admin.config_factory')->updateConfigOptions();
        
        // if the result is a string, return that as an error.
        if (is_string($result)) {
            return response()->json(array(
                'success' => false,
                'error' => $result
            ));
        } // if it's falsy, return the standard error message
elseif (!$result) {
            $messages = $action->getOption('messages');
            
            return response()->json(array(
                'success' => false,
                'error' => $messages['error']
            ));
        } else {
            $fieldFactory = app('admin.field_factory');
            $columnFactory = app('admin.column_factory');
            $fields = $fieldFactory->getEditFields();
            $model = $config->getModel($id, $fields, $columnFactory->getIncludedColumns($fields));
            
            if ($model->exists) {
                $model = $config->updateModel($model, $fieldFactory, $actionFactory);
            }
            
            $response = array(
                'success' => true,
                'data' => $model->toArray()
            );
            
            // if it's a download response, flash the response to the session and return the download link
            if (is_a($result, 'Symfony\Component\HttpFoundation\BinaryFileResponse')) {
                $file = $result->getFile()->getRealPath();
                $headers = $result->headers->all();
                $this->session->put('administrator_download_response', array(
                    'file' => $file,
                    'headers' => $headers
                ));
                
                $response['download'] = route('admin.file_download');
            } // if it's a redirect, put the url into the redirect key so that javascript can transfer the user
elseif (is_a($result, '\Illuminate\Http\RedirectResponse')) {
                $response['redirect'] = $result->getTargetUrl();
            }
            
            return response()->json($response);
        }
    }

    /**
     * Shows the dashboard page.
     *
     * @return Response
     */
    public function main()
    {
        // if the dev has chosen to use a dashboard
        if (config('administrator.use_dashboard')) {
            // set the layout dashboard
            return view(config('administrator.dashboard_view'));
        }
        
        // else we should redirect to the menu item
        $configFactory = app('admin.config_factory');
        $home = config('administrator.home_page');
        
        // first try to find it if it's a model config item
        $config = $configFactory->make($home);
        
        if (!$config) {
            throw new \InvalidArgumentException('Administrator: ' . trans('administrator::administrator.valid_home_page'));
        } elseif ($config->getType() === 'model') {
            return redirect()->route('admin.index', [
                $config->getOption('name')
            ]);
        } elseif ($config->getType() === 'settings') {
            return redirect()->route('admin.settings', [
                $config->getOption('name')
            ]);
        }
    }

    /**
     * Gets the database results for the current model.
     *
     * @param string $modelName
     *
     * @return array of rows
     */
    public function results($modelName)
    {
        $dataTable = app('admin.datatable');
        
        // get the sort options and filters
        $page = $this->request->input('page', 1);
        $sortOptions = $this->request->input('sortOptions', []);
        $filters = $this->request->input('filters', []);
        
        // 排序
        if (empty($sortOptions)) {
            $table = app('itemconfig')->getDataModel()->getTable();
            $tableFields = Schema::getColumnListing($table);
            in_array('sort', $tableFields) && $sortOptions = [
                'field' => 'sort',
                'direction' => 'desc'
            ];
        }
        
        // return the rows
        $rows = $dataTable->getRows(app('db'), $filters, $page, $sortOptions);
        
        return response()->json([
            'error_code' => 0,
            'data' => $rows['results'],
            'meta' => [
                'total' => $rows['total'],
                'per_page' => $this->perPage,
                'current_page' => $this->page,
                'last_page' => $rows['last']
            ]
        ]);
    }

    /**
     * Gets a list of related items given constraints.
     *
     * @param string $modelName
     *
     * @return array of objects [{id: string} ... {1: 'name'}, ...]
     */
    public function updateOptions($modelName)
    {
        $fieldFactory = app('admin.field_factory');
        $response = [];
        
        // iterate over the supplied constrained fields
        foreach ($this->request->input('fields', []) as $field) {
            // get the constraints, the search term, and the currently-selected items
            $constraints = array_get($field, 'constraints', []);
            $term = array_get($field, 'term', []);
            $type = array_get($field, 'type', false);
            $fieldName = array_get($field, 'field', false);
            $selectedItems = array_get($field, 'selectedItems', false);
            
            $response[$fieldName] = $fieldFactory->updateRelationshipOptions($fieldName, $type, $constraints, $selectedItems, $term);
        }
        
        return response()->json($response);
    }

    /**
     * The GET method that displays a file field's file.
     *
     * @return Image / File
     */
    public function displayFile()
    {
        // get the stored path of the original
        $path = $this->request->input('path');
        $data = File::get($path);
        $file = new SFile($path);
        
        $headers = [
            'Content-Type' => $file->getMimeType(),
            'Content-Length' => $file->getSize(),
            'Content-Disposition' => 'attachment; filename="' . $file->getFilename() . '"'
        ];
        
        return response()->make($data, 200, $headers);
    }

    /**
     * The pages view.
     *
     * @return Response
     */
    // public function page($page)
    // {
    // // set the page
    // $this->layout->page = $page;
    
    // // set the layout content and title
    // $this->layout->content = view($page);
    
    // return $this->layout;
    // }
    
    /**
     * The main view for any of the settings pages.
     *
     * @param string $settingsName
     *
     * @return Response
     */
    public function settings($settingsName)
    {
        try {
            $config = app('itemconfig');
            
            // set the layout content and title
            if ($this->request->isMethod('Get')) {
                $this->title = $config->getOption('title');
                return $this->view(compact('settings'), 'admin::settings');
            }
            
            // POST save settings method
            if ($this->request->isMethod('Post')) {
                
                $config->save($this->request, app('admin.field_factory')->getEditFields());
                
                // override the config options so that we can get the latest
                app('admin.config_factory')->updateConfigOptions();
                
                return $this->success('配置更新成功！');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * POST method for handling custom actions on the settings page.
     *
     * @param string $settingsName
     *
     * @return JSON
     */
    public function settingsCustomAction($settingsName)
    {
        $config = app('itemconfig');
        $actionFactory = app('admin.action_factory');
        $actionName = $this->request->input('action_name', false);
        
        // get the action and perform the custom action
        $action = $actionFactory->getByName($actionName);
        $data = $config->getDataModel();
        $result = $action->perform($data);
        
        // override the config options so that we can get the latest
        app('admin.config_factory')->updateConfigOptions();
        
        // if the result is a string, return that as an error.
        if (is_string($result)) {
            return response()->json([
                'success' => false,
                'error' => $result
            ]);
        } // if it's falsy, return the standard error message
elseif (!$result) {
            $messages = $action->getOption('messages');
            
            return response()->json(array(
                'success' => false,
                'error' => $messages['error']
            ));
        } else {
            $response = array(
                'success' => true,
                'actions' => $actionFactory->getActionsOptions(true)
            );
            
            // if it's a download response, flash the response to the session and return the download link
            if (is_a($result, 'Symfony\Component\HttpFoundation\BinaryFileResponse')) {
                $file = $result->getFile()->getRealPath();
                $headers = $result->headers->all();
                $this->session->put('administrator_download_response', array(
                    'file' => $file,
                    'headers' => $headers
                ));
                
                $response['download'] = route('admin.file_download');
            } // if it's a redirect, put the url into the redirect key so that javascript can transfer the user
elseif (is_a($result, '\Illuminate\Http\RedirectResponse')) {
                $response['redirect'] = $result->getTargetUrl();
            }
            
            return response()->json($response);
        }
    }

    /**
     * POST method for switching a user's locale.
     *
     * @param string $locale
     *
     * @return JSON
     */
    public function switchLocale($locale)
    {
        if (in_array($locale, config('administrator.locales'))) {
            $this->session->put('administrator_locale', $locale);
        }
        
        return redirect()->back();
    }
}
