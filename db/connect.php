<?php
    try{
        $connect=new PDO('mysql:host=localhost;dbname=agri','root','');
    }catch(PDOException $e){
        echo $e->getMessage();
    }
?>