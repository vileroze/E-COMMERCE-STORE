<?php

session_start();


if (isset($_SESSION['admin'])) {

    include_once '../../../connection/connect.php';
    $connection = getConnection();

    include_once '../../../includes/html-skeleton/skeleton.php';
    include_once '../../../includes/cdn-links/fontawesome-cdn.php';
    include_once '../../../includes/cdn-links/bootstrap-cdn.php';

    ?>

    <!--External Stylesheet-->
    <link rel="stylesheet" href="./admin-profile.css">

    <main>
        <div class="container-fluid">
            <div class="row">
                <?php include '../admin-side-panel.php' ?>

                <!--Add Products Container Column-->
                <div class="col-xl-10 mx-auto p-0">
                    <?php

                    include_once '../../../assets/trader-types/functions.php';
                    $profile_img = get_profile_image_of_user($_SESSION['admin'], $connection);

                    echo "<div class='user-profile-header'>";

                    if (empty($profile_img)) {
                        $profile_img = "default-image.jpg";
                    }

                    echo "<img src='./profile-img/" . $profile_img . "' alt='profile-icon' width='40px' height='40px'>";
                    echo "</div>";

                    ?>

                    <div class="logout-section position-absolute">
                        <p class="p-2"><a href="/website/project/panels/logout.php" class="btn text-light">logout</a>
                        </p>
                    </div>

                    <?php include './update-profile.php'; ?>

                    <form action="#" method="POST"
                          enctype="multipart/form-data">
                        <fieldset>
                            <!--Title-->
                            <h4 class="user-title my-4">Your Profile</h4>

                            <div class="input-field__container d-flex">

                                <!--Left Input Field Column-->
                                <div class="column-left w-100 mr-3">

                                    <?php

                                    $query = "SELECT * FROM users WHERE user_id = " . $_SESSION['admin'] . "";
                                    $profile = oci_parse($connection, $query);
                                    oci_execute($profile);
                                    ?>

                                    <br>
                                    <?php
                                    while ($row = oci_fetch_assoc($profile)) {
                                        echo "<input type='hidden' name='user_id' value='" . $row['USER_ID'] . "'>";

                                        $profile_image = "";

                                        if (!empty($row['PROFILE_IMG'])) {
                                            $profile_image = $row['PROFILE_IMG'];
                                        } else {
                                            $profile_image = "default-image.jpg";
                                        }


                                        echo "<div class='profile-img mt-4'>";
                                        echo "<img src='./profile-img/" . $profile_image . "' alt='profile-img' width='250px' height='250px'>";
                                        echo "</div>";

                                        echo "<br>";

                                        echo "<div class='img-select mb-3'>";
                                        echo "<input type='file' name='profile_img' id='profile_img' />";
                                        echo "</div>";
                                        if (isset($img_error)) {
                                            echo $img_error;
                                        }

                                        echo "<span class='note'><b>Note:</b> Profile will be updated even if you do not provide profile image or password.</span>";
                                        echo "<br>";
                                        echo "<span class='note'><b>Note:</b> Both profile image and password will be your previous image and password.</span>";

                                        echo "</div>";

                                        echo "<div class='column-right w-100 ml-3'>";
                                        echo "<div class='username first_name mb-4'>";
                                        echo "<label for='first_name' class='form-label'>First name</label>";
                                        echo "<input type='text' class='form-control' id='first_name' name='first_name'  value='" . $row['FIRST_NAME'] . "'>";
                                        echo "</div>";
                                        if (isset($fnameerror)) {
                                            echo $fnameerror;
                                        }

                                        echo "<div class='username last_name mb-4'>";
                                        echo "<label for='last_name' class='form-label'>Last name</label>";
                                        echo "<input type='text' class='form-control' id='last_name' name='last_name'  value='" . $row['LAST_NAME'] . "'>";
                                        echo "</div>";

                                        if (isset($lnameerror)) {
                                            echo $lnameerror;
                                        }


                                        echo "<div class='email my-4'>";
                                        echo "<label for='userEmail' class='form-label'>Email</label>";
                                        echo "<input type='email' class='form-control' id='userEmail' name='userEmail'  value='" . $row['EMAIL'] . "'>";
                                        echo "</div>";

                                        if (isset($email_error)) {
                                            echo $email_error;
                                        }

                                        echo "<div class='password my-4'>";
                                        echo "<label for='user_pass' class='form-label'>Password</label>";
                                        echo "<input type='password' class='form-control' id='user_pass' name='user_pass'  value=''>";
                                        echo "</div>";

                                        if (isset($passerror)) {
                                            echo $passerror;
                                        }

                                        echo "<div class='phone-number my-4'>";
                                        echo "<label for='phone-number' class='form-label'>Phone number</label>";
                                        echo "<input type='number' class='form-control' id='phone-number' name='phone_number'  value='" . $row['PHONE_NUMBER'] . "'>";
                                        echo "</div>";

                                        if (isset($phone_error)) {
                                            echo $phone_error;
                                        }


                                        echo "<div class='address my-4'>";
                                        echo "<label for='address' class='form-label'>Address</label>";
                                        echo "<input type='text' class='form-control' id='address' name='userAddress'  value='" . $row['ADDRESS'] . "'>";
                                        echo "</div>";

                                        echo "<div class='btn-container my-4'>";
                                        echo "<button type='submit' name='adminProfile' class='btn w-100'>Save Changes</button>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                    ?>

                                </div>
                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </main>

    <!--External Script-->
    <script src="../../script.js"></script>


<?php } else {
    header('Location:/website/project/index.php');
}