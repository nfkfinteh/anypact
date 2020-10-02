function newAnyPactPopUp(arData){
    var pu_id = "newAnyPactPopUp"+generateStr(5);
    function closeNewAnyPactPopUp(){
        $('#'+pu_id).parent('.new-pu-overflow').remove();
        if($('.new-pu-overflow').length < 1)
            $('body').css("overflow", "auto");
    }    
    if(arData.TITLE.length < 1) return false;
    var html = '<div class="new-pu-overflow"><div class="new-anypact-popup" id="'+pu_id+'">';
    html += '<div class="new-pu-title-block"><div class="new-pu-title">'+arData.TITLE+'</div></div>';
    html += '</div></div>';
    var newDiv = document.createElement("div");
    $(newDiv).addClass('new-pu-x-button');
    $(newDiv).attr('title', 'Закрыть');
    newDiv.onclick = (closeNewAnyPactPopUp);
    $('body').append(html);
    $('body').css("overflow", "hidden");
    $('#'+pu_id+' .new-pu-title-block').append(newDiv);
    if(arData.BODY.length > 0){
        var newDiv = document.createElement("div");
        $(newDiv).addClass('new-pu-body');
        var body = $(arData.BODY).clone(true);
        $(newDiv).append(body);
        $('#'+pu_id).append(newDiv);
    }
    if(arData.BUTTONS.length > 0){
        $('#'+pu_id).append('<div class="new-pu-buttons-block"><div class="new-pu-buttons"><table cellspacing="0" cellpadding="0"><tbody><tr></tr></tbody></table></div></div>');
        for(var i in arData.BUTTONS){
            if(arData.BUTTONS[i].NAME.length > 0 && (typeof (arData.BUTTONS[i].CALLBACK) === "function"  || arData.BUTTONS[i].SECONDARY == "Y" || arData.BUTTONS[i].CLOSE == "Y")){
                var newBtn = document.createElement("button");
                $(newBtn).text(arData.BUTTONS[i].NAME);
                $(newBtn).addClass('flat_button');
                if(arData.BUTTONS[i].SECONDARY == "Y")
                    $(newBtn).addClass('secondary');
                if(typeof (arData.BUTTONS[i].CALLBACK) === "function")
                    $(newBtn).click(arData.BUTTONS[i].CALLBACK);
                if(arData.BUTTONS[i].CLOSE == "Y")
                    newBtn.onclick = (closeNewAnyPactPopUp);
                var newTd = document.createElement("td");
                $(newTd).append(newBtn);
                $('#'+pu_id+' .new-pu-buttons-block .new-pu-buttons table tr').append(newTd);
            }
        }
    }
    $('#'+pu_id).parent('.new-pu-overflow').on('click', function(event){
        if ($(event.target).closest("#"+pu_id).length) return;
        if ($(event.target).closest(".delete-img").length) return;
        if ($(event.target).closest(".name-allow-change").length) return;
        if ($(event.target).closest(".user-delete").length) return;
        closeNewAnyPactPopUp();
    });
    if(typeof (arData.ONLOAD) === "function"){
        arData.ONLOAD();
    }
}

function generateStr(length = 8) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
       result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}