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
$mess = "Со страницы /help/ отправлено сообщение \n";
$mess .= "ФИО:".$data['FIO']." \n";
$mess .= "email:".$data['FIO']." \n";
$mess .= "сообщение:".$data['FIO']." \n";
mail('info@anypact.ru', 'Сообщение с контактной формы', $mess);
// возвращаем id записи
if (!$result->isSuccess()) {
    //$result = $result->getErrorMessages();
    $result = 'ERROR';
} else {
    $result = $result->getId();
}
echo $result;
