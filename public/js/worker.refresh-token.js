var currentToken;
var refreshRate = 120 * 1000; // 2 minutes

this.addEventListener('message', function(evt){
    currentToken = evt.data.token;
});

var intervalID = setInterval(function(){
    var req = new XMLHttpRequest();
    req.open('GET', '/ajax/refresh-token');
    req.setRequestHeader('Authorization', 'Bearer ' + currentToken);
    req.send();

    req.addEventListener('load', function(evt) {
        var data = JSON.parse(req.responseText);

        if (data.token) {
            currentToken = data.token;
            postMessage({
                token: currentToken
            });
        }
    });
}, refreshRate)