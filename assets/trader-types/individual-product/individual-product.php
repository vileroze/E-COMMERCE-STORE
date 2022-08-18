<?php

session_start();

//Fetching connnection
include_once "../../../connection/connect.php";
$connection = getConnection();

//Fetching product id from query string
$product_id = $_GET['search'] ??= "";
$trader_type = $_GET['type'] ??= "";

//Reviews and Rating
$averageRating = 0;
$totalRating = 0;
$cart_count = 0;

//Offers
$offerPercentage = 0;
$discount = 0;
$totalPriceAfterDiscount = 0;
$offerDescription = "";


if (!empty($product_id) && !empty($trader_type)) {

    include_once "./store-items.php";
    include_once "../../../includes/html-skeleton/skeleton.php"; ?>

    <link rel="stylesheet" href="./node_modules/@splidejs/splide/dist/css/themes/splide-sea-green.min.css">
    <script src="node_modules/@splidejs/splide/dist/js/splide.min.js"></script>


    <?php include_once "../../../includes/cdn-links/bootstrap-cdn.php";
    include_once "../../../includes/cdn-links/fontawesome-cdn.php"; ?>


    <!--External Sylesheet-->
    <link rel="stylesheet" href="../trader-product-css/trader-product.css">

    <header class="position-relative">

        <!--Navbar Section-->
        <?php include_once "../../../includes/page-contents/page-navbar.php" ?>


        <div class="bg-image position-absolute">
            <img src="./bg-img/Farmers-Market-Banner-11-2014.jpg" class="w-100" alt=""/>
        </div>

        <nav class="breadcrumb-navbar" aria-label="breadcrumb">
            <ol class="breadcrumb font-rubik">
                <li class="breadcrumb-item"><a href="/website/project/index.php">Home</a></li>
                <li class="breadcrumb-item"><a
                            href="/website/project/assets/trader-types/<?php echo $trader_type; ?>/<?php echo $trader_type ?>.php"><?php echo $trader_type; ?></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Product
                </li>
            </ol>
        </nav>
    </header>


    <?php
    include_once "../functions.php";
    $result = fetch_individual_products($product_id, $connection);
    ?>


    <main class="my-5">

        <?php

        //Fetch row of a single product out of all products
        while ($row = oci_fetch_assoc($result)) { ?>
            <section class="w-75 mx-auto cart-info">
                <div class="img-container">
                    <div class="row">
                        <div class="col-12 col-sm-11 col-md-12 col-lg-5 col-xl-5">

                            <?php

                            if(empty($row['PRODUCT_IMAGE'])) {
                                $image = 'default-image.png';
                            }else {
                                $image = $row['PRODUCT_IMAGE'];
                            }

                            ?>

                            <img src="../<?php echo $trader_type; ?>/images/products/<?php echo $image; ?>"
                                 class="w-100" alt=""/>
                        </div>
                        <div class="col-12 col-sm-11 col-md-12 col-lg-7 col-xl-7 font-rale">
                            <!--TODO : Provide action for form-->
                            <form action="#" method="POST" class="form">
                                <fieldset>
                                    <p class="prod-name">
                                        <?php echo $row['PRODUCT_NAME'] ?>
                                    </p>

                                    <!--Calculating Rating of product-->
                                    <div class="rating-container">
                                        <?php

                                        $resultSecond = fetch_reviews_from_products($product_id, $connection);

                                        while ($rowSecond = oci_fetch_assoc($resultSecond)) {
                                            $totalRating += $rowSecond['REVIEW_RATING'];
                                            $cart_count++;
                                        }

                                        if ($cart_count > 0) {
                                            //Calculating average rated value
                                            $averageRating = $totalRating / $cart_count;

                                        } else {
                                            $averageRating = $totalRating;
                                        }


                                        for ($i = 1; $i <= floor($averageRating); $i++) { ?>
                                            <i class="fas fa-star text-warning"></i>
                                        <?php }

                                        //Calculating unrated value
                                        $remainingRating = 5 - $averageRating;

                                        for ($i = 1; $i <= $remainingRating; $i++) { ?>
                                            <i class="far fa-star text-warning"></i>
                                        <?php } ?>

                                        <span>(<?php echo $cart_count; ?>)</span>
                                    </div>


                                    <div class="pricing-container my-4">
                                    <?php

                                    $product_availability = $row['AVAILABLILITY'];
                                    $product_quantity = $row['QUANTITY_IN_STOCK'];

                                    if ($product_availability == 1 && $product_quantity > 0) {

                                        //Calculating Offers for individual product
                                        $offerId = $row['FK_OFFER_ID'];
                                        $productPrice = $row['ITEM_PRICE'];


                                        //If a product has a offer we show all product details of offer
                                        if (isset($offerId)) {
                                            $resultThird = fetch_discouted_price_from_products($offerId, $productPrice, $connection); ?>

                                        <p class="my-0">
                                            <del>£<?php echo number_format($productPrice, '2'); ?></del>
                                            <span class="mx-3">(Including all taxes)</span>
                                        </p>

                                        <p class="discount">
                                            £<?php echo number_format($resultThird['total_price_after_discount'], '2') ?>
                                            <span
                                                    class="rounded">-<?php echo $resultThird['offer_percentage']; ?>%</span>
                                        </p>

                                        <?php } else {

                                            //If a product has no offer
                                            $resultThird['total_price_after_discount'] = $productPrice; ?>

                                            <p style="font-size: 2rem !important;" class="discount">
                                                £<?php echo number_format($resultThird['total_price_after_discount'], '2') ?>
                                            </p>

                                        <?php }  ?>





                                        </div>

                                        <!--Hidden input field-->
                                        <input type="hidden" name="product-price"
                                               value="<?php echo $resultThird['total_price_after_discount']; ?>">
                                        <input type="hidden" name="product-name"
                                               value="<?php echo $row['PRODUCT_NAME']; ?>">
                                        <input type="hidden" name="product-image"
                                               value="<?php echo $row['PRODUCT_IMAGE']; ?>">

                                        <?php

                                        if ($product_quantity >= 20) { ?>

                                            <div class="order-quantity d-flex align-items-start my-3">
                                                <p class="mr-2">Quantity :</p>

                                                <div class="quantity ml-3 d-flex">
                                                    <button type="button" class="btn decrease-btn">-</button>
                                                    <input type="number" class="form-control w-25 text-center" value="1"
                                                           name="product_quantity" readonly>
                                                    <button type="button" class="btn increase-btn">+</button>
                                                </div>
                                            </div>

                                        <?php } else { ?>

                                            <div class="order-quantity d-flex align-items-start my-3">
                                                <p class="mr-2">Quantity :</p>

                                                <div class="quantity ml-3 d-flex">
                                                    <button type="button" class="btn decrease-btn">-</button>
                                                    <input type="number" class="form-control w-25 text-center" value="1"
                                                           name="product_quantity" readonly>
                                                    <button type="button"
                                                            class="btn increase-btn btn-<?php echo $product_quantity; ?>">
                                                        +
                                                    </button>
                                                </div>
                                            </div>

                                        <?php }

                                        //If a customer tries to add maximum products than of quanity in stock
                                        if (isset($errors_in_quantity)) {
                                            echo $errors_in_quantity; ?>


                                            <!--Refresh page to hide errors-->
                                        <script>
                                            setTimeout(() => {
                                                window.location.href = "http://localhost/website/project/assets/trader-types/individual-product/individual-product.php?search=<?php echo $product_id ?>&type=<?php echo $trader_type ?>"
                                            }, 5000)
                                        </script>

                                        <?php }

                                        ?>


                                        <div class="order-btn__container my-3">
                                            <button type="submit" class="btn btn-md btn-primary" name="formSubmit">Add
                                                to Cart
                                            </button>
                                        </div>


                                    <?php } elseif ($product_availability == 0) { ?>
                                        <p class="out-of-stock text-uppercase text-center border border-success font-rubik text-danger">
                                            NOT AVAILABLE</p>

                                    <?php } elseif ($product_quantity == 0) { ?>
                                        <p class="out-of-stock text-uppercase text-center border border-success font-rubik text-danger">
                                            OUT OF STOCK</p>
                                    <?php } ?>

                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </section>


            <section class="w-75 mx-auto item-description my-5">
                <div class="row my-5">
                    <div class="col-5 col-sm-5 col-md-4 col-lg-5 col-xl-4 mx-auto d-flex">
                        <img src="../trader-images/undraw_real_time_collaboration_c62i%20(1).svg" class="w-75 mx-auto"
                             alt=""/>
                    </div>
                    <div class="col-10 col-sm-10 col-md-7 col-lg-6 col-xl-6 mx-auto">
                        <div id="accordion">
                            <div class="card">
                                <div class="
                    card-header
                    d-flex
                    align-items-start
                    justify-content-between
                  " data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"
                                     id="headingOne">
                                    <p class="font-rale">Description</p>
                                    <i class="fas fa-chevron-down"></i>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                     data-parent="#accordion">
                                    <div class="card-body">
                                        <p class="font-rubik">
                                            <?php echo $row['PRODUCT_INFO'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="
                    card-header
                    d-flex
                    align-items-center
                    justify-content-between
                  " data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"
                                     id="headingTwo">
                                    <p class="font-rale">Allergy Information</p>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                     data-parent="#accordion">
                                    <div class="card-body">
                                        <p class="font-rubik">
                                            <?php echo $row['ALLERGY_INFO'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="
                    card-header
                    d-flex
                    align-items-center
                    justify-content-between
                  " data-toggle="collapse" data-target="#collapseThree"
                                     aria-expanded="false" aria-controls="collapseThree" id="headingThree">
                                    <p class="font-rale">Offer Description</p>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                     data-parent="#accordion">
                                    <div class="card-body">
                                        <p class="font-rubik">


                                            <?php

                                            if(isset($resultThird['offer_description'])) {
                                                echo $resultThird['offer_description'] ??= "";
                                            } ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <br>

            <!--Items You may like Section-->
            <section>
                <h3 class="font-rale text-center">More Related Products...</h3>
                <hr class="hr"/>

                <div class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">

                            <?php

                            $limitedRandomProducts = fetch_all_products_of_trader($trader_type, $connection);

                            $limit = 0;
                            $randomNumber = rand(0, 5);

                            while ($rowsLimit = oci_fetch_assoc($limitedRandomProducts)) {

                                if ($limit > 15) {
                                    break;
                                } else {

                                    if ($limit == $randomNumber) {
                                        $limit++;
                                        continue;
                                    }
                                }

                                ?>

                                <li class="splide__slide">
                                    <div class="slider_product">
                                        <div class="img-container">

                                            <?php

                                            if(empty($rowsLimit['PRODUCT_IMAGE'])) {
                                                $related_img = 'default-image.png';
                                            }else {
                                                $related_img = $rowsLimit['PRODUCT_IMAGE'];
                                            }

                                            ?>

                                            <img src="../<?php echo $trader_type; ?>/images/products/<?php echo $related_img ?>"
                                                 class="w-100" alt=""/>
                                        </div>

                                        <div class="prod-name d-flex align-items-center justify-content-between mt-3">
                                            <p class="font-rubik"><?php echo substr($rowsLimit['PRODUCT_NAME'], 0, 25) ?>
                                                ....<span
                                                        style="font-size: 0.9rem; color: #0a66c2">more</span></p>


                                            <?php

                                            if (isset($rowsLimit['FK_OFFER_ID'])) {
                                                $discounted_result = fetch_discouted_price_from_products($rowsLimit['FK_OFFER_ID'], $rowsLimit['ITEM_PRICE'], $connection);

                                            } else {
                                                $discounted_result['total_price_after_discount'] = $rowsLimit['ITEM_PRICE'];
                                            }
                                            ?>

                                            <p class="font-rubik price-content">
                                                £<?php echo $discounted_result['total_price_after_discount']; ?></p>
                                        </div>


                                        <div class="btn-container font-rubik">
                                            <a href="/website/project/assets/trader-types/individual-product/individual-product.php?search=<?php echo $rowsLimit['PRODUCT_ID'] ?>&type=<?php echo $trader_type; ?>"
                                               class="btn btn-dark w-100">View Product</a>
                                        </div>
                                    </div>
                                </li>

                                <?php

                                $limit++;

                            } ?>


                        </ul>
                    </div>
                </div>
            </section>


            <br>

            <section class="w-75 mx-auto product-review my-5">
                <h3 class="font-rale text-center">Product's Ratings & Reviews</h3>
                <hr class="hr"/>

                <div class="row mx-auto my-4">
                    <!--Total Average Ratings-->
                    <div class="col-xl-4 mx-auto my-4">
                        <p class="text-center font-rubik"><?php echo number_format($averageRating, 1); ?>/5</p>
                    </div>

                    <?php

                    $resultFourth = fetch_reviews_from_products($product_id, $connection);

                    $fiveStars = 0;
                    $fourStars = 0;
                    $threeStars = 0;
                    $twoStars = 0;
                    $oneStars = 0;


                    while ($rowFourth = oci_fetch_assoc($resultFourth)) {
                        if ($rowFourth['REVIEW_RATING'] == 5) {
                            $fiveStars++;
                        }

                        if ($rowFourth['REVIEW_RATING'] == 4) {
                            $fourStars++;
                        }
                        if ($rowFourth['REVIEW_RATING'] == 3) {
                            $threeStars++;
                        }

                        if ($rowFourth['REVIEW_RATING'] == 2) {
                            $twoStars++;
                        }

                        if ($rowFourth['REVIEW_RATING'] == 1) {
                            $oneStars++;
                        }

                    }
                    ?>

                    <div class="col-xl-5 my-4 mx-auto border p-3">
                        <div class="row">
                            <!--Ratings stars-->
                            <div class="col-xl-6">
                                <div class="icon-container">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <span class="mx-2">(<?php echo $fiveStars; ?>)</span>
                                </div>
                            </div>

                            <!--Progress Bar-->
                            <div class="col-xl-6">
                                <div class="progress">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                         style="width: <?php echo $fiveStars; ?>0%" aria-valuenow="100"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="icon-container">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <span class="mx-2">(<?php echo $fourStars; ?>) </span>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar"
                                         style="width: <?php echo $fourStars; ?>0%" aria-valuenow="100"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="icon-container">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <span class="mx-2">(<?php echo $threeStars; ?>)</span>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="progress">
                                    <div class="progress-bar bg-secondary" role="progressbar"
                                         style="width: <?php echo $threeStars ?>0%" aria-valuenow="100"
                                         aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="icon-container">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <span class="mx-2">(<?php echo $twoStars; ?>)</span>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                         style="width: <?php echo $twoStars; ?>0%" aria-valuenow="100" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-6">
                                <div class="icon-container">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <i class="far fa-star text-warning"></i>
                                    <span class="mx-2">(<?php echo $oneStars; ?>)</span>
                                </div>
                            </div>

                            <div class="col-xl-6">
                                <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar"
                                         style="width: <?php echo $oneStars; ?>0%" aria-valuenow="100" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>


            <section class="w-75 mx-auto comments my-5">
                <div class="container-fluid">
                    <?php

                    $resultFifth = fetch_all_reviews_and_rating($product_id, $connection);

                    while ($rowFifth = oci_fetch_assoc($resultFifth)) { ?>

                        <!--Individual Comments section-->
                        <div class="individual-comments font-rubik my-5">
                            <div class="customer-rating w-100 d-flex align-items-baseline justify-content-between">

                                <div class="rating_reviews d-flex align-items-baseline">
                                    <p class=" username">
                                        <?php echo $rowFifth['FIRST_NAME']; ?>
                                        <?php echo $rowFifth['LAST_NAME']; ?>
                                    </p>


                                    <div class="rating-container mx-3">
                                        <?php

                                        $rating = 0;
                                        $rating = $rowFifth['REVIEW_RATING'];

                                        if ($rating > 0) {
                                            for ($i = 1; $i <= $rating; $i++) { ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php }

                                            $unrated = 5 - $rating;
                                            for ($i = 1; $i <= $unrated; $i++) { ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php }

                                        }else {

                                            for($i = 0; $i < 5; $i++) { ?>
                                                <i class="far fa-star text-warning"></i>
                                        <?php } } ?>

                                    </div>
                                </div>

                                <?php

                                if (isset($_SESSION['admin'])) { ?>

                                    <div class="delete-container mr-4">
                                        <a href="/website/project/assets/trader-types/individual-product/reviews/delete-reviews.php?id=<?php echo $product_id; ?>&review=<?php echo $rowFifth['REVIEW_ID'] ?>&type=<?php echo $trader_type; ?>"><i
                                                    class="fas fa-trash text-danger ml-0"></i></a>
                                    </div>

                                <?php } ?>

                            </div>


                            <hr class="break"/>
                            <p class="description">
                                <?php

                                if (!empty($rowFifth['REVIEW_COMMENT'])) {
                                    echo $rowFifth['REVIEW_COMMENT'];
                                } else {
                                    echo "No comments";
                                }
                                ?>
                            </p>
                        </div>

                    <?php } ?>

                    <?php

                    if (isset($_SESSION['user'])) {

                        include_once "./reviews/reviews.php" ?>

                        <div class="insert-reviews w-100 text-center">
                            <button class="btn btn-primary btn-review font-rubik">Click here to write your Reviews
                            </button>
                            <?php

                            if (isset($review_errors['empty'])) {
                                echo $review_errors['empty'];
                            }

                            if (!empty($success_reviews)) { ?>
                                <script>
                                    setTimeout(() => {
                                        location.href = 'https://localhost/website/project/assets/trader-types/individual-product/individual-product.php?search=<?php echo $product_id ?>&type=<?php echo $trader_type ?>';
                                    })
                                </script>

                            <?php }

                            ?>
                            <div class="container review-container">
                                <div class="row">
                                    <div class="col-xl-8 m-0 p-0 mx-auto">

                                        <form action="#" method="POST" class="mt-4 p-0">
                                            <div class="icon-container review-icons mb-1">
                                                <i class="far fa-star review-star text-warning"></i>
                                                <i class="far fa-star review-star text-warning"></i>
                                                <i class="far fa-star review-star text-warning"></i>
                                                <i class="far fa-star review-star text-warning"></i>
                                                <i class="far fa-star review-star text-warning"></i>
                                            </div>

                                            <input type="hidden" class="rating-value" name="rating_value" value="0">
                                            <input type="hidden" class="product_id" name="product_id"
                                                   value="<?php echo $product_id ?>">
                                            <input type="hidden" class="trader_type" name="trader_type"
                                                   value="<?php echo $trader_type ?>">

                                            <label for="reviews" class="font-rubik p-0">Write your reviews
                                                here...</label>
                                            <textarea name="reviews" id="reviews" cols="100" rows="7"
                                                      class="p-1 form-control"></textarea>
                                            <button type="submit" name="reviewSubmit"
                                                    class="font-rubik btn btn-success mt-3">Submit Your Reviews
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>
            </section>

        <?php } ?>
    </main>

    <!--Footer Section-->
    <?php include_once "../../../includes/page-contents/page-footer.php"; ?>

    <!--External Script-->
    <script>
        new Splide('.splide', {
            type: 'loop',
            perPage: 3,
            perMove: 3,
            gap: "3rem",
            breakpoints: {
                '1024' : {
                    perPage: 2,
                    gap: "1rem"
                },

                '768' : {
                    perPage : 1,
                    height : '50%',
                    width : "50%"
                },

                '375' : {
                    perPage : 1,
                    height: '100%',
                    width: '100%'
                }
            }

        }).mount();
    </script>

    <script src="../trader-scripts/vendor.js"></script>
    <script src="../trader-scripts/reviews.js"></script>

<?php } else {
    header('Location: https://localhost/website/project/index.php');
} ?>