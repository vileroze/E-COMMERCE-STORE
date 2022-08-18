<?php

session_start();

if(isset($_SESSION['trader'])) {

include_once "../../../../connection/connect.php";
$connection = getConnection();


include_once "../../../../includes/html-skeleton/skeleton.php";
include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

<!--External Stylesheet-->
<link rel="stylesheet" href="addoffer.css">

<main>
    <div class="container-fluid">
        <div class="row">
            <?php include '../../trader-side-panel.php' ?>

            <!--Add Products Container Column-->
            <div class="col-xl-10 mx-auto p-0">

                <?php

                include_once '../../../../assets/trader-types/functions.php';
                $profile_img = get_profile_image_of_user($_SESSION['trader'], $connection);

                echo "<div class='user-profile-header'>";

                if (!isset($profile_img)) {
                    $profile_img = "default-image.jpg";
                }

                $trader_id = get_user_type_id($_SESSION['trader'], $connection, "TRADERS");
                $trader_type = get_trader_type_from_traders($trader_id, $connection);

                echo "<p class='trader-type'>" . strtoupper($trader_type) . "</p>";

                echo "<img src='../../profile/profile-img/" . $profile_img . "' alt='profile-icon' width='40px' height='40px'>";
                echo "</div>";

                ?>

                <div class="logout-section position-absolute">
                    <p class="p-2"><a href="/website/project/panels/logout.php" class="btn text-light">logout</a>
                    </p>
                </div>


                <?php include './checkoffer.php'; ?>

                <form action="#" method="POST">
                    <fieldset>

                        <!--Title-->
                        <h4 class="addproduct-title my-4">Add Offers</h4>

                        <div class="input-field__container d-flex">

                            <!--Left Input Field Column-->
                            <div class="column-left w-100 mr-3">
                                <div class="product-name offer_discount">
                                    <label for="offer_discount" class="form-label">Offer Discount ( % by default)</label>
                                    <input type="number" id="offer_discount" class="form-control" name="offer_discount"
                                           placeholder="E.g. 10" />
                                </div>

                                <br />

                                <div class="product-allergy__info offer_desc">
                                    <label for="offer_desc" class="form-label">Offer description</label>
                                    <input type="text" id="offer_desc" class="form-control" name="offer_desc"
                                           placeholder="E.g. Dashain offer" />
                                </div>
                            </div>
                        </div>

                        <!--Button Field Container-->
                        <div class="btn-container mt-4 mb-3 d-flex">
                            <button type="submit" name="insertOffer" class="btn btn-primary w-100 mr-2">ADD
                                OFFER</button>
                            <button type="reset" class="btn btn-danger w-100 ml-2">CLEAR ALL</button>
                        </div>
                    </fieldset>
                </form>


            </div>
        </div>
    </div>
</main>

<!--External Scripts-->
<script src="../../../script.js"></script>

<?php } else {
    header('Location: /website/project/index.php');
}