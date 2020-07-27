<?php
if(isset($_GET['PAGEN_1']) && $_GET['IS_AJAX'] == 'Y'){
    require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
    $CUser = new CUser();
    $rsUsers = $CUser -> GetList(($by="personal_country"), ($order="desc"), array(), array('FIELDS' => array("ID", "NAME", "LAST_NAME", "SECOND_NAME"), 'NAV_PARAMS' => array("nPageSize"=>"100")));
    $rsUsers->NavStart($arNavParams['nPageSize']);
    while($arUser = $rsUsers ->NavNext(true)){
        $CUser -> Update($arUser['ID'], array("NAME" => mb_convert_case($arUser['NAME'], MB_CASE_TITLE), "LAST_NAME" => mb_convert_case($arUser['LAST_NAME'], MB_CASE_TITLE), "SECOND_NAME" => mb_convert_case($arUser['SECOND_NAME'], MB_CASE_TITLE)));
    }
    echo json_encode("OK");
}else{
?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function timeout(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
        var count = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26];
        async function processArray(array) {
            console.log('start');
            for (const item of array) {
                await Promise.all([
                    ajaxRequest(item),
                    timeout(15000)
                ]);
            }
            console.log('done');
        }
        async function ajaxRequest(item) {
            console.log(item);
            $.ajax({
                url: "/ucfirst.php",
                data: {
                    PAGEN_1: item,
                    IS_AJAX: 'Y'
                },
                dataType: 'json',
                success: function(data, textStatus, jqXHR ){
                    console.log(data);
                    console.log(textStatus);
                    console.log(jqXHR);
                },
                error: function(data, textStatus, jqXHR ){
                    console.log(data);
                    console.log(textStatus);
                    console.log(jqXHR);
                }
            });
        }
        processArray(count);
    </script>
<?}?>