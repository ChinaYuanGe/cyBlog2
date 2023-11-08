<?php
function TokenAvailable(string $token){
    global $db;
    CleanExpireToken();
    if($db->QuerySQL("SELECT COUNT(*) FROM token WHERE expire>NOW() AND token={$db->quote($token)}",PDO::FETCH_BOTH)[0][0] > 0){
        return true;
    }else return false;
}
function SetupNewToken(int $expire_second = 86400){
    global $db;
    do{
        $token = md5(rand(PHP_INT_MIN,PHP_INT_MAX).'');
    } while(count($db->QuerySQL("SELECT * FROM token WHERE token='$token'")) > 0);
    $db->ExecSQL("INSERT INTO token(token,expire) VALUES('$token',DATE_ADD(NOW(),INTERVAL $expire_second second))");
    CleanExpireToken();
    return $token;
}
function CleanExpireToken(){
    global $db;
    return $db->ExecSQL("DELETE FROM token WHERE expire<NOW()");
}
function DeleteToken(string $token){
    global $db;
    return $db->ExecSQL("DELETE FROM token WHERE token={$db->quote($token)}");
}
function CheckIfLogin(){
    return $_COOKIE['token'] != null ? TokenAvailable($_COOKIE['token']) : false;
}
?>