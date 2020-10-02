<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("AnyPact || Помощь");
$APPLICATION->SetPageProperty("description", "Сервис AnyPact позволяет заключать широкий круг различных договоров (сделок), для которых законодательством предусмотрена простая письменная форма. Это могут быть договоры купли-продажи движимого имущества, включая автомобили, договоры аренды недвижимости и найма жилья, договоры займа, договоры на оказание различных видов услуг и т.п.");
?>
<?
global $USER;
$arUser = array();
if ($USER->IsAuthorized()) {
    $arUser = \Bitrix\Main\UserTable::getRow([
        'filter' => [
            'ID' => $USER->GetID(),
        ],
        'select' => ['ID','UF_ESIA_AUT']
    ]);
    if ($arUser['UF_ESIA_AUT']) {
        $urlRedirect = '/my_pacts/edit_my_pact/?ACTION=ADD';
        LocalRedirect($urlRedirect);
    }
}

?>
</div>
<!-- Описание сервиса -->
<div class="container content-first-help">
    <div class="row">
        <div class="col-md-4 img-first-help hidden-xs">
            <img src="/upload/static/first-help-colored.png" alt="Помощь">
        </div>
        <div class="col-md-8 col-sm-8">
            <? if (empty($arUser)): ?>
                <p class='first-title'>Авторизуйтесь, чтобы разместить объявление</p>
                <div class='line-block'></div>
                <p class='sec-title'>или зарегистрируйтесь и пройдите проверку через портал Госуслуг</p>
                <div class="block-btn-reg">
                    <button class="btn btn-nfk btn-login" id="open_reg_form" onclick="ym(64629523,'reachGoal','reg_btn');">Регистрация</button>
                </div>
            <? else: ?>
                <div>Для размещения предложения необходимо <a target="__blank" href="/profile/#aut_esia">подтвердить свой аккаунт с помощью учетной записи портала Госуслуг</a></div>
            <? endif; ?>
            <p class="last-text-first-help">AnyPact – это бесплатный сервис для публикаций объявлений и дистанционного заключения сделок через Интернет. Сторонами договоров могут быть как физические, так и юридические лица. Для достоверной идентификации участников сделок используется надежный и уже получивший широкое распространение по всей стране сервис Госуслуг. С помощью нашей платформы Вы находите контрагента, сами участвуете в формировании условий договора, а затем приобретаете права и обязанности в рамках его исполнения.</p>
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>