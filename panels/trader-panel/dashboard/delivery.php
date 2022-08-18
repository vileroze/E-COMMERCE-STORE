<?php

session_start();

include_once "../../../connection/connect.php";
$connection = getConnection();

if (isset($_SESSION['trader']) || isset($_SESSION['admin_as_user'])) {
    $order_id = $_GET['id'] ??= "";

    if (isset($order_id)) {
        $query = "UPDATE ORDERS SET ORDERS.ORDER_STATUS = 'delivered' WHERE ORDERS.ORDER_ID = $order_id";
        $result = oci_parse($connection, $query);
        oci_execute($result);
    }

}

header('Location: http://localhost/website/project/panels/trader-panel/dashboard/dashboard.php');
