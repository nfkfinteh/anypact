$(document).ready(function() {
    $('#more-info').click(function(){
        $(this).find('span').toggleClass('active-span');
        $('.show-extra-info').slideToggle('fast');
    })
    $('.more-org').click(function(){
        $('.extra-profile-org').toggleClass('hide');
    })
    $('.new-profile-send-post textarea, .reply-form-block textarea').each(function () {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
      }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
      });
})