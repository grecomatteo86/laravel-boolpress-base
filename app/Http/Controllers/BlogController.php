<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class BlogController extends Controller
{
    public function index(){

        // prendo i dati dal db
        $posts = Post::where('published', 1)->orderBy('date', 'asc')->limit(5)->get();
        // restituisco la pagina home
        return view('guest.index', compact('posts'));

    }
}
