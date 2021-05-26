<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'html',
            'css',
            'php',
            'js',
            'vuejs',
            'laravel'
        ];

        // creo tanti tags quanti ne ho definiti nell'array con un foreach
        foreach($tags as $tag) {

            $newTag = new Tag();

            $newTag->name = $tag;
            $newTag->slug = Str::slug($tag, '-');
            
            $newTag->save();
        }
    }
}
