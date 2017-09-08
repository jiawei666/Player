<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon"type="image/x-icon" href="./image/icon.jpg"media="screen" />
    <title>Jiawei</title>
    <link rel='stylesheet' href='css/jquery-ui.css'>
    <link rel='stylesheet' href='css/style.css'>
    <link rel="stylesheet" href="css/layui.css"  media="all">
    <script src="js/prefixfree.min.js"></script>
    <script src='js/jquery_and_jqueryui.js'></script>
    <script src='js/jquery.min.js'></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="js/index.js"></script>
    <script src="js/getMusic.js"></script>
    <script>
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN' : '{{csrf_token()}}'
            }
        });
    </script>
</head>

<body>

<!--

Hey, I'm JiaweiYuan！！

-->

<div class="submit_load" >
    <div>
        <img src="./image/load.png"  />
        <span >正在加载</span>
    </div>
</div>

<audio id="audio"  controls style="display:none">
    你的浏览器不支持
</audio>
<div name="songList">

</div>


<div class="music-player" style="left: 30%;" >
    <img src="" class="album" />{{--音乐图片--}}
    <div class="dash">
        <a href="#mute" class="fa fa-volume-up"><img style="margin-left: 10px;" src="./image/mute_01.png"></a>
        <span class="volume-level">
          <em style="width: 75%"></em>
        </span>
        <a href="#share" class="fa fa-share"></a>
        <a href="#love" class="fa fa-heart"></a>
        <div class="seeker">
            <div class="wheel">
                <div class="progress" style="transform: rotate(269deg);"></div>
            </div>
        </div>
        <span href="#seek" style="transform: rotate(269deg);" ></span>
        <div class="controls">
            <a href="#back" class="fa fa-fast-backward"><img src="./image/back.png"></a>
            <a href="#play" class="fa fa-pause"></a>
            <a href="#forward" class="fa fa-fast-forward"><img src="./image/next.png"></a>
        </div>
        <div class="info">
            <i><span name="current">0:00</span> / <span name="duration">0:00</span></i>
            <label>Marteria - OMG</label>{{--歌名--}}
            <small>Zum Glück in die Zukunft II</small>{{--歌手--}}
        </div>
    </div>
</div>

<div style="position: fixed;left: 50%;top: 5%;width: 60%">
    <input type="hidden" name="status" value="1">
    <button class="layui-btn" name="getList" value="1" >新歌榜</button>
    <button class="layui-btn" name="getList" value="2" >热歌榜</button>
    <div style="width: 100%" name="search">
        <input type="text" name="title" required placeholder="搜索" autocomplete="off" class="layui-input" style="width: 70%;float: left">
        <button class="layui-btn"  name="search" value="2" >搜索</button>
    </div>

    <table class="layui-table" lay-even="" lay-skin="nob" >
        <colgroup>
            <col width="150">
            <col width="150">
            <col width="200">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>播放列表</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody id="tbody" style="color: #8a8a8a;cursor: pointer">

        </tbody>
    </table>
</div>

<div style="text-align:center;clear:both">
    {{--<script src="/gg_bd_ad_720x90.js" type="text/javascript"></script>--}}
    {{--<script src="/follow.js" type="text/javascript"></script>--}}
</div>
</body>

</html>