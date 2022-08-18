<?php

include_once "../../../connection/connect.php";
$connection = getConnection();

$message = $_GET['message'] ??= "";
$logout_success = $_GET['logged'] ??= "";
$password_change = $_GET['password_change'] ??= "";

include_once "../../../includes/html-skeleton/skeleton.php";
include_once "../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../includes/cdn-links/bootstrap-cdn.php";

?>

<!--External Stylesheet-->
<link rel="stylesheet" href="signin.css">


<div class="container-fluid">
    <div class="custom-container d-flex justify-content-center align-items-center
        ">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-9 col-lg-8 col-xl-6 mx-auto column-first
            ">
                <form action="/website/project/assets/form/signin/signin.php" method="POST">
                    <fieldset>
                        <h4 class="text-center font-rubik">
                            Sign in to Nature's Fresh Mart
                        </h4>
                        <div class="icon-container d-flex align-items-center justify-content-center my-4">
                            <i class="fab fa-facebook-f mx-3"></i>
                            <i class="fab fa-google mx-3"></i>
                            <i class="fab fa-linkedin-in mx-3"></i>
                        </div>

                        <!--Fetching page that has login proccess-->
                        <?php include_once "check-signin.php"; ?>

                        <?php if (isset($errors['login'])) {
                            echo $errors['login'];
                        } ?>

                        <?php if ($password_change == "success") {
                            echo "<p class='font-rubik border border-success text-success w-75 mx-auto p-1 m-0 d-flex align-items-center justify-content-center'><i class='text-success fas fa-check-circle'></i>&nbsp;SUCCESS: PASSWORD CHANGED SUCCESSFULLY</p>";
                        } ?>


                        <?php if (isset($success)) {
                            echo $success;
                        } ?>

                        <?php if (!empty($message)) {
                            echo "<p style='font-size: 1.2rem' class='font-rubik border border-danger text-danger p-1 m-0 d-flex align-items-center justify-content-center'><i class='text-danger fas fa-times-circle'></i>&nbsp;You must be logged in as customer in order to purchase your items.</p>";
                        } ?>

                        <div class="input-field w-75 mx-auto my-4">
                            <div class="email-field position-relative my-4">
                                <input type="email" id="user-email" class="form-control" placeholder="Email"
                                       name="user-email"/>
                                <i class="fas fa-envelope position-absolute custom-icon"></i>
                            </div>

                            <div class="password-field my-4 position-relative">
                                <input type="password" class="form-control" placeholder="Password" id="user-password"
                                       name="user-password"/>
                                <i class="fas fa-lock position-absolute custom-icon"></i>
                            </div>

                            <div class="role-field my-4">
                                <select name="user-type" id="user-type" class="form-control form-control-lg">
                                    <option value="customers">Customer</option>
                                    <option value="traders">Trader</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <p class="text-center mt-4">
                                <a href="/website/project/index.php">Home</a> |
                                <span>New to Nature's Fresh Mart ? </span><a class="signup text-nowrap" href="/website/project/assets/form/signup/customer-signup/customer-signup.php">Click here...</a>
                            </p>

                            <p class="text-center">
                              Forgot your Password ? <a href="http://localhost/website/project/assets/form/signin/forget-password/forget-password.php">Click here...</a>
                            </p>

                            <div class="btn-container text-center my-4 d-block">
                                <button type="submit" class="btn btn-md btn-primary" name="form-submit">SIGN IN</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
