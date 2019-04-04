<?  require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    include_once('class/send_contract.php');

    // необходимые классы
    use Bitrix\Highloadblock as HL;
    use Bitrix\Main\Entity;

    CModule::IncludeModule('highloadblock');
    if(!CModule::IncludeModule("iblock")) return;
    define("FORMAT_DATETIME", "DD.MM.YYYY HH:MI:SS");
    $id_contract    = 19; //$_POST['id'];
    $id_contragent  = 1; //$_POST['contr'];
    $sms_code       = 77777; //$_POST['smscode'];
    $hash_Send      = md5($id_contract.$id_contragent.$sms_code); 

    // проверяем авторизацию пользователя
    $status_pact = new sendsms();
    //print_r($status_pact->get_status_all_pact(3));

    /*
    $arFilter = Array(
        Array(
           "LOGIC"=>"OR",
           Array(
            "UF_ID_USER_A"=> $UserID
           ),
           Array(
            "UF_ID_USER_B" => $UserID
           )
        )
     );
   */
  $arFilter = Array(
    Array(
        'UF_HASH_SEND' => 'bed449bc63d4b0fe9c4729c4ac283dec'
       )

 );
  print_r($status_pact->get_item_filter(3, $arFilter));

?>