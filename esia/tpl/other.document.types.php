<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 14.04.2017
 * Time: 10:47
 */

?>

<div class='colwpp col-xs-12' id='div_other'>
    <div class='colwpp col-xs-12'>
        <h4></h4>
    </div>

    <div class='colwpp col-xs-12' id='div_depo_no_yes'>
        <input type="hidden" name="depo_type_ch" value="1">

        <div class='colwpp col-xs-12 b-indent'>
            <p>Выберите способ получения дохода по ценным бумагам</p>
        </div>

        <?
            $DepoDohodChecked = ArrayHelper::GetValuePath( $this->Current, 'depo_dohod_ch' );

            switch ( $DepoDohodChecked )
            {
                case 0: $DepoDohodKind = 'R'; break;
                case 1: $DepoDohodKind = 'B'; break;
                default: $DepoDohodKind = 'B'; break;
            }

            //var_dump($DepoDohodKind);
        ?>

        <div class='colwpp col-xs-12'>
            <div class='colwpp col-xs-6 other_documents other_broker'>
                <div class='clearfix'></div>

                <div class='colwpp col-xs-12'>
                    <input id='depo_dohod1' name='depo_dohod_ch' class='checkbox' type='radio' value='1' <?= $DepoDohodKind == 'B' ? 'checked' : '' ?>>
                    <label for='depo_dohod1'>На брокерский счет, открытый в <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;АО "НФК-Сбережения" (при наличии)</label>
                </div>

                <div class='colwpp col-xs-12 other_documents_form' js-depo-dohod-1 <?= $DepoDohodKind == 'B' ? '' : 'style="display: none;"' ?>>
                    <input type='text' class='form-control' id='other_broker_doc_number' name='other_broker_doc_number' placeholder='№ договора' value="<?= ArrayHelper::Value($arResult, 'other_broker_doc_number') ?>">
                    <input type='text' class='form-control' id='other_broker_doc_date' name='other_broker_doc_date' placeholder='Дата договора' value="<?= ArrayHelper::Value($arResult, 'other_broker_doc_date') ?>">								<table border='1' cellspacing='0'>					<tr>
                    	<u>Банковские реквизиты </u>					</tr>					<tr>						<td class = "td_name">Получатель:</td>						<td colspan="3"> &nbsp АО "НФК-Сбережения"</td>					</tr>					<tr>						<td class = "td_name">Корреспондентский счет: </td>						<td colspan="3"> &nbsp 30105810345250000505</td>					</tr>					<tr>						<td class = "td_name">Расчетный счет:</td>						<td colspan="3"> &nbsp 30414810000000000911</td>					</tr>					<tr>						<td class = "td_name">Наименование банка:</td>						<td colspan="3"> &nbsp НКО НКЦ (АО)</td>					</tr>					<tr>						<td class = "td_name">БИК банка</td>						<td colspan="3"> &nbsp 044525505</td>					</tr>					
										</table>					
                </div>
            </div>

            <div class='colwpp col-xs-6 other_documents other_requisites'>
                <div class='clearfix'></div>

                <div class='colwpp col-xs-12'>
                    <input id='depo_dohod2' name='depo_dohod_ch' class='checkbox' type='radio' value='0' <?= $DepoDohodKind == 'R' ? 'checked' : '' ?>>
                    <label for='depo_dohod2'>По реквизитам<br>&nbsp;</label>
                </div>

                <div class='colwpp col-xs-12 other_documents_form' js-depo-dohod-2 <?= $DepoDohodKind == 'R' ? '' : 'style="display: none;"' ?>>
                    <input type='text' class='form-control' id='schen' name='schet' placeholder='номер счета' value="<?= ArrayHelper::Value($arResult, 'shet') ?>">
                    <input type='text' class='form-control' id='k_schen' name='k_schet' placeholder='номер корр.счета' value="<?= ArrayHelper::Value($arResult, 'k_shet') ?>">
                    <input type='text' class='form-control' id='bank' name='bank' placeholder='банк' value="<?= ArrayHelper::Value($arResult, 'bank') ?>">
                    <input type='text' class='form-control' id='bik' name='bik' placeholder='БИК' value="<?= ArrayHelper::Value($arResult, 'bik') ?>">
                    <input type='text' class='form-control' id='other_requisites_inn' name='other_requisites_inn' placeholder='ИНН' value="<?= ArrayHelper::Value($arResult, 'inn') ?>" maxLength=12>
                    <input type='text' class='form-control' id='other_requisites_other_conditions' name='other_requisites_other_conditions' placeholder='Прочие условия: ' value="<?= ArrayHelper::Value($arResult, 'other_requisites_other_conditions') ?>">
                </div>
            </div>
        </div>
    </div>
</div>

<div class='clearfix'></div>

<script>
    document.addEventListener("DOMContentLoaded", function(event)
    {
        $('#depo_dohod1, #depo_dohod2').on('change', function(e)
        {
            console.warn('depo_dohod1 ~ change')

            var Val = $('#depo_dohod1').is(':checked');

            console.log(Val);

            $('[js-depo-dohod-1]').toggle( Val );
            $('[js-depo-dohod-2]').toggle( !Val );
        });
    });
</script>

<style>
    .b-indent
    {
        margin-top: 16px;
    }

    @media screen and ( min-width: 1024px )
    {
        .other_broker .other_documents_form
        {
            padding-right: 4px;
        }

        .other_requisites .other_documents_form
        {
            padding-left: 4px;
        }
    }
</style>
