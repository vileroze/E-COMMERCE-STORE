<?php

session_start();

include_once "../../../connection/connect.php";
$connection = getConnection();

$message = $_GET['type'] ??= "";

include_once "../../../assets/trader-types/functions.php";

if(isset($_SESSION['admin'])) {
    if(isset($message)) {

        $trader_id = fetch_trader_id_from_trader_type($message, $connection);
        $trader_user_id = get_user_id_from_trader_id($trader_id, $connection);

        if(isset($trader_id)) {
            $_SESSION['admin_as_trader'] = $trader_user_id;
            echo $_SESSION['admin_as_trader'];
            header('Location: /website/project/panels/trader-panel/crud-product/displayproduct/displayproduct.php');

        }else {
            header('Location: /website/project/panels/admin-panel/shops/display-shops.php');
        }

    }else {
        header('Location: /website/project/panels/admin-panel/shops/display-shops.php');
    }
}else {
    header('Location: /website/project/index.php');
}