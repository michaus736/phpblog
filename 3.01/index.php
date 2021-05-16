<?php
session_start();
// Załadowanie funkcji
include("data.php");

// Konfiguracja
$posts_file = 'wypowiedzi1.txt';
$topic_file = 'tematy1.txt';
$users_file = "./users.txt";
$separator = ",";

//rejestracja
if(isset($_POST['NEWLOGIN'])&&isset($_POST['NEWAUTHOR'])&&isset($_POST['NEWPASSWORD'])&&isset($_POST['NEWPASSWORD2'])){
  if(!preg_match('/[1-9a-zA-Z"-"]{3,}/i',$_POST['NEWLOGIN'])){
    $badloginregister=true;
  }
  else if($_POST['NEWPASSWORD']!=$_POST['NEWPASSWORD2']){
    $notequalpasswordregister=true;
  }
  else if(isUser($_POST['NEWLOGIN'],$users_file)){
    $userexistalreadyregister=true;
  }
  else{
    addUser($_POST['NEWLOGIN'],$_POST['NEWAUTHOR'],$_POST['NEWPASSWORD'], $users_file);
  }

}
//logowanie
if(isset($_POST['LOGIN'])&&isset($_POST['PASSWORD'])){
    
  if(isUser($_POST['LOGIN'],$users_file)){
      $currentUser=getUserInfo($_POST['LOGIN']);
      if(md5($_POST['PASSWORD'])==$currentUser[2]){
          $_SESSION['LOGIN']=$_POST['LOGIN'];

      }
      else{
          $wronglogin=true;
      }
  }
  else{
      $wronglogin=true;
  }
}
//utrzymanie sesji
if(isset($_SESSION['LOGIN'])){
  $currentUser=getUserInfo($_SESSION['LOGIN']);
}

//komendy
if(isset($_GET['cmd'])){
  if($_GET['cmd']=='logout'){//wylogowanie użytkownika
    unset($_SESSION['LOGIN']);
    header("location: ./index.php");
  }
  elseif($_GET['cmd']=='edit'&&isset($_GET['topictoedit'])&&isset($_GET['execute'])){//edytowanie postów
    edit_post($_GET['topictoedit'], $_POST['topic'], $_POST['edittopic_body'], $currentUser[0], $topic_file);
  }
  elseif($_GET['cmd']=='delete'&&isset($_GET['topictodelete'])){//usuwanie postów
    del_post($_GET['topictodelete'], $topic_file, $posts_file);
  }
  elseif($_GET['cmd']=='showusers'&&isset($_GET['changeuserlevel'])){//zmiana przywilejów użytkownika
    changeuserlevel($_GET['changeuserlevel'], $users_file);
  }
  elseif($_GET['cmd']=='showusers'&&isset($_GET['deleteuser'])){//usuwanie użytkownika
    deleteuser($_GET['deleteuser'], $users_file, $topic_file, $posts_file, $currentUser);
  }
}









if( !is_file($posts_file) ) file_put_contents($posts_file,'');
if( !is_file($topic_file) ) file_put_contents($topic_file,'');

// zapis tematu
if( isset($_POST['topic']) and !isset($_POST['edittopic_body']) and $_POST['topic']!="" and $_POST['topic_body']!="" and isset($currentUser)){
  $res = put_topic($_POST['topic'], $_POST['topic_body'], $currentUser[0], $topic_file, $separator);
  header("Location: index.php");exit;
}   

// zapis lub aktualizacjia postu
if( isset($_POST['post']) and $_POST['post']!="" and isset($currentUser)){
  if( $_POST['postid']!='' ){
     $res = update_post( $_POST['postid'], $_POST['post'], $currentUser[0],  $posts_file, $separator );
  }else{
     $res = put_post( $_GET['topic'], $_POST['post'], $currentUser[0], $posts_file, $separator);
  }
  header("Location: index.php?topic=".$_GET['topic'] );exit;
}   

// kasowanie postu
if( isset($_GET['cmd']) and $_GET['cmd']=="delete" and $_GET['id']!="" and $_GET['topic']!=""){
  delete_post($_GET['id'], $posts_file, $separator);
  header("Location: index.php?topic=".$_GET['topic'] );exit;
}

// pobranie danych postu w celu ich edycji
if( isset($_GET['cmd']) and !isset($_GET['topictoedit']) and $_GET['cmd']=="edit" and $_GET['id']!="" and $_GET['topic']!=""){
  $post = get_post($_GET['id'], $posts_file, $separator);
}else{
  $post=false;
}  

// Pobranie wszystkicj tematów
$topics = get_topics($topic_file, $separator);

//-------------------------------------------------------------
// Prezentacja
//-------------------------------------------------------------
if(!isset($_SESSION['LOGIN'])){
  include("logowanie.php");
}else{
  if( isset($_GET["topic"]) and $_GET["topic"]!='' ) {  
    $posts = get_posts($_GET["topic"], $posts_file, $separator);
    $topic= $topics[$_GET["topic"]];
    include('wypowiedzi.php');
  } else { // widok tematów  
    // policz posty w tematach
    $posts_count = get_posts_count($posts_file, $separator);
    include('tematy.php');
  }
}
//testy


//echo json_encode($_SESSION);
//echo json_encode($currentUser);
//echo json_encode($wronglogin);
//delete_opinion_by_topic(2,$posts_file);
//changeuserlevel("add",$users_file);
?>