function setTlitleNoteM(obj) {
    //obj为返回数据中的note
    var forumTitle = document.getElementById("forum-title");
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1 t3' title='" + obj[key].notename + "'><a href='manageNote.html?NoteId=" + obj[key].noteid + "' target='_blank'>" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a><span class='t2-time'>" + obj[key].time + "</span></div><div class='manage-button'><a href='javascript:void(0)'' onclick='delNote(" + obj[key].noteid + ")'>[删除]</a><a href='javascript:void(0)' onclick='topSet(" + obj[key].noteid + ")'>[置顶]</a></div></div>")
    }
    forumTitle.innerHTML = temp.join("");
}

function setTlitleTopM(obj) {
    //obj为返回数据中的top
    var forumTitle = document.getElementById("top");
    var temp = [];
    for (var key in obj) {
        temp.push("<div class='title-body'><div class='t1 t3' title='" + obj[key].notename + "'><a href='manageNote.html?NoteId=" + obj[key].noteid + "' target='_blank'>[置顶]" + obj[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + obj[key].userid + "' class='t2-author' title='用户ID " + obj[key].userid + "'>ID:" + obj[key].userid + "</a></div><div class='manage-button'><a href='javascript:void(0)'' onclick='delNote(" + obj[key].noteid + ")'>[删除]</a><a href='javascript:void(0)' onclick='topSetCancel(" + obj[key].noteid + ")'>[取消置顶]</a></div></div>")
    }
    forumTitle.innerHTML = temp.join("");
}

function loadNoteFloorM(obj) {
    //obj为返回数据中的floor
    var floor = document.getElementById("floor");
    var temp = [];
    for (var key in obj) {
        if (obj[key].floor == "1") {
            temp.push("<div class='floor'><div class='floor-author'><img src='" + obj[key].userphoto + "'><a href='author.html?userid=" + obj[key].userid + "' target='_blank' title='用户ID " + obj[key].userid + "'>" + obj[key].username + "</a></div><div class='floor-content'><div class='floor-content-text'>" + obj[key].content + "</div><div class='floor-info'>" + obj[key].floor + "楼 " + obj[key].time + "</div></div></div>");
        } else if (obj[key].quoter != "0") {
            temp.push(setFloorM(obj[key], setQuoter(obj[key].quoter)));
        } else if (obj[key].quoter == "0") {
            temp.push(setFloorM(obj[key]));
        }
    }
    floor.innerHTML = temp.join("");
}

function setFloorM(obj, quoter) {
    var temp = quoter || "";
    var floor = "<div class='floor'><div class='floor-author'><img src='" + obj.userphoto + "'><a href='author.html?userid=" + obj.userid + "' target='_blank' title='用户ID " + obj.userid + "'>" + obj.username + "</a></div><div class='floor-content'><div class='floor-content-text'>" + temp + obj.content + "</div><div class='floor-info'>" + obj.floor + "楼 " + obj.time + "</div><a href='javascript:void(0)' onclick='delNoteFloor(" + obj.floor + ")'>删除</a></div></div>";
    return floor;
}

function delNote(id) {
    var delCheck = document.getElementById("delCheck");
    var Noteid = document.getElementById("Noteid");
    Noteid.value = id;
    delCheck.style.display = "block";
}

function delCancel() {
    var formTarget = document.getElementById("formTarget");
    var delCheck = document.getElementById("delCheck");
    delCheck.style.display = "none";
}

function delRes(t) {
    var result = t.contentDocument.getElementsByTagName("body")[0].innerHTML;
    if (result == "Y") {
        alert("删除成功");
        delCancel();
        location.reload();
    } else if (result == "N") {
        alert("删除失败");
    }
}

function topSet(id) {
    ajax({
        "url": "top_set.php",
        "data": {
            "Noteid": id
        },
        "success": function(response) {
            var res = resToJson(response);
            if (res.result == "Y") {
                alert("置顶成功");
                location.reload();
            } else if (res.result == "F") {
                chooseTop(res.top, res["ready_top"]);
            } else if (res.result == "N") {
                alert(res.msg);
            }
        }
    })
}

function chooseTop(top, id) {
    var topCheck = document.getElementById("topCheck");
    topCheck.style.display = "block";
    var container = topCheck.children[0].children[1];
    var temp = [];
    for (var key in top) {
        temp.push("<div class='title-body'><div class='t1 t3' title='" + top[key].notename + "'><a href='manageNote.html?NoteId=" + top[key].noteid + "' target='_blank'>[置顶]" + top[key].notename + "</a></div><div class='t2'><a href='author.html?userid=" + top[key].userid + "' class='t2-author' title='用户ID " + top[key].userid + "'>ID:" + top[key].userid + "</a></div><div class='manage-button'><a href='javascript:void(0)' onclick='updateTop(" + id + ", " + top[key].noteid + ")'>[取消置顶]</a></div></div>");
    }
    container.innerHTML = temp.join("");
}

function updateTop(topid, cancelid) {
    ajax({
        "url": "top_update.php",
        "data": {
            "Top_id": topid,
            "Cancel_id": cancelid
        },
        "success": function(res) {
            if (res == "Y") {
                alert("置顶成功");
                location.reload();
            } else if (res == "N") {
                alert("受到神秘力量阻挡，置顶失败");
            }
        }
    })
}

function topCancel() {
    var topCheck = document.getElementById("topCheck");
    topCheck.style.display = "none";
}

function topSetCancel(id) {
    var r = confirm("真的要取消置顶吗");
    if (r == true) {
        ajax({
            "url": "top_cancel.php",
            "data": {
                "Noteid": id
            },
            "success": function(res) {
                if (res == "Y") {
                    alert("取消成功");
                    location.reload();
                } else if (res == "N") {
                    alert("受到神秘力量阻挡，取消失败");
                }
            }
        })
    }
}

function delNoteFloor(floor) {
    var delCheck = document.getElementById("delCheck");
    var Floor = document.getElementById("Floor");
    delCheck.style.display = "block";
    Floor.value = floor;
}

function setPageButtonM(arr) {
    //arr为pageNumMsg返回的数组
    var pageArea = document.getElementById("page-area");
    var temp = [];
    if (arr[0] < arr[2]) {
        temp.push("<a href='manageForum.html?Page=" + (arr[2] - 1) + "'><div class='page-button'><前一页</div></a>");
    }
    for (var i = arr[0]; i <= arr[1]; i++) {
        if (i == arr[2]) {
            temp.push("<a href='javascript:void(0)'><div class='now-page-button'>" + i + "</div></a>");
        } else {
            temp.push("<a href='manageForum.html?Page=" + i + "'><div class='page-button'>" + i + "</div></a>");
        }
    }
    if (arr[2] < arr[3]) {
        temp.push("<a href='manageForum.html?Page=" + (arr[2] + 1) + "'><div class='page-button'>后一页></div></a>");
    }
    pageArea.innerHTML = temp.join("");
}

function setPageButtonInNoteM(arr, noteid) {
    //arr为pageNumMsg返回的数组
    var pageArea = document.getElementById("page-area");
    var temp = [];
    if (arr[0] < arr[2]) {
        temp.push("<a href='manageNote.html?NoteId=" + noteid + "&Page=" + (arr[2] - 1) + "'><div class='page-button'><前一页</div></a>");
    }
    for (var i = arr[0]; i <= arr[1]; i++) {
        if (i == arr[2]) {
            temp.push("<a href='javascript:void(0)'><div class='now-page-button'>" + i + "</div></a>");
        } else {
            temp.push("<a href='manageNote.html?NoteId=" + noteid + "&Page=" + i + "'><div class='page-button'>" + i + "</div></a>");
        }
    }
    if (arr[2] < arr[3]) {
        temp.push("<a href='manageNote.html?NoteId=" + noteid + "&Page=" + (arr[2] + 1) + "'><div class='page-button'>后一页></div></a>");
    }
    pageArea.innerHTML = temp.join("");
}