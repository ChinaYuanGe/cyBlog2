<?php
$__configer_savedir = $_SERVER["DOCUMENT_ROOT"]."/settings/";
$__configer_default_savedir = $_SERVER["DOCUMENT_ROOT"]."/settings_default/";

//写入配设档案，如果文件存在则覆写
function WriteConfigProfile(string $name,array $value){
    global $__configer_savedir;
    return WriteConfigDirect($__configer_savedir.$name.".config",$value);
}
function WriteConfigDirect(string $path,array $value){
    $profile = $path;
    $content = "";
    foreach($value as $key=>$value){
        $content = $content.$key."=".$value.PHP_EOL;
    }
    return file_put_contents($profile,$content);
}

//读取配设档案，返回一个Array数据,如果档案不存在则返回false
function ReadConfigProfile(string $name){
    global $__configer_savedir;
    $targetConfig = $__configer_savedir.$name.'.config';
    if(is_dir($__configer_savedir) === false) mkdir($__configer_savedir);
    if(file_exists($targetConfig) === false){
        if(CopyDefaultConfigProfile($name) === false) throw new Exception("Cannot Create a global defaule config file:".$targetConfig);
    }
    return ReadConfigDirect($targetConfig);
}
function CopyDefaultConfigProfile(string $name){
    global $__configer_savedir;
    global $__configer_default_savedir;
    $targetConfig = $__configer_savedir.$name.'.config';
    $defaultConfig = $__configer_default_savedir.$name.".config";
    return copy($defaultConfig,$targetConfig);
}
//读取模块专属档案，返回一个Array数据，如果不存在返回False
function ReadModuleConfigProfile(string $name){
    global $targetPage;
    global $pageRoot;
    global $__configer_savedir;
    $pageConfigRoot = $__configer_savedir.$targetPage."/";
    $targetConfig = $pageConfigRoot.$name.'.config';
    if(is_dir($pageConfigRoot) === false) mkdir($pageConfigRoot);
    if(file_exists($targetConfig) === false){
        if(CopyModuleDefaultConfig($name) === false) throw new Exception("Cannot Create a default module config file:".$targetConfig);
    }
    return ReadConfigDirect($targetConfig);
}
//复制模块专属配设的默认配设
function CopyModuleDefaultConfig(string $name){
    global $targetPage;
    global $pageRoot;
    global $__configer_savedir;
    $pageConfigRoot = $__configer_savedir.$targetPage."/";
    $pageDefaultConfigRoot = $pageRoot."config_default/";
    $defaultConfig = $pageDefaultConfigRoot.$name.".config";
    $targetConfig = $pageConfigRoot.$name.'.config';
    return copy($defaultConfig,$targetConfig);
}
function ReadConfigDirect(string $path){
    $profile = $path;
    $target = array();
    if(file_exists($profile) != true){
        return false;
    }
    $content = file_get_contents($profile);
    $content = str_replace("\r",'',$content);
    $listContent = explode(PHP_EOL,$content);

    foreach($listContent as $val){
        if(strpos($val,'#') !== 0 && strpos($val,'=') != false){
            $keyval = mb_split('=',$val);
            $target[$keyval[0]] = $keyval[1];
        }
    }
    return $target;
}

?>