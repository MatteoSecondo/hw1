<?php

    $client_id = 'pU0QYuSbPvN6gILxAx-WKQ';
    $api_key = 'Jdu1eJxWbjREV6DM7w7NhcWfuixA2kFNjuHhT6tm_d1s83I4iqljrHcZ6iVYRHqst_HRZpGAotYRWh0vvYByue4CEBj1SPfyQ_dQ2UODjpX7e1qxOJD2T8-bdc1bZHYx';


    $curl = curl_init();
    $url = "https://api.yelp.com/v3/businesses/". urlencode($_GET['search'])  ."/reviews?limit=20&sort_by=yelp_sort";

    curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $api_key",
        "accept: application/json"
    ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    echo $response;
    }

?>