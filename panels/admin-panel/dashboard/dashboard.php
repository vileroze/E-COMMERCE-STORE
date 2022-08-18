<?php

session_start();

if (isset($_SESSION['admin'])) {

    include_once "../../../connection/connect.php";
    $connection = getConnection();

    include_once "../../../includes/html-skeleton/skeleton.php";
    include_once "../../../includes/cdn-links/fontawesome-cdn.php";
    include_once "../../../includes/cdn-links/bootstrap-cdn.php"; ?>

    <!--External Stylesheet-->
    <link rel="stylesheet" href="dashboard.css">

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

                    echo "<p class='customers'>ALL CUSTOMERS</p>";

                    echo "<img src='../profile/profile-img/" . $profile_img . "' alt='profile-icon' width='40px' height='40px'>"; ?>

                    <div class="logout-section position-absolute">
                        <p class="p-2"><a href="/website/project/panels/logout.php" class="btn text-light">logout</a>
                        </p>
                    </div>

                    <?php echo "</div>";

                    ?>



                    <div class="table-container">

                        <table class="table table-bordered table-hover mt-2">
                            <thead>
                            <th>USERNAME</th>
                            <th>ADDRESS</th>
                            <th>EMAIL</th>
                            <th>PHONE NUMBER</th>
                            <th>STATUS</th>
                            </thead>
                            <tbody>
                            <?php

                            $result = fetch_all_customers_in_users($connection);

                            while ($rows = oci_fetch_assoc($result)) { ?>

                                <tr>
                                    <td><?php echo ucfirst($rows['FIRST_NAME']) ?> <?php echo ucfirst($rows['LAST_NAME']) ?></td>
                                    <td><?php echo ucfirst($rows['ADDRESS']) ?></td>
                                    <td><?php echo ucfirst($rows['EMAIL']) ?></td>
                                    <td><?php echo ucfirst($rows['PHONE_NUMBER']) ?></td>
                                    <?php

                                    if($rows['STATUS'] == 1) { ?>
                                        <td>Regsitered</td>

                                    <?php }else {?>
                                        <td>Not Registered</td>

                                    <?php } ?>
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
