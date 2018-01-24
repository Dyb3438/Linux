function seeInf(element) {
    var info = element.nextElementSibling;
    var warn = info.nextElementSibling;
    info.className = "information";
    warn.className = "information warn slideup";
}

function initInfo(element) {
    var info = element.nextElementSibling;
    var warn = info.nextElementSibling;
    var check = warn.nextElementSibling;
    element.className = "sign-input";
    info.className = "information slideup";
    warn.className = "information warn slideup";
    check.style.display = "none";
}

function inputRight(element) {
    var warn = element.nextElementSibling.nextElementSibling;
    var check = warn.nextElementSibling;
    element.className = "sign-input right";
    warn.className = "information warn slideup";
    check.style.display = "none";
    element.setAttribute("status", "1");
}

function inputErr(element) {
    var warn = element.nextElementSibling.nextElementSibling;
    var check = warn.nextElementSibling;
    element.className = "sign-input warn";
    warn.className = "information warn";
    check.style.display = "block";
    element.setAttribute("status", "0");
}

function resetCode() {
    var codeInput = document.getElementById('Code');
    var codeImage = document.getElementById('code');
    codeInput.value = "";
    codeImage.src = "code.php?p" + Math.random();
}