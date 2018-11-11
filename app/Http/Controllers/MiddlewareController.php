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

        $this->updateMiddlewareClass($middleware);

        session()->flash('notification', [
            'message' => 'Middleware Updated!',
            'type' => 'success',
        ]);

        return redirect()->route('middleware.showEdit', $middleware->id);
    }

    public function showCreate()
    {
        $routes = Page::all();

        return view('middleware.create', ['routes' => $routes]);
    }

    public function create(Request $request)
    {
        $middleware = (new Middleware($request->all()));

        $middleware->save();

        $this->updateMiddlewareClass($middleware);

        session()->flash('notification', [
            'message' => 'Middleware Created!',
            'type' => 'success',
        ]);

        return redirect()->route('middleware.showEdit', ['middleware' => $middleware->id]);
    }

    public function showEdit(Middleware $middleware)
    {
        $routes = Page::all();

        return view('middleware.edit', ['middleware' => $middleware, 'routes' => $routes]);
    }

    public function updateMiddlewareClass(Middleware $middleware)
    {
        $logic = <<<CLASS
<?php

namespace App\Http\Middleware;

use App;
use Closure;

class {$middleware->name}
{
    public function handle(\$request, Closure \$next)
    {
        {$middleware->logic}
    }
}
CLASS;

        file_put_contents(base_path() . '/app/Http/Middleware/' . $middleware->name . '.php', $logic);
    }
}
