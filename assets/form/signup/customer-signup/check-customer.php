<?php

include_once "../../form-functions.php";
include_once "../../../trader-types/functions.php";


$connection = getConnection();

//Final variables after sanitization
$customerAddr = "";
$customerFirstName = "";
$customerLastName = "";
$customerEmail = "";
$customerPhoneNum = "";
$customerPassword = "";

//Variables to display errors
$errors = [];
$success_msg = "";
$mail_error_msg = "";


if (isset($_POST['customerSubmit'])) {

    //Fetch from input
    $addr = $_POST['custAddress'] ??= "";
    $firstName = $_POST['custFirstname'] ??= "";
    $lastName = $_POST['custLastname'] ??= "";
    $email = $_POST['custEmail'] ??= "";
    $phoneNum = $_POST['custPhone'] ??= "";
    $password = $_POST['custPassword'] ??= "";
    $password_check = $_POST['custPasswordCheck'] ??= "";
    $password_check = htmlspecialchars(trim($password_check));

    if (!empty($firstName) && !empty($lastName) && !empty($addr) && !empty($email) && !empty($phoneNum) && !empty($password)) {


        //Checking whether names contain numbers or not
        if (!preg_match('~[0-9]~', $firstName) && !preg_match('~[0-9]~', $lastName)) {

            //Firstname and lastname cannot be greater than 20
            if (strlen($firstName) > 20 || strlen($lastName) > 20) {
                $errors['name'] = "<p class='text-danger  p-0 m-0 d-flex align-items-center '><i class='fas fa-times-circle text-danger'></i>&nbsp;Firstname and Lastname has limit of 20 letters long</p>";

            } else {
                $customerFirstName = trim(htmlentities($firstName));
                $customerLastName = trim(htmlentities($lastName));
            }
        } else {
            $errors['name'] = "<p class='text-danger p-0 m-0  d-flex align-items-center'><i class='fas fa-times-circle text-danger'></i>&nbsp;Firstname and Lastname cannot contain numbers</p>";
        }


        $customerAddr = trim($addr);


        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $count = count_emails_from_users($email, $connection);

            if ($count > 0) {
                $errors['email'] = "<p class='text-danger p-0 m-0 d-flex align-items-centerX'><i class='fas fa-times-circle text-danger'></i>&nbsp;Sorry, Email already exists.</p>";

            } else {
                $customerEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            }

        } else {
            $errors['email'] = "<p class='text-danger p-0 m-0 d-flex align-items-centerX'><i class='fas fa-times-circle text-danger'></i>&nbsp;Given email is in invalid format</p>";
        }


        if (preg_match("*^[0-9]+$*", $phoneNum)) {

            if (strlen($phoneNum) === 10) {

                $count = count_phonenum_from_users($phoneNum, $connection);

                if ($count > 0) {
                    $errors['phonenum'] = "<p class='text-danger p-0 m-0 d-flex align-items-centerX'><i class='fas fa-times-circle text-danger'></i>&nbsp;Sorry, Phone number already exists.</p>";
                } else {
                    $customerPhoneNum = trim($phoneNum);
                }

            } else {
                $errors['phonenum'] = "<p class='text-danger p-0 m-0 d-flex align-items-center'><i class='fas fa-times-circle text-danger'></i>&nbsp;Phone number should be 10 characters long</p>";
            }

        } else {
            $errors['phonenum'] = "<p class='text-danger p-0 m-0 d-flex align-items-center'><i class='fas fa-times-circle text-danger'></i>&nbsp;Given phone number is invalid</p>";
        }


        if (preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && (1 === preg_match('~[0-9]~', $password)) && strlen($password) >= 6) {
            $customerPassword = md5(trim($password));

            if ($password !== $password_check) {
                $errors['password_check'] = "<p class='text-danger p-0 m-0 d-flex align-items-center'><i class='fas fa-times-circle text-danger'></i>&nbsp;Sorry, your password doesn't matches with current password.</p>";
            }

        } else {

            $errors['password'] = "<p class='text-danger p-0 m-0 d-flex align-items-start'> <i class='fas fa-times-circle text-danger'></i>&nbsp;Password must be 6 characters long, must contains at least one uppercase letter, one lowercase letter and a number</p>";
        }


        if (empty($errors['name']) && empty($errors['address']) && empty($errors['email']) && empty($errors['phonenum']) && empty($errors['password']) && empty($errors['password_check'])) {

            $success_msg = "";
            $token = "";

            try {
                $token = bin2hex(random_bytes(15));
            } catch (Exception $e) {
                echo $e;
            }


            $query_for_users = "INSERT INTO USERS(USER_ID, FIRST_NAME, LAST_NAME, ADDRESS, EMAIL, PHONE_NUMBER, PASSWORD, PROFILE_IMG, TOKEN, STATUS) VALUES (null, '$customerFirstName', '$customerLastName', '$customerAddr', '$customerEmail', $customerPhoneNum, '$customerPassword', null , '$token', 0 )";

            $result_for_users = oci_parse($connection, $query_for_users);
            oci_execute($result_for_users);


            //Fetching user id after inserted into users using token
            $user_id = get_user_id_from_token($token, $connection);

            $query_for_customer = "INSERT INTO CUSTOMERS(USER_ID, CUSTOMER_ID) VALUES($user_id, null)";
            $result_for_customer = oci_parse($connection, $query_for_customer);
            oci_execute($result_for_customer);

            $customer_id = get_user_type_id($user_id, $connection, "CUSTOMERS");

            $basket_token = "";

            try {
                $basket_token = bin2hex(random_bytes(25));
            } catch (Exception $exception) {

                $random_value = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";

                for ($i = 0; $i < 20; $i++) {
                    $rand = rand(0, (strlen($random_value) - 1));
                    $basket_token .= $random_value[$rand];
                }
            }

            insert_into_basket($customer_id, $basket_token, $connection);
            $_SESSION['basket_token'] = $basket_token;


            //Mail Requirements
            $receiver = $customerEmail;
            $body = "Dear $customerFirstName $customerLastName, Verify it's you";

            include_once "../../../form/mail-format/customer-signup-format.php";
            $subject = get_customer_mail_signup("localhost/website/project/assets/form/signup/customer-signup/verify-customer.php?token=$token");

            $header = "From: <brajesh18@tbc.edu.np>\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            if (mail($receiver, $body, $subject, $header)) {
                $success_msg = "<div class='text-success border border-success p-1 text-center'>
                            <p class='mb-1 p-0'><i class='fas fa-check-circle text-success m-0'></i>&nbsp;Your account has been successfully created.</p>
                            <p class='m-0 p-0'><i class='fas fa-check-circle text-success'></i>&nbsp;To verify your account, please click the verfication link in your email</p>
                        </div>";


            } else {
                $mail_error_msg = "<div class='text-danger border border-danger p-1 text-center'>
                            <p class='mb-1 p-0'><i class='fas fa-times-circle text-danger m-0'></i>&nbsp;Please, try again later.</p>
                            <p class='m-0 p-0'><i class='fas fa-times-circle text-danger'></i>&nbsp;There might be some issue on server configuration.</p>
                        </div>";
            }


        }


    } else {
        $errors['empty'] = "<p class='text-danger border border-danger p-2 w-75 mx-auto m-0 text-center'><i class='fas fa-times-circle text-danger text-uppercase'></i>&nbsp;ERROR: NON OF THE FIELD SHOULD BE EMPTY</p>";
    }


}
