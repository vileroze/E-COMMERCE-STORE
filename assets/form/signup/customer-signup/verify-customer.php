<?php

include_once "../../../../connection/connect.php";

include_once "../../../../includes/html-skeleton/skeleton.php";
include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../../../includes/cdn-links/bootstrap-cdn.php";

$connection = getConnection();

$token = $_GET['token'];

include_once "../../form-functions.php";
$user_id = get_user_id_from_token($token, $connection);

if(isset($user_id)) {

    $query = "UPDATE USERS SET STATUS = 1 WHERE USERS.TOKEN = '$token'";
    $resultAfter = oci_parse($connection, $query);
    oci_execute($resultAfter);

    ?>

    <div class="w-50 mx-auto mt-5 mb-3">
        <p class="alert alert-success text-center font-rale"><b>Your account has been verified. Go to login page and enjoy shopping, thank you.</b></p>
        <div class="text-center">
            <a href="/website/project/assets/form/signin/signin.php" class="btn btn-primary mt-2">Go back to Login page</a>
        </div>
    </div>

<?php }else { ?>
    <div class="w-50 mx-auto mt-5">
        <p class="alert alert-success text-center font-rale"><b>Verification failed. Please, try again later.</b></p>
        <div class="text-center">
            <a href="/website/project/index.php" class="btn btn-primary">Go back to Registration page</a>
        </div>
    </div>

<?php }
