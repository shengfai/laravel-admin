<?php

use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

//admin index view
View::composer('admin::grid', function ($view) {
    //get a model instance that we'll use for constructing stuff
    $config = app('admin.item_config');
    $fieldFactory = app('admin.field_factory');
    $columnFactory = app('admin.column_factory');
    $actionFactory = app('admin.action_factory');
    $model = $config->getDataModel();

    //add the view fields
    $view->config = $config;
    $view->modelName = Str::of(class_basename($model))->plural()->snake()->lower();
    $view->primaryKey = $model->getKeyName();
    $view->title = $view->title ?? $config->getOption('heading');
    $view->editFields = $fieldFactory->getEditFields();
    $view->arrayFields = $fieldFactory->getEditFieldsArrays();
    $view->columns = $columnFactory->getColumnsOfGrip()->values()->all();
    $view->actions = $actionFactory->getActionsOptions();
    $view->batchActions = $actionFactory->getBatchActionsOptions();
    $view->globalActions = $actionFactory->getGlobalActionsOptions();
    $view->actionPermissions = $actionFactory->getActionPermissions();
    $view->filters = $fieldFactory->getFiltersArrays();
    $view->resultsUrl = route('admin.model.results', $view->modelName);
    $view->itemId = isset($view->itemId) ? $view->itemId : null;
});

// admin detail view
View::composer(['admin::detail', 'admin::form'], function ($view) {
    $config = app('admin.item_config');
    $columnFactory = app('admin.column_factory');
    $fieldFactory = app('admin.field_factory');
    $model = $config->getDataModel();

    $view->modelName = Str::of(class_basename($model))->plural()->snake()->lower();
    $view->columns = $columnFactory->getColumnOptions();
    $view->editFields = $fieldFactory->getEditFields();
    $view->arrayFields = $fieldFactory->getEditFieldsArrays();
});

//admin settings view
View::composer('admin::settings', function ($view) {
    $config = app('admin.item_config');
    $fieldFactory = app('admin.field_factory');
    $actionFactory = app('admin.action_factory');

    //add the view fields
    $view->config = $config;
    $view->settingsName = request()->route()->parameter('settings');
    $view->editFields = $fieldFactory->getEditFields();
    $view->arrayFields = $fieldFactory->getEditFieldsArrays();
    $view->actions = $actionFactory->getActionsOptions();
});

// menus view
View::composer(['admin::partials.menus'], function ($view) {
    $view->menus = app('admin.menu')->getMenus();
});

//the layout view
View::composer(['admin::layouts.default'], function ($view) {
    $view->baseUrl = Str::of(asset(config('administrator.prefix')))->finish('/');
});
