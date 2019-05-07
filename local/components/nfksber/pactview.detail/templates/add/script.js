$(document).ready(function() {
    $('#choice_category li a').on('click', function() {
        let selected_item = $(this);
        let selected_item_text = selected_item.text();
        console.log(selected_item_text);
        $('.select_category').css('display', 'none');
        $('#param_selected_category').html(selected_item_text + '<span class="glyphicon glyphicon-chevron-down"></span>');
        return false;
    });

    $('#param_selected_category').on('click', function() {

        $('.select_category').css({ 'display': 'table', 'height': '0px' });
        $('.select_category').animate({ 'height': '100%' }, 500);
    });

    save_ad
    $('#save_ad').on('click', function() {

        var res = getURLData().then(function(data) {
            console.log(data);
            alert(data)
                /*let box = document.getElementById('inner')
                box.innerHTML = data*/
        })


        //var text = ;
        async function getURLData() {
            var url = '/response/ajax/add_new_ad.php';
            const response = await fetch(url, {
                method: 'post',
                body: JSON.stringify({
                    name: 'test',
                    detail_text: 'тест'
                })
            })
            const data = await response.text()
            return data
        }

    });
});