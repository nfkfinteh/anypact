<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 14.04.2017
 * Time: 11:12
 */

/** @var \EsiaCore $this */

?>
<div class='clearfix'><br/></div>
<div class='colwpp col-xs-12' id='div_iis2'>
	<div class="docs-marg-r">
        <input id='iis' name='iis' class='checkbox' type='checkbox' js-iis-change-handle <?= XChecked($this, 'iis', 1, false ) ?>>


        <label for='iis'>Индивидуальный инвестиционный счет (ИИС)</label>
        <p class="ht30" align="justify">Открыть индивидуальный инвестиционный брокерский счет в понимании статьи 10.2-1
            Федерального закона «О рынке ценных бумаг» № 39-ФЗ от 22.04.1996? <br /><br> Подтверждаю, что ознакомлен со всеми положениями <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/tipovyie_reglamentyi_opredelyayushhie_poryadok_okazaniya_uslug_na_ryinke_czennyix_bumag/'>Регламента брокерского обслуживания</a>, <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/usloviya_osushhestvleniya_depozitarnoj_deyatelnosti/'>Условиями осуществления депозитарной деятельностии</a>, образцами <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/obrazczyi_dogovorov,_predlagaemyie_klientam_pri_predostavlenii_im_uslug/'>договоров</a>, предлагаемых клиентам при предоставлении им услуг АО «НФК-Сбережения» и обязуюсь соблюдать их.</p>
    </div>




    <div class='colwpp col-xs-12' id='div_iis_no_yes'><br>

     <!--   <? require_once $this->DIRRECTORY_ESIA_TPL . 'select.type.iis.php'; ?> -->

        <p>Имеется ли у вас действующий договор "Индивидуального инвестиционного счета"?</p>

        <br>

        <div class='colwpp col-xs-6' id='div_iis_no'>
            <input id='iis1' name='iis_ch' class='checkbox' type='radio' value='not' <?= XChecked($this, 'iis_ch', 0, true ) ?>>
            <label for='iis1'>Договора ИИС не имеется</label>
        </div>

        <div class='colwpp col-xs-6' id='div_iis_yes'>
            <input id='iis2' name='iis_ch' class='checkbox' type='radio' value='yes' <?= XChecked($this, 'iis_ch', 1, false ) ?>>
            <label for='iis2'>Договор ИИС имеется</label>
            <p>В случае, если Вы желаете осуществить перевод активов, укажите наименование профессионального участника
                рынка ценных бумаг, который обязан передать активы</p>
            <div class='colwpp col-xs-12'><input type='text' class='form-control' size='61' id='iis_company'
                                                 name='iis_company' maxLength=80 placeholder='' value="<?=  ArrayHelper::GetValuePath( $this->Detail, 'RESULT/iis_company' ) ?>"></div>
        </div>
		<div class="clearfix"></div>
    </div>
</div>

<div class='clearfix'></div>