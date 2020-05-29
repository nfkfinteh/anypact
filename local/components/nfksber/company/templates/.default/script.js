$(document).ready(function(){
   /* $(document).on('click', '#search_staff', function(){
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
    });*/

    $(document).on('click', '.js-addphoto', function(e){
        e.preventDefault();
        console.log('wtf');
        let href = $(this).attr('href'),
            form = '';

        $('#form__company_profile').find('input').each(function(){
            let that = $(this);
            if(that.attr('type') != 'hidden' && that.val().length>0){
                form += that.attr('name') + '=' + that.val();
            }
        });

        if(form.length>0) href += '&' + form;

        location.href = href;
    });


    //валидация для простых полей формы
    $("#form__company_profile").validate({
        rules: {
            NAME: {
                required: true
            },
            INN: {
                required: true,
                minlength: 10
            },
            OGRN:{
                required: true,
                minlength: 13
            },
            KPP:{
                required: true,
                minlength: 9
            },
            INDEX:{
                required: true,
                minlength: 6
            },
            CITY:{
                required: true
            },
            STREET:{
                required: true
            },
            HOUSE:{
                required: true
            },
            BANK:{
                required: true
            },
            RAS_ACCOUNT:{
                required: true,
                minlength: 20
            },
            INN_BANK:{
                required: true,
                minlength: 10
            },
            BIK:{
                required: true,
                minlength: 9
            },
            KOR_ACCOUNT:{
                required: true,
                minlength: 20
            },
            REGION:{
                required: true
            }
        },
        messages: {
            NAME: 'Данные введены не полностью',
            INN: 'Данные введены не полностью',
            OGRN: 'Данные введены не полностью',
            KPP: 'Данные введены не полностью',
            INDEX: 'Данные введены не полностью',
            CITY: 'Данные введены не полностью',
            STREET: 'Данные введены не полностью',
            HOUSE: 'Данные введены не полностью',
            BANK: 'Данные введены не полностью',
            RAS_ACCOUNT: 'Данные введены не полностью',
            INN_BANK: 'Данные введены не полностью',
            BIK: 'Данные введены не полностью',
            KOR_ACCOUNT: 'Данные введены не полностью',
            REGION: 'Данные введены не полностью'
        },
        ignore: ".ignore-validate, :hidden",
        onsubmit: true,
        showErrors: function(errorMap, errorList) {
            let that = this.lastActive;

            if(errorList.length>0){
                for (let i = 0; i < errorList.length; i++){
                    let messaage = errorList[i].message;
                    if(!$(errorList[i].element).hasClass('validate-error')){
                        $(errorList[i].element).addClass('validate-error');
                        $(errorList[i].element).before('<label class="error-message">'+messaage+'</label>');
                    }
                }
            }
            else{
                $(that).removeClass('validate-error');
                $(that).prev('.error-message').remove();
            }
        }
    });
});