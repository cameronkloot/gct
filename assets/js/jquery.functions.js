;(function($) {
  $(document).ready(function() {
    // $('.nav-primary .menu').slicknav({
    //   label       : '',
    //   prependTo   : '.nav-primary .wrap'
    // });

    $(document).delegate('.ss-tr', 'click', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');
      var text = $(this).data('text');
      var x = parseInt($(window).width()/2) - 300;
      var y = parseInt($(window).height()/2) - 200;
      window.open('https://twitter.com/intent/tweet?&original_referer='+url+'&text='+text+'&tw_p=tweetbutton&url='+url,'Twitter','width=600,height=400,left=0,top='+y);
    });

    $(document).delegate('.ss-fk', 'click', function(e) {
      var href = $(this).prop('href');
      FB.ui({
        method: 'share',
        href: href,
      }, function(response){});
      e.preventDefault();
    });

  });
})(jQuery);
