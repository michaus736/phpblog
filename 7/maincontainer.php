<?php
include("./datafile.php");
class Container{
    
    protected $db;
    protected $guests;
    protected $visitlogs;
    protected $guestsdb;
    protected $visitlogsdb;
    protected $notesdb;
    protected $usersdb;


    public function __construct() {
        try{ 
            $this->db = new PDO('sqlite:'.dirname(__FILE__).'/db.sq3'); 
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         }
         catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode();  exit; }
         $this->guestsdb=new SqlConnector($this->db, "guests", array("cookieID", "addressIP", "URI", "DateAndTime"), "cookieId", false);
         $this->visitlogsdb=new SqlConnector($this->db, "log", array("cookieID", "addressIP", "URIorAction", "DateAndTime"), "cookieId", false);
         $this->notesdb=new SqlConnector($this->db, "notes", array("noteID", "author", "text", "DateAndTime"), "noteID");
         $this->usersdb=new SqlConnector($this->db, "users", array("id", "userName", "userPasswordEnc"));

    }

    public function addDbView(SqlConnector $tableHndl, $res){
        $data= array();
        $data['tableNames']=$tableHndl->getNames();
        $data['queryResult']=$res;
        extract($data);
        return $data;
    }





    public function addViewData(){
        $data=array();
        $data['viewtype']="login";
        if(isset($_GET['view'])){   //login-register view switch
            if($_GET['view']=="login")
                $data['viewtype']="login";
            if($_GET['view']=="register")
                $data['viewtype']="register";
            if($_GET['view']=="login" || $_GET['view']=="register")
                unset($_SESSION['user']);
            
        }
        else{
            
        }

        if(isset($_SESSION['user'])){   //registered user view switch
            $data['viewtype']="notes";
            if(isset($_GET['view'])){
                if($_GET['view']=='administration' && $_SESSION['user']=='admin'){
                    $data['viewtype']="administration";
                }
            }
        }


        if(isset($_POST['userRegisterName'])&&isset($_POST['userRegisterPassword'])){   //register operations
            $userToRegister=$this->usersdb->get($_POST['userRegisterName'], "userName");
            if($userToRegister == [] || $userToRegister == 0){
                $this->usersdb->insert(array(
                    "userName" => $_POST['userRegisterName'],
                    "userPasswordEnc" => md5($_POST['userRegisterPassword'])
                    

                ));
                $data['viewtype']="notes";
                $_SESSION['user']=$_POST['userRegisterName'];
            }
            

        }



        if(isset($_POST['userLoginName'])&&isset($_POST['userLoginPassword'])){ //login operations
            $userToLogin=$this->usersdb->get($_POST['userLoginName'], "userName");
            if($userToLogin != [] && $userToLogin != 0 && md5($_POST['userLoginPassword']) == $userToLogin[0]['userPasswordEnc']){
                $data['viewtype']="notes";
                $data['userToLogin']=$userToLogin;
                $_SESSION['user']=$_POST['userLoginName'];
            }
        }
        
        if(isset($_POST['noteToSend'])){
            $this->notesdb->insert(array(
                "author" => $_SESSION['user'],
                "text" => nl2br($_POST['noteToSend']),
                "DateAndTime" => date("Y-m-d H:i:s"),

            ));
        }


        

        return $data;
    }











    public function createView($data){
        include("./views/header.php");
        extract($data);
        
        
        switch($data["viewtype"]){
            case "register":{
                extract($this->addDbView($this->usersdb, $this->usersdb->getAll()));
                include("./views/dbview.php");
                include("./views/register.php");
                break;
            }

            case "login":{
                extract($this->addDbView($this->usersdb, $this->usersdb->get("123", "userName")));
                include("./views/dbview.php");
                include("./views/login.php");
                break;
            }
            case "notes":{
                extract($this->addDbView($this->notesdb, $this->notesdb->get($_SESSION['user'], "author")));
                include("./views/dbview.php");
                include("./views/notes.php");
                break;
            }
            case "administration":{
                include("./views/administration.php");
                break;
            }
        }
        
        include("./views/footer.php");
    }

};


?>