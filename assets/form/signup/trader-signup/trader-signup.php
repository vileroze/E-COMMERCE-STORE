<?php

include_once "../../../../connection/connect.php";
$connection = getConnection();

include_once "../../../../includes/html-skeleton/skeleton.php";
include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

<!--External Stylesheet-->
<link rel="stylesheet" href="./trader-signup.css">


<div class="custom-container">
    <div class="row">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-6 mx-auto column-second my-1">

            <?php include_once "../../../form/form-functions.php"; ?>
            <?php include_once "./check-trader.php" ?>

            <!--Create Account for Trader-->
            <form action="#" method="POST" class="trader-account">
                <fieldset>
                    <h4 class="text-center">Registeration for Trader Account</h4>
                    <div class="icon-container d-flex justify-content-center align-items-center my-4">
                        <i class="fab fa-facebook-f mx-3"></i>
                        <i class="fab fa-google mx-3"></i>
                        <i class="fab fa-linkedin-in mx-3"></i>
                    </div>


                    <?php

                    if(isset($errors['empty'])) {
                        echo $errors['empty'];
                    }

                    if(isset($success_msg)) {
                        echo $success_msg;
                    }

                    if(isset($mail_error_msg)) {
                        echo $mail_error_msg;
                    }

                    ?>


                    <div class="input-field w-75 mx-auto my-4">
                        <!--Username field-->
                        <div class="username-field position-relative mt-4 d-flex">
                            <input type="text" class="form-control mr-1" placeholder="Firstname" name="firstname" />
                            <i class="fas fa-user custom-icon position-absolute"></i>
                            <input type="text" class="form-control ml-1" placeholder="Lastname" name="lastname">
                        </div>
                        <?php
                        if(isset($errors['name'])) {
                            echo $errors['name'];
                        }
                        ?>

                        <!--Address field-->
                        <div class="address-field position-relative mt-4">
                            <input type="text" class="form-control" placeholder="Address" name="address">
                            <i class="fas fa-address-card custom-icon position-absolute"></i>
                        </div>

                        <!--Email field-->
                        <div class="email-field position-relative mt-4">
                            <input type="text" class="form-control" placeholder="Email" name="email"/>
                            <i class="fas fa-envelope custom-icon position-absolute"></i>
                        </div>
                        <?php

                        if(isset($errors['email'])) {
                            echo $errors['email'];
                        }
                        ?>

                        <!--Contact field-->
                        <div class="contact-field position-relative mt-4">
                            <input type="number" class="form-control" placeholder="Phone Number" name="phone_number" />
                            <i class="fas fa-phone custom-icon position-absolute"></i>
                        </div>
                        <?php

                        if(isset($errors['phonenum'])) {
                            echo $errors['phonenum'];
                        }
                        ?>

                        <!--Shop field-->
                        <div class="shop-field position-relative mt-4">
                            <input type="text" class="form-control"
                                   placeholder="Product Type ( Eg. FishMonger, Greengrocer etc... )" name="product_type" />
                            <i class="fab fa-product-hunt custom-icon position-absolute"></i>
                        </div>


                        <!--Shop field-->
                        <div class="shop-field position-relative mt-4">
                            <input type="text" class="form-control"
                                   placeholder="Shop Name (E.g.The Corner Store)" name="shop_name" />
                            <i class="fas fa-store-alt custom-icon position-absolute"></i>
                        </div>

                        <!--Shop field-->
                        <div class="shop-field position-relative mt-4">
                            <input type="text" class="form-control"
                                   placeholder="Product Category ( Eg. Grilled Meat, Organic Vegetables )" name="product_category" />
                            <i class="fas fa-shopping-cart custom-icon position-absolute"></i>
                        </div>

                        <!--Password field-->
                        <div class="password-field position-relative mt-4">
                            <input type="password" class="form-control"
                                   placeholder="Password" name="password" />
                            <i class="fas fa-lock custom-icon position-absolute"></i>
                        </div>
                        <?php

                        if(isset($errors['password'])) {
                            echo $errors['password'];
                        }
                        ?>


                        <!--Re-Password field-->
                        <div class="password-field position-relative mt-4">
                            <input type="password" class="form-control"
                                   placeholder="Re-type above Password" name="re_password" />
                            <i class="fas fa-user-lock custom-icon position-absolute"></i>
                        </div>
                        <?php

                        if(isset($errors['password_check'])) {
                            echo $errors['password_check'];
                        }
                        ?>


                        <p class="text-center mt-4"><a href="/website/project/index.php">Home</a> | Already have an account ? <a class="signin" href="https://localhost/website/project/assets/form/signin/signin.php">Click here...</a>
                        </p>

                        <div class="btn-container text-center my-4">
                            <button type="submit" class="btn btn-md btn-primary text-uppercase" name="traderSubmit">Sign Up</button>
                        </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>