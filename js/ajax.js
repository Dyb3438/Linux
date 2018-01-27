function ajax(opt) {
    opt = opt || {};
    opt.method = opt.method || "POST";
    opt.url = opt.url || "";
    opt.async = opt.async || true;
    opt.data = opt.data || null;
    opt.success = opt.success || function() {};
    var xmlhttp = null;
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    var params = [];
    for (var key in opt.data) {
        params.push(key + "=" + opt.data[key]);
    }
    var postData = params.join("&");
    if (opt.method.toUpperCase() == "POST") {
        xmlhttp.open(opt.method, opt.url, opt.async);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
        xmlhttp.send(postData);
    } else if (opt.method.toUpperCase() == "GET") {
        xmlhttp.open(opt.method, opt.url + "?" + postData, opt.async);
        xmlhttp.send(null);
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            opt.success(xmlhttp.responseText);
        }
    }
}

function resToJson(res) {
    //把返回信息中的Json数据提出来
    var reg = /{.+}/;
    return JSON.parse(res.match(reg));
    //返回对象
}