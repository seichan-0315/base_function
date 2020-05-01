<?php 

function DBconnect(){
    try{
        $db=new PDO('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root','');
        
        return $db;
    } catch (PDOException $e){
         return false;
    }
}
    ?>
