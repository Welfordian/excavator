<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourcesController extends Controller
{
    public function show()
    {
        $resources = Resource::all();

        return view('resources.show', ['resources' => $resources]);
    }

    public function download($resource)
    {
        $resource = Resource::where('uuid', $resource)->firstOrFail();

        if ($resource->secure && ! \Auth::check()) {
            return abort(403);
        }

        return Storage::disk('local')->download($resource->path, $resource->uuid . "." . $resource->type);
    }

    public function showCreate()
    {
        return view('resources.create');
    }

    public function showEdit(Resource $resource)
    {
        return view('resources.edit', ['resource' => $resource]);
    }

    public function create(Request $request)
    {
        $resource = new Resource();
        $resource->name = $request->get('name');
        $resource->secure = $request->get('secure') === 'on' ? true : false;

        $path = $request->file('resource')->storeAs(
            'resources', str_random(16) . "." . $request->file('resource')->getClientOriginalExtension()
        );

        $resource->path = $path;
        $resource->type = $request->file('resource')->getClientOriginalExtension();
        $resource->uuid = str_random(16);

        $resource->save();

        session()->flash('notification', [
            'message' => 'Resource Created!',
            'type' => 'success',
        ]);

        return redirect()->route('resources.showEdit', ['resource' => $resource->id]);
    }

    public function update(Resource $resource, Request $request)
    {
        $resource->update([
            'name' => $request->get('name'),
            'secure' => $request->get('secure') === 'on' ? true : false
        ]);

        session()->flash('notification', [
            'message' => 'Resourcevs Updated!',
            'type' => 'success',
        ]);

        return redirect()->back();
    }
}
