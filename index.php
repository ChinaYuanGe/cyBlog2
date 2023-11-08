<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/master/database.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/configer.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/router.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/etc.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/jsonResp.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/auth.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/arts.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/comments.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/ios_nofpush.php");
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/botcheck.php");

$routerInfo = GetRouteInfo();


#常用的Profile
$infomations = ReadConfigProfile('infomation');

$docRoot = $_SERVER["DOCUMENT_ROOT"].'/';

#数据库
$db = GetDatabase();

#登录信息
$user_isLogin = CheckIfLogin();


#定向Page
$targetPage = $routerInfo[1];
if(!(strlen($targetPage) > 1)){
    $targetPage = "main";
}
$pageLocation = $_SERVER["DOCUMENT_ROOT"]."/pages/".$targetPage."/main.php";
$pageRoot = substr($pageLocation, 0, strrpos($pageLocation,'/') + 1);
$pageUrlBase = "/$targetPage/";
if(file_exists($pageLocation) != true){
    die(http_response_code(404));
}
//获取page的配设文件
$pageMeta = ReadConfigDirect($pageRoot.'meta.config');
$pageIsLoginRequire = is_null($pageMeta['requireLogin']) ? false : $pageMeta['requireLogin'] == "true" ? true : false;
$pageRequireNoIndex = is_null($pageMeta['seo_noindex']) ? false : $pageMeta['seo_noindex'] == "true" ? true : false;
if($pageIsLoginRequire && $user_isLogin == false){
    die(http_response_code(403));
}

#制定 Title 以及一些 SEO 信息
$title = $infomations['globalTitle'];
$description = $infomations['seo_des'];
$keyword = $infomations['seo_keyword'];
if((include($pageRoot.'shell.php')) == true){
    if(function_exists("GetPageTitle"))$title = GetPageTitle()." - ".$title;
    if(function_exists("GetSeoDes")) $description = GetSeoDes();
    if(function_exists("GetKeyword")) $keyword = GetKeyword();
}


#navbar
$navbarProfile = ReadConfigProfile("navbar");
$navbarArray = array();
foreach($navbarProfile as $key=>$value){
    $cfgs = explode(',',$value);
    $showCondition = $cfgs[1];

    //此处做是否登录判断
    if($showCondition == 1 && $user_isLogin != true){ //登陆后显示
        continue;
    }
    else if($showCondition == 2 && $user_isLogin == true){ //登陆后不显示
        continue;
    }
    $navbarArray[$key] = $cfgs[0];
}

$nowhour = (int)date("H");
$isNight = ($nowhour < 7 || $nowhour > 18) ? true : false;
//$isNight = true;
?>

<!DOCTYPE html>
<html lang="zh">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="cyBlogVer" content="(cyBlog2 v0.1d)">
        <meta name="description" content="<?php echo $description; ?>">
        <meta name="keywords" content="<?php echo $keyword; ?>">
        <?php if($pageRequireNoIndex) echo '<meta name="robots" contect= "noindex">' ?>
        <title><?php echo $title; ?></title>
        <link href="/resources/style/bootstarp/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="/resources/style/bootstarp/bootstrap-grid.min.css" type="text/css" rel="stylesheet">
        <link href="/resources/style/basic.css" type="text/css" rel="stylesheet">
        <?php 
            if($isNight) echo '<link href="/resources/style/night.css" type="text/css" rel="stylesheet">';
        ?>
        <?php
            include($pageRoot.'links.php');
        ?>
        <script> isNight=<?php echo $isNight?"true":"false"; ?>; </script>
        <script src="/resources/jquery/jquery.min.js" type="text/javascript"></script>
        <script src="/resources/popper/popper.min.js" type="text/javascript"></script>
        <script src="/resources/jquery/jquery.cookie-1.4.1.min.js" type="text/javascript"></script>
        <script src="/resources/bootstarp/bootstrap.min.js" type="text/javascript"></script>
        <script src="/resources/js/fastAjax.js" type="text/javascript"></script>
        <script src="/resources/js/globalMethod.js" type="text/javascript"></script>
        <script src="/resources/js/md5.min.js" type="text/javascript"></script>
        <script src="/resources/js/fastbase64.js" type="text/javascript"></script>

        <?php # page链接的js
            include($pageRoot.'js.php');
        ?>

        <style>
        <?php 
        # 此处为page的专用样式表
        $styleRoot = $pageRoot.'styles/';
        $listStyle = array_values(array_diff(scandir($styleRoot),array('.','..')));
        
        foreach($listStyle as $styleFile){
            if(strrpos($styleFile,'.css') !== false){
                if(strrpos($styleFile,'night.') !== false){ //夜晚专用的文件
                    if($isNight) include($styleRoot.$styleFile);
                }
                else{
                    include($styleRoot.$styleFile);
                }
            }
        }
        
        ?>
        </style>
        <script><?php #page里面定义的JavaScript
        $scriptRoot = $pageRoot.'scripts/';
        $listScript = array_values(array_diff(scandir($scriptRoot),array('.','..')));
        
        foreach($listScript as $scriptFile){
            if(strrpos($scriptFile,'.js') != false) {
                echo "\n".'/* '.$scriptFile.' start */'."\n";
                include($scriptRoot.$scriptFile);
                echo "\n".'/* '.$scriptFile.' end*/'."\n";
            }
        }
        ?>
        </script>
        
    </head>
    <body>
        
        <div id="GuiderHolder">
            <div id="GuiderContainer" class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-lg <?php echo $isNight ? 'navbar-dark bg-dark':'navbar-light'; ?> ">
                            <a class="navbar-brand" href="/main"><?php echo ReadConfigProfile("infomation")["globalTitle"]; ?></a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav mr-auto">
                                    <?php
                                        foreach($navbarArray as $display=>$link){
                                            $external = "";
                                            if(!(strpos($link,$targetPage) === false)){
                                                $external = "active";
                                            }
                                            echo "<li class=\"nav-item\"><a class=\"nav-link $external\" href=\"$link\">$display</a></li>\n";
                                        }
                                    ?>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div id="contentContainer" class="container">
            <?php require($pageLocation); ?>
        </div>
    </body>
</html>