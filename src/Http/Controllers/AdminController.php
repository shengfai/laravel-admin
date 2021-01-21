<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Support\Arr;
use Overtrue\LaravelOptions\Option;
use Shengfai\LaravelAdmin\Traits\Response;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;
use Spatie\QueryBuilder\Exceptions\InvalidSortQuery;
use Spatie\QueryBuilder\Exceptions\InvalidFilterQuery;

/**
 * Handles all requests related to managing the data models
 * Class AdminController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class AdminController extends Controller
{

    /**
     * The main view for any of the data models
     *
     * @param string $modelName
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(string $modelName)
    {
        // 列表排序默认处理
        if ($this->request->action === 'resort') {
            $config = app('admin.item_config');
            return $this->resort($config->getDataModel());
        }
        
        // set the layout content and title
        return view('admin::grid');
    }

    /**
     * Gets the item create page / information
     *
     * @param string $modelName
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(string $modelName)
    {
        return view('admin::form');
    }

    /**
     * Gets the item edit page / information
     *
     * @param string $modelName
     * @param mixed $itemId
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function item(string $modelName, $itemId = 0)
    {
        $config = app('admin.item_config');
        $fieldFactory = app('admin.field_factory');
        $actionFactory = app('admin.action_factory');
        $columnFactory = app('admin.column_factory');
        $actionPermissions = $actionFactory->getActionPermissions();
        $fields = $fieldFactory->getEditFields();
        
        // try to get the object
        $model = $config->getModel($itemId, $fields, $columnFactory->getIncludedColumns($fields));
        
        if ($this->request->acceptsHtml()) {
            $model = $config->updateModel($model, $fieldFactory, $actionFactory);
            $view = $this->request->action === 'edit' ? 'admin::form' : 'admin::detail';
            return view($view, [
                'itemId' => $itemId,
                'model' => $model
            ]);
        }
        
        // set the Vary : Accept header to avoid the browser caching the json response
        return $this->response($model->toArray())->header('Vary', 'Accept');
    }

    /**
     * POST save method that accepts data via JSON POST and either saves an old item (if id is valid) or creates a new one
     *
     * @param string $modelName
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function save(string $modelName, $id = false)
    {
        try {
            $config = app('admin.item_config');
            
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
     * POST delete method that accepts data via JSON POST and either saves an old
     *
     * @param string $modelName
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function delete(string $modelName)
    {
        $config = app('admin.item_config');
        $actionFactory = app('admin.action_factory');
        $baseModel = $config->getDataModel();
        $errorResponse = [
            'success' => false,
            'error' => 'There was an error perform batch deletion. Please reload the page and try again.'
        ];
        
        // if don't have permission, send back request
        $permissions = $actionFactory->getActionPermissions();
        if (! $permissions['delete']) {
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
     * Shows the dashboard page
     *
     * @throws \InvalidArgumentException
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
        
        if (! $config) {
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
     * Gets the database results for the current model
     *
     * @param string $modelName
     * @return array of rows
     */
    public function results(string $modelName)
    {
        try {
            
            $dataTable = app('admin.datatable');
            $modelConfig = app('admin.item_config');
            
            // return the rows
            $queryParameters = $modelConfig->getOptions()['query_parameters'] ?? [];
            $results = $dataTable->getRows($modelConfig->getDataModel(), $queryParameters, $this->perPage);
            
            return response()->json([
                'error_code' => 0,
                'data' => $results->items(),
                'meta' => [
                    'total' => $results->total(),
                    'per_page' => $results->perPage(),
                    'current_page' => $results->currentPage(),
                    'last_page' => $results->lastPage()
                ]
            ]);
        } catch (InvalidSortQuery $exception) {
            \dd($exception);
        } catch (InvalidFilterQuery $exception) {
            \dd($exception);
        }
    }

    /**
     * The main view for any of the settings pages.
     *
     * @param string $settingsName
     * @return Response
     */
    public function settings(string $settingsName)
    {
        try {
            
            $config = app('admin.item_config');
            
            // set the layout content and title
            if ($this->request->isMethod('Get')) {
                
                if ($this->request->wantsJson()) {
                    $options = Option::get();
                    return $this->response($options->toArray());
                }
                
                $this->title = $config->getOption('title');
                return $this->view([], 'admin::settings');
            }
            
            // POST save settings method
            if ($this->request->isMethod('Post')) {
                $config->save($this->request, app('admin.field_factory')->getEditFields());
                app('admin.config_factory')->updateConfigOptions();
                return $this->success('配置更新成功！');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * POST method for switching a user's locale.
     *
     * @param string $locale
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
