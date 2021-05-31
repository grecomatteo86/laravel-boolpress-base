<h1>Nuovo Commento</h1>

<div>
    Il post commentato é: {{$post->title}}
    <a href="{{route('admin.posts.show', ['post' => $post->id])}}">Visualizza il post</a>
</div>