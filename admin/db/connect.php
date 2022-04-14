<?php 
    try{
        $connect=new PDO('mysql:host=localhost;dbname=agri','root','');
        //echo "connected";
    }catch(PDOException $e){
        echo $e->getMessage();
    }
?>