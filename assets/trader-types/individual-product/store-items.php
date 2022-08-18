<?php

$connection = getConnection();

include_once "../functions.php";

//On form submit when customer adds to cart
if (isset($_POST['formSubmit'])) {

    $product_id = htmlspecialchars($_GET['search']);
    $product_quantity = $_POST['product_quantity'] ??= 1;
    $product_price = $_POST['product-price'] ??= 0;
    $product_name = $_POST['product-name'] ??= "";
    $product_image = $_POST['product-image'] ??= "";


    //Fetch quantity in stock before adding products
    $quantity_in_stock = fetch_quantity_in_stock_from_products($product_id, $connection);

    if (isset($_SESSION['user'])) {

        //insert into baskets of customer when customer adds item to cart
        $count_cart_items = 0;
        $customer_id = get_user_type_id($_SESSION['user'], $connection, "CUSTOMERS");
        $basket_token = $_SESSION['basket_token'];
        $basket_id = get_basket_id_from_baskets($basket_token, $connection);

        $quantity_from_basket_products = fetch_quantity_from_basket_products($product_id, $basket_id, $connection);


        //If quantity in stock is greater than 20 we can add products upto 20
        if ($quantity_in_stock >= 20) {

            if ($quantity_from_basket_products < 20) {

                $total_product_quantity = intval($quantity_from_basket_products) + $product_quantity;

                if($total_product_quantity > 20) {
                    $extra_quantity = $total_product_quantity  - 20;
                    $product_quantity = $product_quantity - $extra_quantity;
                }

                insert_into_basket_products($basket_id, $product_id, $product_quantity, $connection);

            } else {
                $errors_in_quantity = "<p style='font-size: 1rem;' class='text-danger'>Maximum Product Quantity Reached</p>";
            }

        } else {

            //If quantity in stock is less than 20 we should add products based on the quantity in stock
            if ($quantity_in_stock < 20) {

                if($quantity_from_basket_products < $quantity_in_stock) {

                    $total_product_quantity = intval($quantity_from_basket_products) + $product_quantity;

                    //When a customer has products greater than quantity in stock after adding same product
                    if($total_product_quantity > $quantity_in_stock) {
                        $extra_quantity = $total_product_quantity - $quantity_in_stock;
                        $product_quantity = $product_quantity - $extra_quantity;
                    }

                    insert_into_basket_products($basket_id, $product_id, $product_quantity, $connection);

                }else {
                    $errors_in_quantity = "<p style='font-size: 1rem;' class='text-danger'>Maximum Product Quantity Reached</p>";
                }

            }
        }


        $result = fetch_cart_items_from_baskets($basket_id, $connection);

        while ($rows = oci_fetch_assoc($result)) {
            $count_cart_items++;
        }

        //Updating total cart items count
        $_SESSION['count'] = $count_cart_items;

    } else {
        if (count($_COOKIE) > 0) {
            foreach ($_COOKIE as $key => $item) {
                if ($key == "PHPSESSID") {
                    continue;

                } else {
                    $encodedItem = json_decode($item, true);

                    //If item is already stored before
                    if ($key == $product_id) {
                        $quantity = $encodedItem['quantity'];

                        //If quantity in stock is greater than 20 we can add products upto 20
                        if ($quantity_in_stock >= 20) {

                            if ($quantity <= 20) {
                                $product_quantity = $quantity + $product_quantity;

                                if ($product_quantity > 20) {
                                    $product_quantity = 20;
                                }

                            } else {
                                $product_quantity = $quantity;
                                $_SESSION['quantity'] = "<p style='font-size: 1rem;' class='text-danger'>Maximum Product Quantity Reached</p>";
                            }
                        } else {

                            //If quantity in stock is less than 20 we should add products based on the quantity in stock
                            if ($quantity_in_stock < 20) {

                                if ($quantity <= $quantity_in_stock) {
                                    $product_quantity = $quantity + $product_quantity;

                                    if ($product_quantity > $quantity_in_stock) {
                                        $product_quantity = $quantity_in_stock;
                                    }

                                } else {
                                    $product_quantity = $quantity;
                                    $_SESSION['quantity'] = "<p style='font-size: 1rem;' class='text-danger'>Maximum Product Quantity Reached</p>";
                                }
                            }
                        }
                    }
                }
            }
        }

        $product_price = $product_quantity * $product_price;

        $prod_info = array('id' => $product_id, 'name' => $product_name, "price" => $product_price, 'quantity' => $product_quantity, 'image' => $product_image, 'type' => $trader_type);
        $prod_info = json_encode($prod_info, true);

        //inserting products information on cookies
        setcookie($product_id, $prod_info, time() + (86400 * 30), '/website/project/');

    }
    header("Refresh: 0; url='/website/project/assets/trader-types/individual-product/individual-product.php?search=$product_id&type=$trader_type'");
}


