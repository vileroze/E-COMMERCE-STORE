<?php

session_start();

if (isset($_SESSION['admin'])) {

    include_once "../../../connection/connect.php";
    $connection = getConnection();

    include_once "../../../includes/html-skeleton/skeleton.php";
    include_once "../../../includes/cdn-links/fontawesome-cdn.php";
    include_once "../../../includes/cdn-links/bootstrap-cdn.php"; ?>

    <!--External Stylesheet-->
    <link rel="stylesheet" href="display-shops.css">

    <main>
        <div class="container-fluid">
            <div class="row">
                <?php include '../admin-side-panel.php' ?>

                <!--display Products Container Column-->
                <div class="col-xl-10 mx-auto p-0">

                    <?php

                    include_once '../../../assets/trader-types/functions.php';
                    $profile_img = get_profile_image_of_user($_SESSION['admin'], $connection);

                    echo "<div class='user-profile-header'>";

                    if (empty($profile_img)) {
                        $profile_img = "default-image.jpg";
                    }

                    echo "<img src='../profile/profile-img/" . $profile_img . "' alt='profile-icon' width='40px' height='40px'>";
                    echo "</div>";

                    ?>

                    <div class="logout-section position-absolute">
                        <p class="p-2"><a href="/website/project/panels/logout.php" class="btn text-light">logout</a>
                        </p>
                    </div>

                    <div class="table-container">

                        <table class="table table-bordered table-hover mt-2">
                            <thead>
                            <th>TRADER'S NAME</th>
                            <th>TRADER TYPE</th>
                            <th>SHOP NAME</th>
                            <th>PRODUCT CATEGORY</th>
                            <th>ACTION</th>
                            </thead>
                            <tbody>
                            <?php

                            $result = fetch_all_users_shops_and_traders($connection);

                            while ($rows = oci_fetch_assoc($result)) { ?>

                                <tr>
                                    <td><?php echo ucfirst($rows['FIRST_NAME']) ?> <?php echo ucfirst($rows['LAST_NAME']) ?></td>
                                    <td><?php echo ucfirst($rows['TRADER_TYPE']) ?></td>
                                    <td><?php echo ucfirst($rows['SHOP_NAME']) ?></td>
                                    <td><?php echo ucfirst($rows['PRODUCT_CATEGORY']) ?></td>
                                    <td><a href="http://localhost/website/project/panels/admin-panel/admin-trader/login.php?type=<?php echo $rows['TRADER_TYPE'] ?>">Login as trader</a></td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </main>

    <!--External Scripts-->
    <script src="../../script.js"></script>

<?php } else {
    header("Location: /website/project/index.php");
}
