<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;

use Inertia\Inertia;

class AppController extends Controller
{
    public function index()
    {
        return Inertia::render('Site/App');
    }
}
