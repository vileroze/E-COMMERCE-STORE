<?php

function fetch_email_from_users($email,  $connnection) {
    $query = "SELECT EMAIL FROM USERS WHERE USERS.EMAIL = '$email'";
    $result = oci_parse($connnection, $query);
    oci_execute($result);

    return $result;
}


function count_emails_from_users($email, $connection) {

    $result = fetch_email_from_users($email, $connection);
    $count = 0;

    while($row = oci_fetch_assoc($result)) {
        $count++;
    }

    return $count;
}


function fetch_phonenum_from_users($phonenum, $connnection) {
    $query = "SELECT PHONE_NUMBER FROM USERS WHERE USERS.PHONE_NUMBER = '$phonenum'";
    $result = oci_parse($connnection, $query);
    oci_execute($result);

    return $result;
}


function count_phonenum_from_users($phonenum, $connection) {

    $result = fetch_phonenum_from_users($phonenum, $connection);
    $count = 0;

    while($row = oci_fetch_assoc($result)) {
        $count++;
    }

    return $count;
}


function check_login($user, $email, $password, $connection) {

    $query = "SELECT * FROM USERS, $user WHERE USERS.USER_ID = $user.USER_ID AND USERS.EMAIL = '$email' AND USERS.PASSWORD = '$password' AND USERS.STATUS = 1";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $count = 0;
    $user_id = "";

    while($row = oci_fetch_assoc($result)) {
        $user_id = $row['USER_ID'];
        $count++;
    }

    if($count === 1) {
        return array("result" => true, "id" => $user_id);
    }else {
        return array("result" => false, "id" => $user_id);
    }

}


function get_user_id_from_token($token, $connection) {
    $query = "SELECT USER_ID FROM USERS WHERE USERS.TOKEN = '$token'";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $user_id = "";

    while($rows = oci_fetch_assoc($result)) {
        $user_id = $rows['USER_ID'];
    }

    return $user_id;
}

function get_trader_id_from_traders($user_id, $connection) {
    $query = "SELECT TRADER_ID FROM TRADERS WHERE TRADERS.USER_ID = $user_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $trader_id = '';
    while($rows = oci_fetch_assoc($result)) {
        $trader_id = $rows['TRADER_ID'];
    }

    return $trader_id;
}

function get_admin_mail($connection) {
    $query = "SELECT EMAIL FROM USERS, ADMIN WHERE ADMIN.USER_ID = USERS.USER_ID";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $email = "";
    while($rows = oci_fetch_assoc($result)) {
        $email = $rows['EMAIL'];
    }

    return $email;
}


function get_trader_mail_from_user_id($user_id, $connection) {

    $query = "SELECT EMAIL FROM USERS WHERE USERS.USER_ID = $user_id";
    $result = oci_parse($connection, $query);
    oci_execute($result);

    $email = "";
    while($rows = oci_fetch_assoc($result)) {
        $email = $rows['EMAIL'];
    }

    return $email;
}

