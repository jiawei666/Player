var Player =
{
  isMuted: false,
  isPlaying: false,
  
  duration: 0,
  current: 0,
  
  mute: function()
  {//静音
    this.isMuted = this.isMuted ? false : true;
    if(this.isMuted){
        audio.muted=true;
    }else{
       audio.muted=false;
    }
    if(window.console) console.log(this.isMuted ? 'Muted' : 'Unmuted');
    return this
  },
  
  play: function()
  {
    this.isPlaying = this.isPlaying ? false : true;
    if(this.isPlaying){//播放
      audio.currentTime= this.current;//跳跃到指定位置播放
      audio.play()
    }else {//暂停
      audio.pause()
    }
    if(window.console) console.log(this.isPlaying ? 'Playing' : 'Paused');
    return this
  },

  
  vol: function(v)
  {
    if(window.console) console.log('Set volume to:', v, '%');
    v = v/100;
    audio.volume = v;
    return this
  },
  
  setDuration: function(s)
  {
    this.duration = s;
    
    var m = 0;
    while(s > 60) { m ++; s -= 60 }
    while(String(s).length == 1) s = '0' + s;
    s = parseInt(s)
    $('.music-player > .dash > .info > i > [name="duration"]').html(m + ':' + s);
    
    return this
  },
  
  setCurrent: function(s)
  {
    this.current = s;

    var m = 0, pct = this.current / this.duration;
    while(s > 60) { m ++; s -= 60 }
    while(String(s).length == 1) s = '0' + s;
    
    $('.music-player > .dash > .info > i > [name="current"]').html(m + ':' + s);
    
    $('.music-player > .dash > span[href="#seek"]:not(:active)').each(function()
    {
      var rotate = 'rotate(-' + ((pct * 180) + 90) + 'deg)';
      
      $(this).add('.music-player > .dash > .seeker > .wheel > .progress').css(
      {
        '-webkit-transform': rotate,
        '-moz-transform': rotate,
        '-ms-transform': rotate,
        '-o-transform': rotate,
        'transform' : rotate
      });

    });


    return this
  },

  playing: function()
  {
    if(!this.isPlaying)
      return this;

    if(this.current > (this.duration - 1)){
      var currentID =  $('#audio').attr('name');
      if($('input[name="status"]').val()==1){
        changeMP3(currentID,2);
      }else {
        var nowPlay = $('tr[class="nowPlay"]');
        nowPlay.next().click();
      }

    } else {
      this.setCurrent(this.current + 1);
    }
    return this
  }
};

function changeAuthor(id){//更改歌曲信息
  var author = $('#'+id).attr('author'),title = $('#'+id).attr('title')
  $('.info').find('label').text(title);
  $('.info').find('small').text(author);
}

function changeImg(id){//切图
  $('.album').attr('src','./download/'+id+'.jpg')
};

function changeMP3(id,type){//切歌

  if(type==1){//上一首
    if($('input[name="status"]').val()==1){
      var type = $('#'+id).prev();
      if(!type.length>0){
        type = $('#tbody').find('tr:last-child');
      }
    }else{
      var nowPlay = $('tr[class="nowPlay"]');
      nowPlay.prev().click();
      return
    }
  }else if(type==2) {//下一首
    if($('input[name="status"]').val()==1){
      var type = $('#'+id).next();
      if(!type.length>0){
        type = $('#tbody').find('tr:first-child');
      }
    }else {
      var nowPlay = $('tr[class="nowPlay"]');
      console.log(nowPlay.attr('id'));
      nowPlay.next().click();
      return
    }

  }else if (type==3){
    var type = $('#'+id);
  }
  $('#tbody').find('tr').removeClass('nowPlay');
  type.addClass('nowPlay');
  $('#audio').attr('src','./download/'+type.attr('id')+'.mp3');
  $('#audio').attr('name',type.attr('id'));
  changeImg(type.attr('id'));
  changeAuthor(type.attr('id'));
  $('.progress').attr('style','transform: rotate(269deg)');
  $('span[href="#seek"]').attr('style','transform: rotate(269deg)');
  Player.setCurrent(0);
  Player.isPlaying = false;
  Player.play();
}

