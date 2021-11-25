<?php 
date_default_timezone_set("Asia/Bangkok");
class chat{
    public $username;
    public $password;
    public $email;
    public $subject;
    public $body;
    public $token;
    public function __construct()
    {
        if(isset($_POST["username"])){
            $this->username = str_replace("'","",trim($_POST["username"]));
        }
        if(isset($_POST["password"])){
            $this->password = str_replace("'","",trim($_POST["password"]));
        }
        if(isset($_POST["phone"])){
            $this->phone = str_replace("'","",trim($_POST["phone"]));
        }
        if(isset($_POST["body"])){
            $this->body = str_replace("'","",trim($_POST["body"]));
        }
        if(isset($_POST["subject"])){
            $this->subject = str_replace("'","",trim($_POST["subject"]));
        }
        $this->token = md5(time());
    }

    protected function connect(){
        $connect = mysqli_connect('localhost','root','','project');
        return $connect;
    }

    protected function query($command){
        $query = mysqli_query($this->connect(),$command);
        return $query;
    }

    #register validation
    public function required(){
        foreach($_POST as $key => $val){
            if(empty($val)){
                return false;
            }
        }
        return true;
    }

    protected function usernameCheck(){
        $query = $this->query("select * from user where username = '".$this->username."'");
        $row = mysqli_num_rows($query);
        if($row > 0){
            return false;
        }else{
            return true;
        }
    }


    protected function uniqueNumber(){
        $query = $this->query("select * from user where phone = '".$this->phone."'");
        $row = mysqli_num_rows($query);
        if($row > 0){
            return false;
        }else{
            return true;
        }
    }
    public function noNumeric(){
        if(is_numeric($this->phone) == true){
            return true;
        }else{
            return false;
        }
    }

    public function domainCheck(){
        $number = $this->phone;
        if($number[0] == "0" and $number[1] == "8"){
            return true;
        }else{
            return false;
        }
    }

    public function noLength(){
        $len = strlen($this->phone);
        if($len >= 10 and $len <= 13){
            return true;
        }else{
            return false;
        }
    }
    protected function insertUser(){
        $insert = $this->query("insert into user (username,phone,password) values(
            '".strip_tags($this->username)."',
            '".strip_tags($this->phone)."',
            '".strip_tags(password_hash($this->password,PASSWORD_DEFAULT))."'
        )");
        return $insert;
    }

