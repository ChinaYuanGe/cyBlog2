<?php //movegroup和savecontent的区别就是不论文章status为何种状态，都可以对分组进行操作。
require($_SERVER['DOCUMENT_ROOT']."/api/requires.php");
if(CheckIfLogin() == false) die(http_response_code(403));
?>