<?php
header('Content-Type: application/json');

$headers = getallheaders();
if(!isset($headers['Authorization'])){
    echo json_encode(["status"=>"Token tidak ditemukan"]);
    exit;
}

$token = str_replace("Bearer ","",$headers['Authorization']);
$secret = "secretkey123";

list($headerEnc,$payloadEnc,$sigEnc) = explode('.',$token);
$sigCheck = str_replace(['+','/','='],['-','_',''], base64_encode(hash_hmac('sha256', "$headerEnc.$payloadEnc", $secret, true)));

if($sigCheck !== $sigEnc){
    echo json_encode(["status"=>"Token tidak valid"]);
    exit;
}

$payload = json_decode(base64_decode(str_replace(['-','_'],['+','/'],$payloadEnc)), true);
if($payload['exp'] < time()){
    echo json_encode(["status"=>"Token sudah kadaluarsa"]);
    exit;
}

echo json_encode(["status"=>"Akses diterima", "data"=>$payload]);
