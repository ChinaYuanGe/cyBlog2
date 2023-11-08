<?php
function GetRouteInfo(){
    $info = explode('/',$_SERVER["PATH_INFO"]);
    return $info;
}
?>