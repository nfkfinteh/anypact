function isset(obj) {
    if (typeof (obj) !== 'undefined') return true;
    return false;
}

function parse_url() {
    var url = { host: '', args: {} };

    var args = window.location.href.replace(/#.*/g, '').split('?');
    var params = isset(args[1]) ? args[1].split('&') : [];
    url['host'] = args[0];

    for (var i = 0; i < params.length; i++) {
        args = params[i].split('=');
        key = args[0];
        val = isset(args[1]) ? args[1] : '';

        if (key == '' || val == '') continue;

        if (isset(url['args'][key])) {
            if (typeof url['args'][key] == 'string') {
                url['args'][key] = [url['args'][key], val];
            } else {
                url['args'][key].push(val);
            }
        } else {
            url['args'][key] = val;
        }
    }

    return url;
}

function redirect(values) {
    if (!isset(values)) values = {};
    url = parse_url();

    for (var key in values) {
        if (values[key] == '') {
            delete url['args'][key];
        } else {
            url['args'][key] = values[key];
        }
    }

    args = [];
    for (var key in url['args']) {
        if (typeof url['args'][key] == 'string') {
            args.push(key + '=' + encodeURIComponent(url['args'][key]));
        } else {
            for (var i = 0; i < url['args'][key].length; i++) {
                args.push(key + '=' + encodeURIComponent(url['args'][key]));
            }
        }
    }

    url = args.length > 0 ? url['host'] + '?' + args.join('&') : url['host'];
    window.location.href = url;
}

$(document).ready(function(){
    var deletLine;
    $(document).on('click', '.modal_deleteItem', function(){
        let idItem = $(this).attr('data-id');
        deletLine = $(this).parents('tr').eq(0);
        $('.deleteItem').attr('data-id', idItem);
    });
    $(document).on('click', '.deleteItem', function(e){
        e.preventDefault();
        let url = '/response/ajax/delete_item.php';
        let idItem = $(this).attr('data-id');
        let data = {
            id: idItem
        };

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result){
                $result = JSON.parse(result);
                if($result['TYPE']=='ERROR'){
                    console.log($result['VALUE']);
                    alert($result['VALUE']);
                }
                if($result['TYPE']=='SUCCESS'){
                   $('.deleteItem-modal_close').click();
                   $(deletLine).remove();

                }
            },

        });
    });

    $('.deal-sort').on('change', function(){
        var order = $(this).children('option:selected').attr('data-order');
        var sort = $(this).val();
        redirect({ sort: sort, order: order });
    });

});