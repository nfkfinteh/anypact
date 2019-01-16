<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 17.04.2017
 * Time: 13:23
 */

$phone = PhoneHelper::FormatPhone( $arResult['mobile'] );
$code = $arResult['SMS_CODE_CONFIRM_2'];
$time = DateHelper::GetDateString( $arResult['date_kod_out1'], 'H:i' );

?>

<div class='colwpp col-xs-12' style='margin-top:20px;'>
    <div class='colwpl col-xs-8 finish_block'>
        <p align='justify'>
            <span class = "finish_title">Поздравляем!</span><br/>
            Вы подписали комплект документов для открытия счета в АО «НФК-Сбережения» электронной подписью с номера телефона <?= $phone ?> и кодом подтверждения <?= $code ?> в <?= $time ?> UTC+3:00.<br/>
            Получение Вами письма с подтверждением открытия счета по электронной почте с адреса <u>openaccount@nfksber.ru</u>
            будет означать подписание соответствующих документов со стороны АО «НФК-Сбережения».
            На процесс обработки документов может уйти до двух рабочих дней.
        </p>

        <p><a class='submit btn btn_green btn-md btn_green-fix' href='http://nfksber.ru/'>Вернуться на Главную</a></p>
    </div>

    <div style="clear: both"></div>
</div>

<style>
    #link_instruction
    {
        display: none;
    }
</style>
