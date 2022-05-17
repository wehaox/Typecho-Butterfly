$('.set_toc').click(function(){ 
    $('.mtoc').slideToggle("fast")
});
if (screen.width>=1200){
   $(".mtoc a").click(function() {
        var hre = $(this).attr("href")
        $('html, body').animate({
                scrollTop: $(hre).offset().top-110}, 300)
})
}else if (screen.width>=768 && screen.width<990){
   $(".mtoc a").click(function() {
        var hre = $(this).attr("href")
        $('html, body').animate({
                scrollTop: $(hre).offset().top-120}, 300)
})
}else if(screen.width<768){
$(".mtoc a").click(function() {
        var hre = $(this).attr("href");
        $('html, body').animate({
                scrollTop: $(hre).offset().top-190}, 300)
})
}

const hash = window.location.hash
if (hash != '') {
    $('.mtoc').slideToggle("fast")
}
window.onload=function(){
    const point = localStorage.getItem("point")
    if(point !== null){
       document.getElementById("point").href = point
    }
    document.querySelector(".typecho-option-submit > li > button").addEventListener("click", function(){
        const hash = window.location.hash
        localStorage.setItem("point", hash)
    });
}