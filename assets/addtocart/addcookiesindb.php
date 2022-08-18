<?php

foreach($_COOKIE as $key=>$item) {
    if($key == "PHPSESSID") {
        continue;
    }else {

        $decodedItem = json_decode($item, true);

        $product_id = $decodedItem['id'] ??= "";
    }
}