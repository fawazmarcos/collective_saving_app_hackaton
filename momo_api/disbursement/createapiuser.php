<?php
// Generate UUID with the format 8-4-4-4-12
function generateUUID() {
    if (function_exists('random_bytes')) {
        $data = random_bytes(16);
    } elseif (function_exists('openssl_random_pseudo_bytes')) {
        $data = openssl_random_pseudo_bytes(16);
    } else {
        $data = '';
        for ($i = 0; $i < 16; $i++) {
            $data .= chr(mt_rand(0, 255));
        }
    }

    // Set the version to 4
    $data[6] = chr((ord($data[6]) & 0x0F) | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr((ord($data[8]) & 0x3F) | 0x80);

    // Format the UUID
    $uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

    return $uuid;
}

// Exemple d'utilisation
$uuid = generateUUID();
//echo $uuid;

$reference_id = generateUUID();
$primary_key = "775bd43f5afd484186b3fde99668ae6d";




$url = "https://sandbox.momodeveloper.mtn.com/v1_0/apiuser";
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

# Request headers
$headers = array(
    'X-Reference-Id: '. $reference_id,
    'Content-Type: application/json',
    'Ocp-Apim-Subscription-Key: '. $primary_key,);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

# Request body
$request_body = '{
    providerCallbackHost: "https://macotech.tech"
}';
curl_setopt($curl, CURLOPT_POSTFIELDS, $request_body);

$response = curl_exec($curl);
/*
// Check for errors
 if (curl_errno($curl)) {
   $error_msg = curl_error($curl);
   echo "cURL Error: " . $error_msg;
 } else {
   //Get http status code
   $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //Close the cURL session
   curl_close($curl);
    //Output the response status
   if ($httpcode == 201) {
     echo 'API user created successfully, Ref ID : '. $reference_id .' & response status code is : ' . $httpcode;
   } else {
     echo 'API user creation failed, Response status code is : ' . $httpcode;
     echo "<br>";
     echo "Error : " . $response;
   }
 }
*/