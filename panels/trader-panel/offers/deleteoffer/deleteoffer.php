<?php

session_start();

include_once "../../../../connection/connect.php";
$connection = getConnection();

if(isset($_SESSION['trader'])) {
    $user_id = $_SESSION['trader'];
}

if(isset($_SESSION['admin_as_trader'])) {
    $user_id = $_SESSION['admin_as_trader'];
}


if (isset($user_id)) {

    $offer_id = $_GET['offer_id'] ??= "";

    if (isset($offer_id)) {

        $query = "DELETE FROM OFFERS WHERE OFFERS.OFFER_ID = $offer_id";
        echo $query;
        $result = oci_parse($connection, $query);

        if (oci_execute($result)) {
            header('Location: http://localhost/website/project/panels/trader-panel/offers/displayoffer/displayoffer.php?delete=success');
        }else {
            header('Location: http://localhost/website/project/panels/trader-panel/offers/displayoffer/displayoffer.php?delete=failed');
        }
    }


} else {
    header('Location: /website/project/index.php');
}