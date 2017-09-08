<?php
/*
 * 公共函数
 */

function getSongList($type=1){
    //1-新歌榜,2-热歌榜,11-摇滚榜,12-爵士,16-流行,21-欧美金曲榜,22-经典老歌榜,23-情歌对唱榜,24-影视金曲榜,25-网络歌曲榜
    $info = file_get_contents("http://tingapi.ting.baidu.com/v1/restserver/ting?format=json&calback=&from=webapp_music&method=baidu.ting.billboard.billList&type=$type&size=20&offset=0");
    return json_decode($info,1);
}

function getSongInfoByID($id=877578){//获取歌曲信息
    $info = file_get_contents("http://tingapi.ting.baidu.com/v1/restserver/ting?format=json&calback=&from=webapp_music&method=baidu.ting.song.play&songid=".$id);
    return json_decode($info,1);
}

function search($name){
    //method=baidu.ting.search.catalogSug&query=海阔天空
    $info = file_get_contents("http://tingapi.ting.baidu.com/v1/restserver/ting?format=json&calback=&from=webapp_music&method=baidu.ting.search.catalogSug&query=".$name);
    return json_decode($info,1);
}

function download($url, $file, $timeout=180) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $info = fopen($file,'wb');
    curl_setopt($ch,CURLOPT_FILE,$info);
    $temp = curl_exec($ch);
    fclose($info);
    if(!curl_error($ch)) {
        curl_close($ch);
        return $temp;
    } else {
        return false;
    }

}