function playSearch(mp3_link,jpg_link,song_id){
  console.log('点击播放搜索歌曲');
  loadingShow('show');
  $.post('./downloadSearch',{mp3_link:mp3_link,jpg_link:jpg_link,song_id:song_id},function(){}).done(function(data){
      if(data==1){
          changeMP3(song_id,3);
      }else {
          return alert('搜索获取失败2');
      }
    loadingShow('hide');
  }).error(function(){
    loadingShow('hide');
    return alert('搜索获取失败3');
  })
}

function getProgress(){
  var progressC =  $.cookie('progress');
  if( progressC < 100){
    $.cookie('progress',progressC*1+1*1);//js加法技巧
    $('.submit_load').find('span').text('已加载 '+ $.cookie('progress')+'%');
    console.log($.cookie('progress'));
  }

}

function loadingShow(type){
  if(type=='show'){
    $.cookie('progress',0);
    var timeouot =  setInterval(function(){
      getProgress();
      if($.cookie('progress')>=100){
        clearInterval(timeouot);
      }
    },150);
    $('.submit_load').show();
  }else{
    $.cookie('progress',100);
    $('.submit_load').hide();
  }
}


$(function()
{

  var audio = $('#audio')[0];

  setInterval(function(){ Player.playing() }, 1000);

  audio.addEventListener("canplaythrough", function() {//音频长度
    // @todo
    Player.setDuration(audio.duration);
  }, false);

  Player.setCurrent(Player.current);//当前播放位置



  $('a[href="#back"]').click(function () {//上一首
    var currentID =  $('#audio').attr('name');
    changeMP3(currentID,1)
  });

  $('a[href="#forward"]').click(function () {//下一首
    var currentID =  $('#audio').attr('name');
    changeMP3(currentID,2)
  })
  
  $('.music-player > .dash > a[href="#mute"]').click(function()
  {//静音
    $(this).toggleClass('fa-volume-up fa-volume-off');
    Player.mute();

    return !1;
  });
  
  $('.music-player > .dash > .controls > a[href="#play"]').click(function()
  {//暂停\播放
    $(this).toggleClass('fa-play fa-pause');
    Player.play();
    return !1;
  });
  

  
  $('.music-player > .dash > .volume-level').bind('mousemove', function(e)
  {//音量
    if($(this).is(':active'))
    {
      $(this).find('em').css('width', e.pageX - $(this).offset().left);
      var vol = $(this).find('em').width() / $(this).width() * 100;
      
      Player.vol(vol > 100 ? 100 : vol);
    }
  });
  
  $('.music-player').on('mousemove', function(e)
  {
    //http://jsfiddle.net/sandeeprajoria/x5APH/11/
    
    var wheel = $(this).find('.dash > .seeker > .wheel'), rotate,
      x = (e.pageX - 20) - wheel.offset().left - wheel.width() / 2,
      y = -1 * ((e.pageY - 20) - wheel.offset().top - wheel.height() / 2),
      deg = (90 - Math.atan2(y, x) * (180 / Math.PI)), pct, nc, nm = 0;
      if(deg > 270) deg = 270; else if(deg < 90) deg = 90;
      rotate = 'rotate(' + deg + 'deg)';
      pct = deg; pct = 270 - pct; pct = pct / 180;
      nc = Math.round(Player.duration * pct);
  
    $(this).find('.dash > span[href="#seek"]:active').each(function()
    {//手动设置进度条

      audio.currentTime= nc;//跳跃到指定位置播放
      Player.setCurrent(nc);
      while(nc > 60) { nm ++; nc -= 60 }
      while(String(nc).length == 1) nc = '0' + nc;

      $('.music-player > .dash > .info > i > [name="current"]').html(nm + ':' + nc);

      $(this).add('.music-player > .dash > .seeker > .wheel > .progress').css(
      {
        '-webkit-transform': rotate,
        '-moz-transform': rotate,
        '-ms-transform': rotate,
        '-o-transform': rotate,
        'transform' : rotate
      });
    });
  });
});