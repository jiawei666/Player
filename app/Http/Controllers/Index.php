<?php
namespace App\Http\Controllers;



use Illuminate\Cache\MemcachedConnector;
use App\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class Index extends Controller
{
    public function index(){//播放器首页
        return view('Index/index');
    }

    public function getMusic(Request $data){
        $memcache = new \Memcache();
        $memcache->connect('localhost',11211);

        $type =  $data->input('type');

        if($type==2){
            $songListType = 'hotSongList';
        }elseif($type==1){
            $songListType = 'newSongList';
        }else{
            return 'err_100';
        }

        if(!$memcache->get($songListType)){
            $path = './download/';//储存路径

            if((!$memcache->get('hotSongList')) && (!$memcache->get('newSongList'))){//清空文件夹
                self::deleteDir($path);
            }

            $songListInfo =  getSongList($type);
            foreach($songListInfo['song_list'] as $val){
                $song_id = $val['song_id'];

                //下载MP3
                $songInfo = getSongInfoByID($song_id);
                $res_mp3 = download($songInfo['bitrate']['file_link'],$path.$song_id.'.mp3');
                $songList[$song_id] = ['mp3'=>$path.$song_id.'.mp3',
                    'author'=>$songInfo['songinfo']['author'],
                    'title'=>$songInfo['songinfo']['title'],
                    'druation'=>$songInfo['bitrate']['file_duration'],
                ];

                //下载图片
                $songInfo = getSongInfoByID($song_id);
                $res_img = download($songInfo['songinfo']['pic_premium'],$path.$song_id.'.jpg');
                $songList[$song_id]['image'] = $path.$song_id.'.jpg';

            }
            $memcache->set($songListType,$songList,0,86400);//储存到memcache
        }
        return $memcache->get($songListType);

    }

    public static function deleteDir($path){
        $dh = opendir($path);
        while($file = readdir($dh)){
            if($file != '.' && $file != '..' && $file != false){
                $files[] = $file;
            }
        }
        closedir($dh);
        if(isset($files) && is_array($files)){
            foreach($files as $val){
                $res[] = @unlink($path.$val);
            }
        }else{
            echo 'no exist';
        }
    }

    public function search(Request $data){
        $search =  $data->input('search');
        $searchList = search($search);
        foreach($searchList['song'] as $value){
            $song_id = $value['songid'];
            $songInfo = getSongInfoByID($song_id);
            $Info[$song_id] = [
                'jpg_link'=>$songInfo['songinfo']['pic_premium'],
                'mp3_link'=>$songInfo['bitrate']['file_link'],
                'author'=>$songInfo['songinfo']['author'],
                'title'=>$songInfo['songinfo']['title'],
                'druation'=>$songInfo['bitrate']['file_duration'],
            ];
        }
        return $Info;
    }

    public function downloadSearch(Request $data){
        $song_id = $data->input('song_id');
        $mp3_link = $data->input('mp3_link');
        $jpg_link = $data->input('jpg_link');

        $res_mp3 = download($mp3_link,'./download/'.$song_id.'.mp3');
        if(!empty($jpg_link)){
            $res_img = download($jpg_link,'./download/'.$song_id.'.jpg');
        }
        if($res_mp3){
            return 1;
        }else{
            return 2;
        }
    }

    public function test(){
//        dd(getSongInfoByID(121353608));
//
//        exit;

//        self::deleteDir('./download/');
        $memcache = new \Memcache();
        $memcache->connect('127.0.0.1',11211);
//        dd($memcache->get('hotSongList'));
        var_dump($memcache->get('newSongList'));
        var_dump($memcache->get('hotSongList'));
//        dd(getSongList(2));
//        dd(getSongInfoByID(546920050));
//        dd(search('海阔天空'));

    }



}