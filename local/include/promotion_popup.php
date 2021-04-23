<div class="propmption_popup overflow">
    <div class="block">
        <div class="body">
            <div class="img">
                <img src="<?=SITE_TEMPLATE_PATH?>/image/promo-100-phone.png">
            </div>
            <div class="content">
                <div class="title">
                    Промо-акция<br>от AnyPact
                </div>
                <div class="text">
                    Разместите первое объявление<br>и получите 100 рублей на телефон
                </div>
                <a href="/promotion/" class="button">
                    Подробнее
                </a>
            </div>
        </div>
        <div class="close"></div>
    </div>
</div>
<?
setcookie('PROMOTION_POPUP', date('Ymd'));
$_COOKIE['PROMOTION_POPUP'] = date('Ymd');
$_SESSION['PROMOTION_POPUP'] = date('Ymd');
?>