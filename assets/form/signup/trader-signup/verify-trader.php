<?php

include_once "../../../../connection/connect.php";
$connection = getConnection();

include_once "../../../../includes/html-skeleton/skeleton.php";
include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../../includes/cdn-links/bootstrap-cdn.php";

$token = $_GET['token'] ??= "";
$product_type = $_GET['type'] ??= "";
$shop_name = $_GET['shop'] ??= "";
$product_category = $_GET['cat'] ??= "";

if(isset($token) && isset($product_category) && isset($product_type) && isset($shop_name)) {

    include_once "../../form-functions.php";
    $user_id = get_user_id_from_token($token, $connection);
    $email = get_trader_mail_from_user_id($user_id, $connection);

    if(isset($user_id)) {
        $query = "UPDATE USERS SET STATUS = 1 WHERE USERS.TOKEN = '$token'";
        $resultAfter = oci_parse($connection, $query);

        if(oci_execute($resultAfter)) { ?>
            <div class="w-50 mx-auto mt-5">
                <p class="alert alert-success text-center font-rale"><b>Thank you, Trader has been verified and given access to register his products.</b></p>
            </div>

            <?php

            $queryInsert = "INSERT INTO TRADERS(USER_ID, TRADER_ID, TRADER_TYPE) VALUES($user_id, null, '$product_type')";
            $resultInsert = oci_parse($connection, $queryInsert);
            oci_execute($resultInsert);

            $trader_id = get_trader_id_from_traders($user_id, $connection);
            $queryShop = "INSERT INTO SHOPS(SHOP_ID, SHOP_NAME, PRODUCT_CATEGORY, FK_TRADER_ID) VALUES(null, '$shop_name', '$product_category', $trader_id)";
            $resultShop = oci_parse($connection, $queryShop);
            oci_execute($resultShop);


            $receiver = $email;
            $body = "Registration Successfull!";
            $subject = "<p>You have been registered as a trader and now you have access to register your products.</p>";
            $subject .= "<p> Please note that, there are terms and conditions as well as privacy polices for trader.</p>";
            $subject .= "<p>We will highly appreciate your honesty as a regular and honest trader.</p>";

            $header = "From: <brajesh18@tbc.edu.np>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if(mail($receiver, $body, $subject, $header)) {
            echo "<p class='alert alert-primary text-center font-rale'><b>Mail Sent to Trader.</b></p>";

            }else {
            echo "<p class='alert alert-warning text-center font-rale'><b>Invalid mail.</b></p>";
            }

            ?>

        <?php }

    }else { ?>

        <div class="w-50 mx-auto mt-5">
            <p class="alert alert-success text-center font-rale"><b>Provided token is invalid</b></p>
        </div>

    <?php }

}else {
    header('Location: /website/project/index.php');
}