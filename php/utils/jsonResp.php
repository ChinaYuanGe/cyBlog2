<?php
function GetRespJson(string $status,array $value = null){
    $jsonArray = array("status"=>$status);
    if(!is_null($value)){
        $jsonArray['data'] = $value;
    }
    return json_encode($jsonArray);

}
function GetRespJson_OK(array $value = null){
    return GetRespJson("ok",$value);
}
function GetRespJson_Fail(array $value = null){
    return GetRespJson("fail",$value);
}
function GetRespJson_FailMsg(string $msg){
    return GetRespJson_Fail(array('msg'=>$msg));
}
?>