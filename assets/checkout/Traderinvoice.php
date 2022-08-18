<?php
  $total_sum=0;
?>
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
    body{
      color: black;
    }
    h1{
      margin-left: 40%;
    }
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
       .container{
        position: relative;
       }

       img {
        position: absolute;
        top:5px;
        right: 5px;
        width: 70px;
        height: 50px;
       }

       footer{
        position: absolute;
        bottom:10px;
        right: 30%;
       }
       table{
        margin-left: 20%;
       }

        tr{
          border: 1px dashed black;
       }

       th{
        /*border-left: 1px dashed black;*/
        border-right: 1px dashed black;
        border-bottom: 1px dashed black;
        border-top: 0px dashed black;
       }

       td{
        /*border-left: 1px dashed black;*/
        border-right: 1px dashed black;
        border-bottom: 1px dashed black;
        text-align: center;
       }
       th{
        border-top: 0px;
        padding: 7px 30px;
        text-align: center;
       }
   </style>
</head>

<body>
  <div style='width: 800px; margin: auto; background:#f6f6f6;  border-radius: 10px; padding-top: 1rem; padding-bottom: 2rem;' class='container'>
    <h1>RECEIPT</h1>
    <div class='mail-container'>
      <img src='https://raw.githubusercontent.com/rajeshbasnet/images/13f40fd94b35ab3a730c2d3aefe98201c423b3df/footer_logo.png' alt=''>
      <br>
      <?php
        oci_execute($qp4);
        $countTrader = 0;
        while (($row = oci_fetch_assoc($qp4)) && ($countTrader === 0)){
          echo "<p style='float: left;'><strong>Order ID:&nbsp;&nbsp;".$row['ORDER_ID']."</strong></p>";
          echo "<p style='float: right;'><strong>Date:&nbsp;&nbsp;".$row['PAYMENT_DATE']."</strong></p>";
          echo "<br><br>";
          echo "<p style='float: left;'><strong>User ID:&nbsp;&nbsp;".$row['USER_ID']."</strong></p>";
          echo "<p style='float: right; text-transform: uppercase; '><strong>Customer Name:&nbsp;&nbsp;".$row['FIRST_NAME']."&nbsp;&nbsp;".$row['LAST_NAME']."</strong></p>";
          $countTrader++;
        }
      ?>
      <table>
        <tr>
          <th>Product name</th>
          <th>Item Price</th>
          <th>Quantity</th>
          <th>Item Total</th>
        </tr>
        <?php
          oci_execute($qp4);

          while (($roww = oci_fetch_assoc($qp4)) ){
            $product_id = $roww['PRODUCT_ID'];
            $value = fetch_offerid_and_productprice_from_product_id($product_id, $connection);

            $offer_id = $value['offer_id'];
            $product_price = $value['product_price'];

            if(isset($offer_id)) {
                $array_value = fetch_discouted_price_from_products($offer_id, $product_price, $connection);

            }else {
                $array_value['total_price_after_discount'] = $product_price;
            }

            $discount_price = $array_value['total_price_after_discount'];

            $quantity = $roww['QUANTITY'];
            $individual_sum = floatval($discount_price) * intval($quantity);
            $total_sum = $total_sum + $individual_sum;
            echo "<tr>";
              echo "<td>".$roww['PRODUCT_NAME']."</td>";
              echo "<td>".$discount_price."</td>";
              echo "<td>".$roww['QUANTITY']."</td>";
              echo "<td>".$individual_sum."</td>";
            echo "</tr>";
          }
        ?>
        <tr>
          <td></td>
          <td></td>
          <td><strong>Total</strong></td>
          <td><?php echo $total_sum; ?></td>
        </tr>
      </table>
      <br>

      <br>
      <footer><strong>Thank you for partnering with us !!!</strong></footer>
    </div>
  </div>
</body>

</html>
