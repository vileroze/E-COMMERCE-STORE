<?php

session_start();

include_once "../../../../connection/connect.php";

//including form santization page
include_once "check-customer.php";

include_once "../../../../includes/html-skeleton/skeleton.php";
include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

<!--External Stylesheet-->
<link rel="stylesheet" href="signup.css">


<div class="custom-container">

    <!--Create account for Customer-->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
          class="customer-account w-50 mx-auto">
        <fieldset>
            <h4 class="text-center font-rubik">Registeration for Customer Account</h4>
            <div class="icon-container d-flex justify-content-center align-items-center my-4">
                <i class="fab fa-facebook-f mx-3"></i>
                <i class="fab fa-google mx-3"></i>
                <i class="fab fa-linkedin-in mx-3"></i>
            </div>

            <?php if (isset($success_msg)) {
                echo $success_msg;
            }
            if (isset($mail_error_msg)) {
                echo $mail_error_msg;
            }

            if(isset($errors['empty'])) {
                echo $errors['empty'];
            } ?>


            <!--Username field-->
            <div class="input-field w-75 mx-auto my-4">
                <div class="username-field position-relative mt-3 mb-1 d-flex">
                    <input type="text" name="custFirstname" class="form-control mr-1" placeholder="Firstname"
                           value="<?php if (isset($_POST['custFirstname'])) echo $_POST['custFirstname']; ?>">
                    <i class="fas fa-user custom-icon position-absolute"></i>
                    <input type="text" name="custLastname" class="form-control ml-1" placeholder="Lastname"
                           value="<?php if (isset($_POST['custLastname'])) echo $_POST['custLastname']; ?>">
                </div>
                <?php if (isset($errors['name'])) {
                    echo $errors['name'];
                } ?>

                <!--Address field-->
                <div class="address-field position-relative mt-3 mb-1">
                    <input type="text" name="custAddress" class="form-control"
                           value="<?php if (isset($_POST['custAddress'])) echo $_POST['custAddress']; ?>"
                           placeholder="Address">
                    <i class="fas fa-address-card custom-icon position-absolute"></i>
                </div>
                <?php if (isset($errors['address'])) {
                    echo $errors['address'];
                } ?>

                <!--Email field-->
                <div class="email-field position-relative mt-3 mb-1">
                    <input type="email" name="custEmail" class="form-control" placeholder="Email"
                           value="<?php if (isset($_POST['custEmail'])) echo $_POST['custEmail']; ?>"/>
                    <i class="fas fa-envelope custom-icon position-absolute"></i>
                </div>
                <?php if (isset($errors['email'])) {
                    echo $errors['email'];
                } ?>

                <!--Contact field-->
                <div class="contact-field position-relative mt-3 mb-1">
                    <input type="number" name="custPhone" class="form-control" placeholder="Phone Number"
                           value="<?php if (isset($_POST['custPhone'])) echo $_POST['custPhone']; ?>"/>
                    <i class="fas fa-phone custom-icon position-absolute"></i>
                </div>
                <?php if (isset($errors['phonenum'])) {
                    echo $errors['phonenum'];
                } ?>

                <!--Password field-->
                <div class="password-field position-relative mt-3 mb-1">
                    <input type="password" name="custPassword" class="form-control" placeholder="Password"
                           value="<?php if (isset($_POST['custPassword'])) echo $_POST['custPassword']; ?>"/>
                    <i class="fas fa-lock custom-icon position-absolute"></i>
                </div>
                <?php if (isset($errors['password'])) {
                    echo $errors['password'];
                } ?>

                <!--Password field-->
                <div class="password-field position-relative mt-3 mb-1">
                    <input type="password" name="custPasswordCheck" class="form-control" placeholder="Re-type Password"
                           value="<?php if (isset($_POST['custPasswordCheck'])) echo $_POST['custPasswordCheck']; ?>"/>
                    <i class="fas fa-check-circle custom-icon position-absolute"></i>
                </div>
                <?php if (isset($errors['password_check'])) {
                    echo $errors['password_check'];
                } ?>

                <p class="text-center mt-3">
                    <a href="/website/project/index.php">Home</a> |
                    Already have an account ? <a class="signin" href="/website/project/assets/form/signin/signin.php">Click here...</a>
                </p>

                <div class="btn-container text-center mt-3 mb-1">
                    <button type="submit" class="btn btn-md btn-primary text-uppercase" name="customerSubmit">
                        Sign Up
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
</div>

