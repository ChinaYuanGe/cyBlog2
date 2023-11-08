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
    return "Example";
}
function GetKeyword(){
    return "ExamplePage,Example";
}
?>