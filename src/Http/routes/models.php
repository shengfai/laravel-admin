<?php

use Illuminate\Support\Facades\Route;

// 模型设置
Route::group([
    'middleware' => [
        'Shengfai\LaravelAdmin\Http\Middleware\ValidateModel',
        'Shengfai\LaravelAdmin\Http\Middleware\PostValidate'
    ]
], function () {
    Route::get('{model}', 'AdminController@index')->name('model.index'); // Model Index
    Route::get('{model}/results', 'AdminController@results')->name('model.results'); // Get results
    Route::get('{model}/new', 'AdminController@create')->name('model.new'); // New Item
    Route::get('{model}/{id}', 'AdminController@item')->name('model.item'); // Get Item
    Route::post('{model}/{id?}/save', 'AdminController@save')->name('model.save'); // Save Item
    Route::delete('{model}/batch_delete', 'AdminController@delete')->name('model.batch_delete'); // Batch Delete Item
    Route::delete('{model}/{id}/delete', 'AdminController@delete')->name('model.delete'); // Delete Item
});