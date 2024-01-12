<?php

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
    case "add":
        if (strtolower($reversedpath[1]) == "memo"){
            include "controllers/add/memo.controller.php";
        } else if (strtolower($reversedpath[1]) == "category"){
            include "controllers/add/category.controller.php";
        } else {
            // 404
            include "views/errors/404.php";
        }
        break;
    case "edit":
        if (strtolower($reversedpath[1]) == "memo"){
            include "controllers/edit/memo.controller.php";
        } else if (strtolower($reversedpath[1]) == "category"){
            include "controllers/edit/category.controller.php";
        } else {
            // 404
            include "views/errors/404.php";
        }
        break;
    default:
        // 404
        include "views/errors/404.php";
        break;
}