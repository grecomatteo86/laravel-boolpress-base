<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\Tag;
use Illuminate\Support\Facades\Mail;
use App\Mail\CommentMail;

class BlogController extends Controller
{
    public function index(){

        // prendo i dati dal db
        $posts = Post::where('published', 1)->orderBy('date', 'asc')->limit(5)->get();
        $tags = Tag::all();
        // restituisco la pagina home
        return view('guest.index', compact('posts', 'tags'));

    }

    public function show($slug)
    {
        // prendo i dati dal db
        $post = Post::where('slug', $slug)->first();
        $tags = Tag::all();
        
        if ( $post == null ) {
            abort(404);
        }
        // restituisco la pagina del post
        return view('guest.show', compact('post', 'tags'));
    }

    public function filterTag($slug)
    {

        //dd($slug);

        $tags = Tag::all();
        $tag = Tag::where('slug', $slug)->first();

        //dd($tag->posts);

        if ( $tag == null ) {
            abort(404);
        }

        $posts = $tag->posts()->where('published', 1)->get();

        // restituisco la pagina home
        return view('guest.index', compact('posts', 'tags'));
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

        //invio mail di notifica
        Mail::to('mail@mail.it')->send(new CommentMail($post));

        return back();
    }

}
