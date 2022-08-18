<?php

//Functions page that contains all functions needed for a  user to fetch different data

function fetch_all_products_of_trader($trader_type, $connection)
{
    $query = "SELECT * FROM PRODUCTS, SHOPS, TRADERS
                  WHERE PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID 
                  AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID
                  AND TRADERS.TRADER_TYPE = '$trader_type' AND PRODUCTS.STATUS = 1";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_all_products_of_trader_with_limit($min_row_id, $max_row_id, $trader_type,  $connection)
{
    $query = "SELECT PRODUCT_ID, PRODUCT_NAME, PRODUCT_IMAGE, ITEM_PRICE, PRODUCT_INFO, QUANTITY_IN_STOCK, ALLERGY_INFO, AVAILABLILITY, MAX_ORDER, MIN_ORDER, FK_OFFER_ID, STATUS, rownum AS rnum 
                FROM PRODUCTS, SHOPS, TRADERS 
                WHERE PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID 
                AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID 
                AND TRADERS.TRADER_TYPE = '$trader_type' 
                AND rownum < $max_row_id ";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $arrayProducts = [];

    while($rows = oci_fetch_assoc($result)) {
        if($rows['RNUM'] < $min_row_id) {
            continue;
        }

        $arrayProducts[] = $rows;
    }

    return $arrayProducts;
}

function count_all_products_of_trader($trader_type, $connection) {
    $query = "SELECT COUNT(*) AS COUNT FROM PRODUCTS, SHOPS, TRADERS
                  WHERE PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID 
                  AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID
                  AND TRADERS.TRADER_TYPE = '$trader_type'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $total_products = 0;
   while($rows = oci_fetch_assoc($result)) {
       $total_products = $rows['COUNT'];
   }

   return $total_products;
}

function find_min_row_id_of_products_from_trader($trader_type, $connection) {

}


function find_max_product_id_of_products_from_trader($trader_type, $connection) {
    $query = "SELECT MAX(PRODUCT_ID) AS COUNT FROM PRODUCTS, SHOPS, TRADERS
                  WHERE PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID 
                  AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID
                  AND TRADERS.TRADER_TYPE = '$trader_type'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $min_product_id = 0;
    while($rows = oci_fetch_assoc($result)) {
        $min_product_id = $rows['COUNT'];
    }

    return $min_product_id;
}






function fetch_individual_products($product_id, $connection)
{
    //Select individual product from all products
    $query = "SELECT * FROM PRODUCTS WHERE PRODUCT_ID = $product_id AND STATUS = 1";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_reviews_from_products($product_id, $connection)
{
    $query = "SELECT * FROM REVIEWS WHERE REVIEWS.FK1_PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_offers_from_products($offer_id, $connection)
{
    $query = "SELECT * FROM OFFERS WHERE OFFER_ID = $offer_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_discouted_price_from_products($offer_id, $product_price, $connection)
{

    $result = fetch_offers_from_products($offer_id, $connection);
    $price = [];

    while ($rows = oci_fetch_assoc($result)) {
        $price['offer_percentage'] = $rows['PERCENTAGE'];
        $price['description'] = $rows['DESCRIPTION'];
    }

    $discount = ($product_price * $price['offer_percentage']) / 100;
    $discount = number_format($discount, 2, '.');
    $totalPriceAfterDiscount = $product_price - $discount;
    $totalPriceAfterDiscount = number_format($totalPriceAfterDiscount, 2, '.');

    return array("offer_percentage" => $price['offer_percentage'], "offer_description" => $price['description'], "discount" => $discount,
        "total_price_after_discount" => $totalPriceAfterDiscount);
}


function fetch_offerid_and_productprice_from_product_id($product_id, $connection)
{
    $query = "SELECT ITEM_PRICE, FK_OFFER_ID FROM PRODUCTS WHERE PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $offer_id = "";
    $product_price = "";

    while ($rows = oci_fetch_assoc($result)) {
        $offer_id = $rows['FK_OFFER_ID'];
        $product_price = $rows['ITEM_PRICE'];
    }

    return array('offer_id' => $offer_id, 'product_price' => $product_price);
}


function fetch_all_reviews_and_rating($product_id, $connection)
{
    $query = "SELECT * FROM REVIEWS, USERS, CUSTOMERS
    WHERE REVIEWS.FK2_USER_ID = USERS.USER_ID AND USERS.USER_ID = CUSTOMERS.USER_ID
    AND FK1_PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_all_reviews_and_rating_of_single_user($product_id, $user_id, $connection)
{
    $query = "SELECT * FROM REVIEWS, USERS, CUSTOMERS
    WHERE REVIEWS.FK2_USER_ID = USERS.USER_ID AND USERS.USER_ID = CUSTOMERS.USER_ID
    AND FK1_PRODUCT_ID = $product_id AND FK2.USER_ID = $user_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}


function get_user_type_id($id, $connection, $user_type)
{

    $user_type = strtoupper($user_type);
    $user_type_id = "";

    $query = "SELECT * FROM USERS, $user_type WHERE USERS.USER_ID = $user_type.USER_ID AND USERS.USER_ID = $id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    if ($user_type == "TRADERS") {

        while ($rows = oci_fetch_assoc($result)) {
            $user_type_id = $rows['TRADER_ID'];
        }

    } else if ($user_type == "CUSTOMERS") {

        while ($rows = oci_fetch_assoc($result)) {
            $user_type_id = $rows['CUSTOMER_ID'];
        }
    }


    return $user_type_id;

}

function insert_into_basket($customer_id, $token, $connection)
{
    $query = "INSERT INTO BASKETS (BASKET_ID, TOTAL_SUM, FK_CUSTOMER_ID, TOKEN) VALUES(null, 0, $customer_id, '$token')";
    $result = oci_parse($connection, $query);
    oci_execute($result);
}

//basket id


function check_customers_from_basket($customer_id, $connection)
{

    $count = 0;
    $query = "SELECT FK_CUSTOMER_ID FROM BASKETS WHERE BASKETS.FK_CUSTOMER_ID = $customer_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    while ($rows = oci_fetch_assoc($result)) {
        $count++;
    }

    return $count;
}

function get_basket_id_from_baskets($token, $connection)
{

    $basket_id = "";

    $query = "SELECT BASKET_ID FROM BASKETS WHERE BASKETS.TOKEN = '$token'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    while ($rows = oci_fetch_assoc($result)) {
        $basket_id = $rows['BASKET_ID'];
    }

    return $basket_id;
}

function insert_into_basket_products($basket_id, $product_id, $quantity, $connection)
{

    $getQuantity = fetch_quantity_from_basket_products($product_id, $basket_id, $connection);

    if (empty($getQuantity)) {
        $query = "INSERT INTO BASKET_PRODUCTS(FK_PRODUCT_ID, FK_BASKET_ID, QUANTITY) VALUES($product_id, $basket_id, $quantity)";

    } else {
        $quantity = intval($getQuantity) + $quantity;
        $query = "UPDATE BASKET_PRODUCTS SET QUANTITY = $quantity WHERE FK_BASKET_ID = $basket_id AND FK_PRODUCT_ID = $product_id";
    }
    $result = oci_parse($connection, $query);
    oci_execute($result);
}


function fetch_quantity_from_basket_products($product_id, $basket_id, $connection)
{

    $quantity = "";
    $query = "SELECT * FROM BASKET_PRODUCTS WHERE FK_BASKET_ID = '$basket_id' AND FK_PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    while ($rows = oci_fetch_assoc($result)) {
        if (isset($rows['QUANTITY'])) {
            $quantity = $rows['QUANTITY'];
        };
    }

    return $quantity;

}


function fetch_cart_items_from_baskets($basket_id, $connection)
{
    $query = "SELECT * FROM BASKETS, BASKET_PRODUCTS, PRODUCTS WHERE BASKET_PRODUCTS.FK_BASKET_ID= BASKETS.BASKET_ID AND BASKET_PRODUCTS.FK_PRODUCT_ID = PRODUCTS.PRODUCT_ID AND BASKET_ID = $basket_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_all_from_basket_produts($basket_id, $connection)
{

    $query = "SELECT * FROM BASKET_PRODUCTS WHERE BASKET_PRODUCTS.FK_BASKET_ID = $basket_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_trader_type_from_product($product_id, $connection)
{
    $trader_type = "";
    $query = "SELECT TRADER_TYPE FROM TRADERS, SHOPS, PRODUCTS WHERE PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID AND PRODUCTS.PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    while ($rows = oci_fetch_assoc($result)) {
        $trader_type = $rows['TRADER_TYPE'];
    }

    return $trader_type;

}


function remove_from_basket($basket_id, $product_id, $connection)
{
    $query = "DELETE FROM BASKET_PRODUCTS WHERE BASKET_PRODUCTS.FK_BASKET_ID = $basket_id AND BASKET_PRODUCTS.FK_PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);
}

function fetch_total_sum_from_baskets($basket_id, $connection)
{
    $query = "SELECT * FROM BASKETS WHERE BASKETS.BASKET_ID = $basket_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $total_sum = 0;

    while ($rows = oci_fetch_assoc($result)) {
        $total_sum = $rows['TOTAL_SUM'];
    }

    return $total_sum;
}


function update_total_sum_from_baskets($basket_id, $customer_id, $total_sum, $connection)
{
    $query = "UPDATE BASKETS SET TOTAL_SUM = $total_sum WHERE BASKETS.BASKET_ID = $basket_id AND FK_CUSTOMER_ID = $customer_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);
}


function get_profile_image_of_user($user_id, $connection)
{
    $query = "SELECT PROFILE_IMG FROM USERS WHERE USER_ID = $user_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $profile_img = "";

    while ($rows = oci_fetch_assoc($result)) {
        $profile_img = $rows['PROFILE_IMG'];
    }

    return $profile_img;
}


function count_basket_products($basket_id, $connection)
{
    $query = "SELECT COUNT(*) FROM BASKET_PRODUCTS WHERE FK_BASKET_ID = '$basket_id'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $count = 0;

    while ($rows = oci_fetch_assoc($result)) {
        $count = $count + $rows['COUNT(*)'];
    }

    return $count;
}

function get_all_collection_day($connection)
{
    $query = "SELECT DISTINCT(COLLECTION_DAY) FROM COLLECTION_SLOTS WHERE COLLECTION_SLOT_ID >= 110 AND COLLECTION_SLOT_ID <= 118";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}


function get_all_collection_time($connection)
{
    $query = "SELECT DISTINCT(COLLECTION_TIME) FROM COLLECTION_SLOTS ORDER BY COLLECTION_TIME ASC";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function get_trader_type_from_traders($trader_id, $connection)
{
    $query = "SELECT TRADER_TYPE FROM TRADERS WHERE TRADERS.TRADER_ID = $trader_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $trader_type = "";
    while ($rows = oci_fetch_assoc($result)) {
        $trader_type .= $rows['TRADER_TYPE'];
    }

    return $trader_type;
}


function get_shop_id_of_trader($trader_id, $connection)
{
    $query = "SELECT SHOP_ID FROM SHOPS WHERE FK_TRADER_ID = $trader_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $shop_id = "";
    while ($rows = oci_fetch_assoc($result)) {
        $shop_id = $rows['SHOP_ID'];
    }

    return $shop_id;
}


function fetch_all_offers($trader_id, $connection)
{
    $query = "SELECT * FROM OFFERS WHERE FK_TRADER_ID = $trader_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_all_users_shops_and_traders($connection)
{
    $query = "SELECT * FROM USERS, TRADERS, SHOPS WHERE TRADERS.USER_ID = USERS.USER_ID AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}


function fetch_trader_id_from_trader_type($type, $connection)
{
    $query = "SELECT TRADER_ID FROM TRADERS WHERE TRADER_TYPE = '$type'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $trader_id = "";
    while ($rows = oci_fetch_assoc($result)) {
        $trader_id = $rows['TRADER_ID'];
    }

    return $trader_id;
}

function get_user_id_from_trader_id($user_type_id, $connection)
{
    $query = "SELECT USERS.USER_ID FROM USERS, TRADERS WHERE USERS.USER_ID = TRADERS.USER_ID AND TRADERS.TRADER_ID = $user_type_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $user_id = "";
    while ($rows = oci_fetch_assoc($result)) {
        $user_id = $rows['USER_ID'];
    }

    return $user_id;
}


function get_latest_payment_dates_of_customer($connection, $customer_id)
{
    $query = "SELECT  payment_date, payment_date+1 AS payment
                FROM ORDERS, BASKETS, BASKET_PRODUCTS, PRODUCTS, users, customers, shops
                WHERE ORDERS.FK_BASKET_ID = BASKETS.BASKET_ID
                AND BASKET_PRODUCTS.FK_BASKET_ID = BASKETS.BASKET_ID AND BASKET_PRODUCTS.FK_PRODUCT_ID = PRODUCTS.PRODUCT_ID 
                AND baskets.fk_customer_id=customers.customer_id AND customers.customer_id = $customer_id
                GROUP BY payment_date";

    $result = oci_parse($connection, $query);
    oci_execute($result);

    $payment_date = "";
    $payment_date_plus_one = "";

    while($rows = oci_fetch_assoc($result)) {
        $payment_date = $rows['PAYMENT'];
        $payment_date_plus_one = $rows['PAYMENT_DATE'];
    }

    return array('payment_date' => $payment_date, "payment_date_plus_one" => $payment_date_plus_one);
}



function fetch_all_customers_in_users($connection) {
    $query = "SELECT * FROM USERS, CUSTOMERS WHERE CUSTOMERS.USER_ID = USERS.USER_ID";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}


function fetch_collection_id($collection_day, $collection_time, $connection) {
    $query = "SELECT COLLECTION_SLOT_ID FROM COLLECTION_SLOTS WHERE COLLECTION_TIME = '$collection_time' AND COLLECTION_DAY = '$collection_day'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $collection_slot_id = "";

    while($rows = oci_fetch_assoc($result)) {
        $collection_slot_id = $rows['COLLECTION_SLOT_ID'];
    }

    return $collection_slot_id;
}

function fetch_latest_basket_token($customer_id, $connection) {
    $query = "SELECT BASKETS.TOKEN FROM BASKETS,CUSTOMERS WHERE BASKETS.FK_CUSTOMER_ID = CUSTOMERS.CUSTOMER_ID AND BASKETS.BASKET_ID = (SELECT MAX(BASKET_ID) FROM BASKETS)";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $token = "";
    while($rows = oci_fetch_assoc($result)) {
        $token = $rows['TOKEN'];
    }

    return $token;
}

function get_orders_of_slots($collection_id, $current_date, $connection) {

    $query = "SELECT COUNT(*) AS COUNT FROM ORDERS WHERE FK_COLLECTION_SLOT_ID = $collection_id
                AND TO_CHAR(TO_DATE(PAYMENT_DATE)) = TO_CHAR(TO_DATE(SYSDATE))";

    $result = oci_parse($connection, $query);
    oci_execute($result);

    $count = 0;
    while($rows = oci_fetch_assoc($result)) {
        $count= $rows['COUNT'];
    }

    return $count;

}


function fetch_all_products_of_trader_execept_selected($trader_type, $product_id, $connection)
{
    $query = "SELECT * FROM PRODUCTS, SHOPS, TRADERS
                  WHERE PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID 
                  AND SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID
                  AND TRADERS.TRADER_TYPE = '$trader_type' AND PRODUCTS.STATUS = 1 AND PRODUCTS.PRODUCT_ID != $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}

function fetch_quantity_in_stock_from_products($product_id, $connection) {
    $query = "SELECT QUANTITY_IN_STOCK FROM PRODUCTS WHERE PRODUCTS.PRODUCT_ID = $product_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $quantity_in_stock = 0;

    while($rows = oci_fetch_assoc($result)) {
        $quantity_in_stock = $rows['QUANTITY_IN_STOCK'];
    }

    return $quantity_in_stock;
}


function update_quantity_in_stock_of_products($product_id, $quantity, $connection) {

    $total_quantity_in_stock = fetch_quantity_in_stock_from_products($product_id, $connection);
    $quantity = intval($total_quantity_in_stock) - intval($quantity);

    $query = "UPDATE PRODUCTS SET QUANTITY_IN_STOCK = '$quantity' WHERE PRODUCTS.PRODUCT_ID = '$product_id'";
    $result = oci_parse($connection, $query);
    oci_execute($result);
}



function fetch_all_order_info_of_customer($customer_id, $connection) {
    $query = "SELECT * FROM ORDERS, BASKETS, BASKET_PRODUCTS, PRODUCTS, CUSTOMERS, USERS WHERE
                products.product_id = basket_products.fk_product_id AND basket_products.fk_basket_id = baskets.basket_id AND baskets.fk_customer_id = customers.customer_id AND
                customers.user_id = users.user_id AND baskets.basket_id = orders.fk_basket_id AND CUSTOMERS.CUSTOMER_ID = $customer_id AND ORDER_ID = (SELECT MAX(ORDER_ID) FROM ORDERS)";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    return $result;
}