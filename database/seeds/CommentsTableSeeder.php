<?php

use Illuminate\Database\Seeder;
use App\Post;
use App\Comment;
use Faker\Generator as Faker;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        // 1. seleziono solo i posts pubblicati
        $posts = Post::where('published', 1)->get();

        // 2. ciclo sui posts pubblicati per generare i commenti random (da 1 a 3)
        foreach($posts as $post){
            // 3.  Non tutti i post devono per forza avere dei commenti, possono anche non averne (se i = 0)
            for($i = 0; $i < rand(0, 3); $i++){

                $newComment = new Comment();

                $newComment->post_id = $post->id;
                $newComment->name = $faker->name();
                $newComment->content = $faker->text();

                $newComment->save();

            }

        }
     
    }
}
