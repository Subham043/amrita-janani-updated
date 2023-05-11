<?php

namespace App\Services;

class TagService
{
    public function get_tags($data)
    {
        $tags_exist = array();
        foreach ($data as $tag) {
            $arr = explode(",",$tag->tags);
            foreach ($arr as $i) {
                if (!(in_array($i, $tags_exist))){
                    array_push($tags_exist,$i);
                }
            }
        }

        $topics_exist = array();
        foreach ($data as $topic) {
            $arr = explode(",",$topic->topics);
            foreach ($arr as $i) {
                if (!(in_array($i, $topics_exist))){
                    array_push($topics_exist,$i);
                }
            }
        }

        return array(
            'tags_exist' => $tags_exist,
            'topics_exist' => $topics_exist,
        );
    }
}
