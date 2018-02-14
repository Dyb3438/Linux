function logOff() {
    ajax({
        "url": "Log_Off.php",
        "method": "GET",
        "success": function(res) {
            var msg = JSON.parse(res);
            if (msg.result == "Y") {
                location.reload();
            } else if (msg.result == "N") {
                alert("可能你姿势不对，注销失败");
                location.reload();
            }
        }
    })
}

function postres(t) {
    var res = t.contentDocument.getElementsByTagName("body")[0].innerHTML;
    if (res != "") {
        var reg = /{.+}/;
        var msg = JSON.parse(res.match(reg));
        console.log(msg);
        if (msg.result == "Y") {
            location.reload();
        } else if (msg.result == "N") {
            alert("受到神秘力量阻拦，发送失败");
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
        if (i == arr[2]) {
            temp.push("<a href='javascript:void(0)'><div class='now-page-button'>" + i + "</div></a>");
        } else {
            temp.push("<a href='forum.html?Page=" + i + "'><div class='page-button'>" + i + "</div></a>");
        }
    }
    if (arr[2] < arr[3]) {
        temp.push("<a href='forum.html?Page=" + (arr[2] + 1) + "'><div class='page-button'>后一页></div></a>");
    }
    pageArea.innerHTML = temp.join("");
}

function setPageButtonInNote(arr, noteid) {
    //arr为pageNumMsg返回的数组
    var pageArea = document.getElementById("page-area");
    var temp = [];
    if (arr[0] < arr[2]) {
        temp.push("<a href='note.html?NoteId=" + noteid + "&Page=" + (arr[2] - 1) + "'><div class='page-button'><前一页</div></a>");
    }
    for (var i = arr[0]; i <= arr[1]; i++) {
        if (i == arr[2]) {
            temp.push("<a href='javascript:void(0)'><div class='now-page-button'>" + i + "</div></a>");
        } else {
            temp.push("<a href='note.html?NoteId=" + noteid + "&Page=" + i + "'><div class='page-button'>" + i + "</div></a>");
        }
    }
    if (arr[2] < arr[3]) {
        temp.push("<a href='note.html?NoteId=" + noteid + "&Page=" + (arr[2] + 1) + "'><div class='page-button'>后一页></div></a>");
    }
    pageArea.innerHTML = temp.join("");
}

function setTlitleNote(obj) {
    //obj为返回数据中的note
    var forumTitle = document.getElementById("forum-title");
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1' title='" + obj[key].notename + "'><a href='note.html?NoteId=" + obj[key].noteid + "' target='_blank'>" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a><span class='t2-time'>" + obj[key].time + "</span></div></div>")
    }
    forumTitle.innerHTML = temp.join("");
}

function setTlitleTop(obj) {
    //obj为返回数据中的top
    var forumTitle = document.getElementById("top");
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1' title='" + obj[key].notename + "'><a href='note.html?NoteId=" + obj[key].noteid + "' target='_blank'>[置顶]" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a></div></div>")
    }
    forumTitle.innerHTML = temp.join("");
}

function reply(f) {
    document.getElementById("Floor").value = f;
    document.getElementById("quote").innerHTML = "（引用 " + f + "楼）";
    document.getElementById("quoteButton").style.display = "inline-block";
}

function quotoCancel() {
    document.getElementById("Floor").value = 0;
    document.getElementById("quote").innerHTML = "";
    document.getElementById("quoteButton").style.display = "none";
}

function loadNoteFloor(obj) {
    //obj为返回数据中的floor
    var floor = document.getElementById("floor");
    var temp = [];
    for (var key in obj) {
        if (obj[key].floor == "1") {
            temp.push("<div class='floor'><div class='floor-author'><img src='" + obj[key].userphoto + "'><a href='author.html?userid=" + obj[key].userid + "' target='_blank' title='用户ID " + obj[key].userid + "'>" + obj[key].username + "</a></div><div class='floor-content'><div class='floor-content-text'>" + obj[key].content + "</div>" + setPraise(obj[key]["T-Fpraise"], obj[key].praiseid, obj[key].floor) + "<div class='floor-info'>" + obj[key].floor + "楼 " + obj[key].time + "</div></div></div>");
        } else if (obj[key].quoter != "0") {
            temp.push(setFloor(obj[key], setQuoter(obj[key].quoter)));
        } else if (obj[key].quoter == "0") {
            temp.push(setFloor(obj[key]));
        }
    }
    floor.innerHTML = temp.join("");
}

