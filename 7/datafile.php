<?php
class SqlConnector{

    
    protected $db, $table, $key, $autoincrement;
    protected $names;
    public function __construct( &$db, $table, $names, $key='id', $autoincrement=true) {
       $this->db = $db;
       $this->table=$table;
       $this->names = $names;
       $this->key = $key;
       $this->autoincrement = $autoincrement;

       $query="CREATE TABLE IF NOT EXISTS ".$this->table." ( ";
       foreach( $this->names as $v ) {
         if( $this->autoincrement ){ 
            if($this->key==$v) $query .= " $v INTEGER PRIMARY KEY AUTOINCREMENT, ";
            else $query .= " $v TEXT, ";
         }else{
            if($this->key==$v) $query .= " $v TEXT PRIMARY KEY, ";
            else $query .= " $v TEXT, ";
         }
       }
       $query = substr($query,0, strlen($query)-2);  
       $query.=" )";
       try{  $this->db->exec($query); }
       catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode(); exit; }
    }

    protected function query_insert($data){
        $query="insert into ".$this->table." ( ";
        foreach( $this->names as $v ) {
          if( $this->autoincrement and ($this->key==$v) ) continue; 
          $query .= " $v, ";
        }
        $query = substr($query,0, strlen($query)-2);
        $query.=" ) values ( ";
        foreach( $this->names as $v ) {
          if( $this->autoincrement and ($this->key==$v) ) continue; 
          $query .= " '$data[$v]', ";
        }
        $query = substr($query,0, strlen($query)-2);
        $query.=" )";
        return $query;
     }    
 
     public function insert($data) {
        $query = $this->query_insert($data);
        try{ $r = $this->db->exec($query); }
        catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query";  exit;}
        return $r;           
     }

     public function getNames(){ return $this->names; }

     public function getAll($val=false,$key=false, $order="") {
        if(!$key) $key=$this->key;
        if($val) $query="select * from ".$this->table." where $key='$val'".(($order)?" ORDER BY $order ":"");
        else $query="select * from ".$this->table.(($order)?" ORDER BY $order ":""); 
        try{ $r = $this->db->query($query); }
        catch(PDOException $e){ echo $e->getMessage().": ".$e->getCode()."<br />\nQuery: $query"; exit;}
        $result=array();
        while( $data = $r->fetch(\PDO::FETCH_ASSOC) ){
           $result[$data[$this->key]] = $data;
        }
        return $result;           
     }

     public function get($val,$key=false) {
    
        if(!$key) {
           $key=$this->key;
           }
       else{}
  
       $query="select * from ".$this->table." where $key='$val'"; // sql
  
       try{
           $r = $this->db->query($query);
          }
       catch(PDOException $e){
           echo $e->getMessage().":".$e->getCode()."SQL : $query";
           exit; 
       }
  
       return $r->fetchAll(\PDO::FETCH_ASSOC); 
         
      }

}

?>