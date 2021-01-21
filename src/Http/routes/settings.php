<?php

use Illuminate\Support\Facades\Route;

// 页面设置
Route::group([
    'middleware' => [
        'Shengfai\LaravelAdmin\Http\Middleware\ValidateSettings',
        'Shengfai\LaravelAdmin\Http\Middleware\PostValidate'
    ]
], function () {
    Route::get('settings/{settings}', 'AdminController@settings')->name('settings');             // Settings Pages
    Route::post('settings/{settings}/save', 'AdminController@settings')->name('settings.save');  // Save Item
});
