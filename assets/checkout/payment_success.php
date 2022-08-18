<?php session_start(); ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>payment Success</title>
        <!--Bootstrap CDN Link-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"/>

        <!--External CSS Link-->
        <link rel="stylesheet" href="displayProducts.css"/>

        <!-- Font awesome CDN -->
        <script src="https://kit.fontawesome.com/962cfbd2be.js" crossorigin="anonymous"></script>
    </head>
    <body>
    <?php
    include '../../connection/connect.php';
    $connection = getConnection();


    include_once "../trader-types/functions.php";

    //GET from paypal.php url
    $collection_id = $_GET['collection_slot_id'];
    $user_id = $_SESSION['user'];
    $customer_id = get_user_type_id($user_id, $connection, "CUSTOMERS");
    $basket_token = $_SESSION['basket_token'];
    $basket_id = get_basket_id_from_baskets($basket_token, $connection);
    $total_sum = fetch_total_sum_from_baskets($basket_id, $connection);

    //check if connection was successful

    if (isset($_SESSION['user'])) {

        //insert into order after payment success
        $query1 = "INSERT INTO orders (order_id, payment_date, order_status, fk_basket_id, fk_collection_slot_id)
                VALUES (null, sysdate, null, $basket_id, $collection_id)";
        $qp1 = oci_parse($connection, $query1);

        if (oci_execute($qp1)) {
            echo "<button type='button' class='btn btn-outline-success btn-lg mt-4' style='white-space: normal;' disabled><i class='fas fa-check-circle' style='color:green;'></i>&nbsp;&nbsp;&nbsp;Orders table ma insert bhayo</button>";
        } else {
            echo "<button type='button' class='btn btn-outline-danger btn-lg mt-4' style='white-space: normal; margin-left: 20%;' disabled><i class='fas fa-times-circle' style='color:red;'></i>&nbsp;&nbsp;&nbsp;ERROR: Could not execute query</button>";
        }

        //making invoice and sending it to customer via mail
        $query2 = "SELECT product_id, order_id, email, customers.user_id, orders.payment_date,  first_name, last_name, product_name, item_price, basket_products.quantity
            FROM customers, users, products, basket_products, orders, baskets WHERE
            products.product_id = basket_products.fk_product_id AND basket_products.fk_basket_id = baskets.basket_id AND baskets.fk_customer_id = customers.customer_id AND
            customers.user_id = users.user_id AND baskets.basket_id = orders.fk_basket_id AND users.user_id = $user_id AND orders.order_id = (SELECT MAX(order_id) FROM orders)";
        $qp2 = oci_parse($connection, $query2);
        ob_start();
        include "Customerinvoice.php";
        $msg = ob_get_clean();
        $header = "From: <brajesh18@tbc.edu.np>\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        //send email to customer
        oci_execute($qp2);
        $countMail = 0;
        while (($roww = oci_fetch_assoc($qp2)) && ($countMail === 0)) {
            mail($roww['EMAIL'],"Nature's Fresh Mart - INVOICE",$msg, $header);
            $countMail++;
        }

        //Decreasing quantity in stock after ordering
        $resultQuantity = fetch_all_order_info_of_customer($customer_id, $connection);

        while($rowsDecrease = oci_fetch_assoc($resultQuantity)) {
            update_quantity_in_stock_of_products($rowsDecrease['PRODUCT_ID'], $rowsDecrease['QUANTITY'], $connection);
        }

        //making invoice and sending it to trader via mail
        $query3 = "SELECT DISTINCT traders.user_id, email FROM ORDERS, BASKETS, BASKET_PRODUCTS, PRODUCTS, users, traders, shops
                WHERE orders.order_id = (SELECT MAX(order_id) FROM orders)
                AND ORDERS.FK_BASKET_ID = BASKETS.BASKET_ID AND BASKET_PRODUCTS.FK_BASKET_ID = BASKETS.BASKET_ID
                AND BASKET_PRODUCTS.FK_PRODUCT_ID = PRODUCTS.PRODUCT_ID AND PRODUCTS.fk_shop_id = shops.shop_id
                AND shops.fk_trader_id = traders.trader_id AND traders.user_id = users.user_id";
        $qp3 = oci_parse($connection, $query3);

        oci_execute($qp3);
        while (($row = oci_fetch_assoc($qp3))){
            //individual trader and product data
            $query4 = "SELECT product_id, users.user_id, payment_date, order_id, email, first_name, last_name, product_name, item_price, basket_products.quantity, baskets.total_sum FROM ORDERS, BASKETS, BASKET_PRODUCTS, PRODUCTS, users, traders, shops
              WHERE orders.order_id = (SELECT MAX(order_id) FROM orders) AND ORDERS.FK_BASKET_ID = BASKETS.BASKET_ID
              AND BASKET_PRODUCTS.FK_BASKET_ID = BASKETS.BASKET_ID AND BASKET_PRODUCTS.FK_PRODUCT_ID = PRODUCTS.PRODUCT_ID AND PRODUCTS.fk_shop_id = shops.shop_id
              AND shops.fk_trader_id = traders.trader_id AND traders.user_id = users.user_id AND users.user_id = ".$row['USER_ID']."";

            $qp4 = oci_parse($connection, $query4);
            ob_start();
            include "Traderinvoice.php";
            $msg = ob_get_clean();
            $header = "From: <brajesh18@tbc.edu.np>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            //send email to trader
            oci_execute($qp4);
            $countTraderMail = 0;
            while (($rowt = oci_fetch_assoc($qp4)) && ($countTraderMail === 0)) {
                mail($rowt['EMAIL'],"Nature's Fresh Mart - TRADER INVOICE",$msg, $header);
                $countTraderMail++;
            }
        }

        //Inserting new basket id after payment for same customer
        $final_basket_token = "";

        try {
            $final_basket_token = bin2hex(random_bytes(25));

        }catch (Exception $exception) {

            $random_value = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

            for($i = 0; $i < 20; $i++) {
                $rand = rand(0, (strlen($random_value) - 1));
                $final_basket_token .= $random_value[$rand];
            }
        }

        insert_into_basket($customer_id, $final_basket_token, $connection);
        $_SESSION['basket_token'] = $final_basket_token;

        header('Location: /website/project/index.php?payment=success');

    } else {
        header('Location: /website/project/index.php');
    }

    ?>

    </body>
    </html>
<?php

