<?php

session_start();

include_once "../form-functions.php";
include_once "../../trader-types/functions.php";

$connection = getConnection();

if (isset($_POST['form-submit'])) {

    //If a users tries to log in from two different page
    if (isset($_SESSION['logged_in']) != 'yes') {

        $user = $_POST['user-type'] ??= "";
        $email = $_POST['user-email'] ??= "";
        $password = $_POST['user-password'] ??= "";

        //Sanitizing values
        $user = htmlspecialchars(trim($user));
        $email = htmlspecialchars(trim($email));
        $password = htmlspecialchars(trim($password));

        //Error
        $errors = [];
        $success = "";


        //Validation and Sanitization of all fields
        if (!empty($user) && !empty($email) && !empty($password)) {

            $hashed_password = md5($password);

            $result = check_login(strtoupper($user), $email, $hashed_password, $connection);

            if ($result['result'] === true) {

                if ($user == 'customers') {
                    $_SESSION['user'] = $result['id'];

                } elseif ($user == 'traders') {
                    $_SESSION['trader'] = $result['id'];

                } elseif ($user == 'admin') {
                    $_SESSION['admin'] = $result['id'];
                }

                //If any user is already logged in we can do check to logged in another user if a user tries to login from another signin page
                $_SESSION['logged_in'] = 'yes';

                $success = "<div class='text-success border border-success p-1 text-center'>
                            <p class='mb-1 p-1'><i class='fas fa-check-circle text-success m-0'></i>&nbsp;Great, you have been successfully logged in.</p>
                            <p class='m-0 p-1'><i class='fas fa-check-circle text-success'></i>&nbsp;Please wait, you will be redirected to homepage automatically.</p>
                        </div>";

                ?>

                <script>

                    setTimeout(() => {
                        window.location.href = "/website/project/index.php";
                    }, 1500);
                </script>

                <?php

            } else {
                $errors['login'] = "<p class='text-danger text-center border border-danger p-1 m-0 w-75 mx-auto font-rubik'><i class='text-danger fas fa-times-circle'></i>&nbsp;Sorry, your username or password is invalid.</p>";
            }

        } else {
            $errors['login'] = "<p class='font-rubik border border-danger w-75 mx-auto text-danger p-2 m-0 d-flex align-items-center justify-content-center'><i class='text-danger fas fa-times-circle'></i>&nbsp;ERROR : NON OF THE FIELD SHOULD BE LEFT EMPTY</p>";
        }

    }else {
        header("Refresh: 0 url='http://localhost/website/project/index.php'");
    }

}