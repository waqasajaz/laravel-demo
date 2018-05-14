$(document).ready(function(){
 $('.fix-nav-bar li a').click(function(){
    $('li a').removeClass("current");
    $(this).addClass("current");
 });
});
