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


    //валидация для простых полей формы
    $("#form__company_profile").validate({
        rules: {
            INN: {
                minlength: 12,
            },
        },
        messages: {
            INN: 'Данные введены не полностью',
        },
        ignore: ".ignore-validate, :hidden",
        onsubmit: false,
        showErrors: function(errorMap, errorList) {
            let that = this.lastActive;

            if(errorList.length>0){
                let messaage = errorList[0].message;
                if(!$(that).hasClass('validate-error')){
                    $(that).addClass('validate-error');
                    $(that).before('<label class="error-message">'+messaage+'</label>');
                }
            }
            else{
                $(that).removeClass('validate-error');
                $(that).prev('.error-message').remove();
            }
        }
    });
});