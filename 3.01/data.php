<?php
// ---------------------------------------------------------------------------
// Topics - funkcje zarzadzania tematami
//------------------------------------------------------------------------------
// funkcja zapisu do pliku
function put_topic($topic, $topic_body, $username, 
                   $datafile="tematy.txt", $separator=":-:" )
{
   // ostatni wiersz zawiera najmłodszy wpis
   if( is_file($datafile) ){
      // odczyt pliku
      $data=file( $datafile );
      // pobranie danych z ostatniego elementu tablicy $data
      $record = explode( $separator, trim(array_pop($data))); 
      $id = (count($record)>1)?($record[0] + 1):1;
   }else{
      $id = 1;    
   }
   // utworzenie nowego wiersz danych
   // zakodowanie przez bin2hex() danych przesłanych przez użtykownika
   $data = implode( $separator, 
                     array( $id, 
                            bin2hex($topic),
                            bin2hex($topic_body), 
                            bin2hex($username), 
                            date("Y-m-d H:i:s") 
                  ));
   // zapis danych na końcu pliku
   if( $fh = fopen( $datafile, "a+" )){
      fwrite($fh, $data."\n");
      fclose($fh);
      return $id;
   }else{
      return FALSE;
   };                               
}

//------------------------------------------------------------------------------
// funkcja odczytu z pliku wszystkich tematów
function get_topics( $datafile="tematy.txt", $separator=":-:" )
{
   // wczytanie pliku do tablicy stringów
   if( $data=file( $datafile ) ){
      // utworzenie pustej tablicy wynikowej
      $topics=array();
      // dla każdego elementu tablicy $data
      //    $k - klucz ementu,  $v - wartość elementu
      foreach($data as $k=>$v){
          // umieszcza kolejne elementy wiersza rozdzielone separatoerm 
          // w kolejnych elementach zwracanej tablicy
          $record = explode( ',', trim($v));
          //echo "<div>".json_encode($record)."</div>";
          // jesli pasuje identyfikator tematu
          // przepakowanie do $posts[] i dekodowanie danych użytkownika
          $topics[$record[0]]=array( 
             "topicid"    => $record[0],
             "topic"      => hex2bin($record[1]),
             "topic_body" => hex2bin($record[2]),
             "username"   => hex2bin($record[3]),
             "date"       => $record[4]
          );
      }
      // zwraca tablice z wynikami
      return $topics;   
   }else{
      // zwraca kod błędu
      return FALSE;
   }
}

//------------------------------------------------------------------------------
// funkcja wyznacza id poprzedniego tematu
function get_previous_topic_id( $topicid, 
                                $datafile="tematy.txt", $separator=":-:")
{
    $data=file( $datafile );
    $pre=0;
    if( count($data) ){
       foreach($data as $k=>$v ){
          $r = explode( $separator, trim($v));
          if( $r[0]<$topicid) $pre=$r[0];
          if( $r[0]==$topicid ) break;  
       }
    }
    return $pre;
}

//------------------------------------------------------------------------------
// funkcja wyznacza id następnego tematu
function get_next_topic_id( $topicid, 
                            $datafile="tematy.txt", $separator=":-:")
{
    $data=file( $datafile );
    $next=0;
    if( count($data) ){
       foreach($data as $k=>$v ){
          $r = explode( $separator, trim($v));
          if( $r[0]<$topicid ) continue;
          if( $r[0]>$topicid) {
             $next=$r[0];
             break;
          }     
       }
    }
    return $next;
}

