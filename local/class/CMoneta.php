<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER['DOCUMENT_ROOT']."/local/php_interface/libraries/moneta-sdk-lib/autoload.php");

class CMoneta {

    const MONETA_ASYNC_CALLBACK_URL = 'https://anypact.ru/local/scripts/monetaCheckAsync.php?check_code=3dyvhfangy8b5R84EOGFADHI';

    private function getOperationCategoryName($category){
        $arCategory = array(
            "DEPOSIT" => "Пополнение",
            "WITHDRAWAL" => "Вывод",
            "TRANSFER" => "Перевод",
            "BUSINESS" => "Оплата",
        );
        return $arCategory[$category];
    }

    private function GetEntityDataClass($HlBlockId) {
        if (empty($HlBlockId) || $HlBlockId < 1)
        {
            return false;
        }
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($HlBlockId)->fetch();   
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    function pvtMonetaCreateAttribute($key, $value)
    {
        $monetaAtribute = new \Moneta\Types\KeyValueAttribute();
        $monetaAtribute->key = $key;
        $monetaAtribute->value = $value;

        return $monetaAtribute;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function getOperationStatusName($status){
        $arStatus = array(
            "SUCCEED" => "Выполнен",
            "INPROGRESS" => "Выполняется",
            "TAKENIN_NOTSENT" => "Зачислен",
            "CREATED" => "Создан",
            "CANCELED" => "Отменен",
            "FROZEN" => "Замарожен",
            "TAKENOUT" => "Списан",
            'WRONG' => 'Счет не найден или не верные данные'
        );
        return $arStatus[$status];
    }

    function formateDate($date, $format = 'Y-m-d H:i:s'){
        if(empty($date)) return '';
        $d = new DateTime($date);
        return $d->format($format);
    }

    function addHLOperation($accountID, $category, $amount, $operationId = ''){

        \Bitrix\Main\Loader::includeModule('highloadblock');

        global $USER;
        $amount = number_format($amount, 2, '.', '');

        $entity_data_class = self::GetEntityDataClass(MONETA_OPERATION_HLB_ID);
        $date = date("d.m.Y H:i:s");
        $result = $entity_data_class::add(array(
            "UF_USER_ID" => $USER -> GetID(),
            "UF_ACCOUNT_ID" => $accountID,
            "UF_CATEGORY" => $category,
            "UF_STATUS" => "CREATED",
            "UF_DATE_CREATE" => $date,
            "UF_DATE_MODIFY" => $date,
            "UF_AMOUNT" => $amount,
            "UF_OPERATION_ID" => $operationId
        ));
        $operation_id = $result->getId();

        return $operation_id;
    }

    function updateHLOperation($id, $status, $operationId = ''){

        \Bitrix\Main\Loader::includeModule('highloadblock');

        $date = date("d.m.Y H:i:s");

        $arFields = array(
            'UF_STATUS' => $status, 
            "UF_DATE_MODIFY" => $date
        );

        if(!empty($operationId))
            $arFields['UF_OPERATION_ID'] = $operationId;

        $entity_data_class = self::GetEntityDataClass(MONETA_OPERATION_HLB_ID);
        $entity_data_class::update($id, array("UF_OPERATION_ID" => $operationId));

    }

    function registerProfile($arData){

        $unitID = 0;
        $accountID = 0;
        $documentId = 0;

        if($arData['MONETA_UNIT_ID'] > 0)
            $unitID = $arData['MONETA_UNIT_ID'];

        if($arData['MONETA_ACCOUNT_ID'] > 0)
            $accountID = $arData['MONETA_ACCOUNT_ID'];
            
        if($arData['UF_MONETA_DOC_ID'] > 0)
            $documentId = $arData['UF_MONETA_DOC_ID'];

        if($arData['ESIA_AUT'] != 1 || empty($arData['ETAG_ESIA']) || intval($arData['ESIA_ID']) < 1)
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "esia_auth", "ERROR_DESCRIPTION" => "Ваш аккаунт не был подтвержден через Госуслуги");
        if(strlen($arData['NAME']) < 2)
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_name", "ERROR_DESCRIPTION" => "В вашем профиле отсутсвует имя");
        if(strlen($arData['LAST_NAME']) < 2)
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "empty_last_name", "ERROR_DESCRIPTION" => "В вашем профиле отсутсвует фамилия");
        if(!filter_var($arData['EMAIL'], FILTER_VALIDATE_EMAIL))
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "wrong_email", "ERROR_DESCRIPTION" => "В вашем профиле неверный e-mail. Пример: your-email@mail.com");
        if(!is_numeric($arData['SPASSPORT']) || strlen($arData['SPASSPORT']) != 4 || !is_numeric($arData['NPASSPORT']) || strlen($arData['NPASSPORT']) != 6 || !self::validateDate($arData['DATA_PASSPORT'], 'd.m.Y') || strlen($arData['KEM_VPASSPORT']) < 11 || !is_numeric(str_replace("-", "", $arData['DEPARTMENT'])) || strlen($arData['DEPARTMENT']) != 7)
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "wrong_pass_data", "ERROR_DESCRIPTION" => "Неверные паспортные данные");
        // if(!is_numeric($arData['PAYMENT_PASS']) || strlen($arData['PAYMENT_PASS']) < 5)
        //     return array("STATUS" => "ERROR", "ERROR_TYPE" => "wrong_pass_data", "ERROR_DESCRIPTION" => "Поле Платежный пароль должен состоять только из цифр, минимум из пяти");
        // if($arData['PAYMENT_PASS'] != $arData['PAYMENT_PASS_REPEAT'])
        //     return array("STATUS" => "ERROR", "ERROR_TYPE" => "wrong_pass_data", "ERROR_DESCRIPTION" => "Платежные пароли не совпадают");

        $adress = "";
        if(!empty($arData['REGION'])) $adress .= $arData['REGION']." район";
        if(!empty($adress)) $adress .= ", ";
        if(!empty($arData['STREET'])) $adress .= "ул. ".$arData['STREET'];
        if(!empty($adress)) $adress .= ", ";
        if(!empty($arData['N_HOUSE'])) $adress .= "дом ".$arData['N_HOUSE'];
        if(!empty($adress)) $adress .= ", ";
        if(!empty($arData['N_HOUSING'])) $adress .= "копус ".$arData['N_HOUSING'];
        if(!empty($adress)) $adress .= ", ";
        if(!empty($arData['N_APARTMENT'])) $adress .= "кв. ".$arData['N_APARTMENT'];

        $phone = str_replace(array(" ", "(", ")", "-"), "", $arData['PHONE']);

        if(strlen($phone) == 11)
            $phone = "7".substr($phone, 1);
        else if(strlen($phone) == 10)
            $phone = "7".$phone;
        else
            $phone = "";

        // $paymentPassword = $arData['PAYMENT_PASS'];

        $arFields['last_name'] = $arData['LAST_NAME'];
        $arFields['first_name'] = $arData['NAME'];
        $arFields['middle_initial_name'] = $arData['SECOND_NAME'];
        $arFields['country'] = (GetCountryByID($arData['PERSONAL_COUNTRY'], "ru") == NULL ? "" : GetCountryByID($arData['PERSONAL_COUNTRY'], "ru"));
        $arFields['state'] = $arData['PERSONAL_STATE'];
        $arFields['city'] = $arData['PERSONAL_CITY'];
        $arFields['zip'] = $arData['PERSONAL_ZIP'];
        $arFields['address'] = $adress;
        $arFields['email_for_notifications'] = $arData['EMAIL'];
        $arFields['phone'] = $phone;
        $arFields['cell_phone'] = $phone;
        // $arFields['sex'] = ($arData['PERSONAL_GENDER'] == "M" ? "MALE" : "FEMALE");
        // $arFields['date_of_birth'] = self::formateDate($arData['PERSONAL_BIRTHDAY_DATE'], 'Y-m-d');
        $arFields['inn'] = $arData['INN'];
        $arFields['snils'] = $arData['SNILS'];
        $arFields['ui_language'] = "RU";

        $arFields['pass_data']['SERIES'] = $arData['SPASSPORT'];
        $arFields['pass_data']['NUMBER'] = $arData['NPASSPORT'];
        $arFields['pass_data']['ISSUED'] = self::formateDate($arData['DATA_PASSPORT'], 'Y-m-d');
        $arFields['pass_data']['ISSUER'] = $arData['KEM_VPASSPORT'];
        $arFields['pass_data']['DEPARTMENT'] = $arData['DEPARTMENT'];

        $CUser = new CUser;

        try {
            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            if($unitID == 0){

                $CreateProfileRequest = new \Moneta\Types\CreateProfileRequest();
                // группа "анонимные пользователи"
                $CreateProfileRequest->unitId = 14264576;
                // Ид из Битрикса
                // $CreateProfileRequest->profileId = $arData['ID'];
                // тип профайла: физлица
                $CreateProfileRequest->profileType = \Moneta\Types\ProfileType::client;

                $profile = new \Moneta\Types\Profile();

                foreach($arFields as $key => $value){
                    if($key == 'pass_data') continue;
                    $attribute = new \Moneta\Types\KeyValueApprovedAttribute();
                    $attribute->approved = false;
                    $attribute->key = $key;
                    $attribute->value = $value;
                    $profile->addAttribute($attribute);
                }

                $CreateProfileRequest->profile = $profile;

                // создать нового пользователя
                $unitID = $monetaSdk->monetaService->CreateProfile($CreateProfileRequest);
                
                $CUser -> Update($arData['ID'], array('UF_MONETA_UNIT_ID' => $unitID));

            }

            if($unitID > 0){

                if($accountID == 0){
                    $CreateAccountRequest = new \Moneta\Types\CreateAccountRequest();

                    $CreateAccountRequest->type = 2;
                    $CreateAccountRequest->currency = "RUB";
                    // $CreateAccountRequest->paymentPasswordType = "STATIC";
                    // $CreateAccountRequest->paymentPassword = $paymentPassword;
                    $CreateAccountRequest->paymentPasswordExpirationDate = true;

                    $CreateAccountRequest->unitId = $unitID;

                    $CreateAccountRequest->prototypeAccountId = "21306526";

                    $CreateAccountRequest->onSuccessfulDebitUrl = "https://anypact.ru/profile/wallet/?SuccessfulDebit=Y";
                    $CreateAccountRequest->onSuccessfulCreditUrl = "https://anypact.ru/profile/wallet/?SuccessfulCredit=Y";
                    $CreateAccountRequest->onCancelledDebitUrl = "https://anypact.ru/profile/wallet/?CancelledDebit=Y";
                    $CreateAccountRequest->onCancelledCreditUrl = "https://anypact.ru/profile/wallet/?CancelledCredit=Y";
                    $CreateAccountRequest->onAuthoriseUrl = "https://anypact.ru/profile/wallet/?Authorise=Y";

                    $accountID = $monetaSdk->monetaService->CreateAccount($CreateAccountRequest);

                    $CUser -> Update($arData['ID'], array('UF_MONETA_ACCOUNT_ID' => $accountID));

                }
                if($accountID > 0){

                    if($documentId == 0){
                        $CreateProfileDocumentRequest = new \Moneta\Types\CreateProfileDocumentRequest();
                        $CreateProfileDocumentRequest -> unitId = $unitID;

                        $CreateProfileDocumentRequest -> type = \Moneta\Types\DocumentType::PASSPORT;

                        foreach($arFields['pass_data'] as $key => $value){
                            $attribute = new \Moneta\Types\KeyValueApprovedAttribute();
                            $attribute->approved = false;
                            $attribute->key = $key;
                            $attribute->value = $value;
                            $CreateProfileDocumentRequest->addAttribute($attribute);
                        }

                        $documentId = (array)$monetaSdk->monetaService->CreateProfileDocument($CreateProfileDocumentRequest);

                        $CUser -> Update($arData['ID'], array('UF_MONETA_DOC_ID' => $documentId['id']));

                        $documentId = $documentId['id'];
                    }
                    if($documentId > 0){
                        $confimPhone = self::sendConfimSMS($unitID);
                    }
                }
            }
            if(!empty($confimPhone)){
                return $confimPhone;
            }else{
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => "У вас уже есть аккаунт на Moneta");
            }
        } catch (Exception $e) {
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }
    }

    function sendConfimSMS($unitID){
        try {
            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();
            $ApprovePhoneSendConfirmationRequest = new \Moneta\Types\ApprovePhoneSendConfirmationRequest();

            $ApprovePhoneSendConfirmationRequest -> unitId = $unitID;
            $ApprovePhoneSendConfirmationRequest -> text = 'Kod podtverzhdeniya na AnyPact.ru: {CODE}';
            $res = (array)$monetaSdk->monetaService->ApprovePhoneSendConfirmation($ApprovePhoneSendConfirmationRequest);

            if(!empty($res['phoneNumber']))
                return array("STATUS" => 'SUCCESS', 'DATA' => $res['phoneNumber']);
            else
                return array("STATUS" => 'ERROR', 'ERROR_MESSAGE' => 'Не удалось отправить SMS. Повторите позднее');
        }
        catch (Exception $e) {
            if($e->getMessage() == "Телефон уже подтвержден")
                return self::profileIdentification($unitID);
            else
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_MESSAGE" => $e->getMessage());
        }
    }

    function SMSCodeApply($unitID, $code, $profileIdentification = false){

        try {

            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $ApprovePhoneApplyCodeRequest = new \Moneta\Types\ApprovePhoneApplyCodeRequest();

            $ApprovePhoneApplyCodeRequest -> unitId = $unitID;
            $ApprovePhoneApplyCodeRequest -> confirmationCode = $code;
            $monetaSdk->monetaService->ApprovePhoneApplyCode($ApprovePhoneApplyCodeRequest);

            if($profileIdentification)
                return self::profileIdentification($unitID);
            else
                return array("STATUS" => "SUCCESS");
        }
        catch (Exception $e) {
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }

    }

    function profileIdentification($unitID){

        try {

            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $SimplifiedIdentificationRequest = new \Moneta\Types\SimplifiedIdentificationRequest();
            $SimplifiedIdentificationRequest->unitId = $unitID;

            $AsyncRequest = new \Moneta\Types\AsyncRequest();
            $AsyncRequest->callbackUrl = self::MONETA_ASYNC_CALLBACK_URL;

            $AsyncRequest -> SimplifiedIdentificationRequest = $SimplifiedIdentificationRequest;

            $response = (array)$monetaSdk->monetaService->Async($AsyncRequest);

            global $USER;
            $CUser = new CUser;
            $CUser -> Update($USER -> GetID(), array('UF_MONETA_CHECKOP_ID' => $response['asyncId'], 'UF_MONETA_CHECK_STAT' => $response['asyncStatus']));

            return array("STATUS" => "SUCCESS");
        }
        catch (Exception $e) {
            if($e->getMessage() == 'Пользователь уже имеет статус "Упрощённая идентификация"'){
                global $USER;
                $CUser = new CUser;
                $CUser -> Update($USER -> GetID(), array('UF_MONETA_CHECKOP_ID' => $response['id'], 'UF_MONETA_CHECK_STAT' => 'SUCCESS'));
    
                return array("STATUS" => "PROFILE_CHECKED");
            }else
                return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }

    }

    function checkAsync($asyncId){
        try {

            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $AsyncRequest = new \Moneta\Types\AsyncRequest();
            $AsyncRequest->asyncId = $asyncId;

            $response = $monetaSdk->monetaService->Async($AsyncRequest);

            $response = json_decode(json_encode($response), 1);

            if(isset($response['SimplifiedIdentificationResponse'])){
                $CUser = new CUser;
                if($response['SimplifiedIdentificationResponse']['success'] == true)
                    $status = 'SUCCESS';
                else{
                    $status = 'FAILED';
                    $error_mess = $response['SimplifiedIdentificationResponse']['error'];
                }

                foreach($response['SimplifiedIdentificationResponse']['personalInformation']['profile']['attribute'] as $value){
                    if($value['key'] == 'unitid'){
                        $unitId = $value['value'];
                        break;
                    }
                }

                $res = CUser::GetList(($by="personal_country"), ($order="desc"), array('UF_MONETA_CHECKOP_ID' => $asyncId, 'UF_MONETA_UNIT_ID' => $unitId), array('FIELDS' => array('ID'), 'SELECT' => array('UF_MONETA_UNIT_ID', 'UF_MONETA_CHECKOP_ID')));
                if($arUser = $res -> getNext()){
                    if($arUser['UF_MONETA_UNIT_ID'] == $unitId && $arUser['UF_MONETA_CHECKOP_ID'] == $asyncId){
                        $CUser -> Update($arUser['ID'], array('UF_MONETA_CHECK_STAT' => $status, 'UF_IDENT_ERROR_MESS' => $error_mess));
                    }
                }
            }

            return true;

        }catch (Exception $e) {
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }
    }

    function getBalance($accountID) {
        try {
            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();
    
            $response = $monetaSdk->showAccountBalance($accountID);
    
            return array("STATUS" => "SUCCESS", "DATA" => $response->data['balance']);
        }
        catch (Exception $e) {
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }
    }

    function getHistory($unitID, $accountID, $dateFrom, $dateTo, $page = 1){

        try {
            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();
    
            $FindOperationsListRequest = new \Moneta\Types\FindOperationsListRequest();
            $FindOperationsListRequestFilter = new \Moneta\Types\FindOperationsListRequestFilter();
    
            $FindOperationsListRequestFilter -> unitId = $unitID;
            $FindOperationsListRequestFilter -> accountId = $accountID;
            $FindOperationsListRequestFilter -> dateFrom = $dateFrom;
            $FindOperationsListRequestFilter -> dateTo = $dateTo;
    
            $FindOperationsListRequest -> pager = array("pageSize" => 10, "pageNumber" => $page);
            $FindOperationsListRequest -> filter = $FindOperationsListRequestFilter;
    
            $response = $monetaSdk->monetaService->FindOperationsList($FindOperationsListRequest);

            $response = json_decode(json_encode($response), 1);
            if(!isset($response['operation'][0])){
                $array = $response['operation'];
                unset($response['operation']);
                $response['operation'][0] = $array;
            }

            foreach($response['operation'] as $key => $value){
                if(is_array($value['attribute']) && !empty($value['id'])){
                    $acc = "";
                    $cat = "";
                    $arItems[$key]['ID'] = $value['id'];
                    foreach($value['attribute'] as $attr){
                        if($attr['key'] == 'modified'){
                            $date = new DateTime($attr['value']);
                            $arItems[$key]['DATE'] = $date -> format('d.m.Y');
                        }
                        if($attr['key'] == 'category'){
                            $arItems[$key]['CATEGORY'] = self::getOperationCategoryName($attr['value']);
                            $cat = $attr['value'];
                            if($acc == 317 && $cat == 'BUSINESS')
                                $arItems[$key]['CATEGORY'] = self::getOperationCategoryName('DEPOSIT');
                        }
                        if($attr['key'] == 'statusid'){
                            $arItems[$key]['STATUS'] = self::getOperationStatusName($attr['value']);
                        }
                        if($attr['key'] == 'sourceamounttotal'){
                            $arItems[$key]['AMOUNT'] = $attr['value'];
                        }
                        if($attr['key'] == 'targetaccountid'){
                            $acc = $attr['value'];
                            if($cat == 'BUSINESS' && $acc == 317)
                                $arItems[$key]['CATEGORY'] = self::getOperationCategoryName('DEPOSIT');
                        }
                    }
                }
            }

            $response['ITEMS'] = $arItems;
    
            return array("STATUS" => "SUCCESS", "DATA" => $response);
           
        }
        catch (Exception $e) {
            return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }

    }

    function makeDeposit($accountID, $amount){

        $opId = self::addHLOperation($accountID, "DEPOSIT", $amount);

        try {
            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $invoiceRequest = new \Moneta\Types\InvoiceRequest();

            $invoiceRequest->payer = 317;

            $invoiceRequest->payee = $accountID;
            $invoiceRequest->amount = $amount;
            $invoiceRequest->clientTransaction = $opId;

            $invoiceResponse = $monetaSdk->monetaService->Invoice($invoiceRequest);

            if (is_object($invoiceResponse)) {
                $transactionId = $invoiceResponse->transaction;
            } else if (is_array($invoiceResponse) && isset($invoiceResponse['transaction'])) {
                $transactionId = $invoiceResponse['transaction'];
            }

            // $res = $monetaSdk->sdkMonetaCreateInvoice(317, $accountID, $amount, $opId);

            self::updateHLOperation($opId, "SUCCESS", $transactionId);

            return array("STATUS" => "SUCCESS", "DATA" => $transactionId);
        }
        catch (Exception $e) {

            self::updateHLOperation($opId, "ERROR");

            return array("STATUS" => "ERROR", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }
    }

    function makeWithdrawal($accountID, $paymentPass, $serviceId, $amount, $cartNumber){

        $opId = self::addHLOperation($accountID, "WITHDRAWAL", $amount);
        
        try {

            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $attributes = array('PAYEECARDNUMBER' => $cartNumber);

            $amount = number_format($amount, 2, '.', '');
            $monetaTransaction = new \Moneta\Types\PaymentRequest();
            $monetaTransaction->payer = $accountID;
            if ($paymentPass) {
                $monetaTransaction->paymentPassword = $paymentPass;
            }
            $monetaTransaction->payee = $serviceId;
            $monetaTransaction->amount = $amount;
            // $monetaTransaction->description = $description;
            $monetaTransaction->isPayerAmount = true;
            $monetaTransaction->version = "VERSION_2";
            if (is_array($attributes) && count($attributes)) {
                $operationInfo = new \Moneta\Types\OperationInfo();
                foreach ($attributes AS $key => $value) {
                    $operationInfo->addAttribute(self::pvtMonetaCreateAttribute($key, $value));
                }
                $attributesInParameters = $attributes;
                unset($attributesInParameters['SECURETOKEN']);
                unset($attributesInParameters['PAYMENTTOKEN']);
                unset($attributesInParameters['PAYEECARDNUMBER']);
                unset($attributesInParameters['CARDNUMBER']);
                unset($attributesInParameters['CARDEXPIRATION']);
                unset($attributesInParameters['CARDCVV2']);
                $operationInfo->addAttribute(self::pvtMonetaCreateAttribute('customurlparameters', http_build_query($attributesInParameters)));
                $monetaTransaction->operationInfo = $operationInfo;
            }
            if ($opId) {
                $monetaTransaction->clientTransaction = $opId;
            }
    
            $response = $monetaSdk->monetaService->Payment($monetaTransaction);

            $response = json_decode(json_encode($response), 1);

            if (!$response['id']) {
                self::updateHLOperation($opId, "ERROR");
                throw new Exception(print_r($response, true));
            }
        
            foreach ($response['attribute'] as $key => $attribute) {
                if ('statusid' == $attribute['key']) {
                    self::updateHLOperation($opId, $attribute['value'], $response['id']);
                    return array("STATUS" => $attribute['value'], "DATA" => $response['id']);
                    break;
                }
            }

        }
        catch (Exception $e) {
            self::updateHLOperation($opId, "ERROR");
            return array("STATUS" => "WRONG", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }
    }

    function makeTransfer($accountID, $paymentPass, $sendID, $amount){

        $opId = self::addHLOperation($accountID, "TRANSFER", $amount);

        try {

            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $PaymentRequest = new \Moneta\Types\VerifyPaymentRequest();

            $PaymentRequest -> payer = $accountID;
            $PaymentRequest -> payee = $sendID;
            $PaymentRequest -> amount = number_format($amount, 2, '.', '');
            $PaymentRequest -> isPayerAmount = true;
            // $PaymentRequest -> paymentPassword = $paymentPass;
            $PaymentRequest -> paymentPasswordChallenge = true;
            $PaymentRequest -> clientTransaction = $opId;

            $response = (array)$monetaSdk->monetaService->VerifyPayment($PaymentRequest);
            
            if($response['isTransactionValid'] === true){
                $PaymentRequest = new \Moneta\Types\PaymentRequest();

                $PaymentRequest -> payer = $accountID;
                $PaymentRequest -> payee = $sendID;
                $PaymentRequest -> amount = number_format($amount, 2, '.', '');
                $PaymentRequest -> isPayerAmount = true;
                // $PaymentRequest -> paymentPassword = $paymentPass;
                $PaymentRequest -> paymentPasswordChallenge = true;
                $PaymentRequest -> clientTransaction = $opId;
    
                $response = $monetaSdk->monetaService->Payment($PaymentRequest);

                $response = json_decode(json_encode($response), 1);
    
                if (!$response['id']) {
                    self::updateHLOperation($opId, "ERROR");
                    throw new Exception(print_r($response, true));
                }
            
                foreach ($response['attribute'] as $key => $attribute) {
                    if ('statusid' == $attribute['key']) {
                        self::updateHLOperation($opId, $attribute['value'], $response['id']);
                        return array("STATUS" => $attribute['value'], "DATA" => $response['id']);
                        break;
                    }
                }
            }else{
                return array("STATUS" => "WRONG", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $response['description']);
            }

            
        }
        catch (Exception $e) {
            self::updateHLOperation($opId, "ERROR");
            return array("STATUS" => "WRONG", "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }
    }

    function getOperationStatus($accountID, $operationId){

        try {

            $monetaSdk = new \Moneta\MonetaSdk($_SERVER['DOCUMENT_ROOT'].'/block/moneta config/');
            $monetaSdk->checkMonetaServiceConnection();

            $response = $monetaSdk->monetaService->GetOperationDetailsById($operationId);

            $response = json_decode(json_encode($response), 1);

            foreach($response['operation']['attribute'] as $value){
                if($value['key'] == 'sourceaccountid'){
                    if($value['value'] == $accountID){
                        $is_acc = "Y";
                    }
                }
                if($value['key'] == 'statusid'){
                    $status = $value['value'];
                }
                if($value['key'] == 'clienttransaction'){
                    $clientTransactionId = $value['value'];
                }
            }
            if($is_acc == "Y"){
                return array('STATUS' => $status, 'CLIENT_TRANSACTION_ID' => $clientTransactionId);
            }
            
            return array('STATUS' => 'WRONG', 'CLIENT_TRANSACTION_ID' => 0);
        }
        catch (Exception $e) {
            return array("STATUS" => "WRONG", 'CLIENT_TRANSACTION_ID' => 0, "ERROR_TYPE" => "moneta_error", "ERROR_DESCRIPTION" => $e->getMessage());
        }

    }

}
?>