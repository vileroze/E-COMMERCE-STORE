<?php

$user_token = $_GET['user_token'] ??= "";

if (!empty($user_token)) {

    include_once "../../../../connection/connect.php";
    $connection = getConnection();

    include_once "../../../../includes/html-skeleton/skeleton.php";
    include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
    include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

    <!--External Stylesheet-->
    <link rel="stylesheet" href="./reset-password.css">


    <?php

    if (isset($_POST['resetPassSubmit'])) {

        $password = "";
        $reset_password = "";

        $errors = [];

        if (isset($_POST['userPass'])) {

            if (preg_match('/[A-Z]/', $_POST['userPass']) && preg_match('/[a-z]/', $_POST['userPass']) && (1 === preg_match('~[0-9]~', $_POST['userPass'])) && strlen($_POST['userPass']) >= 6) {

                if ($_POST['userPass'] !== $_POST['re_pass']) {
                    $errors['password_check'] = "<p class='text-danger p-0 m-0 d-flex align-items-center'>Sorry, your password doesn't matches with current password.</p>";

                } else {


                    //Fetching password and encrpting using md5 algorithm
                    $password = trim($_POST['userPass']);
                    $password = md5($password);

                    $query = "UPDATE USERS SET USERS.PASSWORD = '$password' WHERE USERS.TOKEN = '$user_token' AND USERS.STATUS = 1";
                    $result = oci_parse($connection, $query);
                    oci_execute($result);

                    header("Location: /website/project/assets/form/signin/signin.php?password_change=success");

                }


            } else {
                $errors['password'] = "<p class='text-danger p-0 m-0 d-flex align-items-start'>Password must be 6 characters long, must contains at least one uppercase letter, one lowercase letter and a number</p>";
            }

        } else {
            $errors['empty'] = "<p class='font-rubik border border-danger w-100 mx-auto text-danger my-4 d-flex align-items-center justify-content-center'><i class='text-danger fas fa-times-circle'></i>&nbsp;NONE OF THE FIELD SHOULD BE LEFT EMPTY</p>";
        }

    }


    ?>

    <div class="container-fluid d-flex align-items-center justify-content-center font-rubik">
        <div class="form-container w-25 mx-auto">
            <h3 class="mb-4">Reset your Password here...</h3>

            <?php
            if (isset($errors['empty'])) {
                echo $errors['empty'];
            }

            ?>

            <form action="#" method="POST">
                <div class="password-field mt-2">
                    <label for="userPass" class="form-label">Enter your New Password</label>
                    <input type="password" name="userPass" id="userPass" class="form-control">
                </div>
                <?php
                if (isset($errors['password'])) {
                    echo $errors['password'];
                }

                ?>

                <div class="repassword-field mt-4">
                    <label for="userrePass" class="form-label">Re-type Password</label>
                    <input type="password" name="re_pass" id="userrePass" class="form-control">
                </div>
                <?php
                if (isset($errors['password_check'])) {
                    echo $errors['password_check'];
                }

                ?>

                <div class="btn-container mt-4">
                    <button type="submit" class="btn w-100 text-uppercase" name="resetPassSubmit">Submit</button>
                </div>
            </form>
        </div>

        <div class="eclipse-container">
            <div class="bg-eclipse position-absolute">
                <img src="../forget-password/images/big-eclipse.svg" alt="big-eclipse">
            </div>

            <div class="md-eclipse position-absolute">
                <img src="../forget-password/images/mid-eclipse.svg" alt="mid-eclipse">
            </div>
        </div>

    </div>

<?php } else {
    header('Location: https://localhost/website/project/assets/form/signin/forget-password/forget-password.php?success=no');
}