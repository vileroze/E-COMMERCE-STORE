<?php

session_start();
include_once '../../connection/connect.php';
$connection = getConnection();
$collection_slot_id = "";

include_once '../../includes/html-skeleton/skeleton.php';
include_once '../../includes/cdn-links/fontawesome-cdn.php';
include_once '../../includes/cdn-links/bootstrap-cdn.php';


if (isset($_SESSION['user'])) {

    if ($_SESSION['checkout'] == true) {

        $bool = 0;

        include_once '../trader-types/functions.php';

        //Fetching all the necessary information needed
        $user_id = $_SESSION['user'];
        $customer_id = get_user_type_id($user_id, $connection, "CUSTOMERS");
        $basket_token = $_SESSION['basket_token'];
        $basket_id = get_basket_id_from_baskets($basket_token, $connection);
        $total_sum = fetch_total_sum_from_baskets($basket_id, $connection);
        ?>
        <link rel="stylesheet" href="checkout.css">

        <div id="pre-loader"></div>

        <div class="custom-container d-flex align-items-center justify-content-center">
            <div class="payment-container">
                <h3 class="font-rale mb-5">Choose a collection slot suitable for you.</h3>


                <?php
                if (isset($_POST['select_slot'])) {

                    //Get all the collection slot details when customer chooses a slot
                    $collection_day = $_POST['collection_day'] ??= "";
                    $collection_time = $_POST['collection_time'] ??= "";
                    $collection_slot_id = fetch_collection_id($collection_day, $collection_time, $connection);


                    $currentDate = date('m/d/Y', time());
                    $total_orders_in_a_slot = get_orders_of_slots($collection_slot_id, $currentDate, $connection);

                    //Checking whether a slot is full or not when customer tries to select a slot
                    if ($total_orders_in_a_slot < 20) {
                        echo "<p style='border-width:1px; font-size: 1.1rem;'  class='text-success border border-success mt-4 text-center p-2'><i class='fas fa-check-circle'></i>&nbsp;&nbsp;&nbsp;Your collection slot preferrence has been saved</p>";
                        $bool = 1;

                    } else {
                        echo "<p style='border-width:1px; font-size: 1.1rem;'  class='text-danger border border-danger mt-4 text-center p-2'><i class='fas fa-times-circle'></i>&nbsp;&nbsp;&nbsp;Selected slot is already full. Choose another slot!</p>";
                    }
                }
                ?>


                <form action="" method="POST">
                    <label for="collection_slot" class="from-label">I would like my order to be delivered coming
                        :</label>
                    <div class="d-flex align-items-center mt-2 mb-4">
                        <select name="collection_day" class="form-control mr-2 collection_day__container">
                            <?php

                            //Fetching all the collection day details from collection_slots
                            $slot_result = get_all_collection_day($connection);

                            while ($rows = oci_fetch_assoc($slot_result)) {
                                echo "<option value = '" . $rows['COLLECTION_DAY'] . "' class='collection_day'>" . $rows['COLLECTION_DAY'] . "</option>";
                            }
                            ?>
                        </select>

                        <select name="collection_time" class="form-control mr-2 collection_time__container">

                            <?php

                            //Fetching all the collection time details from collection_slots
                            $slot_result = get_all_collection_time($connection);

                            while ($rows = oci_fetch_assoc($slot_result)) {
                                echo "<option value = '" . $rows['COLLECTION_TIME'] . "' class='collection_time'>" . $rows['COLLECTION_TIME'] . "</option>";
                            }
                            ?>

                        </select>

                        <button class="btn coll_btn btn-outline-success ml-2" type="submit" name="select_slot">SAVE
                        </button>
                    </div>
                </form>

                <?php

                //Paypal button will only be orginated when a customer selects a selectable slot
                if ($bool === 1) { ?>

                    <h3 class="font-rale mb-5">Click below button to pay for purchased order.</h3>
                    <div id="paypal-button-container"></div>

                <?php } ?>

            </div>
        </div>

        <script
                src="https://www.paypal.com/sdk/js?client-id=AWmp5U1QZ3zCa3GpPbxNZ_tfFnbQNp2v1-7Cqhcga82O1fEGRtnqo-p6BhCDFM6WxnX7kqHIgx91aYZi&currency=GBP"> // Required. Replace YOUR_CLIENT_ID with your sandbox client ID.
        </script>
        <script>
            paypal.Buttons({
                createOrder: function (data, actions) {
                    // This function sets up the details of the transaction, including the amount and line item details.
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                currency_code: "GBP",
                                //ya amount render garnu parcha session bata
                                value: '<?php echo $total_sum; ?>'
                            }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    // This function captures the funds from the transaction.
                    return actions.order.capture().then(function (details) {
                        //Setting Preloader to show after payment
                        let preLoader = document.getElementById('pre-loader');
                        setTimeout(() => {
                            preLoader.style.display = 'block';
                            location.href = 'payment_success.php?&collection_slot_id=<?php echo $collection_slot_id;?>';
                        }, 500)
                    });
                }
            }).render('#paypal-button-container');
            // This function displays Smart Payment Buttons on your web page.
        </script>

        <!--External Scripts-->
        <script src="slots.js"></script>

    <?php } else {
        header('Location: /website/project/assets/addtocart/addtocart.php');
    }

} else {
    header('Location: /website/project/assets/form/signin/signin.php?message=login');
}
