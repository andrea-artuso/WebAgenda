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
    case "add":
        router_crud_handler("add", strtolower($reversedpath[1]));
        break;
    case "edit":
        router_crud_handler("edit", strtolower($reversedpath[1]));
        break;
    case "delete":
        router_crud_handler("delete", strtolower($reversedpath[1]));
        break;
    default:
        // 404
        include "views/errors/404.php";
        break;
}


function router_crud_handler($operation, $resource){
    if (in_array($resource, RESOURCES)){
        include "controllers/$resource/$operation.controller.php";
    } else {
        // 404
        include "views/errors/404.php";
    }
}