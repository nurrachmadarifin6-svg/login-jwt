<?php
header('Content-Type: application/json');

$conn = new mysqli("127.0.0.1","root","22april2003","jwt_login");
if($conn->connect_error) die(json_encode(["status"=>"Koneksi gagal"]));

$data = json_decode(file_get_contents("php://input"), true);
if(!$data) {
    echo json_encode(["status"=>"Data kosong"]);
    exit;
}

$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if(!$username || !$password){
    echo json_encode(["status"=>"Username & Password wajib diisi"]);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username,password) VALUES (?,?)");
$stmt->bind_param("ss",$username,$hash);

if($stmt->execute()){
    echo json_encode(["status"=>"Register sukses"]);
} else {
    if($conn->errno===1062){
        echo json_encode(["status"=>"Username sudah dipakai"]);
    } else {
        echo json_encode(["status"=>"Register gagal","error"=>$conn->error]);
    }
}

$stmt->close();
$conn->close();
