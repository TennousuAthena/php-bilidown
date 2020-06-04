<?php

if(strtoupper(substr(PHP_OS,0,3))==='WIN'){
    //是Win酱！
    exec("chcp 65001");
    $win=1;
}
if(!function_exists("curl_init"))
    exit("\033[1;38;5;9mFailed: php-curl lib is required\e[0m\n" . PHP_EOL);

//守护我们最好的哔哩哔哩~
echo "\033[1;36;5;9m";
echo base64_decode("ICAgICAgICAgICAgICAgICAvLwogICAgIFxcICAgICAgICAgLy8KICAgICAgXFwgICAgICAgLy8KIyNEREREREREREREREREREREREREREREIyMKIyMgREREREREREREREREREREREREREQgIyMgICBfX19fX19fXyAgIF9fXyAgIF9fXyAgICAgICAgX19fICAgX19fX19fX18gICBfX18gICBfX18gICAgICAgIF9fXwojIyBoaCAgICAgICAgICAgICAgICBoaCAjIyAgIHxcICAgX18gIFwgfFwgIFwgfFwgIFwgICAgICB8XCAgXCB8XCAgIF9fICBcIHxcICBcIHxcICBcICAgICAgfFwgIFwKIyMgaGggICAgLy8gICAgXFwgICAgaGggIyMgICBcIFwgIFx8XCAvX1wgXCAgXFwgXCAgXCAgICAgXCBcICBcXCBcICBcfFwgL19cIFwgIFxcIFwgIFwgICAgIFwgXCAgXAojIyBoaCAgIC8vICAgICAgXFwgICBoaCAjIyAgICBcIFwgICBfXyAgXFwgXCAgXFwgXCAgXCAgICAgXCBcICBcXCBcICAgX18gIFxcIFwgIFxcIFwgIFwgICAgIFwgXCAgXAojIyBoaCAgICAgICAgICAgICAgICBoaCAjIyAgICAgXCBcICBcfFwgIFxcIFwgIFxcIFwgIFxfX19fIFwgXCAgXFwgXCAgXHxcICBcXCBcICBcXCBcICBcX19fXyBcIFwgIFwKIyMgaGggICAgICB3d3d3ICAgICAgaGggIyMgICAgICBcIFxfX19fX19fXFwgXF9fXFwgXF9fX19fX19cXCBcX19cXCBcX19fX19fX1xcIFxfX1xcIFxfX19fX19fXFwgXF9fXAojIyBoaCAgICAgICAgICAgICAgICBoaCAjIyAgICAgICBcfF9fX19fX198IFx8X198IFx8X19fX19fX3wgXHxfX3wgXHxfX19fX19ffCBcfF9ffCBcfF9fX19fX198IFx8X198CiMjIE1NTU1NTU1NTU1NTU1NTU1NTU1NICMjCiMjTU1NTU1NTU1NTU1NTU1NTU1NTU1NTSMjICAgICAgICBQSFAgQmlsaWRvd24gUmVsZWFzZSAxLjAuMSBodHRwczovL2dpdGh1Yi5jb20vVGVubm91c3VBdGhlbmEvcGhwLWJpbGlkb3du");
echo PHP_EOL . "“哔哩哔哩 (゜-゜)つロ 干杯~”" . PHP_EOL;
echo "\e[0m\n";
echo "\033[1;33;5;9mChecking ffmpeg...\e[0m\n";
if(strlen(shell_exec("ffmpeg -h")) >= 1000){
    //返回的这么长一定已经安装了（没错简单粗暴
    echo "\033[1;32;5;9m[Done!]\e[0m\n" . PHP_EOL;
    define("FFMPEG", 1);
}else{
    echo "\033[1;38;5;9m[Failed]\e[0m\n" . PHP_EOL;
}
echo "Please choose API address:
[0] https://api.bilibili.com/ (Official, Cookie required)
[1] https://api.bilibili.love/ (Unofficial, Bigmember included)
>";
if(!trim(fgets(STDIN))){
    //官方API地址
    echo "Import you cookies:
e.g. buvid3=BA2917BA-46D4-1BA5-BFCR-QQR404FC1C9B47169lnfoc;SESSDATA=3c191323%2C1605059154%2Ce2e3f*a1;
>";
    $bookie = trim(fgets(STDIN));
    define("API_VIEW", "https://api.bilibili.com/x/web-interface/view");
    define("API_PLAY", "https://api.bilibili.com/x/player/playurl");
}else{
    define("API_VIEW", "https://api.bilibili.love/view");
    define("API_PLAY", "https://api.bilibili.love/playurl");
}

