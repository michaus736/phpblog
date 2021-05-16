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
        if(isset($_GET['view'])){
            if($_GET['view']=="login")
                $data['viewtype']="login";
            if($_GET['view']=="register")
                $data['viewtype']="register";
            
        }
        else{
            
        }

        if(isset($_POST['userRegisterName'])&&isset($_POST['userRegisterPassword'])){
            if($this->usersdb->get($_POST['userRegisterName'], "userName")==[] || $this->usersdb->get($_POST['userRegisterName'], "userName") == 0){
                $this->usersdb->insert(array(
                    "userName" => $_POST['userRegisterName'],
                    "userPasswordEnc" => md5($_POST['userRegisterPassword'])
                    

                ));
                $data['viewtype']="notes";
                $_SESSION['user']=$_POST['userRegisterName'];
            }
            

        }
        return $data;
    }











    public function createView($data){
        include("./views/header.php");
        extract($data);
        switch($data["viewtype"]){
            case "register":{
                include("./views/register.php");
                break;
            }

            case "login":{
                include("./views/login.php");
            }
            case "notes":{
                include("./views/notes.php");
            }
        }
        extract($this->addDbView($this->usersdb, $this->usersdb->getAll()));
        include("./views/dbview.php");
        include("./views/footer.php");
    }

};


?>