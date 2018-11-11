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

require_once(base_path() . '/env_check.php');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/resource/{uuid}', ['uses' => 'ResourcesController@download', 'as' => 'resources.download']);

Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function() {
    Route::get('/', ['uses' => 'AdminController@index', 'as' => 'admin.index']);

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

    Route::group(['prefix' => 'migrations'], function () {
       Route::get('/', ['uses' => 'MigrationsController@show', 'as' => 'migrations.show']);
       Route::get('/create', ['uses' => 'MigrationsController@showCreate', 'as' => 'migrations.showCreate']);
       Route::post('/create', ['uses' => 'MigrationsController@create', 'as' => 'migrations.create']);
       Route::get('{migration}', ['uses' => 'MigrationsController@showEdit', 'as' => 'migrations.showEdit']);
    });
});

/**
 * Dynamic Model Routes
 */
$internalTables = ['pages', 'layouts', 'user_migrations', 'middlewares', 'resources', 'migrations', 'password_resets'];

$tables = \App\UserMigration::all()->pluck('table_name')->push('users');

foreach($tables as $table) {
    Route::get('/admin/' . $table, function (\Illuminate\Http\Request $request) use ($table) {
       $model_name = (preg_replace('/s$/', '', str_replace(' ', '', ucwords(str_replace('_', ' ', $table)))));
       $model = app()->make('App\\' . $model_name);

       return app()->make('App\\Http\\Controllers\\DynamicModelController')->all($model::all(), $table, $request);
    });

    Route::post('/admin/' . $table. '/create', function(\Illuminate\Http\Request $request) use ($table) {
        $model_name = (preg_replace('/s$/', '', str_replace(' ', '', ucwords(str_replace('_', ' ', $table)))));
        $model = app()->make('App\\' . $model_name);

        return app()->make('App\\Http\\Controllers\\DynamicModelController')->create($model, $table, $request);
    });

    Route::get('/admin/' . $table . '/create', function (\Illuminate\Http\Request $request) use ($table) {
        return app()->make('App\\Http\\Controllers\\DynamicModelController')->showCreate($table, $request);
    });

    Route::get('/admin/' . $table . '/{id}', function ($id, \Illuminate\Http\Request $request) use ($table) {
        $model_name = (preg_replace('/s$/', '', str_replace(' ', '', ucwords(str_replace('_', ' ', $table)))));
        $model = app()->make('App\\' . $model_name);

        $model = $model::find($id);

        return app()->make('App\\Http\\Controllers\\DynamicModelController')->showUpdate($table, $model, $request);
    });
}

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

    $uri = preg_replace('/:(.*?)(?=\})/', '', $page->uri);

    Route::get($uri, function() use($page, $uri, $models, $attributes) {

        $data = [];

        foreach(func_get_args() as $key => $attribute) {
            try {
                $model = app('App\\' . ucfirst($models[0][$key]));
                $data[$models[0][$key]] = $model::where($attributes[0][$key], $attribute)->firstOrFail();
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return abort(404);
            }
        }

        $file_name = str_replace(['{', '}'], '', $uri);
        $file_name = str_replace('/', '_', $file_name);

        foreach($page->with as $with) {
            $data[strtolower(str_replace('App\\', '', $with)) . "s"] = app('App\\' . $with)::all();
        }

        return view($file_name, $data);

    })->middleware($pageMiddleware);
}
