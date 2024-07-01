<?php
error_reporting(0);
//require('proxy/proxy.php');

function Get($string, $start, $end)
{
    $str = explode($start, $string);
    $str = explode($end, $str[1]);
    return $str[0];

}

$clientKey = $_GET['clientKey'];
#echo"<br>clientKey: $clientKey";
$websiteURL = $_GET['websiteURL'];
#echo"<br>websiteURL: $websiteURL";
$websiteKey = $_GET['websiteKey'];
#echo"<br>websiteKey: $websiteKey";
$isInvisible = $_GET['isInvisible'];
#echo"<br>pageAction: $pageAction";

while(true){
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.ez-captcha.com/getBalance',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "clientKey" => $clientKey
    ])
    ]);
    
    $getBalance = curl_exec($curl);
    #echo"<br>createTask: $getBalance";
    
    if(strpos($getBalance, 'Too Many Requests.')){
        continue;
    }
    break;
    }

while(true){
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.ez-captcha.com/createTask',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "clientKey" => $clientKey,
        "task" => [
            "websiteURL" => $websiteURL,
            "websiteKey" => $websiteKey,
            "type" => "ReCaptchaV2TaskProxyless",
            "isInvisible" => $isInvisible
        ]
    ])
    ]);
    
    $createTask = curl_exec($curl);
    #echo"<br>createTask: $createTask";
    
    if(strpos($createTask, 'Too Many Requests.')){
        continue;
    }
    break;
    }
    $task_id = json_decode($createTask)->taskId;
    #echo"<br>task_id: $task_id";
    
    while(true){
    $curl = curl_init();
    curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.ez-captcha.com/getTaskResult',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "clientKey" => $clientKey,
        "taskId" => $task_id
    ])
    ]);
    
    $getTaskResult = curl_exec($curl);
    echo"<br>getTaskResult: $getTaskResult";
    
    if(strpos($getTaskResult, 'processing')){
        continue;
    }
    break;
    }
?>
