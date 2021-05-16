<?php
include("datafile.php");

class Forum
{
    // property declaration
    public $u=false;
    public $context;
    public $error="";
    public $baseurl;
    
    protected $topic;
    protected $post;
    protected $user;
    public $photos;

    public function __construct() {
      $this->topic = new Datafile( array("topic","topic_body","date","userid","topicid"), "topic.txt", "topicid" );
      $this->post  = new Datafile( array("post", "userid","topicid","date","postid"), "post.txt", "postid" );
      $this->user  = new Datafile( array("userid", "username","userlevel","pass"), "usr.txt", "userid", false );
      $this->photos = new Datafile(array("topicid","postid", "userid","oldname","newname", "detail", "date", "photoid"), "photos.txt", "photoid");

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
       $this->topic->insert(array("topic"=>$topic,
       "topic_body"=>$topic_body,
       "date"=>date("Y-m-d H:i:s"),
       "userid"=>$this->u['userid']
       ));
       $this->reload();
    }
    public function insert_photo($oldname, $newname, $detail){
       $a=array(//"topicid","postid", "userid","oldname","newname","date", "photoid"
          "topicid"=>$_SESSION['topicid'],
          "postid"=>$_POST['postId'],
          "userid"=>$this->u['userid'],
          "oldname"=>$oldname,
          "newname"=>$newname,
          "detail"=>$detail,
          "date"=>date("Y-m-d H:i:s"),
       );
       if($this->photos->insert($a))$this->reload();
       //echo "123";
       else return false;
    }
    public function update_photo($photoid, $newdetail){
       $photosAll=$this->photos->getAll();
       if($photosAll!=false){
         foreach($photosAll as $photo){
            if($photo['photoid']==$photoid){
               $photoToEdit=$photo;
               break;
            }
         }
         $this->photos->update(array(
            "topicid"=>$photoToEdit['topicid'],
            "postid"=>$photoToEdit['postid'],
            "userid"=>$photoToEdit['userid'],
            "oldname"=>$photoToEdit['oldname'],
            "newname"=>$photoToEdit['newname'],
            "detail"=>$newdetail,
            "date"=>date("Y-m-d H:i:s"),
            "photoid"=>$photoid

         ));
         $this->reload();
       }else{
          return false;
       }
       
    }

    public function delete_photo($photoid){
      $photosearch=$this->photos->getAll();
      

      //$data["chk"]=$photosearch;
      if($photosearch != false){
         foreach($photosearch as $photo){
            if($photo['photoid']==$photoid){
               $fileToDel=$photo['newname'];
               break;
            }
         }
         $this->photos->delete($photoid);
         unlink($fileToDel);
      }
      else{

         return false;
      }
      $this->reload();
    }

