<?php

session_start();

if(isset($_SESSION['trader'])) {
    $user_id = $_SESSION['trader'];
}

if(isset($_SESSION['admin_as_trader'])) {
    $user_id= $_SESSION['admin_as_trader'];
}

if (isset($user_id)) {

    $product_id = $_GET['id'] ??= "";

    if (isset($product_id)) {

        include_once "../../../../connection/connect.php";
        $connection = getConnection();

        include_once "../../../../includes/html-skeleton/skeleton.php";
        include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
        include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

        <!--External Stylesheet-->
        <link rel="stylesheet" href="../addproduct/addproduct.css">

        <main>
            <div class="container-fluid">
                <div class="row">
                    <?php include '../../trader-side-panel.php' ?>

                    <!--Add Products Container Column-->
                    <div class="col-xl-10 mx-auto p-0">

                        <?php

                        include_once '../../../../assets/trader-types/functions.php';
                        $profile_img = get_profile_image_of_user($user_id, $connection);

                        echo "<div class='user-profile-header'>";

                        if (!isset($profile_img)) {
                            $profile_img = "default-image.jpg";
                        }

                        $trader_id = get_user_type_id($user_id, $connection, "TRADERS");
                        $trader_type = get_trader_type_from_traders($trader_id, $connection);

                        echo "<p class='trader-type'>" . strtoupper($trader_type) . "</p>";

                        echo "<img src='../../profile/profile-img/" . $profile_img . "' alt='profile-icon' width='40px' height='40px'>";
                        echo "</div>";

                        ?>

                        <div class="logout-section position-absolute">
                            <p class="p-2"><a href="/website/project/panels/logout.php"
                                              class="btn text-light">logout</a>
                            </p>
                        </div>

                        <?php include "./check-updateproduct.php"; ?>

                        <?php

                        $result = fetch_individual_products($product_id, $connection);

                        while ($rows = oci_fetch_assoc($result)) { ?>

                            <form action="#" method="POST" enctype="multipart/form-data">
                                <fieldset>

                                    <!--Title-->
                                    <h4 class="addproduct-title my-4">Add Products</h4>

                                    <div class="input-field__container d-flex">

                                        <!--Left Input Field Column-->
                                        <div class="column-left w-100 mr-3">
                                            <div class="product-name">
                                                <label for="product-name" class="form-label">Product Name<span class="text-danger">*</span></label>
                                                <input type="text" id="product-name" class="form-control"
                                                       name="product-name"
                                                       value="<?php echo $rows['PRODUCT_NAME'] ??= ""; ?>"
                                                       placeholder="E.g. Whole Grain Sliced Bread"/>
                                            </div>

                                            <br/>

                                            <div class="product-price">
                                                <label for="product-price" class="form-label">Product Price<span class="text-danger">*</span></label>
                                                <input type="number" id="product-price" class="form-control"
                                                       name="product-price"
                                                       value="<?php echo $rows['ITEM_PRICE'] ??= ""; ?>"
                                                       placeholder="E.g. 35"/>
                                            </div>
                                            <?php if (isset($pnameerror)) {
                                                echo $pnameerror;
                                            } ?>

                                            <br/>

                                            <div class="product-quantity">
                                                <label for="product-quantity" class="form-label">Quantity in
                                                    stock<span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="product-quantity"
                                                       name="product-quantity"
                                                       value="<?php echo $rows['QUANTITY_IN_STOCK'] ??= ""; ?>"
                                                       placeholder="Total number of available product"/>
                                            </div>

                                            <br/>

                                            <div class="product-availability">
                                                <label for="form-label" class="product-availability">Product
                                                    Availability<span class="text-danger">*</span></label>
                                                <select name="product-availability" id="product-availability"
                                                        class="form-control">
                                                    <option value="1">Yes (Default)</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>

                                            <br/>

                                            <div class="product-desc">
                                                <label for="product-desc">Product Description<span class="text-danger">*</span></label>
                                                <textarea name="product-desc" id="product-desc" rows="5"
                                                          class="form-control" placeholder="Description about product"><?php echo $rows['PRODUCT_INFO'] ??= ""; ?></textarea>
                                            </div>
                                        </div>

                                        <div class="column-right w-100 ml-3">

                                            <div class="product-allergy__info">
                                                <label for="product-allergy__info" class="form-label">Allergy
                                                    Information</label>
                                                <textarea rows="5" class="form-control" id="product-allergy__info"
                                                          name="product-allergy__info"><?php echo $rows['ALLERGY_INFO'] ??= ""; ?></textarea>
                                            </div>

                                            <div style="margin-top: 30px;" class="offer_code">
                                                <label for="fk_offer_id" class="form-label">Offer code</label>
                                                <input type="number" class="form-control" id="fk_offer_id"
                                                       name="fk_offer_id"
                                                       value="<?php echo $rows['FK_OFFER_ID'] ??= ""; ?>"/>
                                            </div>

                                            <br/>

                                            <div class="img-select mb-1">
                                                <input type="file" name="product-img" id="product-img"/>
                                            </div>
                                            <?php if (isset($img_error)) {
                                                echo $img_error;
                                            } ?>
                                            <span class="note"><b>Note : </b>Default image will be uploaded if not choosen any image.</span>
                                        </div>

                                    </div>

                                    <!--Button Field Container-->
                                    <div class="btn-container mt-4 mb-3 d-flex">
                                        <button type="submit" name="updateProduct" class="btn btn-primary w-100 mr-2">
                                            UPDATE PRODUCT
                                        </button>
                                        <button type="reset" class="btn btn-danger w-100 ml-2">CLEAR ALL</button>
                                    </div>
                                </fieldset>
                            </form>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </main>

        <!--External Scripts-->
        <script src="../../../script.js"></script>

    <?php } else {
        header('Location: http://localhost/website/project/panels/trader-panel/crud-product/displayproduct/displayproduct.php');
    }
} else {
    header('Location: /website/project/index.php');
}