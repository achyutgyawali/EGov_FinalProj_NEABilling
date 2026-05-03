<?php
require '../db/connection.php';

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->token) || !isset($data->amount)) {
    echo json_encode(["success" => false]);
    exit();
}

$token = $data->token;
$billId = $data->product_identity;
$secretKey = "f694cc42710a4389867c5040191a4f5a";

$headers = ["Authorization: Key $secretKey"];
$payload = ["token" => $token, "amount" => $data->amount];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://khalti.com/api/v2/payment/verify/");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true);

if ($responseData['state']['name'] === "Completed") {
    $conn->query("UPDATE bills SET status='Paid' WHERE id=$billId");
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
