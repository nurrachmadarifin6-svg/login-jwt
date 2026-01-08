<?php
header('Content-Type: application/json');

$conn = new mysqli("127.0.0.1","root","22april2003","jwt_login");
if($conn->connect_error) die(json_encode(["status"=>"Koneksi gagal"]));

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if(!$username || !$password){
    echo json_encode(["status"=>"Username & Password wajib diisi"]);
    exit;
}

$res = $conn->query("SELECT * FROM users WHERE username='$username'");
$user = $res->fetch_assoc();

if($user && password_verify($password, $user['password'])){
    $header = json_encode(["alg"=>"HS256","typ"=>"JWT"]);
    $payload = json_encode([
        "iss"=>"localhost",
        "aud"=>"localhost",
        "iat"=>time(),
        "exp"=>time()+3600,
        "username"=>$user['username']
    ]);

    $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    $secret = "secretkey123";

    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", $secret, true);
    $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

    $jwt = "$base64Header.$base64Payload.$base64Signature";

    echo json_encode(["token"=>$jwt]);
}else{
    echo json_encode(["status"=>"Username / Password salah"]);
}

$conn->close();
