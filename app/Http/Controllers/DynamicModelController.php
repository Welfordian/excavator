<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DynamicModelController extends Controller
{
    public function all($models, $table)
    {
        $title_name = preg_replace('/s$/', '', ucwords(str_replace('_', ' ', $table)));

        return view('admin.all_dynamic_models', ['models' => $models, 'table' => $table, 'title_name' => $title_name]);
    }

    public function showCreate($table, Request $request)
    {
        $title_name = preg_replace('/s$/', '', ucwords(str_replace('_', ' ', $table)));
        $database_name = \Config::get("database.connections." . \Config::get('database.default') . ".database");

        $columns = collect(DB::select('SELECT * FROM information_schema.columns WHERE table_schema=\'' . $database_name . '\' AND table_name=\'' . $table . '\' '))->map(function($column) {
            return [
                'name' => $column->COLUMN_NAME,
                'type' => $column->DATA_TYPE
            ];
        });

        return view('admin.dynamic_model', ['columns' => $columns, 'title_name' => $title_name, 'table' => $table]);
    }

    public function showUpdate($table, $model, Request $request)
    {
        $title_name = preg_replace('/s$/', '', ucwords(str_replace('_', ' ', $table)));
        $database_name = \Config::get("database.connections." . \Config::get('database.default') . ".database");

        $columns = collect(DB::select('SELECT * FROM information_schema.columns WHERE table_schema=\'' . $database_name . '\' AND table_name=\'' . $table . '\' '))->map(function($column) {
            return [
                'name' => $column->COLUMN_NAME,
                'type' => $column->DATA_TYPE
            ];
        });

        return view('admin.update_dynamic_model', ['columns' => $columns, 'title_name' => $title_name, 'table' => $table, 'model' => $model]);
    }

    public function create($model, $table, Request $request)
    {
        $columns = $request->except(['_token', 'table_name']);

        $model = new $model();

        foreach($columns as $name => $value) {
            if ($name === 'password') {
                $model->{$name} = Hash::make($value);
            } else {
                $model->{$name} = $value;
            }
        }

        $model->save();

        return redirect()->to('/admin/' . $request->get('table_name') . '/' . $model->id);
    }
}
