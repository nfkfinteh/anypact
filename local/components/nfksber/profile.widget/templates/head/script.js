$(document).ready(function () {
   $('.widget_user_profile_name').click(function () {
      $(this).toggleClass('widget_user_profile_name-active');
      $('.widget_user_profile_select').toggleClass('widget_user_profile_select-active');
   });
});