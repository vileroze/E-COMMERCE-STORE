<?php

session_start();

include_once "../../../../connection/connect.php";
$connection = getConnection();

if(isset($_SESSION['admin_as_trader'])) {

    $product_id = $_GET['id'] ??= "";

    if(!empty($product_id)) {
        $query = "UPDATE PRODUCTS SET STATUS = 1 WHERE PRODUCTS.PRODUCT_ID = $product_id";
        $result = oci_parse($connection, $query);

        if(oci_execute($result)) {
            header("Location: http://localhost/website/project/panels/trader-panel/crud-product/displayproduct/displayproduct.php?enabled=success");

        }else {
            header("Location: http://localhost/website/project/panels/trader-panel/crud-product/displayproduct/displayproduct.php?enabled=unsucess");
        }

    }

}else {
    header('Location: /website/project/index.php');
}