<?php
usleep(500*1000);
require($_SERVER['DOCUMENT_ROOT'].'/api/requires.php');

$rightPassHash = ReadConfigProfile('user')['password'];
$passHash = $_POST['passmd5'];
if($rightPassHash == $passHash){
    $token = SetupNewToken();
    echo GetRespJson_OK(array('token'=>$token));
    setcookie("token",$token);
}else echo GetRespJson_Fail();
?>