var system = require('system');
var page = require('webpage').create();
var timeout = 5000;

if (system.args.length !== 4) {
        console.log('args: url sessionid timeout');
        phantom.exit(1);
} else {
    url = system.args[1];
    session = system.args[2];
    to = system.args[3];
}

phantom.addCookie({
    'name': 'sessionid',
    'value': session,
    'domain': '127.0.0.1',
    'path': '/',
    'httponly': false
});

phantom.addCookie({
    'name': 'sessionid',
    'value': session,
    'domain': 'localhost',
    'path': '/',
    'httponly': false
});

phantom.addCookie({
    'name': 'sessionid',
    'value': session,
    'domain': '35.198.114.228',
    'path': '/',
    'httponly': false
});
 
page.onNavigationRequested = function(url, type, willNavigate, main) {
    console.log("[phantom][URL] URL="+url);  
    //console.log(page.content);
    //console.log("[SESSION] sessionid="+session);  
};

page.onResourceRequested = function(requestData, networkRequest) {
    console.log("[phantom][Resource requested] URL="+requestData.url);  
    //console.log("Resource requested: "+JSON.stringify(requestData));  
};

page.onResourceReceived = function(response) {
  //console.log('Response (#' + response.id + ', stage "' + response.stage + '"): ' + JSON.stringify(response));
};

page.onConsoleMessage = function(msg) {
      console.log(msg);
};

 
page.settings.resourceTimeout = timeout;
page.onResourceTimeout = function(e) {
    setTimeout(function(){
        console.log("[phantom][INFO] Timeout")
        phantom.exit();
    }, to * 1000);
};

 
page.open(url, function(status) {
    console.log("[phantom][INFO] rendered page");
    //console.log(page.content);
    //console.log("\n\n\n\n");
    setTimeout(function(){
        phantom.exit();
    }, to * 1000);
});
