使用 302 跳转绕过协议及对 php 的过滤

ip?url=vps/302.php?a=1.jpg

接着通过 file 协议读取 nginx 配置文件
file:///etc/nginx/sites-enabled/default

发现 fastcgi_pass 127.0.0.1:9001

发现服务器架构为 Nginx + PHP-FPM
PHP-FPM fastcgi protocol 是以 bind port 的方式跑在本機上

但是当我利用的时候发现浏览器总是报协议，但是 自从libcurl 7.19.4版本后，302/301 跳转不跟随 file 协议被禁止掉（详见[CURLOPT_FOLLOWLOCATION explained](https://curl.haxx.se/libcurl/c/CURLOPT_FOLLOWLOCATION.html)

在真實世界中，只要發現對方的 PHP FastCGI 是可以外連的話那就可以拿 shell
所以使用 gopher 構造 FastCGI Protocol 訪問本機的 9001 port 就可以任意代碼執行


# Reference
[HITCON CTF 2015 Quals Web 出題心得](https://kb.hitcon.org/post/131488130087/hitcon-ctf-2015-quals-web-%E5%87%BA%E9%A1%8C%E5%BF%83%E5%BE%97)
[Hitcon 2015 lalala Web400 task](https://docs.google.com/document/d/1eALKwCyogM5Mw_D4qWe48X-PAGZw_2vT82aP0EPIr-8/edit)
[说说http/webserver/fastcgi/php-fpm](http://www.lxlxw.me/?p=216)\
[Fastcgi协议分析 && PHP-FPM未授权访问漏洞 && Exp编写](https://www.leavesongs.com/PENETRATION/fastcgi-and-php-fpm.html)
[302/301跳转绕过](https://www.from0to1.me/2018/03/07/31.html)