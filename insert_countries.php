<?php
    //api: numverify
    require_once 'auth.php';

    if (!checkAuth()) {
        header("Location: login.php");
        exit;
    }  

    // set API Access Key
    $access_key = 'd25eb35f3bd2c12ab2a5c8247b7b3cd1';

    // set phone number
    $phone_number = '14158586273';

    // Initialize CURL:
    $ch = curl_init("http://apilayer.net/api/countries?access_key=".$access_key);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Store the data:
    $json = curl_exec($ch);
    curl_close($ch);
    $json = str_replace("'", " ", $json);

    //salvo il json
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($conn));
    $query = "INSERT INTO json (json) VALUES ('$json')";
    mysqli_query($conn, $query);
    mysqli_close($conn);

?>