    public function delete_topic($topicid){
       $this->topic->delete($topicid);
       $this->reload();
    }
    public function update_topic($topicid,$topic,$topic_body){
       $this->topic->update(array("topicid"=>$topicid,
       "topic"=>$topic,
       "topic_body"=>$topic_body,
       "date"=>date("Y-m-d H:i:s"),
       "userid"=>$this->u['userid']
       ));
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
public function process(){

$data = array( "last_post"=>($lastpost = $this->post->getLastItem())?$lastpost["date"]:"- brak wpisów -",
               "topic"=>false,
               "error1"=>"",
               "error2"=>""
             );
//---------- Akcje -------------
if(isset($_POST['userid']) and $_POST['userid']!="" and isset($_POST['pass'])){
   if( !$this->login($_POST['userid'],$_POST['pass']) ){
      $data["error1"]=$this->error;
   }
}
if(isset($_POST['userid']) and $_POST['pass1']!="" and ($_POST['pass1']==$_POST['pass2'])){
   if( !$this->register($_POST['userid'],$_POST['username'],$_POST['pass1']) ){
      $data["error2"]=$this->error;
   }
}
if(isset($_GET['cmd']) and $_GET['cmd']=='logout'){
   $this->logout();
}
if(isset($_GET['cmd']) and $_GET['cmd']=='topics'){
   $_SESSION['context']=$this->context='topics';
   $this->reload();
}

if(isset($_GET['topic']) and $_GET['topic']!=''){
   $_SESSION['context']=$this->context="posts";
   $_SESSION['topicid']=$_GET['topic'];
   $this->reload();
}

if(isset($_FILES['newphoto'])){
   $file=$_FILES['newphoto'];
   $detail=$_POST['detail'];

    $fileName=$file['name'];
    $filePath=$file['tmp_name'];
    $fileSize=$file['size'];
    $fileError=$file['error'];
    $fileErrorMes= array();

    $fileNameExt=explode(".", $fileName);
    $fileExt=strtolower(end($fileNameExt));
    $photoExt=array("jpg","jpeg","png","gif");

    if(in_array($fileExt,$photoExt)){
        if($fileError==0){
            if($fileSize<=500000){
                $newFileName=uniqid('',true).".".$fileExt;
                $fileServerPath="photos/".$newFileName;

                if(move_uploaded_file($filePath, $fileServerPath)){
                    $this->insert_photo($fileName, $fileServerPath, $detail);
                }
                else{
                    $fileErrorMes['final']=1;
                }

            }else{
                $fileErrorMes['size']=1;
            }
        }else{
            $fileErrorMes['fileUploadError']=1;
        }
    }else{
        $fileErrorMes['ExtError']=1;
    }
}

if(isset($_GET['cmd'])&&$_GET['cmd']=='checkphotos'){
   $_SESSION['context']=$this->context='photos';
   $data['photos']=$this->photos->getAll();
   $data['users']=$this->user->getAll();
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
    if(isset($_GET['cmd']) and $_GET['cmd']=='delete')
       $this->delete_post($_GET['id']);
    if(isset($_GET['cmd']) and $_GET['cmd']=='edit'){
       $data['post']=$this->post->get($_GET['id']);
    }
    if(isset($_GET['cmdphoto'])&&isset($_GET['execute'])){
       if($_GET['cmdphoto']=='edit'){
          
         $this->update_photo($_GET['photoid'],$_GET['newdetail']);
       }
       elseif($_GET['cmdphoto']=='delete'){
         $this->delete_photo($_GET['photoid']);
       }

    }
  $data['users']=$this->user->getAll();
  $data['photos']=$this->photos->getAll(); 
  
} // end of context posts

if($this->context=='topics'){
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
 if(isset($_GET['cmd']) and $_GET['cmd']=='userlist'){
   $_SESSION['userlist']=($_SESSION['userlist'])?false:true;
   $this->context='topics';
   $this->reload();
 }
 if(isset($_GET['cmd']) and $_GET['cmd']=='topicdelete' and $this->u['userlevel']==10){
    if($p=$this->post->getAll($_GET['id'],'topicid')) 
        foreach( $p as $k=>$v) $this->post->delete($k);
   if($p=$this->photos->getAll($_GET['postid']))
      foreach($p as $k=>$v)$this->photos->delete($k);
    $this->topic->delete($_GET['id']);
    $this->reload();
 }
 if(isset($_GET['cmd']) and $_GET['cmd']=='topicedit' and $this->u['userlevel']==10){
    $data["topic"]=$this->topic->get($_GET['id']);
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
         if($p=$this->photos->getAll($_GET['userid'],'userid'))
            foreach($p as $k=>$v) $this->photos->delete($k);
       $this->delete_user($_GET['userid']);

    }   
 }
 $data['users']=$this->user->getAll();
 $data['topics']=$this->topic->getAll();
} // end of context topics
return $data;
} //---- end of function process()

  public function makepage($data){
    $this->view("header",$data);
    switch($this->context){
      case "topics":
        $this->view("userinfo",$data);
        $this->view("topics",$data);
      break;
      case "posts":
        $this->view("posts",$data);
      break;
      case "photos":
         $this->view("userinfo",$data);
         $this->view("photos",$data);
      break;
      default:
       $this->view("login",$data);
    }
    $this->view("footer",$data);
  } 
    
  public function view($view,$data=NULL){
      if($data) extract($data);
      include("view/$view.php");
  }
  
  protected function reload(){
     header("Location: $this->baseurl");
     exit;
  }
} //------ end of class Form ---------------------------------------------------   