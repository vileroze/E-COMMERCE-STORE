<?php

session_start();

include_once "../../../../connection/connect.php";
$connection = getConnection();

if (isset($_SESSION['trader']) || isset($_SESSION['admin_as_trader'])) {
    $id = $_GET['id'] ??= "";

    if (isset($id)) {
        $query = "DELETE FROM PRODUCTS WHERE PRODUCTS.PRODUCT_ID = $id";
        $result = oci_parse($connection, $query);
        oci_execute($result);

        header('Location: http://localhost/website/project/panels/trader-panel/crud-product/displayproduct/displayproduct.php?delete=success');

    }else {
        header('Location: http://localhost/website/project/panels/trader-panel/crud-product/displayproduct/displayproduct.php');
    }

} else {
    header('Location: /website/project/index.php');
}