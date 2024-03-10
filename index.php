<?php

define("RESOURCES", array("memo", "category"));

$patharray = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$reversedpath = array_reverse($patharray);

switch (strtolower($reversedpath[0])){
    case "":
    case "home":
        include "controllers/home.controller.php";
        break;
    case "login":
        include "controllers/auth/login.controller.php";
        break;
    case "register":
        include "controllers/auth/register.controller.php";
        break;
    case "logout":
        include "controllers/auth/logout.controller.php";
        break;
    case "memo":
        router_action_handler("memo");
        break;
    case "category":
        router_action_handler("category");
        break;
    default:
        // 404
        include "views/errors/404.php";
        break;
}


function router_action_handler($resource){
    if (isset($_GET['add'])){
        include "controllers/$resource/add.controller.php";
    } else if (isset($_GET['edit'])){
        include "controllers/$resource/edit.controller.php";
    } else if (isset($_GET['delete'])) {
        include "controllers/$resource/delete.controller.php";
    } else {
        header("Location: home");
    }
}