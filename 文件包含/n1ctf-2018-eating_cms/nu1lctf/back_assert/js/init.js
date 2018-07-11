$(function(){
    var buf = [];
    buf.push('<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>');
    buf.push('<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px" data-ad-client="ca-pub-0608155192548477" data-ad-slot="8305246055"></ins>');
    buf.push('<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>');
    buf.push('<script>var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "http://hm.baidu.com/hm.js?244ff9d4fa95dcc8d7e59d2dfaf5b2c4";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(hm, s);})();</script>');
    buf.push('<script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16"},"slide":{"type":"slide","bdImg":"1","bdPos":"right","bdTop":"100"}};with(document)0[(getElementsByTagName("head")[0]||body).appendChild(createElement("script")).src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion="+~(-new Date()/36e5)];</script>');
    $('.footer-banner').html(buf.join(''));
});