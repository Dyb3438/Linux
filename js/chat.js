var chatTimer;
var listTimer;

var myId = "";
var objectId = "";

var chatBody = document.getElementById("chatBody");

function sendDialog(e) {
    var chatText = document.getElementById("ChatText");
    if (e.keyCode == 13 && e.ctrlKey) {
        chatText.value += "\n";
    } else if (e.keyCode == 13 && chatText.value != "") {
        e.preventDefault();
        document.getElementById("chatInputForm").submit();
        cleanTextarea();
    } else if (e.keyCode == 13) {
        e.preventDefault();
    }
}

function sendedDialog(t) {
    var res = t.contentDocument.getElementsByTagName("body")[0].innerHTML;
    var reg = /Y/;
    if (reg.test(res)) {
        chackChatContent(chatBody, chatBody.getAttribute("receiverId"));
    }
    t.contentDocument.getElementsByTagName("body")[0].innerHTML = "";
}

function setChatContent(obj) {
    var temp = [];
    var chatBody = document.getElementById("chatBody");
    for (var key in obj) {
        if (obj[key].sender.userid == objectId) {
            temp.push("<div class='objectDialog'><img src='" + obj[key].sender.userphoto + "' class='avatar'><div class='chatContent'><div class='name'>" + obj[key].sender.username + "&nbsp;&nbsp;" + obj[key].time + "</div><div class='dialog'>" + obj[key].image + "</div></div></div>");
        } else if (obj[key].sender.userid == myId) {
            temp.push("<div class='myDialog'><img src='" + obj[key].sender.userphoto + "' class='avatar'><div class='chatContent'><div class='name'>" + obj[key].time + "&nbsp;&nbsp;" + obj[key].sender.username + "</div><div class='dialog'>" + obj[key].image + "</div></div></div>");
        }
    }
    chatBody.innerHTML += temp.join("");
    chatBody.scrollTop = chatBody.scrollHeight - chatBody.offsetHeight;
}

function cleanTextarea() {
    document.getElementById("ChatText").value = "";
}

function checkFriend() {
    ajax({
        "url": "chat-friend.php",
        "method": "GET",
        "success": function(response) {
            var res = resToJson(response);
            setFriendList(res.friend);
        }
    })
}

function setFriendList(obj) {
    var address = document.getElementById("address");
    var temp = [];
    for (var key in obj) {
        if (obj[key].number == "0") {
            temp.push("<div class='friend' onclick='chatWithF(this, " + obj[key].userid + ")'><img src='" + obj[key].userphoto + "' class='avatar'><div class='friendName'>" + obj[key].username + "</div></div>");
        } else {
            temp.push("<div class='friend' onclick='chatWithF(this, " + obj[key].userid + ")'><img src='" + obj[key].userphoto + "' class='avatar'><div class='friendName'>" + obj[key].username + "</div><div class='newCall'>" + obj[key].number + "</div></div>");
        }
    }
    address.innerHTML = temp.join("");
}

function chatWithF(element, id) {
    objectId = id;
    document.getElementById("objectName").innerHTML = element.childNodes[1].innerHTML;
    chatBody.setAttribute("chatId", 0);
    chatBody.setAttribute("receiverId", id);
    chatBody.innerHTML = "";
    document.getElementById("Receiver").value = id;
    checkFriend();
    chackChatContent(chatBody, id);
    window.clearInterval(chatTimer);
    chatTimer = self.setInterval(function() {
        chackChatContent(chatBody, id)
    }, 5000);
}

function chackChatContent(chatBody, id) {
    ajax({
        "url": "Image-check.php",
        "method": "GET",
        "data": {
            "Id": chatBody.getAttribute("chatId"),
            "Receiver": id
        },
        "success": function(response) {
            var res = resToJson(response);
            if (res.id != chatBody.getAttribute("chatId")) {
                chatBody.setAttribute("chatId", res.id);
                setChatContent(res.new_image);
            }
        }
    })
}