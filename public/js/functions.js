function w3_open() {
    document.getElementById("main").style.marginLeft = "15%";
    document.getElementById("menu").style.width = "15%";
    document.getElementById("menu").style.display = "flex";
    document.getElementsByClassName("menu-toggler")[0].style.display = 'none';

}

function w3_close() {
    document.getElementById("main").style.marginLeft = "0%";
    document.getElementById("menu").style.display = "none";
    document.getElementsByClassName("menu-toggler")[0].style.display = "inline-block";
}