echo "Video ID type:
[0] aid   e.g. 170001
[1] bvid  e.g. BV16a4y1e7oC
>";
if(!trim(fgets(STDIN))){
    //Av号
    echo "Input Av number (Integer only) >";
    $aid = trim(fgets(STDIN));
	if(!$aid)
		exit("\033[1;38;5;9mFailed: Something went wrong\e[0m\n" . PHP_EOL);
    $getInfo = getAPI(API_VIEW, ["aid" => $aid]);
}else{
    //Bv号
    echo "Input Bv number >";
    $bvid = trim(fgets(STDIN));
	if(!$bvid)
		exit("\033[1;38;5;9mFailed: Something went wrong\e[0m\n" . PHP_EOL);
    $getInfo = getAPI(API_VIEW, ["bvid" => $bvid]);
}

echo PHP_EOL. "Video title: " . $getInfo->data->title . PHP_EOL;
echo "Video uploader: " . $getInfo->data->owner->name . PHP_EOL;
echo "Video description: “" . $getInfo->data->desc . "”" . PHP_EOL;

echo "Video includes [" . count((array)$getInfo->data->pages) . "] Pages" . PHP_EOL;
echo "Which page would you like to download? >";
$page = trim(fgets(STDIN));
if(!$page){
    $page = 0;
}else{
    $page--;
}

$cid = $getInfo->data->pages[$page]->cid;

$bvid = $getInfo->data->bvid;
if($cid == ""){
    exit("\033[1;38;5;9mFailed: Something went wrong\e[0m\n" . PHP_EOL);
}

echo "Video Cid: " . $cid . PHP_EOL;

$downInfo = getAPI(API_PLAY, ["bvid"=>$bvid, "cid"=>$cid]);
echo "Video accept quality:". json_encode($downInfo->data->accept_quality). PHP_EOL;
echo "Choose download quality >";
$qn = trim(fgets(STDIN));
$downInfo = getAPI(API_PLAY, ["bvid"=>$bvid, "cid"=>$cid, "qn"=>$qn]);


var_dump($downInfo->data);
echo "\n Everything is OK, press enter to start downloading >";
fgets(STDIN);
$fileName = $bvid."[" . ++$page . "].flv";
downloadUrlToFile($downInfo->data->durl[--$page]->url, $fileName);


echo "\033[1;32;5;9mDone √\e[0m\n" . PHP_EOL;
if(FFMPEG){
    echo "Would you like to convert the video to mp4? (yes/no)>";
    if(trim(fgets(STDIN)) != "no"){
        shell_exec("ffmpeg -i $fileName -vcodec copy -acodec copy $fileName.mp4");
    }

}

function downloadUrlToFile($url, $outFileName, $wget = false)
{   
    if($wget){
        // shell_exec("curl -o $outFileName -A \"User-Agent: BiliDown/1.1 (+https://github.com/TennousuAthena/php-bilidown)\" -e \"https://www.bilibili.com/\" $url");
    }else{
        if(is_file($url)) {
            copy($url, $outFileName); 
        } else {
            $options = array(
                CURLOPT_FILE    => fopen($outFileName, 'w'),
                CURLOPT_TIMEOUT =>  28800, //8小时……
                CURLOPT_URL     => $url,
                CURLOPT_REFERER => "https://www.bilibili.com/",
                CURLOPT_USERAGENT => "BiliDown/1.1 (+https://github.com/TennousuAthena/php-bilidown)"
            );
            $ch = curl_init();
            //echo "Downloading $url" . PHP_EOL;
            curl_setopt_array($ch, $options);
            curl_exec($ch);
            curl_close($ch);
        }
    }
}
function getAPI($url, $query=[], $cookies=""){
    $url = $url."?".http_build_query($query);
    echo ("Sending GET Request to ".$url) . PHP_EOL;
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    $output = curl_exec($ch); 
    curl_close($ch);
    echo "\033[1;32;5;9mGet √\e[0m\n";
    return json_decode($output);
}