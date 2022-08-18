<?php
session_start();

$totalPrice = 0;
$customer_id = "";
$basket_id = "";

include_once "../../connection/connect.php";
$connection = getConnection();

//Imp;orting functions
include_once "../trader-types/functions.php";
include_once '../../includes/html-skeleton/skeleton.php';
include_once "../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../includes/cdn-links/bootstrap-cdn.php";

?>

<!--External Styleshee-->
<link rel="stylesheet" href="addtocart.css">

<div class="overlay"></div>
<div class="agreement-section w-50 position-absolute">
    <div class="agreement p-4">
        <p class="text-center text-success font-rale"><i class="fas fa-check-circle"></i>&nbsp;You are agreeing our all
            terms and conditions by clicking checkout btn.</p>
        <p class="text-center text-success"><i class="fas fa-check-circle"></i>&nbsp;<b class="font-rale">You will be
                redirected to checkout page for further processing.</b></p>
    </div>
</div>

<header class="position-relative">

    <!--        Navbar Section-->
    <?php include_once "../../includes/page-contents/page-navbar.php" ?>

    <div class="bg-image position-absolute">
        <img src="./images/240_F_332384525_lhyX7giR1uKSRNpWDWR0v3Y1hooQMaqx.jpg" class="w-100" alt=""/>
    </div>

    <!--Breadcrumbs-->
    <nav class="breadcrumb-navbar" aria-label="breadcrumb">
        <ol class="breadcrumb font-rubik">
            <li class="breadcrumb-item"><a href="/website/project/index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Cart items</li>
        </ol>
    </nav>
</header>

