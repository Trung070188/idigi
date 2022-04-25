<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppController;
use Illuminate\Http\Request;

class ElfinderController extends AppController
{
    protected $name = 'elfinder';

    public function index()
    {
        $title = 'Quản lý file';
        $component = 'ElfinderIndex';
        return view('admin.layouts.vue', compact('title', 'component'));
    }
}
