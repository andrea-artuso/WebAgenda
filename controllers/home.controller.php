<?php
session_start();
require_once "core/cipher.php";

if (isset($_SESSION['logged_user'])){
    $id = $_SESSION['logged_user']["id"];
    $cipherkey = $_SESSION['logged_user']["cipher_key"];

    if (isset($_SESSION['success'])){
        echo "<div class='alert alert-success' role='alert'>".$_SESSION['success']."</div>";
        unset($_SESSION['success']);
    } else if (isset($_SESSION['error'])){
        echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']."</div>";
        unset($_SESSION['error']);
    }

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