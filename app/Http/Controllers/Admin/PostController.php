<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Post;
use App\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    protected $validation = [
        'title' => 'required|string|max:255|unique:posts',
        'date' => 'required|date',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $posts = Post::all();
        // dd($posts);

        // query per prendere tutti i post
        $posts = Post::all();
        // return della view iniettando i posts
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // mi prendo tutti i tag dal db
        $tags = Tag::all();

        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // verifico i tags che mi arrivano, che possono anche essere null
        //dd($request->all());

        $validation = $this->validation;
        $validation['title'] = 'required|string|max:255|unique:posts';

        // validation
        $request->validate($validation);

        $data = $request->all();
        
        // controllo checkbox
        $data['published'] = !isset($data['published']) ? 0 : 1;
        // imposto lo slug partendo dal title
        $data['slug'] = Str::slug($data['title'], '-');

        //upload file immagine
        if(isset($data['image'])){
            $data['image'] = Storage::disk('public')->put('images', $data['image']);
        }

        // Insert
        $newPost = Post::create($data);
        
        // aggiungo i tags
        if(isset($data['tags'])){

            // con questo controllo se l'utente non seleziona nessun tag posso andare avnti col programma, altrimenti tags risulta indefinito
            $newPost->tags()->attach($data['tags']);

        }

        // redirect
        return redirect()->route('admin.posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //dd($post);

        //se volessi accedere ai commenti di un post
        // dd($post->comments);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {

        $tags = Tag::all();

        return view('admin.posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        $validation = $this->validation;
        $validation['title'] = $validation['title'] . ',title,' . $post->id;

        // validation
        $request->validate($validation);

        $data = $request->all();
        
        // controllo checkbox
        $data['published'] = !isset($data['published']) ? 0 : 1;
        // imposto lo slug partendo dal title
        $data['slug'] = Str::slug($data['title'], '-');

        //update
        $post->update($data);

        // aggiorno i tags
        if(!isset($data['tags'])){
            //con questo controllo il programma mi va avanti anche se nella modifica l'utente toglie i tag precedentemente selezionati e non ne aggiunge piu nessuno
            $data['tags'] = [];
        }
        $post->tags()->sync($data['tags']);

        //return
        return redirect()->route('admin.posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('message', 'Il post è stato eliminato!');
    }
}
