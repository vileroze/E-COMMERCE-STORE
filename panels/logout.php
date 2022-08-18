<?php

session_start();

include_once "../connection/connect.php";
$connection = getConnection();

//Destroying session when customer or admin or traders logs out from website
if(isset($_SESSION['user']) || isset($_SESSION['trader']) || isset($_SESSION['admin'])) {
    session_destroy();
    header('Location: /website/project/index.php?logged=out');
}else {
    header('Location: /website/project/index.php');
}