function setQuoter(quoter) {
    var quote = "<div class='quoter'><div class='floor-info'>引用 " + quoter.quoterid + "楼 </div><a href='author.html?userid=" + quoter.userid + "' target='_blank' title='用户ID " + quoter.userid + "'>" + quoter.username + "</a><div class='floor-info'> " + quoter.time + "</div><br><br>" + quoter.content + "</div><br>";
    return quote;
}

function setFloor(obj, quoter) {
    var temp = quoter || "";
    var floor = "<div class='floor'><div class='floor-author'><img src='" + obj.userphoto + "'><a href='author.html?userid=" + obj.userid + "' target='_blank' title='用户ID " + obj.userid + "'>" + obj.username + "</a></div><div class='floor-content'><div class='floor-content-text'>" + temp + obj.content + "</div>" + setPraise(obj["T-Fpraise"], obj.praiseid, obj.floor) + "<div class='floor-info'>" + obj.floor + "楼 " + obj.time + "</div><a href='#post-forum' onclick='reply(" + obj.floor + ")'>回复</a></div></div>";
    return floor;
}

function setPraise(status, num, id) {
    var temp = "";
    var numS
    if (num == 0) {
        numS = "";
    } else {
        numS = num
    }
    if (status == "0") {
        temp = "<a href='javascript:void(0)' onclick='thumbUp(this, " + id + " , " + status + ", " + num + ")'>点赞 " + numS + " </a>";
    } else if (status == "1") {
        temp = "<a href='javascript:void(0)' onclick='thumbUp(this, " + id + " , " + status + ", " + num + ")'>&hearts; 已点赞 " + numS + " </a>";
    }
    return temp;
}

function thumbUp(element, id, status, num) {
    num = parseInt(num);
    ajax({
        "url": "praise.php",
        "data": {
            "Noteid": getParm().NoteId,
            "Floor": id,
            "Type": status
        },
        "success": function(res) {
            if (res == "Y") {
                if (status == "0") {
                    element.innerHTML = "&hearts; 已点赞 " + (num + 1);
                    element.onclick = function() {
                        thumbUp(element, id, 1, num + 1)
                    }
                } else if (status == "1") {
                    var numS;
                    if (num - 1 == 0) {
                        numS = "";
                    } else {
                        numS = num - 1
                    }
                    element.innerHTML = "点赞 " + numS;
                    element.onclick = function() {
                        thumbUp(element, id, 0, num - 1)
                    }
                }
            } else if (res == "N") {
                alert("点赞失败，你可能未登录！");
            }
        }
    })
}

function setNoteTitle(notename, noteid) {
    var tiezi = document.getElementById("tiezi");
    tiezi.innerHTML = "<div class='t1' title='" + notename + "'><a href='note.html?NoteId=" + noteid + "'>" + notename + "</a></div>";
}

function search(e, s) {
    //s为搜索关键词
    if (e.keyCode == 13 && s != "") {
        location.href = "search.html?Search=" + s;
    }
}

function setNotice() {
    var boardButton = document.getElementById("boardButton");
    status = localStorage.getItem("boardStatus") || "0";
    if (status == "0") {
        seeBoard(boardButton)
    } else if (status == "1") {
        hideBoard(boardButton)
    }
}

function hideBoard(element) {
    var noticeBoard = document.getElementById("notice-board");
    noticeBoard.style.display = "none";
    localStorage.setItem("boardStatus", "1");
    element.innerHTML = "显示公告栏";
    element.onclick = function() {
        seeBoard(element);
    }
}

function seeBoard(element) {
    var noticeBoard = document.getElementById("notice-board");
    var container = document.getElementById("notice-board-container");
    ajax({
        "url": "Notice.php",
        "method": "GET",
        "success": function(res) {
            container.innerHTML = res;
        }
    })
    noticeBoard.style.display = "block";
    localStorage.setItem("boardStatus", "0");
    element.innerHTML = "隐藏公告栏";
    element.onclick = function() {
        hideBoard(element);
    }

}