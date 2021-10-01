$('.set_toc').click(function(){ 
    $('.mtoc').slideToggle("fast");
});
if (screen.width>=1200){
   $(".mtoc a").click(function() {
        var hre = $(this).attr("href");
        $('html, body').animate({
                scrollTop: $(hre).offset().top-110}, 300);
});
}else if (screen.width>=768 && screen.width<990){
   $(".mtoc a").click(function() {
        var hre = $(this).attr("href");
        $('html, body').animate({
                scrollTop: $(hre).offset().top-120}, 300);
}); 
}else if(screen.width<768){
$(".mtoc a").click(function() {
        var hre = $(this).attr("href");
        $('html, body').animate({
                scrollTop: $(hre).offset().top-160}, 300);
});
}