// ---------------------------------------------------------------------------
// Posts - funkcje zarzadzania wypowiedziami
//------------------------------------------------------------------------------
// funkcja wyszukująca wypowiedzi na określony temat
//   $topicid - identyfikator tematu
//   $datafile - ścieżka do pliku zawierającego dane
//   $separator - znaki tworzące separator pól rekordu
//
// format pliku danych:
// postid:-:topicid:-:post:-:username:-:date
// 
function get_posts( $topicid, 
                    $datafile="wypowiedzi.txt", $separator=":-:")
{
   // wczytanie pliku do tablicy stringów
   if( $data=file( $datafile ) ){
      // utworzenie pustej tablicy wynikowej
      $posts=array();
      // dla każdego elementu tablicy $data
      //    $k - klucz ementu,  $v - wartość elementu
      foreach($data as $k=>$v){
          // umieszcza kolejne elementy wiersza rozdzielone separatoerm 
          // w kolejnych elementach zwracanej tablicy
          $record = explode( $separator, trim($v));
          // jesli pasuje identyfikator tematu
          if( $record[1]==$topicid ){
              // przepakowanie do $posts[] i dekodowanie danych użytkownika
              $posts[]=array( 
                 "postid"  => $record[0],
                 "topicid" => $record[1],
                 "post"    => hex2bin($record[2]),
                 "username"=> hex2bin($record[3]),
                 "date"    => $record[4]
              );
          }
      }
      // zwraca tablice z wynikami
      return $posts;   
   }else{
      // zwraca kod błędu
      return FALSE;
   }
}

//------------------------------------------------------------------------------
// funkcja zapisu wypowiedzi do pliku
function put_post( $topicid, $post, $username, 
                   $datafile="wypowiedzi.txt", $separator=":-:")
{
   // ostatni wiersz zawiera najmłodszy wpis
   if( is_file($datafile) ){
      // odczyt pliku
      $data=file( $datafile );
      $postid = 1;
      // pobranie danych z ostatniego elementu tablicy $data
      if( $last = trim(array_pop($data)) ){
         $record = explode( $separator, $last); 
         $postid = $record[0]+1;
      }
   }      
   // utworzenie nowego wiersz danych
   // zakodowanie przez bin2hex() danych przesłanych przez użtykownika
   $data = implode( $separator, 
                     array( $postid, 
                            $topicid, 
                            bin2hex($post), 
                            bin2hex($username), 
                            date("Y-m-d H:i:s") 
                     )
                  );
   // zapis danych na końcu pliku
   if( $fh = fopen( $datafile, "a+" )){
      fwrite($fh, $data."\n");
      fclose($fh);
      return $postid;
   }else{
      return FALSE;
   };                               
}

//------------------------------------------------------------------------------
// funkcja pobiera z pliku wypowiedz o danym $id
function get_post( $id, 
                   $datafile="wypowiedzi.txt", $separator=":-:" )
{
    $data = file( $datafile );
    $post=FALSE;
    foreach($data as $v ){
       $r = explode( $separator, trim($v));
       if( $r[0]==$id ){
           $post = array( 
                 "postid"  => $r[0],
                 "topicid" => $r[1],
                 "post"    => hex2bin($r[2]),
                 "username"=> hex2bin($r[3]),
                 "date"    => $r[4]
              );
            break;  
       }
    }
    return $post; 
}

//------------------------------------------------------------------------------
// funkcja aktualizuje w pliku dane dla wypowiedzi o danym $postid
function update_post( $postid, $post, $username, 
                      $datafile="wypowiedzi.txt", $separator=":-:")
{
    $data=file( $datafile ); 
    $new_post=FALSE;
    foreach($data as $k=>$v ){
       $r = explode( $separator, trim($v));
       if( $r[0]==$postid ){
           $new_post = array( 
                 "postid"  => $r[0],
                 "topicid" => $r[1],
                 "post"    => bin2hex($post),
                 "username"=> bin2hex($username),
                 "date"    => date("Y-m-d H:i:s")
              );
              $data[$k] = implode($separator,$new_post)."\n";
              file_put_contents($datafile, implode("", $data));  
            break;  
       }
    }
    return $new_post; 
}

//------------------------------------------------------------------------------
// funkcja usuwa z pliku dane dla wypowiedzi o danym $id
function delete_post( $id, 
                      $datafile="wypowiedzi.txt", $separator=":-:")
{
   if( $data=file( $datafile ) ){
      foreach($data as $k=>$v){
         $r = explode( $separator, trim($v));
         if( $r[0]==$id ){
            unset($data[$k]);
            break;
         }   
      }
      return file_put_contents($datafile,implode("", $data)); 
   }else{
      return FALSE;
   }   
}

