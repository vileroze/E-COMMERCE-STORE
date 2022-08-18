<?php


session_start();

include_once "../../../../connection/connect.php";
$connection = getConnection();

include_once "../../functions.php";

if(isset($_SESSION['admin'])) {

    $product_id = $_GET['id'] ??= "";
    $review_id = $_GET['review'] ??= "";
    $trader_type = $_GET['type'] ??= "";

    if(isset($product_id)  && isset($review_id) && isset($trader_type)) {


        $query = "DELETE FROM REVIEWS WHERE REVIEWS.REVIEW_ID = $review_id";
        $result = oci_parse($connection, $query);

        if(oci_execute($result)) {
            header('Location: /website/project/assets/trader-types/individual-product/individual-product.php?search='. $product_id . '&type='.$trader_type);
        }

    }else {
        header('Location: /website/project/index.php');
    }
}else {
    header('Location: /website/project/index.php');
}