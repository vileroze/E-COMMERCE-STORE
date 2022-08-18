<?php

session_start();

//Fetch Connection
include_once "../../../connection/connect.php";
$connection = getConnection();

include_once "../../../includes/html-skeleton/skeleton.php";
include_once "../../../includes/cdn-links/bootstrap-cdn.php";
include_once "../../../includes/cdn-links/fontawesome-cdn.php";
?>

    <!--External CSS-->
    <link rel="stylesheet" href="../trader-css/traders.css">

    <header>

        <!--Navbar Section-->
        <?php include_once "../../../includes/page-contents/page-navbar.php" ?>

        <div class="bg-image position-absolute">
            <img src="images/bg-img/delibanner.jpg" class="w-100" alt=""/>
        </div>


        <div class="
          trader-description
          position-absolute
          font-rubik
          text-dark
          py-2
          px-4
        ">
            <h2 class="text-center mb-4">Delicatessen</h2>
            <p class="text-center font-rubik">
                Our organic delicatessen has everything,
                from veggie and vegan delights like houmous and olives,
                to mouth-watering cured meat. Bring it all home to your table.The proof's in the tasting,
                so fill your boots with the best of the season's veggie delights right here.
            </p>
        </div>
    </header>


    <br/>
    <br/>

    <main>
        <!--Search and Sort section-->
        <section class="sidebar-section">
            <div class="container-fluid">
                <div class="row d-flex align-items-baseline justify-content-between">
                    <div class="breadcrumb-section">
                        <!--Breadcrumbs-->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 font-rubik">
                                <li class="breadcrumb-item">
                                    <a href="/website/project/index.php">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="/website/project/assets/trader-types/delicatessen/delicatessen.php">Delicatessen</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Products
                                </li>
                            </ol>
                        </nav>
                    </div>

                    <!--Search and sort form-->
                    <div class="search-sort">
                        <form action="#" method="POST" class="d-flex align-items-center justify-content-end">
                            <div class="search-field mx-2">
                                <input type="text" class="form-control font-rubik" name="search-items"
                                       placeholder="Eg. Bananas, Apple"/>
                            </div>

                            <div class="sort-field mx-2">
                                <select name="sort-items" id="sort-items" class="form-control font-rubik">
                                    <option value="" selected disabled>Sort By</option>
                                    <option value="high_price">Price (Highest)</option>
                                    <option value="low_price">Price (Lowest)</option>
                                    <option value="product_name">Product name</option>
                                </select>
                            </div>

                            <div class="btn-container mx-2 font-rubik">
                                <button type="submit" name="searchSubmit" class="btn btn-md">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <br/>
        <br/>

        <?php

        include_once "../functions.php";

        if (isset($_POST['searchSubmit'])) {
            $search_items = $_POST['search-items'] ??= "";
            $sort_items = $_POST['sort-items'] ??= "";

            $query = "SELECT * FROM TRADERS, SHOPS, PRODUCTS WHERE SHOPS.FK_TRADER_ID = TRADERS.TRADER_ID AND PRODUCTS.FK_SHOP_ID = SHOPS.SHOP_ID AND TRADERS.TRADER_TYPE = 'delicatessen' AND PRODUCTS.STATUS = 1 ";

            if (!empty($search_items)) {

                $query .= "AND UPPER(PRODUCT_NAME) LIKE UPPER('%$search_items%') ";

            }

            if (!empty($sort_items)) {

                if ($sort_items == 'high_price') {
                    $query .= " ORDER BY PRODUCTS.ITEM_PRICE DESC";

                } elseif ($sort_items == "low_price") {
                    $query .= " ORDER BY PRODUCTS.ITEM_PRICE ASC";

                } elseif ($sort_items == 'product_name') {
                    $query .= " ORDER BY PRODUCTS.PRODUCT_NAME ASC";
                }

            }

            $result = oci_parse($connection, $query);
            oci_execute($result);

        } else {
            $result = fetch_all_products_of_trader("delicatessen", $connection);
        }

        ?>

        <!--Products Section-->
        <section class="traders-products border">
            <div class="container-fluid w-100">
                <div class="products">


                    <!--Individual Products-->
                    <?php

                    $count_animation = 0;
                    $delay = 0.5;
                    $count_products = 0;


                    while ($row = oci_fetch_assoc($result)) {

                        $count_products++;
                        $count_animation++;
                        $delay = $delay + 0.2;

                        ?>
                        <style>
                            .in-<?php echo $count_animation;?>{
                                animation-delay: <?php echo $delay; ?>s;
                            }
                        </style>


                        <div class="individual-product in-<?php echo $count_animation;?> animate__animated animate__fadeInUp">
                            <div class="img-container position-relative">

                                <?php

                                if(empty($row['PRODUCT_IMAGE'])) {
                                    $image = 'default-image.png';
                                }else {
                                    $image = $row['PRODUCT_IMAGE'];
                                }

                                ?>

                                <img src="./images/products/<?php echo $image; ?>" alt=""/>
                                <i class="fas fa-search position-absolute"></i>
                            </div>
                            <div class="info d-flex align-items-baseline justify-content-between mt-3">
                                <p class="font-rubik"><?php echo substr($row['PRODUCT_NAME'], 0, 25) ?>....<span style="font-size: 0.9rem; color: #0a66c2">more</span></p>

                                <?php

                                if (isset($row['FK_OFFER_ID'])) {
                                    $discounted_result = fetch_discouted_price_from_products($row['FK_OFFER_ID'], $row['ITEM_PRICE'], $connection);

                                } else {
                                    $discounted_result['total_price_after_discount'] = $row['ITEM_PRICE'];
                                }
                                ?>
                                <p class="font-rubik price-content">£<?php  echo number_format($discounted_result['total_price_after_discount'], '2', '.') ?></p>
                            </div>
                            <div class="btn-container font-rubik">
                                <a href="/website/project/assets/trader-types/individual-product/individual-product.php?search=<?php echo $row['PRODUCT_ID'] ?>&type=delicatessen"
                                   class="btn btn-dark">View Product</a>
                            </div>
                        </div>
                    <?php }

                    if($count_products == 0) { ?>

                        <p style="font-size: 1.4rem; width: 100vw" class="font-rubik">Sorry, there is no such products to show :) </p>

                    <?php }

                    ?>


                </div>
            </div>
        </section>
    </main>

    <!--Footer Section-->
<?php include_once "../../../includes/page-contents/page-footer.php"; ?>
