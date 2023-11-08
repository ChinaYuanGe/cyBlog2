<?php
function GetPageTitle(){
    $profile = ReadConfigProfile("infomation");
    if(key_exists("page_main_title",$profile)){
        $picker = explode('|',$profile['page_main_title']);
        if(count($picker) <= 1){
            if(strlen($picker[0]) < 1) return "博客主页";
            else return $picker[0];
        }
        else{
            $pickNum = rand(0,count($picker)-1);
            return $picker[$pickNum];
        }

    }
    else return "博客主页";
}
function GetSeoDes(){
    return "这里是ChinaYuanGe的个人博客,界面是自己亲手使用PHP进行编写的,相对简陋但大部分基础功能已完善,欢迎访问。";
}
function GetKeyword(){
    return "ChinaYuanGe,个人博客,博客,生活记录,喜好,自行开发的系统";
}
?>