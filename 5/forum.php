<?php
include("datafile.php");
include("uploadfile.php");
include("captcha.php");
class Forum
{
    // property declaration
    public $u=false;
    public $context;
    public $error="";
    public $baseurl;
    public $captcha;
    
    protected $topic;
    protected $post;
    protected $user;
    protected $image;

    public function __construct() {
      $this->topic = new Datafile( array("topic","topic_body","date","userid","topicid"), "topic.txt", "topicid" );
      $this->post  = new Datafile( array("post", "userid","topicid","date","postid"), "post.txt", "postid" );
      $this->user  = new Datafile( array("userid", "username","userlevel","pass"), "usr.txt", "userid", false );
      $this->image = new Uploadfile( array("userid","postid","topicid","name","sufix","title","date","id"), "image.txt", "id" );
      if(!isset($this->content) and isset($_GET['register']) and $_GET['register']=="true"){
         $this->captcha = new Captcha();

      }
      $this->baseurl = "index.php";
      $this->context = (isset($_SESSION["context"]))?$_SESSION["context"]:NULL;
      $this->u = (isset($_SESSION["user"]))?$_SESSION["user"]:false;
      $this->user->insert(array( "userid"=>"admin", "username"=>"admin","userlevel"=>10,"pass"=>md5("admin") ));
    }                             
    
    public function login($userid,$pass){
         if( !($this->u=$this->user->get($userid)) ) {
            $this->error="Bad user name or password!";
            return false;
         } 
         if( $this->u["pass"]!=md5($pass) ){
            $this->error="Bad user name or password!"; 
            return false;
         }
         $_SESSION = array();
         session_regenerate_id();
         $_SESSION["token"] = md5(session_id().__FILE__);
         $_SESSION["user"] = $this->u;
         $_SESSION["context"] = "topics";
         $this->reload();
    }
    public function logout(){
       $_SESSION = array();
       if (ini_get("session.use_cookies")) {
          $params = session_get_cookie_params();
          setcookie(session_name(), '', time() - 42000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]
          );
       }
       session_destroy();
       $this->reload();
    }
    public function register($userid,$username,$pass){
      
       if($u=$this->user->get($userid)){
          $this->error .= "Bad username";return false; }
       $u = array("userid"=>$userid,"username"=>$username,"userlevel"=>0,"pass"=>md5($pass));   
       $this->user->insert($u);
       $_SESSION["user"] = $u;
       $_SESSION["context"] = "topics";
       $this->reload();
    }
    public function insert_topic($topic,$topic_body){
       $this->topic->insert(array("topic"=>$topic,"topic_body"=>$topic_body,"date"=>date("Y-m-d H:i:s"),"userid"=>$this->u['userid'] ));
       $this->reload();
    }
    public function delete_topic($topicid){
       $this->topic->delete($topicid);
       $this->reload();
    }
    public function update_topic($topicid,$topic,$topic_body){
       $this->topic->update(array("topicid"=>$topicid,"topic"=>$topic,"topic_body"=>$topic_body,"date"=>date("Y-m-d H:i:s"),"userid"=>$this->u['userid'] ));
       $this->reload();
    }
    public function insert_post($post){
          $p = array(
            "post"=>$post, 
            "userid"=>$this->u["userid"],
            "topicid"=>$_SESSION["topicid"],
            "date"=>date("Y-m-d H:i:s")
          );  
          if($this->post->insert($p)) $this->reload();
          else return false;
    }
    public function delete_post($postid){
          if( $this->post->delete($postid) ) $this->reload();
          else return false;
    }
    public function update_post($post,$postid){
       if($p = $this->post->get($postid)){
          $p['post']=$post;
          if( $this->post->update($p) ) $this->reload();
          else return false;
       }else return false;
    }
    public function delete_user($userid){
          if( $this->user->delete($userid) ) $this->reload();
          else return false;
    }
    public function update_user($userid){
       if($u = $this->user->get($userid)){
          $u['userlevel']=($u['userlevel']==10)?0:10;
          if( $this->user->update($u) ) $this->reload();
          else return false;
       }else return false;
    }
    public function count_posts($topicid){
        if( $p=$this->post->getAll($topicid,"topicid") ) return count($p);
        else return 0;   
    }
