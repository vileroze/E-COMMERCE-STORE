<?php

session_start();

if(isset($_SESSION['trader'])) {
    $user_id = $_SESSION['trader'];
}

if(isset($_SESSION['admin_as_trader'])) {
    $user_id = $_SESSION['admin_as_trader'];
}

if (isset($user_id)) {

    $is_deleted = $_GET['delete'] ??= "";

    include_once "../../../../connection/connect.php";
    $connection = getConnection();

    include_once "../../../../includes/html-skeleton/skeleton.php";
    include_once "../../../../includes/cdn-links/fontawesome-cdn.php";
    include_once "../../../../includes/cdn-links/bootstrap-cdn.php"; ?>

    <!--External Stylesheet-->
    <link rel="stylesheet" href="displayoffer.css">


    <main>
        <div class="container-fluid">
            <div class="row">
                <?php include '../../trader-side-panel.php'; ?>

                <!--display Products Container Column-->
                <div class="col-xl-10 mx-auto p-0">

                    <?php

                    include_once '../../../../assets/trader-types/functions.php';
                    $profile_img = get_profile_image_of_user($user_id, $connection);

                    echo "<div class='user-profile-header'>";

                    if (empty($profile_img)) {
                        $profile_img = "default-image.jpg";
                    }

                    $trader_id = get_user_type_id($user_id, $connection, "TRADERS");
                    $trader_type = get_trader_type_from_traders($trader_id, $connection);

                    echo "<p class='trader-type'>" . strtoupper($trader_type) . "</p>";

                    echo "<img src='../../profile/profile-img/" . $profile_img . "' alt='profile-icon' width='40px' height='40px'>";
                    echo "</div>";

                    ?>

                    <div class="logout-section position-absolute">
                        <p class="p-2"><a href="/website/project/panels/logout.php" class="btn text-light">logout</a>
                        </p>
                    </div>


                    <?php

                    if($is_deleted == 'success') {
                        echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-success border p-2 border-success w-50 mx-auto text-center mt-4'><i class='fas fa-check-circle'></i>&nbsp;&nbsp;SUCCESS: OFFER REMOVED SUCCESSFULLY</p>";
                    }

                    if($is_deleted == 'failed') {
                        echo "<p style='border-width:2px !important; font-size: 1.1rem; font-weight : bold;' class='text-danger border p-2 border-danger w-75 mx-auto text-center mt-4'><i class='fas fa-times-circle'></i>&nbsp;&nbsp;FAILED: THIS OFFER IS STILL IN USE. YOU MUST REMOVE THIS OFFER FROM USED PRODUCTS</p>";
                    }

                    $trader_id = get_user_type_id($user_id, $connection, "TRADERS");
                    $result = fetch_all_offers($trader_id, $connection);

                    ?>

                    <div class="table-container m-5">

                        <span class="note"><b>Note : </b>For Traders only, use below given offer id while adding product in offer field to add respective offer in your product.</span>

                        <table class="table table-bordered table-hover mt-2">
                            <thead>
                            <th class="text-uppercase">Offer Id</th>
                            <th class="text-uppercase">Offer in %</th>
                            <th class="text-uppercase">Offer description</th>
                            <th class="text-uppercase">Action</th>
                            </thead>

                            <tbody>
                            <?php
                            while (($row = oci_fetch_assoc($result))) { ?>
                                <tr>
                                    <td><?php echo $row['OFFER_ID'] ?></td>
                                    <td><?php echo $row['PERCENTAGE'] ?>%</td>
                                    <td><?php echo $row['DESCRIPTION'] ?></td>
                                    <td><a href="http://localhost/website/project/panels/trader-panel/offers/deleteoffer/deleteoffer.php?offer_id=<?php echo $row['OFFER_ID'] ?>">Delete</a></td>
                                </tr>
                            <?php }
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <script src="../../../script.js"></script>

<?php } else {
    header('Location: /website/project/index.php');
}