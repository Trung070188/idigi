<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminBaseController extends Controller
{
    public function __invoke(Request $req)
    {
        // TODO: Implement __invoke() method.
        $action = $req->route('action', 'index');

        return $this->{$action}($req);
    }
}
