<?php
require_once 'autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['add']))
    {
        if($_POST['task'] == "")
        {
            Helper::redirect('index.php', 'Please fill the task and date');
        }
        if($_POST['due'] !== "")
        {
            $datetime = new DateTime($_POST['due']);
            $due = $datetime ->format('Y-m-d H:i:s');
        }
        else {
            $due = NULL;
        }
        $name = $_POST['task'];
        $createdTask = DB::create('tasks',["name"=> $name,
            "due_date"=> $due]);
        if ($createdTask)
        {
            $_POST['due'] = '';
            Helper::redirect('index.php','Task Created Successfully');
        }
    }