//------------------------------------------------------------------------------
// funkcja zlicza wypowiedzi na każdy z tematów
function get_posts_count( $datafile="wypowiedzi.txt", $separator=":-:" )
{
   if( !is_file($datafile) ) 
      return FALSE;
   $post_count = array();   
   if( $data=file( $datafile ) ){
      foreach( $data as $v ){
         if( strlen(trim($v))>0 ){
           $p = explode( $separator, trim($v));
           if( isset($post_count[$p[1]]) )
             $post_count[$p[1]] = $post_count[$p[1]] + 1;
           else
             $post_count[$p[1]] = 1;
         }
      }
      return $post_count; 
   }else{
      return FALSE;
   }
}

//------------------------------------------------------------------------------
// funkcja pobiera date ostatniej wypowiedzi
function get_last_post_date($datafile="wypowiedzi.txt", $separator=":-:")
{
    if( $data=file( $datafile ) ){
        $record = explode( $separator, trim(array_pop($data)));
        return $record[4];
    }else{
        return '- brak postów -';
    } 
}

//-------------------------------------------------------------------------------
//pliki
function readFileDetails(string $path, $separator=",", $separator2="\n"):array{
   $file=file_get_contents($path, true);
   $filearray=explode($separator2, $file);
   if($filearray[count($filearray)-1]=="\n"||$filearray[count($filearray)-1]==''){
       array_pop($filearray);
   }
   $itemarray=array();
   foreach($filearray as $item){
       $itemdetail=explode($separator, $item);
       array_push($itemarray, $itemdetail);
   }


   return $itemarray;
}
//-------------------------------------------------------------------------------
//rejestracja
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
function addUser(string $login, string $author, string $password, $userdatabase){
   $file=file_get_contents($userdatabase, true);
   $file.=nl2br($login.",".$author.",".md5($password).","."user")."\n";
   file_put_contents($userdatabase,$file);
}
//-------------------------------------------------------------------------------
//logowanie
function getUserInfo(string $login){
   $users=readFileDetails("./users.txt");
   foreach($users as $user){
       if($user[0]==$login)
           return $user;
   }


}
//-------------------------------------------------------------------------------
//edycja i usuwanie postów
function edit_post($topicid,$topictext,$topic_body,$author,$topic_file, $separator=":-:"){
   $posts=get_topics($topic_file);
   //foreach($posts as $post)echo "<div>".json_encode($post)."</div>";
   $editedarray=array();
   foreach($posts as $post){
      if($post['topicid']==$topicid)
         array_push($editedarray, nl2br(
            $post['topicid'].','.
            bin2hex($topictext).','.
            bin2hex($topic_body).','.
            bin2hex($author).','.
            date("Y-m-d H:i:s")
         ));
      else
         array_push($editedarray, nl2br(
            $post['topicid'].','.
            bin2hex($post['topic']).','.
            bin2hex($post['topic_body']).','.
            bin2hex($post['username']).','.
            $post['date']
         ));
   }
   //echo "<br \><br \><br \>";
   //foreach($editedarray as $post)echo "<div>".json_encode($post)."</div>";
   $newdata=implode("\n",$editedarray);
   //echo $newdata;
   file_put_contents($topic_file,$newdata);
   header("location: ./index.php");
}

