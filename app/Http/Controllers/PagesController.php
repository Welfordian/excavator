<?php

namespace App\Http\Controllers;

use App\Layout;
use App\Page;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class PagesController extends Controller
{
    public function listModels()
    {
        return $this->getModels();
    }

    public function show()
    {
        $pages = Page::all();

        return view('pages.show', compact('pages'));
    }

    public function showCreate()
    {
        $layouts = Layout::all();

        return view('pages.create', ['layouts' => $layouts]);
    }

    public function showEdit(Page $page)
    {
        $layouts = Layout::all();

        return view('pages.edit', ['page' => $page, 'models' => $this->getModels(), 'layouts' => $layouts]);
    }

    public function update(Page $page, Request $request)
    {
        $page->update($request->only(['uri', 'template', 'layout']));


        $page->update(['with' => (is_null($request->get('with')) ? [] : explode(',', $request->get('with')))]);

        $this->updatePageTemplate($page);

        return redirect()->route('pages.showEdit', $page->id);
    }

    public function create(Request $request)
    {
        $page = new Page();

        $page->uri = $request->get('uri');
        $page->template = $request->get('template');
        $page->layout = $request->get('layout');
        $page->with = (is_null($request->get('with')) ? [] : explode(',', $request->get('with')));

        $page->save();

        $this->updatePageTemplate($page);

        return redirect()->route('pages.showEdit', $page->id);
    }

    public function getModels()
    {
        $models = collect(scandir(base_path() . '/app'))->filter(function($item) {
            return preg_match('/.php/', $item);
        });

        $models = $models->map(function($model) {
            return str_replace('.php', '', $model);
        });

        return $models->values();
    }

    public function updatePageTemplate(Page $page)
    {
        $uri = preg_replace('/:(.*?)(?=\})/', '', $page->uri);

        $file_name = str_replace('{', '', $uri);
        $file_name = str_replace('}', '', $file_name);
        $file_name = str_replace('/', '_', $file_name);

        if ($page->layout) {
            file_put_contents(base_path() . "/resources/views/layouts/{$page->getLayout->name}.blade.php", $page->getLayout->template);

            file_put_contents ( base_path() . "/resources/views/" . $file_name . '.blade.php', "@extends('layouts.{$page->getLayout->name}')\n\n" . $page->template);
        } else {
            file_put_contents ( base_path() . "/resources/views/" . $file_name . '.blade.php', $page->template);
        }
    }
}
