/**
 * Created by 95708 on 2017/8/26.
 */
$(function () {
    $('button[name="getList"]:eq(1)').css('background-color','#ce1a00');

    function setDruation(time){
        var s = time%60;
        var m = (time-s)/60
        return m+':'+s;
    }

    function ajaxGetMusic(type){
        //$('.submit_load').show();
        loadingShow('show');
        $.post('./getMusic',{type:type}, function (data) {

        }).done(function(data){
            $.each(data,function(index,content){

                $('#tbody').append(' <tr onclick="changeMP3('+index+',3)" id="'+index+'" author="'+content['author']+'" title="'+content['title']+'">'+
                    '<td>'+content['title']+'</td>'+
                    '<td>'+content['author']+'</td>'+
                    '<td>'+setDruation(content['druation'])+'</td>'+
                    '</tr>');
                console.log(content['mp3']);
            });
            //$('.submit_load').hide();
            var firstSong =  $('#tbody').find('tr:first-child');
            changeMP3(firstSong.attr('id'),3);
            loadingShow('hide');
        }).error(function(data){
            return alert('系统连接出错');
        });
    }

    ajaxGetMusic(2);

    $('button[name="getList"]').click(function(){
        Player.isPlaying=true;
        Player.play();
        $('input[placeholder="搜索"]').val('');
        $(this).css('background-color','#ce1a00');
        $(this).siblings('button').css('background-color','#009688');
        $('button[name="search"]').css('background-color','#009688');
        $('#tbody').html('');
        $('input[name="status"]').val(1); //状态为1 默认下一首播放新歌、热歌榜
        $.cookie('progress',0);
        var timeouot =  setInterval(function(){
            getProgress();
            if($.cookie('progress')>=100){
                clearInterval(timeouot);
            }
        },150);
        ajaxGetMusic($(this).attr('value'));

    });


    $('button[name="search"]').click(function(){
        loadingShow('show');
        $('button[name="getList"]').css('background-color','#009688');
        $(this).css('background-color','#ce1a00');
        var search = $(this).prev().val();
        console.log(search);
        if(search==''){
            return alert('请输入歌名或歌手');
        }
        $.post('./search',{search:search},function(){}).done(function(data){
            $('#tbody').html('');
            $.each(data,function(index,content){
                content['jpg_link']= content['jpg_link'] == ''? './image/image_png_00002.png' : content['jpg_link'];
                $('#tbody').append(' <tr onclick="playSearch(\''+content['mp3_link']+'\',\''+content['jpg_link']+'\','+index+')" id="'+index+'" author="'+content['author']+'" title="'+content['title']+'" mp3_link="'+content['mp3_link']+'" >'+
                    '<td>'+content['title']+'</td>'+
                    '<td>'+content['author']+'</td>'+
                    '<td>'+setDruation(content['druation'])+'</td>'+
                    '</tr>');
                console.log(content['jpg_link']);
            });

            loadingShow('hide');
            $('input[name="status"]').val(2);//状态为2 默认下一首播放搜索列表
        }).error(function(){
            loadingShow('hide');
            return alert('搜索获取失败')

        })
    })




});//http://localhost/Player/public/getMusic
