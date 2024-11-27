<?php
include('secrets.php');

$message = "ETA 15 minutes - Handyman";

$ch = curl_init('https://textbelt.com/text');
$data = array(
  'phone' => $phone,
  'message' => $message,
  'key' => $textbelt_api_key . '_test' // comment to save quota when testing
);

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// Decode the JSON response
$responseData = json_decode($response, true);

// Check the response
if ($responseData['success']) {
    echo "SMS sent successfully!";
    echo "Quota remaining: " . $responseData['quotaRemaining'];
} else {
    echo "Failed to send SMS. Error: " . $responseData['error'];
}
header("Location: dashboard.php");
exit();
?>