<main>

    <!--Breadcrumbs and Cart items-->
    <section class="cart-items my-5">

        <div class="container-fluid mt-5">
            <div class="row">
                <!--Products table-->
                <div class="col-xl-9">

                    <?php

                    if (isset($_SESSION['user'])) {

                        $count = 0;
                        $customer_id = get_user_type_id($_SESSION['user'], $connection, "CUSTOMERS");

                        $basket_token = $_SESSION['basket_token'];
                        $basket_id = get_basket_id_from_baskets($basket_token, $connection);
                        $count_items = fetch_cart_items_from_baskets($basket_id, $connection);

                        $count_basket_products = "";
                        $count_basket_products = count_basket_products($basket_id, $connection);


                        while ($rows = oci_fetch_assoc($count_items)) {
                            $count++;
                        }

                        if ($count > 0) { ?>
                            <table class="table font-rubik table-hover">
                                <thead class="text-center">
                                <th>No.</th>
                                <th>Product image</th>
                                <th>Product name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Action</th>
                                </thead>

                                <tbody class="text-center ">

                                <?php

                                $i = 0;
                                $cart_items = fetch_cart_items_from_baskets($basket_id, $connection);

                                while ($rows = oci_fetch_assoc($cart_items)) {
                                    $i++;
                                    $product_price = $rows['ITEM_PRICE'];
                                    $offer_id = $rows['FK_OFFER_ID'];

                                    $trader_type = fetch_trader_type_from_product($rows['PRODUCT_ID'], $connection);

                                    if (isset($offer_id)) {
                                        $discounted_price = fetch_discouted_price_from_products($offer_id, $product_price, $connection);

                                    } else {
                                        $discounted_price['total_price_after_discount'] = $product_price;
                                    }

                                    $total_price = $discounted_price['total_price_after_discount'] * $rows['QUANTITY'];
                                    $totalPrice = $total_price + $totalPrice;
                                    ?>
                                    <tr>
                                        <td style="font-weight: bold;"
                                            class="align-middle"><?php echo $i; ?></td>
                                        <td class="align-middle">
                                            <img src="../trader-types/<?php echo $trader_type; ?>/images/products/<?php echo $rows['PRODUCT_IMAGE'] ?>"
                                                 alt="" class="my-3"/>
                                        </td>
                                        <td class="align-middle"><?php echo $rows['PRODUCT_NAME'] ?></td>
                                        <td class="align-middle"><?php echo $rows['QUANTITY'] ?></td>
                                        <td class="align-middle">£<?php echo $total_price; ?></td>
                                        <td class="align-middle">
                                            <a href="/website/project/assets/addtocart/remove-items.php?basket_id=<?php echo $rows['BASKET_ID'] ?>&product_id=<?php echo $rows['PRODUCT_ID'] ?>"><i
                                                        class="fas fa-trash-alt text-danger"></i></a>
                                        </td>
                                    </tr>

                                <?php } ?>

                                </tbody>
                            </table>

                        <?php } else { ?>

                            <p class="text-center">
                                <img src="images/oops.svg" class="no-items w-25" alt="">
                            </p>
                            <p class="alert alert-warning text-center font-rale"><b>No products have been placed to
                                    cart items. Please, go to products to add items in your cart.</b></p>

                        <?php }

                    } else {

                        if (count($_COOKIE) > 1) { ?>

                            <table class="table font-rubik table-hover">
                                <thead class="text-center bg-light border">
                                <th>No.</th>
                                <th>Product image</th>
                                <th>Product name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Action</th>
                                </thead>

                                <tbody class="text-center">

                                <?php $index = 0;

                                foreach ($_COOKIE as $key => $item) {


                                    if ($key == "PHPSESSID") {
                                        continue;

                                    } else {
                                        $index++;
                                        $decodedItem = json_decode($item, true);
                                        $totalPrice = $totalPrice + $decodedItem['price']; ?>

                                        <tr>
                                            <td style="font-weight: bold;"
                                                class="align-middle"><?php echo $index; ?></td>
                                            <td class="align-middle">
                                                <img src="../trader-types/<?php echo $decodedItem['type'] ?>/images/products/<?php echo $decodedItem['image'] ?>"
                                                     alt="" class="my-3"/>
                                            </td>
                                            <td class="align-middle"><?php echo $decodedItem['name'] ?></td>
                                            <td class="align-middle"><?php echo $decodedItem['quantity'] ?></td>
                                            <td class="align-middle">£<?php echo $decodedItem['price'] ?></td>
                                            <td class="align-middle">
                                                <a href="/website/project/assets/addtocart/remove-items.php?key=<?php echo $decodedItem['id'] ?>"><i
                                                            class="fas fa-trash-alt text-danger"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>

                                </tbody>
                            </table>

                        <?php } else { ?>
                            <p class="text-center">
                                <img src="images/oops.svg" class="no-items w-25" alt="">
                            </p>
                            <p class="alert alert-warning text-center font-rale"><b>No products have been placed
                                    to cart items. Please, go to products to add items in your cart.</b></p>
                        <?php }
                    } ?>
                </div>

                <!--Cart total section-->
                <div class="col-xl-3">
                    <div class="cart-total font-rubik text-center">
                        <p class="summary pt-3 pb-2">Final Summary</p>

                        <div class="promo-code mt-5">
                            <p class="code text-uppercase">Do you have a promo code ?</p>
                            <input type="text" class="form-control" placeholder="#####"/>
                        </div>

                        <p class="total my-5 text-uppercase">
                            <?php
                            if (isset($_SESSION['user'])) {
                                update_total_sum_from_baskets($basket_id, $customer_id, $totalPrice, $connection);
                            }
                            ?>

                            Cart total <span class="total-price mx-2">£<?php echo $totalPrice; ?></span>
                        </p>
                        <div class="terms-conditions mt-5 w-100">
                            <p class="m-0 p-0 w-100">By clicking the checkout button,</p>
                            <p class="m-0 p-0 w-100">You are agreeing to our Terms & Conditions</p>
                        </div>

                        <?php

                        if (isset($_SESSION['user'])) {

                            $total_product_per_orders = $_SESSION['count'];

                            $_SESSION['checkout'] = false;

                            if ($total_product_per_orders === 0) {

                                $_SESSION['checkout'] = false;

                                ?>

                                <div class="checkout-error mt-2 font-rubik border border-warning">
                                    <p class="text-warning text-center mt-2">There are no items in your cart.</p>
                                </div>


                            <?php } elseif ($total_product_per_orders <= 20 && $total_product_per_orders > 0) {

                                $_SESSION['checkout'] = true;

                                ?>

                                <a href="/website/project/assets/checkout/checkout.php"
                                   class="checkout-btn btn mt-5 font-rubik">
                                    CHECKOUT<i class="fas fa-lock mx-2"></i>
                                </a>

                            <?php } elseif ($total_product_per_orders > 20) {

                                $_SESSION['checkout'] = false;

                                ?>

                                <div class="checkout-error mt-2 font-rubik border border-warning">
                                    <p class="text-warning text-center">Cart has exceeded it's limit.</p>
                                    <p class="text-warning text-center p-0 mt-1">Please,
                                        remove <?php echo($total_product_per_orders - 20) ?> different items</p>
                                </div>

                            <?php }
                        } else { ?>
                            <a href="/website/project/assets/checkout/checkout.php"
                               class="checkout-btn btn mt-5 font-rubik">
                                CHECKOUT<i class="fas fa-lock mx-2"></i>
                            </a>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!--Footer Section-->
<?php include_once "../../includes/page-contents/page-footer.php"; ?>
