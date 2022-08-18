<?php

include_once "../../../../connection/connect.php";
$connection = getConnection();

$failed_reset = $_GET['success'] ??= "";

include_once "../../../../includes/html-skeleton/skeleton.php";
include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

<!--External Stylesheet-->
<link rel="stylesheet" href="./forget-password.css">


<?php

if(isset($_POST['resetSubmit'])) {

    $email = "";
    $phone_num = "";
    $errors = [];

    if(!empty($_POST['userEmail']) & !empty($_POST['phoneNum'])) {

        if(filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL)) {
            $email = trim($_POST['userEmail']);

        }else {
            $errors['email'] = "<p class='font-rubik w-100 mx-auto text-danger m-0'>Invalid email format</p>";

        }


        if(strlen($_POST['phoneNum']) == 10) {
            $phone_num = htmlspecialchars(trim($_POST['phoneNum']));

        }else {
            $errors['phone'] = "<p class='font-rubik w-100 mx-auto text-danger m-0'>Invalid phone number format</p>";

        }


        $count_errors = 0;
        foreach($errors as $error) {
            if(isset($error)) {
                $count_errors++;
            }
        }

        if($count_errors == 0) {
            $query = "SELECT * FROM USERS WHERE USERS.EMAIL = '$email' AND USERS.PHONE_NUMBER = $phone_num AND USERS.STATUS = 1";
            $result = oci_parse($connection, $query);
            oci_execute($result);

            $token = "";
            while($rows = oci_fetch_assoc($result)) {
                $token .= $rows['TOKEN'];
            }


            if(oci_execute($result)) {

                $token = "";
                while($rows = oci_fetch_assoc($result)) {
                    $token .= $rows['TOKEN'];
                }


                echo $token;

                header("Location: http://localhost/website/project/assets/form/signin/reset-password/reset-password.php?user_token=$token");
            }else {
                $errors['error'] = "<p class='font-rubik border border-danger w-100 mx-auto text-danger m-0 d-flex align-items-center justify-content-center'><i class='text-danger fas fa-times-circle'></i>&nbsp;ERROR : COULD NOT PERFORM ACTION</p>";

            }
        }

    }else {
        $errors['empty'] = "<p class='font-rubik border border-danger w-100 mx-auto text-danger my-4 d-flex align-items-center justify-content-center'><i class='text-danger fas fa-times-circle'></i>&nbsp;NONE OF THE FIELD SHOULD BE LEFT EMPTY</p>";
    }

}


?>

<div class="container-fluid d-flex align-items-center justify-content-center font-rubik">
    <div class="form-container w-25 mx-auto">
        <h3 class="mb-4">Enter your following details </h3>

        <?php

        if(isset($errors['empty'])) {
            echo $errors['empty'];
        }

        if(isset($errors['error'])) {
            echo $errors['error'];
        }

        if($failed_reset == "no") {
            echo "<p class='font-rubik border border-danger w-100 mx-auto text-danger my-4 d-flex align-items-center justify-content-center'><i class='text-danger fas fa-times-circle'></i>&nbsp;ERROR : CANNOT PERFORM ACTION</p>";
        }

        ?>

        <form action="#" method="POST">
            <div class="email-field mt-2">
                <label for="userEmail" class="form-label">Email</label>
                <input type="email" name="userEmail" id="userEmail" class="form-control">
            </div>
            <?php
            if(isset($errors['email'])) {
                echo $errors['email'];
            }

            ?>

            <div class="phone-number mt-4">
                <label for="userPhone" class="form-label">Phone Number</label>
                <input type="number" name="phoneNum" id="userPhone" class="form-control">
            </div>
            <?php
            if(isset($errors['phone'])) {
                echo $errors['phone'];
            }

            ?>

            <div class="btn-container mt-4">
                <button type="submit" class="btn w-100 text-uppercase" name="resetSubmit">Submit</button>
            </div>
        </form>
    </div>

    <div class="eclipse-container">
        <div class="bg-eclipse position-absolute">
            <img src="./images/big-eclipse.svg" alt="big-eclipse">
        </div>

        <div class="md-eclipse position-absolute">
            <img src="./images/mid-eclipse.svg" alt="mid-eclipse">
        </div>
    </div>

</div>
