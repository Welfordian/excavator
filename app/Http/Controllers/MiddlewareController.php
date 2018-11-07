<?php

namespace App\Http\Controllers;

use App\Middleware;
use App\Page;
use Illuminate\Http\Request;

class MiddlewareController extends Controller
{
    public function show()
    {
        $middleware = Middleware::all();

        return view('middleware.show', ['middlewares' => $middleware]);
    }

    public function update(Middleware $middleware, Request $request)
    {
        $middleware->update($request->all());

        return redirect()->route('middleware.showEdit', $middleware->id);
    }

    public function showCreate()
    {
        $routes = Page::all();

        return view('middleware.create', ['routes' => $routes]);
    }

    public function create(Request $request)
    {
        $middleware = (new Middleware($request->except('routes')));

        $middleware->routes = json_encode($request->get('routes'));

        $middleware->save();

        return redirect()->route('middleware.showEdit', ['middleware' => $middleware->id]);
    }

    public function showEdit(Middleware $middleware)
    {
        $routes = Page::all();

        return view('middleware.edit', ['middleware' => $middleware, 'routes' => $routes]);
    }
}
