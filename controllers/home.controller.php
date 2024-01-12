<?php
session_start();

if (isset($_SESSION['logged_user_id'])){
    $id = $_SESSION['logged_user_id'];

    require_once "core/functions.php";
    if (!isset($_GET['d'])){
        $d = date("Y-m-d");
    } else if (is_valid_date($_GET['d'])) {
        $d = $_GET['d'];
    } else {
        $error = "The date you selected is not valid.";
        require "views/home.view.php";
        exit;
    }
    
    require_once "core/database.php";
    $query = "SELECT * FROM memos WHERE memo_user_id = $id AND memo_date = '$d';";
    $result = $dbc->query($query);
    if ($result == false){
        $error = "Impossible to fetch data.";
    }

    require "views/home.view.php";
} else {
    require "views/errors/401.php";
    exit;
}