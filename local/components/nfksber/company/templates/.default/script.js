$(document).ready(function(){
    $(document).on('click', '#search_staff', function(){
      if($('#staff').val().length < 3) return false;
      var staff = $('input[name="STAFF"]').val();
      BX.ajax.post(
          window.location.toString(),
          {
             'ajax_result':'y',
             'staff_email':$('#staff').val(),
             'staff_add': staff
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
});