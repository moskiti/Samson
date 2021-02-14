<?php
$connect = mysqli_connect('localhost','root','admin','test_samson');
$connect->set_charset("utf8");

if(!$connect){
    die('Error');
}