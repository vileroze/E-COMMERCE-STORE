<?php

session_start();

include_once "../../includes/html-skeleton/skeleton.php";
include_once "../../includes/cdn-links/fontawesome-cdn.php";
include_once "../../includes/cdn-links/bootstrap-cdn.php"; ?>

<!--External CSS-->
<link rel="stylesheet" type="text/css" href="about-us.css" />

<header class="position-relative">

    <!--Navbar Section-->
    <?php include_once "../../includes/page-contents/page-navbar.php" ?>

    <div class="bg-image position-absolute">
        <img src="images/aboutus-1.jpg" class="w-100" alt="" />
    </div>

    <!--Breadcrumbs-->
    <nav class="breadcrumb-navbar d-flex justify-content-center" aria-label="breadcrumb">
        <ol class="breadcrumb font-rubik">
            <li class="breadcrumb-item"><a href="/website/project/index.php">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">About Us</A></li>
        </ol>
    </nav>
</header>

<div class="about-us" id="mtc">
    <h2 class="font-cursive">About Us</h2>
    <hr class="mx-auto mt-2 d-block" style=" width: 100px; height: 3px; background-color: rgb(24, 210, 216);">
    <div class="aboutus-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <img src="images/aboutus-2.jpg" alt="chef" />
                </div>
                <div class="col-lg-4">
                    <p class="font-rubik">
                        We’re on a mission to help you live better easily. It starts
                        with keeping shopping simple – which is why our minimum order is
                        just £12 and we only ever charge £1.50 for delivery. We’ll
                        deliver to your nearest location the day after. We have a wide
                        collection of pantries you can choose from. Not finding what you
                        want is a thing of the past!
                    </p>
                    <p class="quote">"We're here to help"</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 font-rubik">
                    <p>
                        We’ve worked with master craftsmen and women for donkeys’,
                        speaking with them every day and often stopping by to have a
                        glimpse of their new harvests. They share our commitment to
                        ethically made food, and being organic means they have the
                        highest animal welfare standards. We think they’re superheroes.
                        Every wellie wearing, hand harvesting, flat cap donning, weather
                        worshipping one of them.
                    </p>
                    <p class="quote">"Farmers, makers and bakers"</p>
                </div>
                <div class="col-lg-8">
                    <img src="images/aboutus-3.jpg" alt="chef2" />
                </div>
            </div>
        </div>
    </div>
</div>

<!--Footer Section-->
<?php include_once "../../includes/page-contents/page-footer.php"; ?>