$(window).scroll(function() {
if ($(this).scrollTop() > 60){
    $('.navbar-fixed-top').addClass("logo-fix-adon");

  }
  else{
    $('.navbar-fixed-top').removeClass("logo-fix-adon");

  }

});