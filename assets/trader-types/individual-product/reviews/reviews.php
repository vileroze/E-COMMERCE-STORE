<?php

if(isset($_POST['reviewSubmit'])) {

    $reviews = "";
    $customer_user_id = $_SESSION['user'];

    $review_errors = [];

    if(empty($_POST['reviews']) && empty($_POST['rating_value'])) {
        $review_errors['empty'] = "<p style='font-weight: bold;' class='alert alert-danger w-75 mx-auto my-2 font-rale'><i class='fas fa-times-circle'></i>&nbsp;At least one field should not be empty</p>";

    }

    if(empty($review_errors['empty'])) {
        $rating = trim($_POST['rating_value']);

        if(!empty($_POST['reviews'])) {
            $review_comment = trim($_POST['reviews']);


            $query_reviews ="INSERT INTO REVIEWS(REVIEW_RATING, REVIEW_COMMENT, FK1_PRODUCT_ID, FK2_USER_ID) VALUES('$rating', '$review_comment', $product_id, $customer_user_id)";
        }else {

            $query_reviews ="INSERT INTO REVIEWS(REVIEW_RATING, REVIEW_COMMENT, FK1_PRODUCT_ID, FK2_USER_ID) VALUES('$rating', '', $product_id, $customer_user_id)";
        }


        $result_reviews = oci_parse($connection, $query_reviews);

        if(oci_execute($result_reviews)) {
            $success_reviews = "<p style='font-weight: bold;' class='alert alert-success w-75 mx-auto font-rale my-2'><i class='fas fa-check-circle'></i>&nbsp;Your reviews has been added successfully.</p>";
        }
    }


}