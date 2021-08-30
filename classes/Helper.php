<?php

class Helper
{
    public static function echoArr($arr)
    {
        echo "<pre style='color: red;'>";
        print_r($arr);
        die();
    }

    public static function redirect($route, $message = "")
    {
        if ($message !== "") {
            $_SESSION['message'] = $message;
        }
        header("location:$route");
        die();
    }
}