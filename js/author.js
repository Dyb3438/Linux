var checkChatTimer;

function initColor() {
    document.getElementById("info").firstChild.style.color = "";
    document.getElementById("follow").firstChild.style.color = "";
    document.getElementById("dynamic").firstChild.style.color = "";
    document.getElementById("record").firstChild.style.color = "";
    document.getElementById("fans").firstChild.style.color = "";
    window.onscroll = function() {}
}

function myself(id) {
    setInfo(id);
    document.getElementById("info").onclick = function() {
        myInfo(id);
    };
    document.getElementById("follow").onclick = function() {
        getfollow(id);
    };
    document.getElementById("fans").onclick = function() {
        getfans(id);
    };
    document.getElementById("record").onclick = function() {
        historyPost(id, 1);
    };
    document.getElementById("dynamic").onclick = function() {
        getMoment();
    };
    myInfo(id);
    document.getElementById("chat").onclick = function() {
        checkChat();
    }
    checkChat();
    checkChatTimer = self.setInterval("checkChat()", 3000);
}

function others(id) {
    var dynamic = document.getElementById("dynamic");
    var chat = document.getElementById("chat");
    dynamic.style.display = "none";
    chat.style.display = "none";
    document.getElementById("follow").style.display = "none";
    document.getElementById("fans").style.display = "none";
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
    var manage = document.getElementById("manage");
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
            if (response.identity == "管理员" || response.identity == "版主") {
                manage.style.display = "inline";
                manage.href = "manageForum.html";
            }
            checkFollow(response.guanzhu, id);
        }
    })
}

function checkFollow(status, id) {
    var followButton = document.getElementById("followButton");
    if (status == "") {
        followButton.style.display = "none";
    } else if (status == "0" || status == "2") {
        followButton.className = "follow";
        followButton.innerHTML = "关注";
        followButton.style.display = "block";
        followButton.onclick = function() {
            ajax({
                "url": "follow_sb.php",
                "data": {
                    "Type": 1,
                    "Followed": id
                },
                "success": function(response) {
                    var res = resToJson(response);
                    if (res.result == "Y") {
                        setInfo(id);
                    } else if (res.result == "N") {
                        alert(res.msg)
                    }
                }
            })
        }
    } else if (status == "1" || status == "3") {
        followButton.className = "follow followed";
        followButton.innerHTML = "已关注";
        followButton.style.display = "block";
        followButton.onclick = function() {
            ajax({
                "url": "follow_sb.php",
                "data": {
                    "Type": 0,
                    "Followed": id
                },
                "success": function(response) {
                    var res = resToJson(response);
                    if (res.result == "Y") {
                        setInfo(id);
                    } else if (res.result == "N") {
                        alert(res.msg)
                    }
                }
            })
        }
    }
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

function getMoment() {
    initColor();
    document.getElementById("dynamic").firstChild.style.color = "#fb7299";
    ajax({
        "url": "user_moment.php",
        "method": "GET",
        "success": function(res) {
            var response = resToJson(res);
            console.log(response);
            if (response.moment.length == 0) {
                document.getElementById("inner-body-right").innerHTML = "<div class='right-container'>暂无新动态</div>";
            } else {
                setMoment(response.moment);
            }
        }
    })
}

function setMoment(obj) {
    var container = document.getElementById("inner-body-right");
    var temp = [];
    for (var key in obj) {
        if (obj[key].type == "comment") {
            temp.push(setComment(obj[key]));
        } else if (obj[key].type == "response") {
            temp.push(setResponse(obj[key]));
        } else if (obj[key].type == "friend_moment") {
            temp.push(setFriendMoment(obj[key]));
        }
    }
    container.innerHTML = temp.join("");
}

function setComment(obj) {
    return "<div class='right-container'><a href='note.html?NoteId=" + obj.moment.noteid + "' target='_blank'>" + obj.moment.notename + "</a> 有 <span style='color: #00a1d6;'>" + obj.moment.number + "</span> 条新评论<br><br><div class='t2-time'>" + obj.time + "</div></div>";
}

function setResponse(obj) {
    return "<div class='right-container'><a href='author.html?userid=" + obj.moment.userid + "'>" + obj.moment.username + "</a> 回复了你在 <a href='note.html?NoteId=" + obj.moment.noteid + "' target='_blank'>" + obj.moment.notename + "</a> 内的评论<div class='floor-info'>" + obj.time + "</div><br><br><div class='floor-content-text'><div class='quoter'><div class='floor-info'>引用 " + obj.moment.quoter.quoterid + "楼 </div><a href='author.html?userid=" + obj.moment.quoter.userid + "' title='用户ID " + obj.moment.quoter.userid + "' target='_blank'>" + obj.moment.quoter.username + "</a><div class='floor-info'> " + obj.moment.quoter.time + "</div><br><br>" + obj.moment.quoter.content + "</div><br>" + obj.moment.content + "</div></div>";
}

function setFriendMoment(obj) {
    return "<div class='right-container'><a href='author.html?userid=" + obj.moment.userid + "'>" + obj.moment.username + "</a> 发布了新帖子 <a href='note.html?NoteId=" + obj.moment.noteid + "' target='_blank'>" + obj.moment.notename + "</a><br><br><div class='t2-time'>" + obj.time + "</div></div>";
}

function getfollow(id) {
    initColor();
    document.getElementById("follow").firstChild.style.color = "#fb7299";
    ajax({
        "url": "Friend-list.php",
        "method": "GET",
        "data": {
            "type": 1
        },
        "success": function(response) {
            var res = resToJson(response);
            setFriendList(res.following);
        }
    })
}

function getfans(id) {
    initColor();
    document.getElementById("fans").firstChild.style.color = "#fb7299";
    ajax({
        "url": "Friend-list.php",
        "method": "GET",
        "data": {
            "type": 2
        },
        "success": function(response) {
            var res = resToJson(response);
            setFriendList(res.fans);
        }
    })
}

function setFriendList(obj) {
    var container = document.getElementById("inner-body-right");
    if (obj.length == 0) {
        container.innerHTML = "<div class='right-container'>列表中无任何内容</div>";
        return;
    }
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='right-container'><img src='" + obj[key].userphoto + "' class='friendAvatar'><a href='author?userid=" + obj[key].userid + "' class='friendName'>" + obj[key].username + "</a></div>");
    }
    container.innerHTML = temp.join("");
}

function checkChat() {
    var chat = document.getElementById("chat");
    ajax({
        "url": "chat-outside.php",
        "method": "GET",
        "success": function(res) {
            if (chat.getAttribute("newChat") != res) {
                chat.setAttribute("newChat", res);
                if (res != "0") {
                    chat.innerHTML = "<div class='left-list'>· 聊天</div><div class='newCall'>" + res + "</div>";
                } else if (res == "0") {
                    chat.innerHTML = "<div class='left-list'>· 聊天</div>";
                }
            }
        }
    })
}