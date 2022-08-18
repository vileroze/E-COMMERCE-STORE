<?php


if(isset($_POST['insertOffer'])) {

    if(!empty($_POST['offer_discount'])) {

        $offer_discount = htmlspecialchars(trim($_POST['offer_discount']));

        $trader_id = get_user_type_id($_SESSION['trader'], $connection, "TRADERS");

        if(!empty($_POST['offer_desc'])) {
            $description = htmlspecialchars(trim($_POST['offer_desc']));
            $query = "INSERT INTO OFFERS(OFFER_ID, PERCENTAGE, DESCRIPTION, FK_TRADER_ID) VALUES(null, $offer_discount, '$description', $trader_id)";

        }else {
            $query = "INSERT INTO OFFERS(OFFER_ID, PERCENTAGE, DESCRIPTION, FK_TRADER_ID) VALUES(null, $offer_discount, null , $trader_id)";
        }

        $result = oci_parse($connection, $query);
        oci_execute($result);

        echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-success border p-2 border-success w-50 mx-auto text-center mt-4'><i class='fas fa-check-circle'></i>&nbsp;&nbsp;SUCCESS: OFFER ADDED SUCCESSFULLY</p>";

    }else {
        echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-danger border p-2 border-danger w-50 mx-auto text-center mt-4'><i class='fas fa-times-circle'></i>&nbsp;&nbsp;ERROR: OFFER DISCOUNT FIELD CANNOT BE LEFT EMPTY</p>";

    }

}