//-----------------------------------------
//---------- metoda prosess() -------------
//-----------------------------------------    
public function process(){

if( isset($_SESSION["token"]) and $_SESSION["token"] != md5(session_id().__FILE__)) $this->logout();

if(isset($_GET["image"]) and $_GET["image"]!=""){
   echo $this->image->send($_GET["image"], 300);
   exit;
}
if((isset($_POST['username']) and isset($_POST['pass1']) and isset($_POST['pass2']) and isset($_POST['captcha']) and isset($_POST['captcha'] ))
or (isset($_GET['register']) and isset($_GET['reloadimg']))){
   unlink($_GET['reloadimg']);
   header("location: index.php?register=true");
}


$data = array( "last_post"=>($lastpost = $this->post->getLastItem())?$lastpost["date"]:"- brak wpisów -",
               "topic"=>false,
               "images"=>false 
             );
//---------- Akcje publiczne -------------
if(isset($_POST['userid']) and $_POST['userid']!="" and isset($_POST['pass'])){
   if( !$this->login($_POST['userid'],$_POST['pass']) ){
      $data["error1"]=$this->error;
   }
}
if(isset($_POST['userid']) and $_POST['pass1']!="" and ($_POST['pass1']==$_POST['pass2'])
 and isset($_POST['captcha']) and strtoupper($_POST['captcha'])==$_POST['captcha2']){
   if( !$this->register($_POST['userid'],$_POST['username'],$_POST['pass1']) ){
      $data["error2"]=$this->error;
   }
}
if(isset($_GET['cmd']) and $_GET['cmd']=='logout'){
   $this->logout();
}
if(!isset($this->context) and isset($_GET['register']) and $_GET['register']=="true"){
   
   $data['imgkod']=$this->captcha->letterkey;
   $data['captcha']=$this->captcha->img;
   $data['test12321']="123";
   
   $data['photoimg']=$this->captcha->filepath;
   
}
if($this->context){  // --- akcje wymagajace zalogowania ---

   if(isset($_GET['cmd']) and $_GET['cmd']=='topics'){
     $_SESSION['context']=$this->context='topics';
     $this->reload();
   }
   if(isset($_GET['cmd']) and $_GET['cmd']=='images'){
     $_SESSION['context']=$this->context="images";
     $this->reload();
   }

   if(isset($_GET['cmd']) and $_GET['cmd']=='userlist'){
     $_SESSION['userlist']=($_SESSION['userlist'])?false:true;
     $this->reload();
   }
   if(isset($_GET['cmd']) and $_GET['cmd']=='changeuser' and $this->u['userlevel']==10){
      if($_GET['userid']!="admin") $this->update_user($_GET['userid']);
   }
   if(isset($_GET['cmd']) and $_GET['cmd']=='deluser' and $this->u['userlevel']==10){
      if($_GET['userid']!="admin") {
         if($p=$this->post->getAll($_GET['userid'],'userid')) 
             foreach( $p as $k=>$v) $this->post->delete($k);
         if($p=$this->topic->getAll($_GET['userid'],'userid')) 
             foreach( $p as $k=>$v) $this->topic->delete($k);
         if($img=$this->image->getAll( $_GET['userid'], "userid" ))
             foreach($img as $k=>$v) $this->image->delete($k);    
         $this->delete_user($_GET['userid']);
      }   
   }
}

if($this->context=='posts'){
    $data["topic"]=$this->topic->get($_SESSION['topicid']);
    $data["posts"]=$this->post->getAll($_SESSION['topicid'],"topicid");
    $data['post']=false;
    if(isset($_POST['post']) and $_POST['post']!='')
       if($_POST['postid']!='')
           $this->update_post($_POST['post'],$_POST['postid']);
       else
           $this->insert_post($_POST['post']);
    if(isset($_GET['cmd']) and $_GET['cmd']=='delete'){
       if( $this->image->delete_from_post($_GET['id']) )
           $this->delete_post($_GET['id']);
    }
    if(isset($_GET['cmd']) and $_GET['cmd']=='edit'){
       $data['post']=$this->post->get($_GET['id']);
    }

    if(isset($_GET['cmd']) and $_GET['cmd']=='imgdelete'){
       $this->image->delete($_GET['imgid']);  
        $this->reload();  
    }
    if(isset($_GET['cmd']) and $_GET['cmd']=='imgedit'){
       $_SESSION["imgedit"]=true;
       $_SESSION["imgid"]=$_GET['imgid'];
       $_SESSION["postid"]=$_GET['postid'];
       $this->reload();
    }
    if( isset($_FILES['image']) and $_FILES['image']['name']!="" ){
       if( !$this->image->insert($_FILES['image']) ){
         $data['uploaderror'] = $this->image->error; 
       }else{
         $this->reload();
       }
    }
    if( isset($_SESSION["imgedit"]) and isset($_POST['imagetitle']) ){
       if( !$this->image->update($_SESSION["imgid"], $_POST['imagetitle']) ){
         $data['uploaderror'] = $this->image->error; 
       }else{
         unset($_SESSION["imgedit"]);
         unset($_SESSION["imgid"]);
         $this->reload();
       }
    }

  $data['images']=$this->image->getAll($_SESSION['topicid'],"topicid");  
  $data['users']=$this->user->getAll(); 
} // end of context posts

if($this->context=='topics'){
  if(isset($_GET['cmd']) and $_GET['cmd']=='posts'){
      $_SESSION['context']=$this->context="posts";
      $_SESSION['topicid']=$_GET['id'];
      $this->reload();
  }
 if( isset($_POST['topic']) and $_POST['topic'] and $_POST['topic_body'] ){
   if($_POST['topicid']=="")
   $this->topic->insert(array("topic"=>$_POST['topic'],"topic_body"=>$_POST['topic_body'],
                              "date"=>date("Y-m-d H:i:s"),"userid"=>$this->u['userid']));
   else
   $this->topic->update(array("topic"=>$_POST['topic'],"topic_body"=>$_POST['topic_body'],
                              "date"=>date("Y-m-d H:i:s"),"userid"=>$this->u['userid'],
                              "topicid"=>$_POST['topicid']));
   $this->reload();
   }
 if(isset($_GET['cmd']) and $_GET['cmd']=='topicdelete' and $this->u['userlevel']==10){
    if($p=$this->post->getAll($_GET['id'],'topicid')) 
        foreach( $p as $k=>$v) $this->post->delete($k);
    $this->topic->delete($_GET['id']);
    $this->reload();
 }
 if(isset($_GET['cmd']) and $_GET['cmd']=='topicedit' and $this->u['userlevel']==10){
    $data["topic"]=$this->topic->get($_GET['id']);
 }
 $data['users']=$this->user->getAll();
 $data['topics']=$this->topic->getAll();
} // end of context topics

if($this->context=='images'){
  $data['users']=$this->user->getAll();
  $data['images']=$this->image->getAll();
}  // end of context images

return $data;
} //---- end of function process()


//-----------------------------------------
//---------- metoda makepage() -------------
//-----------------------------------------    
  public function makepage($data){
    $this->view("header",$data);
    switch($this->context){
      case "topics":
        $this->view("userinfo",$data);
        $this->view("topics",$data);
      break;
      case "posts":
        $this->view("userinfo",$data);
        $this->view("posts",$data);
      break;
      case "images":
        $this->view("userinfo",$data);
        $this->view("images",$data);
      break;
      default:
      if(isset($_GET['register']) and $_GET['register']=='true') 
      {
         $this->view("register",$data);
      }
      else $this->view("login",$data);
    }
    $this->view("footer",$data);
    //echo json_encode($data);
  } 
    
//-----------------------------------------
//---------- metoda view() -------------
//-----------------------------------------    
  public function view($view,$data=NULL){
      if($data) extract($data);
      include("view/$view.php");
  }
  
  protected function reload(){
     header("Location: $this->baseurl");
     exit;
  }
  
} //------ end of class Form ---------------------------------------------------   