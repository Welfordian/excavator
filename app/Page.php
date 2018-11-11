<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['uri', 'template', 'with', 'layout'];

    protected $casts = ['with' => 'array'];

    public function getWithForInputAttribute()
    {
        return join(',', $this->getAttribute('with'));
    }

    public function getMiddlewaresAttribute()
    {
        $middlewares = Middleware::all();

        return $middlewares->filter(function($middleware) {
            return in_array($this->id, $middleware->routes);
        });
    }

    public function getLayout()
    {
        return $this->belongsTo(Layout::class, 'layout');
    }
}
