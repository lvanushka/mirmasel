<?php
class User {
    private $id;
    private $name;
    private $fullName;
    private $pass;
    private $role;
    private $isActive;
    public function __construct($id) {
        require_once './inc/config.inc';
        $con=Con();
        $sql="select * from person where id=?";
        $stmt=$con->prepare($sql);
        $stmt->bind_param(i, $id);
        $stmt->bind_result($user['id'],$user['name'],$user['fullName'],
                           $user['pass'],$user['role'],$user['active']);
        $stmt->execute();$stmt->store_result();
        if ($stmt->affected_rows==1){
        $stmt->fetch();
        $this->id=$user['id'];
        $this->name=$user['name'];
        $this->fullName=$user['fullName'];
        $this->pass=$user['pass'];
        $this->role=$user['role'];
        $this->isActive=$user['active'];
        return $this;
        } else 
        {return false;}
    }
    public function info(){
                $user['id']=$this->id;
                $user['name']=$this->name;
                $user['fullName']= $this->fullName;
                $user['pass']= $this->pass;
                $user['role']=$this->role;
                $user['active']=$this->isActive;
                return $user;}
    public function get($userId){
        require_once 'inc/config.inc';
        $con=Con();
            }
    public function put(){}
}

class Product {
    public function __construct() {
        ;
    }
    public function info(){}
    public function get() {}
    public function put() {}
    //put your code here
}
class Cart {
    public function __construct() {
        ;
    }
    public function info(){}
    public function get() {}
    public function put() {}
    //put your code here
}

class Order {
    public function __construct() {
        ;
    }
    public function info(){}
    public function get() {}
    public function put() {}
    //put your code here
}
