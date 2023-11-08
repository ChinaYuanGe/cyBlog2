<?php
require_once($_SERVER['DOCUMENT_ROOT']."/php/utils/configer.php");
function PushIOSNof(string $title,string $content){
    $config = ReadConfigProfile("ios_nofpush");

    $curl = curl_init();
    $url = "{$config['scheme']}://{$config['domain']}/v1/sender/{$config['token']}";
    curl_setopt_array($curl, [
        CURLOPT_URL           => $url,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS    => [ 
            'title' => $title,
            'text' => $content,
            'sound' => '1'
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);
    error_log('TargetUrl='.$url);
    $resp = curl_exec($curl);
    curl_close($curl);
}
?>