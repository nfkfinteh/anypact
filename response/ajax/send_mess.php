<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

Loader::includeModule("highloadblock");

$data = json_decode($_POST['checkin'], true);

foreach ($data as $key=>&$value){
    $value = htmlspecialcharsEx($value);
}

$hlbl = 10;
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();

// Массив полей для добавления
$data = array(
    "UF_DATA"=>date("d.m.Y H:i:s"),
    "UF_FIO"=>$data['FIO'],
    "UF_EMAIL"=>$data['IMAIL'],
    "UF_TEXT"=>$data['TEXT']
);

$result = $entity_data_class::add($data);
// возвращаем id записи
if (!$result->isSuccess()) {
    //$result = $result->getErrorMessages();
    $result = 'ERROR';
} else {
    //отправка сообщения
    $send_data = array(
        'FIO'=>$data['UF_FIO'],
        'EMAIL'=>$data['UF_EMAIL'],
        'TEXT'=> $data['UF_TEXT']
    );
    CEvent::Send("NEW_FEEDBACK", "s1", $send_data);

    $result = $result->getId();
}
echo $result;
