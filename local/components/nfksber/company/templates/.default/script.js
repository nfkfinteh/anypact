$(document).ready(function(){
    $(document).on('click', '#search_staff', function(){
      if($('#staff').val().length < 3) return false;
      var staff = $('input[name="STAFF"]').val();
      var staff_add_no_active = $('input[name="STAFF_NO_ACTIVE"]').val();
      BX.ajax.post(
          window.location.toString(),
          {
             'ajax_result':'y',
             'staff_email':$('#staff').val(),
             'staff_add': staff,
             'staff_add_no_active': staff_add_no_active
          },
          function(result)
          {
             if(BX.type.isString(result))
             {
               $('.search-result').replaceWith(result);
             }
          }
      );
    });
    $(document).on('click', '.add_staff', function(){
        var id = $(this).parent().attr('data-id');
        var staff = $('input[name="STAFF"]').val();
        if($(this).hasClass('staff_znak-ok')){
            if(staff.indexOf(' '+id+',') != -1) staff = staff.replace(' '+id+',', '');
            $('input[name="STAFF"]').val(staff);
            $(this).removeClass('staff_znak-ok');
        }else {
            if (staff.indexOf(' ' + id + ',') == -1) staff += ' ' + id + ',';
            $('input[name="STAFF"]').val(staff);
            $(this).addClass('staff_znak-ok');
        }
    });

    $(document).on('click', '.add_staff__no-active', function(){
        var id = $(this).parent().attr('data-id');
        var staff = $('input[name="STAFF_NO_ACTIVE"]').val();
        if($(this).hasClass('staff_znak-ok')){
            if(staff.indexOf(' '+id+',') != -1) staff = staff.replace(' '+id+',', '');
            $('input[name="STAFF_NO_ACTIVE"]').val(staff);
            $(this).removeClass('staff_znak-ok');
        }else {
            if (staff.indexOf(' ' + id + ',') == -1) staff += ' ' + id + ',';
            $('input[name="STAFF_NO_ACTIVE"]').val(staff);
            $(this).addClass('staff_znak-ok');
        }
    });

    $('input[name="PREVIEW_PICTURE"]').on('change', function () {
        var files = this.files;
        if(files.length){
            for (var i = 0; i < files.length; i++) {
                console.log(files[i]);
                preview(files[i]);
            }
        }
    });
    function preview(file) {
        var reader = new FileReader();

        reader.addEventListener('load', function(e) {
            let slide = '<img class="company-logo" src="'+e.target.result+'">';

            $('#preview-picture label').after($(slide));
        });
        reader.readAsDataURL(file);
    };

    function validateNumber(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode( key );
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }
});