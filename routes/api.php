<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/events/search', function (Request $request) {
  return \App\Models\Event::where('name', 'like', "%{$request->query('q')}%")
    ->select('id', 'name')
    ->limit(10)
    ->get();
});