function del_post($topicid, $topic_file, $posts_file){
   $posts=get_topics($topic_file);
   //foreach($posts as $post)echo "<div>".json_encode($post)."</div>";
   $editedarray=array();
   foreach($posts as $post){
      if($post['topicid']!=$topicid)
         array_push($editedarray, nl2br(
            $post['topicid'].','.
            bin2hex($post['topic']).','.
            bin2hex($post['topic_body']).','.
            bin2hex($post['username']).','.
            $post['date']
         ));
   }
   //echo "<br \><br \><br \>";
   //foreach($editedarray as $post)echo "<div>".json_encode($post)."</div>";
   $newdata=implode("\n",$editedarray);
   //echo $newdata;
   file_put_contents($topic_file,$newdata);
   //usuwanie wszystkich wypowiedzi z danego postu
   delete_opinion_by_topic($topicid, $posts_file);




   header("location: ./index.php");

}
//-------------------------------------------------------------------------------
//usuwanie wypowiedzi z danego postu
function delete_opinion_by_topic($topicid, $posts_file){
   $opinions=file($posts_file);
   $editedopinions=array();
   //foreach($opinions as $opinion)echo "<div>".json_encode($opinion)."</div>";
   foreach($opinions as $opinion){
      $opiniondetail=explode(",",$opinion);
      if($opiniondetail[1]!=$topicid){
         array_push($editedopinions, (
            $opinion
         ));
      }
   }
   //echo "<br \><br \><br \>";
   //foreach($editedopinions as $opinion)echo "<div>".json_encode($opinion)."</div>";
   $newdata=implode("\n", $editedopinions);
   //echo $newdata;
   file_put_contents($posts_file,$newdata);
}
//-------------------------------------------------------------------------------
//zmiana uprawnien uzytkownika
function changeuserlevel($userlogin,$userdatabase){
   $users=readFileDetails($userdatabase, ",", "\n");
   $updatedusers=array();
   foreach($users as $user){
      if($user[0]==$userlogin){
         array_push($updatedusers, implode(",",array(
            $user[0],
            $user[1],
            $user[2],
            ($user[3]=='admin')?'user':'admin'  
         )));
      }else{
         array_push($updatedusers, implode(",",$user));

      }
      
      //echo "<div>".json_encode($user)."</div>";
   }
   //foreach($updatedusers as $user)echo "<div>".json_encode($user)."</div>";
   $newdata=implode("\n",$updatedusers)."\n";
   //echo "<br \><br \><br \>";
   //echo $newdata;
   file_put_contents($userdatabase, $newdata);

}
//-------------------------------------------------------------------------------
//edycja i usuwanie postów
function deleteuser($userlogin, $userdatabase, $postfile, $opinionfile, $curruser){
   if($userlogin=='admin')
      return;
   //usuwanie postow i wypowiedzi uzytkownika
   $posts=readFileDetails($postfile);
   
   //foreach($posts as $post)echo "<div>".json_encode(($post[0]))."</div>";
   foreach($posts as $post)
      if(hex2bin($post[3])==$userlogin)
         del_post($post[0],$postfile, $opinionfile);


   $opinions=readFileDetails($opinionfile);
   //foreach($opinions as $opinion)echo "<div>".hex2bin($opinion[3]) ."</div>";
   //foreach($opinions as $opinion)echo "<div>".json_encode($opinion) ."</div>";
   $editedopinions=array();
   foreach($opinions as $opinion){
      if(hex2bin($opinion[3])!=$userlogin){
         array_push($editedopinions, nl2br(
            $opinion[0]. "," .
            $opinion[1]. "," .
            $opinion[2]. "," .
            $opinion[3]. "," .
            $opinion[4]
         ));
      }
   }
   //echo "<br \><br \><br \>";
   //foreach($editedopinions as $opinion)echo "<div>".json_encode($opinion) ."</div>";
   $newdata=implode("\n", $editedopinions)."\n";
   //echo $newdata;
   file_put_contents($opinionfile,$newdata);
   //usuwanie użytkownika
   $users=readFileDetails($userdatabase);
   //foreach($users as $user)echo "<div>".json_encode($user) ."</div>";
   $editedusers=array();
   foreach($users as $user)
      if($user[0]!=$userlogin){
         array_push($editedusers, nl2br(
            $user[0]. ",".
            $user[1]. ",".
            $user[2]. ",".
            $user[3]
         ));
      }
   //echo "<br \><br \><br \>";
   //foreach($editedusers as $user)echo "<div>".json_encode($user) ."</div>";
   $newdata=implode("\n", $editedusers)."\n";
   file_put_contents($userdatabase,$newdata);
   //jeżeli usuwamy zalogowanego użytkownika, wylogowujemy go
   if($userlogin==$curruser[0]){
      header("location: ./index.php?cmd=logout");
   }else{
      header("location: ./index.php?cmd=showusers");
   }
}