    public function registerExec(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if($this->required() == false){
                $build["message"] = "please required this field";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->usernameCheck() == false){
                $build["message"] = "user is already exist";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->noNumeric() == false){
                $build["message"] = "phone must contain a number";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->domainCheck() == false){
                $build["message"] = "phone must contain 08 at the beginning";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->noLength() == false){
                $build["message"] = "please contain a valid number";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->uniqueNumber() == false){
                $build["message"] = "Number is already exist";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }else{
                if($this->insertUser()){
                    $build["message"] = "data has been insert";
                    $build["status"] = true;
                    header("HTTP/1.1 200 OK");
                }
            }
        }else{
            $build["message"] = "method not supported";
            $build["status"] = false;
            header("HTTP/1.1 404 ");
        }
        return $build;
    }

    #login validation
    protected function checkUser(){
        $query = $this->query("select * from user where username = '".$this->username."'");
        $row = mysqli_num_rows($query);
        if($row == 0 ){
            return false;
        }else{
            return true;
        }
    }

    protected function passwordCheck(){
        $query = $this->query("select * from user where username = '".$this->username."'");
        while($show = mysqli_fetch_assoc($query)){
            if (password_verify($this->password, $show['password'])) {
                return true;
            } else {
                return false;
            }
        }
      
    }

    protected function insertToken(){
        $query = $this->query("insert into token (username,token) values(
            '".strip_tags($this->username)."',
            '".$this->token ."'
        )");
        return $query;
    }

    public function loginExec(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            if($this->required() == false){
                $build["message"] = "please required this field";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->checkUser() == false){
                $build["message"] = "user is doesnt exist";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }elseif($this->passwordCheck() == false){
                $build["message"] = "invalid password";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
            }else{
                if($this->insertToken()){
                    $build["message"] = "login success";
                    $build["status"] = true;
                    $build["token"] = $this->token;
                    header("HTTP/1.1 200 ");
                }
            }
        }else{
            $build["message"] = "method not supported";
            $build["status"] = false;
            header("HTTP/1.1 404 ");
        }
        return $build;
    }


    #dashboard function
    public function tokenRequire(){
        $header = getallheaders();
        $token = $header["token"];
        if($token == null){
            return false;
        }else{
            return true;
        }
    }

    public function tokenCheck(){
        $header = getallheaders();
        $token = $header["token"];
        $query = $this->query("select * from token where token = '".$token."' ");
        $row = mysqli_num_rows($query);
        if($row == 0 ){
            return false;
        }else{
            return true;
        }
    }

    protected function checkMessage(){
        $query = $this->query("select * from message");
        $row = mysqli_num_rows($query);
        if($row == 0){
            return false;
        }else{
            return true;
        }
    }
 
    protected function listMessage(){
      $query = $this->query("select * from message  where sender = '".$this->getSender()."'");
      $row = mysqli_num_rows($query);
      if($row == 0){
        $build["message"] = "you dont have a convertation before";
      }else{
        while($show = mysqli_fetch_assoc($query)){
        $getUser = $this->query("select * from user where id = '".$show['recv_id']."'");
            while($shows = mysqli_fetch_assoc($getUser)){
            $getSender = $this->query("select * from user where id = '".$show['sender']."'");
                    while($showSender = mysqli_fetch_assoc($getSender)){
                    $build[] = $show;  
                    foreach($build as $key => $val){
                        $build[$key]["sender"] = $showSender['username'];
                        $build[$key]["received"] = $shows['username'];
                        $build[$key]["time"] = date("d-m-Y H:i a",$show['time']);
                    }
                }  
            }
        }
    } 
      return $build;
    
    }
    
    public function dashboardExec(){
        if($this->tokenRequire() == false){
            $build["message"] = "please required token on header";
            $build["status"] = false;
            header("HTTP/1.1 401 ");
        }elseif($this->tokenCheck() == false){
            $build["message"] = "token is doesnt exist";
            $build["status"] = false;
            header("HTTP/1.1 401 ");
        }elseif($this->checkMessage() == false){
            $build["message"] = "you dont have a conversation yet";
            $build["status"] = true;
            header("HTTP/1.1 200 ");
        }else{
            $build["data"] = $this->listMessage();
            
            header("HTTP/1.1 200 ");
          
        }
        return $build;
    }
    protected function deleteToken(){
        $header = getallheaders();
        $token = $header["token"];
        $delete =  $this->query("delete from token where token = '".$token."'");
        return $delete;
    }

    protected function logout(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){ 
           if($this->tokenCheck() == false){
                $build["message"] = "token is doesnt exist";
                $build["status"] = false;
                header("HTTP/1.1 401 ");
           }else{
                if($this->deleteToken()){
                    $build["message"] = "logout success ";
                    $build["status"] = true;
                    header("HTTP/1.1 200 ");
                }
           }
        }else{
            $build["message"] = "method not supported";
            $build["status"] = false;
            header("HTTP/1.1 404 ");
        }
        return $build;
    }


    public function logouts(){
        return $this->logout();
    }

    // chat function
    protected function getSender(){
        $header = getallheaders();
        $token = $header["token"];
        $getToken = $this->query("select * from token where token = '".$token."'");
        while($show = mysqli_fetch_assoc($getToken)){
            $list = $this->query("select * from user where username = '".$show['username']."'");
            while($show_user = mysqli_fetch_assoc($list)){
                $user = $show_user['id'];
            }
        }
        return $user;
    }
    protected function getReceiver(){
        $query = $this->query("select * from user where username = '".$this->username."'");
        $row = mysqli_num_rows($query);
        if($row == 0){
            return false;
        }else{
            while($show = mysqli_fetch_assoc($query)){
                $id = $show['id'];
            }
            return $id;
        }
        
    }
    protected function insertChat(){
        $insert = $this->query("insert into message (sender,recv_id,body,time) values(
            '".strip_tags($this->getSender())."',
            '".strip_tags($this->getReceiver())."',
            '".$this->body."',
            '".time()."'
        )");
        return $insert;
    }

    public function duplicateSender(){
        if($this->getSender() == $this->getReceiver()){
            return false;
        }else{
            return true;
        }
    }

    public function chatExec(){
        $date = date("d-m-Y H:i a",time());
        if($_SERVER["REQUEST_METHOD"] == "POST"){ 
            if($this->tokenCheck() == false){
                 $build["message"] = "token is doesnt exist";
                 $build["status"] = false;
                 header("HTTP/1.1 401 ");
            }elseif($this->getReceiver() == false){
                $build["message"] = "user has doesnt exist";
                 $build["status"] = false;
                 header("HTTP/1.1 401 ");
            }elseif($this->duplicateSender() == false){
                 $build["message"] = "seems you sent your messages to yourself";
                 $build["status"] = false;
                 header("HTTP/1.1 401 ");
            }else{
                if($this->insertChat()){
                 $build["message"] = "message sent!";
                 $build["status"] = true;
                 $build["body"] = $this->body;
                 $build["time"] = $date;
                 header("HTTP/1.1 200 ");
                }
            }
         }else{
             $build["message"] = "method not supported";
             $build["status"] = false;
             header("HTTP/1.1 404 ");
         }
        return $build;
    }

    // contacts function 
    protected function getContacts(){
        $header = getallheaders();
        $token = $header["token"];
        $query = $this->query("select * from token where token = '".$token."'");
        while($show = mysqli_fetch_assoc($query)){
            $getUser = $this->query("select * from user ");
            while($shows = mysqli_fetch_assoc($getUser)){
                $build[] = $shows;
                foreach($build as $key => $val){
                    $build[$key]["phone"] = $shows["phone"];
                    $build[$key]["password"] = "hidden";
                }
            }
           
        }
        return $build;
    }

    public function Contacts(){
        return $this->getContacts();
    }
}

?>