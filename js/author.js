function initColor() {
    document.getElementById("info").firstChild.style.color = "";
    document.getElementById("follow").firstChild.style.color = "";
    document.getElementById("dynamic").firstChild.style.color = "";
    document.getElementById("record").firstChild.style.color = "";
}

function myself(id) {
    setInfo(id);
    document.getElementById("info").onclick = function() {
        myInfo(id);
    };
    document.getElementById("record").onclick = function() {
        historyPost(id, 1);
    };
    myInfo(id);
}

function others(id) {
    var dynamic = document.getElementById("dynamic");
    var chat = document.getElementById("chat");
    dynamic.style.display = "none";
    chat.style.display = "none";
    document.getElementById("info").onclick = function() {
        otherInfo(id);
    };
    document.getElementById("record").onclick = function() {
        historyPost(id, 1);
    };
    setInfo(id);
    otherInfo(id);
}

function setInfo(id) {
    var headAvatar = document.getElementById("head-avatar");
    var headName = document.getElementById("head-name");
    var headIdentity = document.getElementById("head-identity");
    ajax({
        "url": "Image.php",
        "method": "GET",
        "data": {
            "Userid": id
        },
        "success": function(res) {
            var response = resToJson(res);
            if (response.result == "N") {
                document.write("404 NOT FOUND!");
                return;
            }
            headAvatar.src = response.userphoto;
            headName.innerHTML = response.username;
            headIdentity.innerHTML = response.identity;
        }
    })
}

function myInfo(id) {
    var container = document.getElementById("inner-body-right");
    initColor();
    document.getElementById("info").firstChild.style.color = "#fb7299";
    ajax({
        "url": "Image.php",
        "method": "GET",
        "data": {
            "Userid": id
        },
        "success": function(res) {
            var response = resToJson(res);
            container.innerHTML = "<div class='right-container'>ID：" + response.userid + "<br><br>昵称：" + response.username + "<br><br>身份：" + response.identity + "<br><br>性别：" + response.sex + "<br><br>生日：" + response.birth + "<br><br>QQ：" + response.QQ + "<br><br>电子邮箱：" + response.email + "<br><br><button class='blueButton modify-button' onclick='modify()'>修改密码</button><br><br><button class='blueButton modify-button' onclick=\"location.href=\'modify.html\'\">修改个人信息</button></div>";
        }
    })
}

function otherInfo(id) {
    var container = document.getElementById("inner-body-right");
    initColor();
    document.getElementById("info").firstChild.style.color = "#fb7299";
    ajax({
        "url": "Image.php",
        "method": "GET",
        "data": {
            "Userid": id
        },
        "success": function(res) {
            var response = resToJson(res);
            container.innerHTML = "<div class='right-container'>ID：" + response.userid + "<br><br>昵称：" + response.username + "<br><br>身份：" + response.identity + "<br><br>性别：" + response.sex + "<br><br>生日：" + response.birth + "<br><br>QQ：" + response.QQ + "<br><br>电子邮箱：" + response.email + "</div>";
        }
    })
}

function modify() {
    var iframe = document.getElementById("modifyPassword");
    var background = document.getElementById("background-gray");
    background.style.display = "block";
    iframe.src = "modifyPassword.html";
    iframe.style.display = "block";
}

function historyPost(id, page) {
    document.getElementById("inner-body-right").innerHTML = "<div class='right-container'></div><div id='loading' class='right-container' style='display: none;'>加载中。。。</dia>";
    initColor();
    document.getElementById("record").firstChild.style.color = "#fb7299";
    getHistoryPost(id, page);
}

function getHistoryPost(id, page) {
    var loading = document.getElementById("loading");
    loading.style.display = "block";
    ajax({
        "url": "History-post.php",
        "method": "GET",
        "data": {
            "Userid": id,
            "Page": page
        },
        "success": function(res) {
            loading.style.display = "none";
            var response = resToJson(res);
            if (response.note == "") {
                loading.innerHTML = "你已经加载完了";
                loading.style.display = "block";
                window.onscroll = function() {}
                return;
            }
            setTlitleNote(response.note);
            if (response.pages == "1") {
                loading.innerHTML = "你已经加载完了";
                loading.style.display = "block";
                window.onscroll = function() {}
                return;
            }
            window.onscroll = function() {
                var scrollTop = window.document.documentElement.scrollTop;
                var innerHeight = window.innerHeight;
                var scrollHeight = window.document.documentElement.scrollHeight;
                // console.log(scrollTop);
                // console.log(innerHeight);
                // console.log(scrollHeight);
                if (scrollTop >= scrollHeight - innerHeight - 1) {
                    window.onscroll = function() {}
                    getHistoryPost(id, page + 1);
                }
            }
        }
    })
}

function setTlitleNote(obj) {
    //obj为返回数据中的note
    var container = document.getElementById("inner-body-right").firstChild;
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1' title='" + obj[key].notename + "'><a href='note.html?NoteId=" + obj[key].noteid + "' target='_blank'>" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a><span class='t2-time'>" + obj[key].time + "</span></div></div>")
    }
    container.innerHTML += temp.join("");
}