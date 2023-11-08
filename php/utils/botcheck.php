<?php
function Bot_UA_Match(string $userAgent){
    $ua = strtolower($userAgent);
    $matchs = array('bot','spider');
    foreach($matchs as $word){
        if(strpos($ua,$word) !== false){
            return true;
        }
    }
    return false;
}
?>