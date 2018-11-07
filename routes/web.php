<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/resource/{uuid}', ['uses' => 'ResourcesController@download', 'as' => 'resources.download']);

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function() {
    Route::get('/models', ['uses' => 'PagesController@listModels', 'as' => 'listModels']);
    Route::get('/routes', ['uses' => 'MiddlewareController@listRoutes', 'as' => 'listRoutes']);

    Route::group(['prefix' => 'pages'], function () {
        Route::get('/', ['uses' => 'PagesController@show', 'as' => 'pages.show']);
        Route::get('/create', ['uses' => 'PagesController@showCreate', 'as' => 'pages.showCreate']);
        Route::post('/create', ['uses' => 'PagesController@create', 'as' => 'pages.create']);
        Route::post('{page}', ['uses' => 'PagesController@update', 'as' => 'pages.update']);
        Route::get('{page}', ['uses' => 'PagesController@showEdit', 'as' => 'pages.showEdit']);
    });

    Route::group(['prefix' => 'layouts'], function () {
       Route::get('/', ['uses' => 'LayoutsController@show', 'as' => 'layouts.show']);
       Route::get('/create', ['uses' => 'LayoutsController@showCreate', 'as' => 'layouts.showCreate']);
       Route::post('/create', ['uses' => 'LayoutsController@create', 'as' => 'layouts.create']);
       Route::get('{layout}', ['uses' => 'LayoutsController@showEdit', 'as' => 'layouts.showEdit']);
       Route::post('{layout}', ['uses' => 'LayoutsController@update', 'as' => 'layouts.update']);
    });

    Route::group(['prefix' => 'resources'], function () {
       Route::get('/', ['uses' => 'ResourcesController@show', 'as' => 'resources.show']);
       Route::get('/create', ['uses' => 'ResourcesController@showCreate', 'as' => 'resources.showCreate']);
       Route::post('/create', ['uses' => 'ResourcesController@create', 'as' => 'resources.create']);
       Route::get('{resource}', ['uses' => 'ResourcesController@showEdit', 'as' => 'resources.showEdit']);
       Route::post('{resource}', ['uses' => 'ResourcesController@update', 'as' => 'resources.edit']);
    });

    Route::group(['prefix' => 'middleware'], function () {
       Route::get('/', ['uses' => 'MiddlewareController@show', 'as' => 'middleware.show']);
       Route::get('/create', ['uses' => 'MiddlewareController@showCreate', 'as' => 'middleware.showCreate']);
       Route::post('/create', ['uses' => 'MiddlewareController@create', 'as' => 'middleware.create']);
       Route::get('{middleware}', ['uses' => 'MiddlewareController@showEdit', 'as' => 'middleware.showEdit']);
       Route::post('{middleware}', ['uses' => 'MiddlewareController@update', 'as' => 'middleware.update']);
    });
});


/**
 * Dynamic Page Routes
 */

$pages = App\Page::all();

foreach($pages as $page) {
    $pageMiddleware = [];

    foreach ($page->middlewares as $middleware) {
        $pageMiddleware[] = 'App\\Http\\Middleware\\' . $middleware->name;
    }

    preg_match_all('/(?<=\{)(.*?)(?=\:)/', $page->uri, $models);
    preg_match_all('/(?<=\:)(.*?)(?=\})/', $page->uri, $attributes);

    $models = $models[0];
    $attributes = $attributes[0];

    $uri = preg_replace('/:(.*?)(?=\})/', '', $page->uri);

    Route::get($uri, function() use($page, $uri, $models, $attributes) {

        $data = [];

        foreach(func_get_args() as $key => $attribute) {
            try {
                $model = app('App\\' . ucfirst($models[$key]));
                $data[$models[$key]] = $model::where($attributes[$key], $attribute)->firstOrFail();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return abort(404);
            }
        }

        $file_name = str_replace('{', '', $uri);
        $file_name = str_replace('}', '', $file_name);
        $file_name = str_replace('/', '_', $file_name);

        foreach($page->with as $with) {
            $data[strtolower(str_replace('App\\', '', $with)) . "s"] = app('App\\' . $with)::all();
        }

        return view($file_name, $data);

    })->middleware($pageMiddleware);
}
