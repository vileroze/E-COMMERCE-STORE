<?php

if (isset($_POST['traderSubmit'])) {

    //To hold input values
    $firstname = "";
    $lastname = "";
    $address = "";
    $email = "";
    $phone_number = "";
    $product_type = "";
    $shop_name = "";
    $product_category = "";
    $password = "";

    //To hold errors
    $errors = [];

    if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['address']) && !empty($_POST['email']) && !empty($_POST['phone_number']) && !empty($_POST['product_type']) && !empty($_POST['shop_name']) && !empty($_POST['product_category']) && !empty($_POST['password']) && !empty($_POST['re_password'])) {


        if (!preg_match('~[0-9]~', $_POST['firstname']) && !preg_match('~[0-9]~', $_POST['lastname'])) {

            //Firstname and lastname cannot be greater than 20
            if (strlen($_POST['firstname']) > 20 || strlen($_POST['lastname']) > 20) {
                $errors['name'] = "<p class='text-danger  p-0 mt-1 d-flex align-items-center '>Firstname and Lastname has limit of 20 letters long</p>";

            } else {
                $firstname = trim(htmlentities($_POST['firstname']));
                $lastname = trim(htmlentities($_POST['lastname']));
            }

        } else {
            $errors['name'] = "<p class='text-danger p-0 mt-1  d-flex align-items-center'>Firstname and Lastname cannot contain numbers</p>";
        }


        $address = htmlspecialchars(trim($_POST['address']));


        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $count = count_emails_from_users($_POST['email'], $connection);

            if ($count > 0) {
                $errors['email'] = "<p class='text-danger p-0 mt-1 d-flex align-items-centerX'>Sorry, Email already exists.</p>";

            } else {
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            }

        } else {
            $errors['email'] = "<p class='text-danger p-0 mt-1 d-flex align-items-centerX'>Given email is in invalid format</p>";
        }


        if (preg_match("*^[0-9]+$*", $_POST['phone_number'])) {

            if (strlen($_POST['phone_number']) === 10) {

                $count = count_phonenum_from_users($_POST['phone_number'], $connection);

                if ($count > 0) {
                    $errors['phonenum'] = "<p class='text-danger p-0 mt-1 d-flex align-items-centerX'>Sorry, Phone number already exists.</p>";
                } else {
                    $phone_number = trim($_POST['phone_number']);
                }

            } else {
                $errors['phonenum'] = "<p class='text-danger p-0 mt-1 d-flex align-items-center'>Phone number should be 10 characters long</p>";
            }

        } else {
            $errors['phonenum'] = "<p class='text-danger p-0 mt-1 d-flex align-items-center'>Given phone number is invalid</p>";
        }


        $product_type = htmlspecialchars(trim($_POST['product_type']));
        $shop_name = htmlspecialchars(trim($_POST['shop_name']));
        $product_category = htmlspecialchars(trim($_POST['product_category']));


        if (preg_match('/[A-Z]/', $_POST['password']) && preg_match('/[a-z]/', $_POST['password']) && (1 === preg_match('~[0-9]~', $_POST['password'])) && strlen($_POST['password']) >= 6) {

            $password = md5($_POST['password']);

            if ($_POST['password'] !== $_POST['re_password']) {
                $errors['password_check'] = "<p class='text-danger p-0 mt-1 d-flex align-items-center'>Sorry, your password doesn't matches with current password.</p>";
            }

        } else {

            $errors['password'] = "<p class='text-danger p-0 mt-1 d-flex align-items-start'>Password must be 6 characters long, must contains at least one uppercase letter, one lowercase letter and a number</p>";
        }

    } else {
        $errors['empty'] = "<p style='font-size: 1.2rem; border-width: 2px;' class='text-danger p-1 text-center border border-danger'><i class='fas fa-times-circle text-danger'></i>&nbsp;ERROR: NON OF THE FIELD[S] SHOULD BE LEFT EMPTY</p>";
    }

    $count = 0;

    foreach ($errors as $error) {
        $count++;
    }

    if ($count == 0) {

        $random_token = "";

        try {
            $random_token = bin2hex(random_bytes(15));
        } catch (Exception $exception) {
        }

        $query = "INSERT INTO USERS(USER_ID, FIRST_NAME, LAST_NAME, ADDRESS, EMAIL, PHONE_NUMBER, PASSWORD, PROFILE_IMG, TOKEN, STATUS, PERMISSIONS, ADMIN_ACCESS) VALUES (null, '$firstname', '$lastname', '$address', '$email', $phone_number, '$password', null , '$random_token', 0 , '$email', 'RAJES')";
        $result = oci_parse($connection, $query);
        oci_execute($result);


        //Sending mail to trader
        $admin_email = get_admin_mail($connection);
        $reciever = trim($admin_email);
        $body = "New Trader Alert!";

        include_once "../../mail-format/trader-signup-format.php";
        $name = $firstname . ' ' . $lastname;
        $subject = get_trader_mail_signup("https://localhost/website/project/assets/form/signup/trader-signup/verify-trader.php?token=$random_token&type=$product_type&shop=$shop_name&cat=$product_category", $name, $email, $phone_number, $product_type, $shop_name, $product_category);

        $header = "From: <brajesh18@tbc.edu.np>\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if (mail($reciever, $body, $subject, $header)) {
            $success_msg = "<div class='text-success border border-success p-1 text-center'>
                            <p class='mb-1 p-0'><i class='fas fa-check-circle text-success m-0'></i>&nbsp;Thank you for registering your account in Nature's Fresh Mart</p>
                            <p class='m-0 p-0'><i class='fas fa-check-circle text-success'></i>&nbsp;Your account will be successfully created after admin verifies your account.</p>
                        </div>";

        } else {
            $mail_error_msg = "<div class='text-danger border border-danger p-1 text-center'>
                            <p class='mb-1 p-0'><i class='fas fa-times-circle text-danger m-0'></i>&nbsp;Please, try again later.</p>
                            <p class='m-0 p-0'><i class='fas fa-times-circle text-danger'></i>&nbsp;There might be some issue on server configuration</p>
                        </div>";
        }

    }
}