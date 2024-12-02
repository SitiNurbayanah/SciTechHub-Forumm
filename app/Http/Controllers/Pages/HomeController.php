<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;

class HomeController extends Controller
{
    public function index()
    {   
        return view('pages.threads.index', [
            'threads'       => Thread::orderBy('id', 'desc')->paginate(10),
        ]);
    }
}
