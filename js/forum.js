function logOff() {
    ajax({
        "url": "Log_Off.php",
        "method": "GET",
        "success": function(res) {
            var msg = JSON.parse(res);
            if (msg.result == "Y") {
                location.pathname = "index.html";
            } else if (msg.result == "N") {
                alert("可能你姿势不对，注销失败");
                location.pathname = "index.html";
            }
        }
    })
}

function postres(t) {
    var res = t.contentDocument.getElementsByTagName("body")[0].innerHTML;
    console.log(res);
    if (res != "") {
        var reg = /{.+}/;
        var msg = JSON.parse(res.match(reg));
        if (msg.result == "Y") {
            location.pathname = "forum.html";
        } else if (msg.result == "N") {
            location.pathname = "forum.html";
            alert("受到神秘力量阻拦，发表失败");
        }
    }
}