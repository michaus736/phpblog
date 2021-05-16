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
    function addUser(string $login, string $author, string $password, $userdatabase){
        $file=file_get_contents($userdatabase, true);
        $file.=nl2br($login.":-:".$author.":-:".md5($password).":-:"."user"."-:-:-");
        file_put_contents($userdatabase,$file);
    }

    
    function isUser(string $login, $userdatabase):bool{
        $flag=false;
        $users=readFileDetails($userdatabase);
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
    //opinions
    function addNewOpinion(string $opinion, string $user, int $topicid):void{
        $file=file_get_contents("./wypowiedzi.txt", true);
        $filecontent=readFileDetails("./wypowiedzi.txt");
        if(count($filecontent)==0){
            $opinioncount=1;
        }
        else{
            $opinioncount=$filecontent[count($filecontent)-1][2]+1;
        }
        $file.=nl2br($user.":-:".$topicid.":-:".$opinioncount.":-:".$opinion.":-:".date("d.m.Y, H:i:s")."-:-:-");
        file_put_contents("./wypowiedzi.txt", $file);
    }
    function countOpinions(int $topicid):int{
        $count=0;
        $opinions=readFileDetails("./wypowiedzi.txt");
        foreach($opinions as $opinion)
            if($opinion[1]==$topicid)
                $count++;
        

        return $count;
    }
    function editOpinion(int $topic, int $opinionid, array $user, string $content){
        $newcontent="";
        $opinions=readFileDetails("./wypowiedzi.txt");
        foreach($opinions as $opinion){
            echo json_encode($opinion);
            if($opinion[1]==$topic&&$opinion[2]==$opinionid){
                $newcontent.=nl2br($user[0].":-:".$topic.":-:".$opinionid.":-:".$content.":-:".date("d.m.Y, H:i:s")."-:-:-");
            }else{
                $newcontent.=nl2br($opinion[0].":-:".$opinion[1].":-:".$opinion[2].":-:".$opinion[3].":-:".$opinion[4]."-:-:-");
            }
        }
        file_put_contents("./wypowiedzi.txt", $newcontent);
    }
    function delOpinion($topic, $id){
        $newcontent="";
        $opinions=readFileDetails("./wypowiedzi.txt");
        foreach($opinions as $opinion){
            if($opinion[1]!=$topic||$opinion[2]!=$id){
                $newcontent.=nl2br($opinion[0].":-:".$opinion[1].":-:".$opinion[2].":-:".$opinion[3].":-:".$opinion[4]."-:-:-");
            }
        }
        file_put_contents("./wypowiedzi.txt", $newcontent);
    }



?>