<?php
/**
 * Created by PhpStorm.
 * User: 95708
 * Date: 2017/8/17
 * Time: 12:24
 */
namespace App;
use \Illuminate\Database\Eloquent\Model;

class Music extends Model{
    private $musicname;
    public function __construct($musicname){
        $this->musicname = $musicname;
    }
    private function map_url(){

        $url = "http://shopcgi.qqmusic.qq.com/fcgi-bin/shopsearch.fcg?value=".urlencode(iconv("utf-8","gb2312",$this->musicname));
        if(!function_exists("file_get_contents"))
        {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }else{
            $file_contents = file_get_contents($url);
        }
        return $file_contents;
    }

    public function getmusic(){
        $data = $this-> map_url();
        $data = substr($data,15);
        $data = substr($data,0,-2);
        preg_match("/songlist\:\[(?P<music>.*)\]\}/i", $data,$musicdata);
        $musicdata = explode(",",$musicdata['music']);
        $music = array();
        foreach($musicdata as $v){
            if(preg_match("/\{idx\:(?P<id>.*)/i",$v,$a)){
                $id = trim($a[id],"\"");
            }
            if(preg_match("/song_id\:(?P<song_id>.*)/i", $v,$c)){
                $music[$id]['song_id'].=trim($c['song_id'],"\"");
            }
            if(preg_match("/song_name\:(?P<song_name>.*)/i",$v,$s)){
                $music[$id]['song_name'].=trim($s['song_name'],"\"");
            }
            if(preg_match("/album_name\:(?P<album_name>.*)/i",$v,$n)){
                $music[$id]['album_name'].=trim($n['album_name'],"\"");
            }
            if(preg_match("/singer_name\:(?P<singer_name>.*)/i",$v,$name)){
                $music[$id]['singer_name'].=trim($name['singer_name'],"\"");
            }
            if(preg_match("/location\:(?P<location>.*)/i",$v,$l)){
                $music[$id]['location'].=trim($l['location'],"\"");
            }
        }
        return $music;
    }
    public function getmusicurl(){
        $muiscurl = "";
        $result = $this->getmusic();
        foreach ($result as $id =>$v){

            $muiscurl.="歌曲{$id},歌曲名称:".iconv("gb2312","utf-8",$v['song_name']).",歌手：".iconv('gb2312','utf-8',$v['singer_name']).",专辑：".iconv('gb2312','utf-8',$v['album_name']).",歌曲地址：http://stream1{$v['location']}.qqmusic.qq.com/3{$v['song_id']}.mp3<br>";
        }
        return $muiscurl;
    }
}