function removeElement(arr, sElement) {
    var tmp = new Array();
    for (var i = 0; i < arr.length; i++)
        if (arr[i] != sElement) tmp[tmp.length] = arr[i];
    arr = null;
    arr = new Array();
    for (var i = 0; i < tmp.length; i++) arr[i] = tmp[i];
    tmp = null;
    return arr;
}

function SectionClick(id) {
    var div = document.getElementById('user_div_' + id);
    if (div.className == "profile-block-hidden") {
        opened_sections[opened_sections.length] = id;
    } else {
        opened_sections = removeElement(opened_sections, id);
    }

    document.cookie = cookie_prefix + "_user_profile_open=" + opened_sections.join(",") + "; expires=Thu, 31 Dec 2020 23:59:59 GMT; path=/;";
    div.className = div.className == 'profile-block-hidden' ? 'profile-block-shown' : 'profile-block-hidden';
}

$(document).ready(function() {
    // рамка редактирования фото
    $('.user_profile_form_editdata_foto').mouseover(function() {
        $('#edit_user_photo').css('top', '0');
    });
    $('.user_profile_form_editdata_foto').mouseleave(function() {
        $('#edit_user_photo').css('top', '168px');
    });


    $(document).on('submit', '.edit-profile', function(e){
        e.preventDefault();
        let that = $(this);

        var res = getURLData(that).then(function(data) {

            $result = JSON.parse(data);

            if($result['TYPE']=='ERROR'){
                alert($result['VALUE']);
            }
            if($result['TYPE']=='SUCCES'){
                location.reload();
            }

        });

    });

    //добавление изображения
    $('.user_profile_form_editdata_foto img').on( 'click', function( event ){
        event.preventDefault();
        $('#filePicture').click();
    });

    $('#filePicture').on('change', function () {
        var file = this.files;
        preview(file[0]);
    });

    // Создание превью
    function preview(file) {
        var reader = new FileReader();
        reader.addEventListener('load', function(e) {
            let img = $('.user_profile_form_editdata_foto').children('img');
            if(img.length == 2){
                img.eq(0).attr('src', e.target.result);
            }
            else if(img.length == 1){
                img.eq(0).attr('src', e.target.result);
                let editImg = document.createElement('img');
                editImg.setAttribute('src', '/local/templates/anypact//img/edit_user_photo.png');
                $(editImg).appendTo('.user_profile_form_editdata_foto');
            }
        });
        reader.readAsDataURL(file);
    }


    async function getURLData(that) {
        var url =  that.attr('action');
        var id = that.attr('id');

        var formData = new FormData(that[0]);

        const response = await fetch(url, {
            method: 'post',
            body:formData
        });
        const data = await response.text();
        return data
    }

    $(document).on('change', '#hide_profile', function(){
        let input = $('.hide_profile_input');
        if($(this).prop('checked')){
            input.val(1);
        }
        else{
            input.val(0);
        }
    });
});