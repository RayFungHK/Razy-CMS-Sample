__(function() {
  var menuopened = false;
  __('#menu').click(function() {
    menuopened = !menuopened;
    __(this).find('a').toggleClass('active');
    __('sidebar').toggleClass('opened');
  });

  __('body').on('touchmove', function(e) {
    if (menuopened) {
      e.preventDefult();
    }
  });
});
