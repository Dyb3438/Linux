var background_1 = document.getElementById("background-slideshow").children[0].style;
var background_2 = document.getElementById("background-slideshow").children[1].style;
var status = 1;
var image_src = ["background-image/22600763_p0_master1200.jpg",
    "background-image/26339586_p0_master1200.jpg",
    "background-image/29601755_p0_master1200.jpg",
    "background-image/31251762_p0_master1200.jpg",
    "background-image/34512986_p0_master1200.jpg",
    "background-image/35019721_p0_master1200.jpg",
    "background-image/35231457_p0_master1200.jpg",
    "background-image/36919122_p0_master1200.jpg",
    "background-image/37016225_p0_master1200.jpg",
    "background-image/37203249_p0_master1200.jpg",
    "background-image/37602900_p0_master1200.jpg",
    "background-image/38631998_p0_master1200.jpg",
    "background-image/40191798_p0_master1200.jpg",
    "background-image/43409888_p0_master1200.jpg",
    "background-image/47621790_p0_master1200.jpg",
    "background-image/49281286_p0_master1200.jpg",
    "background-image/57080648_p0_master1200.jpg",
    "background-image/57196809_p0_master1200.jpg",
    "background-image/8913281_p0_master1200.jpg",
    "background-image/11333874_p0_master1200.jpg",
    "background-image/15126670_p0_master1200.jpg",
    "background-image/16848987_p0_master1200.jpg",
    "background-image/16983806_p0_master1200.jpg",
    "background-image/22208183_p0_master1200.jpg",
];


(function() {
    var temp = Math.round(23 * Math.random());
    background_1.backgroundImage = "url('" + image_src[temp] + "')";
    var img = new Image();
    img.src = image_src[temp];
    img.onload = function() {
        temp = Math.round(23 * Math.random());
        background_2.backgroundImage = "url('" + image_src[temp] + "')";
        setTimeout(function() {
            backgroundTimer(temp);
        }, 20000);
    }
})();

function changeBackground(temp) {
    if (status == 1) {
        background_1.opacity = 0;
        setTimeout(function() {
            background_2.zIndex = -1;
            background_1.zIndex = -2;
            background_1.opacity = 1;
            background_1.backgroundImage = "url('" + image_src[temp] + "')";
        }, 600)
        status = 2;
    } else if (status == 2) {
        background_2.opacity = 0;
        setTimeout(function() {
            background_1.zIndex = -1;
            background_2.zIndex = -2;
            background_2.opacity = 1;
            background_2.backgroundImage = "url('" + image_src[temp] + "')";
        }, 600)
        status = 1;
    }
}

function backgroundTimer(temp) {
    var img = new Image();
    img.src = image_src[temp];
    img.onload = function() {
        temp = Math.round(23 * Math.random());
        changeBackground(temp);
        setTimeout(function() {
            backgroundTimer(temp);
        }, 20000);
    }
}