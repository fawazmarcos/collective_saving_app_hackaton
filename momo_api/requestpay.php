<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if (isset($_POST['telephone']) && isset($_POST['montant'])) {
    //INCLUDE ACCESS TOKEN
    include "createaccesstoken.php";
    $phone = $_POST['telephone'];
    // Set the request URL
    $url = "https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay";
    // Set the headers
    $headers = array(
        'Authorization: Bearer '.$access_token,
        'X-Reference-Id: '. $reference_id,
        'X-Target-Environment: sandbox',
        'Content-Type: application/json',
        'Ocp-Apim-Subscription-Key: '.$primary_key
    );

    //GENRATE AN EXTERNAL ID 8 DIGITS
    $external_id = rand(10000000, 99999999);

    // Set the request body
    $body = array(
        'amount' => $_POST['montant'],
        'currency' => 'EUR',
        "externalId" => $external_id,
        'payer' => array(
            'partyIdType' => 'MSISDN',
            'partyId' => $phone
        ),
        'payerMessage' => 'MacoTech SA',
        'payeeNote' => 'Merci pour votre paiement.'
    );
    // Encode the request body as JSON
    $json_body = json_encode($body);
    // Initialize cURL
    $curl = curl_init();
    // Set the cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $json_body
    ));
    // Execute the cURL request
    $response = curl_exec($curl);
    // Check for errors
    if(curl_errno($curl)) {
        $error_msg = curl_error($curl);
        echo "cURL Error: " . $error_msg;
    }
    // Close the cURL session
    curl_close($curl);
    // Output the response
    // Check for errors
     if (curl_errno($curl)) {
       $error_msg = curl_error($curl);
       echo "cURL Error: " . $error_msg;
     } else {
       //Get http status code
       $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       // Close the cURL session
       curl_close($curl);
       // Output the response status
       if ($httpcode == 202) {
            $response= array(
                "status" => 200,
                "message" => "Paiement effectué avec succès"
            );
       } else {
            $response= array(
                "status" => 200,
                "message" => $response
            );
        
       }
     }

}
else{
    $response= array(
        "status" => 400,
        "message" => "Veuillez renseigner tous les champs requis"
    );
}
exit(json_encode($response));
