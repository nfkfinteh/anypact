function loadMorePacts(STATE_SDEL, callback, additionalURL = "", IDs = Array()) {
    $.ajax({
        url: UPPA_component.ajaxUrl + additionalURL,
        method: 'POST',
        dataType: 'html',
        data: {
            via_ajax: 'Y',
            action: 'loadPacts',
            sessid: BX.bitrix_sessid(),
            SITE_ID: UPPA_component.siteID,
            signedParamsString: UPPA_component.signedParamsString,
            STATE_SDEL: STATE_SDEL,
            DEL_ID: IDs
        }
    }).done(function(result){
        if (result.length > 0){
            callback(result);
        }
    });
}

function loadAllPacts(){
    if($('.new-profile_block .btn-category.active').length > 0 && $('.new-profile_block .tenders__row .extra-hide-offers').find('.tender-post').length < 1){
        preload('show');
        var STATE_SDEL = $('.new-profile_block .btn-category.active').attr('data-state');
        var IDs = [];
        $('.new-profile_block .tender-block').find('.tender-post').each(function(){
            IDs.push($(this).data('id'));
        });
        loadMorePacts(STATE_SDEL, (function(result){
            $result = $.parseHTML(result);
            $('.new-profile_block .tenders__row .extra-hide-offers').html($result);
            preload('hide');
        }), "?SHOWALL_2=1", IDs);
    }
}

function morePosts(){
    $('.more-info-link').find('span').toggleClass('active-span');
    $('.extra-hide-offers').toggleClass('show-extra-offers');
}

$(document).ready(function(){

    if($('.new-profile_block .btn-category').length > 0){
        $('.new-profile_block .btn-category').click(function(){
            if(!$(this).hasClass('active')){
                preload('show');
                var el = this;
                var STATE_SDEL = $(el).attr('data-state');
                loadMorePacts(STATE_SDEL, (function(result){
                    $result = $.parseHTML(result);
                    $($result).find('.more-info-link').click(loadAllPacts);
                    $($result).find('.more-info-link').click(morePosts);
                    $('.new-profile_block .tenders__row').html($result);
                    $('.new-profile_block .btn-category.active').removeClass('active');
                    $(el).addClass('active');
                    preload('hide');
                }), "?SHOWALL_2=0");
            }
        });
    }

    if($('.new-profile_block .tenders__row .more-info-link').length > 0)
        $('.new-profile_block .tenders__row .more-info-link').click(loadAllPacts);

    $('.more-info-link').click(morePosts);

});