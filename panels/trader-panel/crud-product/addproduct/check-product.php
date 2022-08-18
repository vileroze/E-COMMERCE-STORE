<?php

if (isset($_POST['insertProduct'])) {
    if (!empty($_POST['product-name']) && !empty($_POST['product-price']) && ($_POST['product-quantity'] >= 0) && ($_POST['product-availability'] == 1 || $_POST['product-availability'] == 0) && !empty($_POST['product-desc'])) {

        $productName = htmlspecialchars(trim($_POST['product-name']));
        $productPrice = htmlspecialchars(trim($_POST['product-price']));
        $instock = htmlspecialchars(trim($_POST['product-quantity']));
        $availability = htmlspecialchars(trim($_POST['product-availability']));

        if($availability == 1) {
            $minOrder = 1;
            $maxOrder = $instock;

        }else {
            $minOrder = 0;
            $maxOrder = 0;
        }

        $productDesc = htmlspecialchars(trim($_POST['product-desc']));
        $allergyInfo = htmlspecialchars(trim($_POST['product-allergy__info']));
        $product_image = $_FILES['product-img'];

        $trader_id = get_user_type_id($_SESSION['trader'], $connection, "TRADERS");
        $fk_shop_id = get_shop_id_of_trader($trader_id, $connection);

        $fk_offer_id = $_POST['fk_offer_id'] ??= null;
        $status = 1;
        $image_name = "";
        $query = "";


        if (!empty($product_image['name'])) {

            $prod_img = $product_image;

            try {
                $image_name = bin2hex(random_bytes(15));

            } catch (Exception $exception) {
            }

            $image_name .= '.jpg';
            $temp_path = $product_image['tmp_name'];
            $trader_type = get_trader_type_from_traders($trader_id, $connection);

            if (preg_match('*.jpg|.png|.jpeg*i', $prod_img['name'])) {

                $actual_path = 'C:/xampp/htdocs/website/project/assets/trader-types/' . $trader_type . '/images/products/' . $image_name . '';
                move_uploaded_file($temp_path, $actual_path);

                $query = "INSERT INTO products (product_id, product_name, item_price, quantity_in_stock, availablility, min_order, max_order, allergy_info, product_info, product_image, fk_shop_id, fk_offer_id, status)
                                VALUES (null, '$productName', $productPrice, $instock, $availability, $minOrder, $maxOrder, '$allergyInfo', '$productDesc', '$image_name', $fk_shop_id, '$fk_offer_id', $status)";

            } else {
                $img_error = "<p style='border-width:1px !important' class='text-danger border border-danger text-center mt-4'><i class='fas fa-times-circle'></i>&nbsp;&nbsp;ERROR: INVALID IMAGE FORMAT</p>";
            }

        } else {
            $query = "INSERT INTO products (product_id, product_name, item_price, quantity_in_stock, availablility, min_order, max_order, allergy_info, product_info, product_image, fk_shop_id, fk_offer_id, status)
                                VALUES (null, '$productName', $productPrice, $instock, $availability, $minOrder , $maxOrder, '$allergyInfo', '$productDesc', '', $fk_shop_id, '$fk_offer_id', $status)";
        }

        $qte = oci_parse($connection, $query);
        if (oci_execute($qte)) {
            echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-success border p-2 border-success w-50 mx-auto text-center mt-4'><i class='fas fa-check-circle'></i>&nbsp;&nbsp;SUCCESS: PRODUCT INSERTED SUCCESSFULLY</p>"; ?>

            <script>
                setTimeout(() => {
                    window.location.href = "http://localhost/website/project/panels/trader-panel/crud-product/addproduct/addproduct.php";
                }, 2000)
            </script>

        <?php } else {
            echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-danger border p-2 border-danger w-50 mx-auto text-center mt-4'><i class='fas fa-check-circle'></i>&nbsp;&nbsp;ERROR: COULD NOT INSERT YOUR PRODUCT. TRY-AGAIN</p>";
        }

    } else {
        echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-danger border p-2 border-danger w-50 mx-auto text-center mt-4'><i class='fas fa-times-circle'></i>&nbsp;&nbsp;ERROR: REQUIRED FIELD[S] CANNOT BE LEFT EMPTY</p>";
    }
}

