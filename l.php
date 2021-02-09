<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

if($USER->IsAdmin()){
    include_once($_SERVER['DOCUMENT_ROOT']."/local/php_interface/libraries/moneta-sdk-lib-master/autoload.php");

    $monetaSdk = new \Moneta\MonetaSdk();
    $monetaSdk->checkMonetaServiceConnection();

    $request = new \Moneta\Types\CreateProfileRequest();
    // группа "анонимные пользователи"
    //$request->unitId = ********;
    // тип профайла: физлица
    $request->profileType = \Moneta\Types\ProfileType::client;

    $profile = new \Moneta\Types\Profile();

    // имя
    $attribute = new \Moneta\Types\KeyValueApprovedAttribute();
    $attribute->approved = false;
    $attribute->key = "first_name";
    $attribute->value = "Test";
    $profile->addAttribute($attribute);

    // фамилия
    $attribute = new \Moneta\Types\KeyValueApprovedAttribute();
    $attribute->approved = false;
    $attribute->key = "last_name";
    $attribute->value = "Test";
    $profile->addAttribute($attribute);

    // email
    $attribute = new \Moneta\Types\KeyValueApprovedAttribute();
    $attribute->approved = true;
    $attribute->key = "email_for_notifications";
    $attribute->value = "Test@test.tes";
    $profile->addAttribute($attribute);

    $request->profile = $profile;

    // создать нового пользователя
    $result = $monetaSdk->monetaService->CreateProfile($request);

    echo "unit ID нового пользователя:<br/>";
    print_r($result);
    echo "<br/>";
}
?>