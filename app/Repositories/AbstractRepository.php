<?php

namespace App\Repositories;

abstract class AbstractRepository
{

    const BAN_POST_TEXT = 'post was banned!';


    /**
     * delete message
     *
     * @param $post
     * @return int $id
     */
    public function delete($post)
    {
        $id = $post->id;
        $post->delete();

        return $id;
    }


    /**
     * delete all
     *
     * @param $posts
     * @return int $id
     */
    public function deleteAll($posts)
    {
        foreach ($posts as $post) {
            $post->delete();
        }
    }


    /**
     * ban post
     * @param $post
     */
    public function ban($post)
    {
        $post->text = self::BAN_POST_TEXT;
        $post->old = 1;
        $post->save();
    }


    /**
     * ban post
     * @param $posts
     */
    public function banAll($posts)
    {
        foreach ($posts as $post) {
            $post->text = self::BAN_POST_TEXT;
            $post->old = 1;
            $post->save();
        }
    }


}