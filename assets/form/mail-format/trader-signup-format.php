<?php

function get_trader_mail_signup($uri, $trader_name, $trader_email, $trader_phone, $trader_type, $shop_name, $product_category)
{

    return "
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Document</title>
    <link rel='preconnect' href='https://fonts.googleapis.com'>
    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
    <link href='https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500&display=swap' rel='stylesheet'>

    <style>
        .font-rubik {
    font-family: 'Rubik', sans-serif;
        }

        .btn {
            color: white;
    text-decoration: none;
            padding: 0.8rem 2.5rem;
            border: 2px solid #15c0a6;
            border-radius: 5px;
            transition: all 0.2s ease-in;
        }

        .btn-primary {
            background-color: #15c0a6;
        }


        .mail-container {
            padding: 1rem;
        }

        img {
            width: 200px;
        }
    </style>
</head>

<body>
    <div style='width: 600px; margin: auto; background:#f6f6f6;  border-radius: 10px; padding-top: 1rem; padding-bottom: 2rem;' class='container'>
        <div class='mail-container'>
            <img src='https://raw.githubusercontent.com/rajeshbasnet/images/13f40fd94b35ab3a730c2d3aefe98201c423b3df/footer_logo.png' alt=''>
            <br>
            <br>
            <p style='color: black;' class='font-rubik'><b>Hey Admin,</b> New trader has been registered in our website.</p>
            <p style='color: black;'  class='font-rubik'>Below are the information about Trader : </p>
                       
            <div style='background: white; padding: 1rem; box-shadow: 0 0 30px 0 #d4d4d4; border-radius: 10px;'>
            <p style='color: black;'  class='font-rubik'><b>Trader Name : </b>$trader_name</p>
            <p style='color: black;'  class='font-rubik'><b>Trader Email :</b> $trader_email</p>
            <p style='color: black;'  class='font-rubik'><b>Trader Phone :</b> $trader_phone</p>
            <p style='color: black;'  class='font-rubik'><b>Trader Type :</b> $trader_type</p>
            <p style='color: black;'  class='font-rubik'><b>Shop Name :</b> $shop_name</p>
            <p style='color: black;'  class='font-rubik'><b>Product Category :</b> $product_category</p>
            </div>            
            
            <p style='color: black;'  class='font-rubik'>Click the below buttom to give access to trader to register his products in our website.</p>
            <br>
            <a style='text-decoration: none; color: white' href='$uri' class='btn btn-primary font-rubik'>Verify</a>
        </div>
    </div>
</body>

</html>
";
}

