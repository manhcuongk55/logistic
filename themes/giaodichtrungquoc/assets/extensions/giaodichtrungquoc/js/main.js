$(document).ready(function() {
 
  var owl = $("#footerPartnerCarousel");
 
  owl.owlCarousel({
    items : 7, //10 items above 1000px browser width
    itemsDesktop : [1000,6], //5 items between 1000px and 901px
    itemsDesktopSmall : [900,5], // betweem 900px and 601px
    itemsTablet: [600,4], //2 items between 600 and 0
    itemsMobile : [479,3], // itemsMobile disabled - inherit from itemsTablet option
    navigation: true,
    navigationText:  ["<i class='fa fa-angle-left fa-2x'></i>","<i class='fa fa-angle-right fa-2x'></i>"],
    pagination: false,
  });
 
  // Custom Navigation Events
  $(".next").click(function(){
    owl.trigger('owl.next');
  })
  $(".prev").click(function(){
    owl.trigger('owl.prev');
  })
  $(".play").click(function(){
    owl.trigger('owl.play',1000); //owl.play event accept autoPlay speed as second parameter
  })
  $(".stop").click(function(){
    owl.trigger('owl.stop');
  })
 
});