$(document).ready(function(){
    $(document).on('click', '.js-auth_company', function(e){
        e.preventDefault();
        let idCompany = $(this).attr('data-id');

        $.post(
            "/response/ajax/select_profile.php", {
                id_element: idCompany,
                action: 'company'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            let result = JSON.parse(data);
            if(result['TYPE']=='ERROR'){
                showResult('#popup-error','Ошибка сохранения', result['VALUE']);
            }
            if(result['TYPE']=='SUCCESS'){
                location.href = '/';
            }
        }
    });

    $(document).on('click', '.js-auth_user', function(e){
        e.preventDefault();

        $.post(
            "/response/ajax/select_profile.php", {
                action: 'user'
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            console.log(data);
            let result = JSON.parse(data);
            if(result['TYPE']=='ERROR'){
                showResult('#popup-error','Ошибка сохранения', result['VALUE']);
            }
            if(result['TYPE']=='SUCCESS'){
                location.href = '/';
            }
        }
    });
});