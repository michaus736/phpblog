<?php
function getTopics($filename){
    $file=file_get_contents($filename,true);
    //echo $file;
    $postarray=explode('-:-:-',$file);
    $index=1;
    foreach($postarray as $a){
        $a=explode(":-:", $a);
        $a[]=$index;
        $index++;
        $topics[]=$a;
    }
    //echo json_encode($topics);
    array_pop($topics);
    return $topics;
}
function GetPosts($filename){
    $posts=array();
    $file=file_get_contents($filename, true);
    $postarray=explode('-:-:-',$file);
    foreach($postarray as $a){
        $a=explode(":-:",$a);
        $posts[]=$a;
    }
    array_pop($posts);
    return $posts;
}
function countPostsById($id,$posts){
    $count=0;
    foreach($posts as $post){
        if($post[0]==$id)
        $count++;
    }
    return $count;
}





?>