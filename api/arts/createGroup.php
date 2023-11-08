<?php
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(!CheckIfLogin()) die(http_response_code(403));

$groupName = $_POST['name'];

$group = AddGroup($groupName);

if($group === false){
    die(GetRespJson_Fail(array('msg'=>'无法添加分组')));
}

$groupName = $db->QuerySQL("SELECT `name` FROM groups WHERE id=$group")[0][0];

echo GetRespJson_OK(array('id'=>$group,'name'=>$groupName));
?>