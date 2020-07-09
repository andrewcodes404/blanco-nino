jQuery(document).ready(function($) {
  // NAV STUFF
  $(".nav-menu-mobile").hide();
  var btn = $(".menu-btn");
  btn.addClass("fa-bars");
  btn.click(function() {
    //toggle slide the mob-menu
    $(".nav-menu-mobile").slideToggle("slow", function() {});

    //change class of font-awesome icon
    if (btn.hasClass("fa-bars")) {
      btn.addClass("fade-out");

      setTimeout(() => {
        btn.removeClass("fa-bars fade-out").addClass("fa-times fade-in");
      }, 300);
    } else {
      btn.removeClass("fade-in");
      btn.addClass("fade-out");
      setTimeout(() => {
        btn.removeClass("fa-times fade-out").addClass("fa-bars fade-in");
      }, 300);
    }

  });



  
  var videoBtn = $(".invest-hero-desktop");
  var videoModal = $(".video-modal");
  var closeBtn = $(".close-button");

  videoBtn.click(function() {
    videoModal.css("display", "flex");
    videoModal.addClass("fade-in");
    videoModal.html(
      '<iframe src="https://player.vimeo.com/video/315517029" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
    );
    closeBtn.css("display", "block");

    console.log("video button clicked");
  });

  closeBtn.click(function() {
    
    closeBtn.css("display", "none");
    videoModal.removeClass("fade-in");
    videoModal.addClass("fade-out");
    
    setTimeout(function (){
        videoModal.empty();
        videoModal.css("display", "none");
        console.log('timeout occureed');
        
    }, 300);
  });

  // videoModal.click(function() {
  //   videoModal.css("display", "none");
  //   // videoModal.removeClass("fade-in");
  //   // videoModal.addClass("fade-out");
  // });
});
