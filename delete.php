<?php

require_once 'autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET'  && isset($_GET['id']))
{
    $id = $_GET['id'];
    $flag = DB::delete('tasks',$id);

    if($flag)
    {
        Helper::redirect("index.php");
    }


}