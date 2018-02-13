function response(text) {
    var container = document.getElementById("response");
    container.innerHTML = text;
}

var fd = new FormData();

function sendReq() {
    var req = new XMLHttpRequest();
    req.open("POST", "index.php", false);
    req.send(fd);
    if (req.status != 200) {
        // error
        alert(req.status + ': ' + req.statusText);
    } else {
        // parse result
        response(req.responseText);
    }
}

function sendtoserver(obj) {
    for (var z in obj) {
        fd.append(z, obj[z]);
        //console.log(z+obj[z]);
    }
    sendReq();

}