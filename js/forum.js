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

function getParm() {
    var search = location.search;
    return searchToObject(search);
}

function searchToObject(str) {
    var temp = str.slice(1);
    var arr = temp.split("&");
    var obj = {};
    for (var i = arr.length - 1; i >= 0; i--) {
        var temp = arr[i].split("=");
        obj[temp[0]] = temp[1];
    }
    return obj;
}

function pageNumMsg(now, all) {
    //now为当前页 all为总共页数
    var temp = parseInt(all);
    var temp2 = parseInt(now);
    var start = parseInt(now);
    var end = parseInt(now);
    for (var i = 1; i >= 0; i--) {
        if (start - 1 > 0) {
            start = start - 1;
        } else if (end + 1 <= temp) {
            end = end + 1;
        }
    }
    for (var i = 1; i >= 0; i--) {
        if (end + 1 <= temp) {
            end = end + 1;
        } else if (start - 1 > 0) {
            start = start - 1;
        }
    }
    return [start, end, temp2, temp];
    //返回设置页码按钮所需信息[页码开始，页码结束，当前页，所有页]
}

function setPageButton(arr) {
    //arr为pageNumMsg返回的数组
    var pageArea = document.getElementById("page-area");
    var temp = [];
    if (arr[0] < arr[2]) {
        temp.push("<a href='forum.html?Page=" + (arr[2] - 1) + "'><div class='page-button'><前一页</div></a>");
    }
    for (var i = arr[0]; i <= arr[1]; i++) {
        temp.push("<a href='forum.html?Page=" + i + "'><div class='page-button'>" + i + "</div></a>");
    }
    if (arr[1] < arr[3]) {
        temp.push("<a href='forum.html?Page=" + (arr[2] + 1) + "'><div class='page-button'>后一页></div></a>");
    }
    pageArea.innerHTML = temp.join("");
}

function setTlitleNote(obj) {
    //obj为返回数据中的note
    var forumTitle = document.getElementById("forum-title");
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1' title='" + obj[key].notename + "'><a href='note.html?NoteId=" + obj[key].noteid + "'>" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a><span class='t2-time'>" + obj[key].time + "</span></div></div>")
    }
    forumTitle.innerHTML = temp.join("");
}

function setTlitleTop(obj) {
    //obj为返回数据中的top
    var forumTitle = document.getElementById("top");
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1' title='" + obj[key].notename + "'><a href='note.html?NoteId=" + obj[key].noteid + "'>[置顶]" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a><span class='t2-time'>" + obj[key].time + "</span></div></div>")
    }
    forumTitle.innerHTML = temp.join("");
}