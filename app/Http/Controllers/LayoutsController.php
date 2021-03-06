<?php

namespace App\Http\Controllers;

use App\Layout;
use Illuminate\Http\Request;

class LayoutsController extends Controller
{
    public function show()
    {
        $layouts = Layout::all();

        return view('layouts.show', ['layouts' => $layouts]);
    }

    public function showCreate()
    {
        return view('layouts.create');
    }

    public function showEdit(Layout $layout)
    {
        return view('layouts.edit', ['layout' => $layout]);
    }

    public function update(Layout $layout, Request $request)
    {
        $layout->update($request->all());

        file_put_contents(base_path() . "/resources/views/layouts/{$layout->name}.blade.php", $layout->template);

        session()->flash('notification', [
            'message' => 'Layout Updated!',
            'type' => 'success',
        ]);

        return redirect()->route('layouts.showEdit', ['layout' => $layout->id]);
    }

    public function create(Request $request)
    {
        $layout = new Layout($request->all());

        $layout->save();

        file_put_contents(base_path() . "/resources/views/layouts/{$layout->name}.blade.php", $layout->template);

        session()->flash('notification', [
            'message' => 'Layout Created!',
            'type' => 'success',
        ]);

        return redirect()->route('layouts.showEdit', ['layout' => $layout->id]);
    }
}
