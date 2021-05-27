<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;

class BlogController extends Controller
{
    public function index(){

        // prendo i dati dal db
        $posts = Post::where('published', 1)->orderBy('date', 'asc')->limit(5)->get();
        // restituisco la pagina home
        return view('guest.index', compact('posts'));

    }

    public function show($slug)
    {
        // prendo i dati dal db
        $post = Post::where('slug', $slug)->first();
        
        if ( $post == null ) {
            abort(404);
        }
        // restituisco la pagina del post
        return view('guest.show', compact('post'));
    }

    public function addComment(Request $request, Post $post)
    {

        //dd($request->all());

        $request->validate([
            'name' => 'nullable|string|max:100',
            'content' => 'required|string',
        ]);

        $newComment = new Comment();

        $newComment->name = $request->name;
        $newComment->content = $request->content;
        $newComment->post_id = $post->id;

        $newComment->save();

        return back();
    }

}
