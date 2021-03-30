<?php
    //file
    function readFileDetails(string $path):array{
        $file=file_get_contents($path, true);
        $filearray=explode("-:-:-",$file);
        if($filearray[count($filearray)-1]=="\n"||$filearray[count($filearray)-1]==''){
            array_pop($filearray);
        }
        $itemarray=array();
        foreach($filearray as $item){
            $itemdetail=explode(":-:",$item);
            array_push($itemarray,$itemdetail);
        }


        return $itemarray;
    }
    //user
    function addUser(string $login, string $author, string $password){
        $file=file_get_contents("./users.txt", true);
        $file.=nl2br($login.":-:".$author.":-:".md5($password).":-:"."user"."-:-:-");
        file_put_contents("./users.txt",$file);
    }

    
    function isUser(string $login):bool{
        $flag=false;
        $users=readFileDetails("./users.txt");
        foreach($users as $user){
                if($user[0]==$login){
                    $flag=true;
                    break;
                }
        }
        return $flag;
    }
    function getUserInfo(string $login):array{
        $users=readFileDetails("./users.txt");
        foreach($users as $user){
            if($user[0]==$login)
                return $user;
        }


    }

    //posts
    function addNewPost(string $postTitle, string $postDetail, string $login){
        $file=file_get_contents("./posty.txt", true);
        $filecontent=readFileDetails("./posty.txt");
        if(count($filecontent)==0){
            $topicount=1;
        }
        else{
            $topicount=$filecontent[count($filecontent)-1][3]+1;
        }
        $file.=nl2br($postTitle.":-:".$postDetail.":-:".$login.":-:".$topicount.":-:".date("d.m.Y, H:i:s")."-:-:-");
        file_put_contents("./posty.txt",$file);
    }
    //opinions
    function getTopicOfOpinions(int $topicid):array{
        $posts=readFileDetails("./posty.txt");
        foreach($posts as $post){
            if($post[3]==$topicid){
                return $post;
            }
        }
    }




?>