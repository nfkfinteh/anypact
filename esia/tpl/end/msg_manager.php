<?php
/**
 * Created by PhpStorm.
 *
 * User: Admin
 * Date: 16.04.2018
 * Time: 16:00
 *
 * [ Copyrights ]
 * 2018 Dark-Software
 * WebSite: https://www.dark-software.ru
 * E-Mail: support@dark-software.ru
 */

/** @var \EsiaCore $this */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/HTTPHelper.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/ArrayHelper.class.php';

function XRenderInternalTable($DataX)
{
    foreach ( $DataX as $DataXN => $DataXV )
    {
        ?>
            <tr>
                <td style="width: 200px; padding: 4px; font-weight: bold; padding-left: 0;"><?= $DataXN ?>:</td>
                <td style="padding: 4px; padding-right: 0;"><?= empty( $DataXV ) ? '-' : $DataXV ?></td>
            </tr>
        <?
    }
}

function XRenderInternalCBTable( $Source, $Key, $Value, $Text, $Full = false )
{
    $Res = ArrayHelper::GetValuePath( $Source, $Key );

    $Checked = ( $Res === $Value );

    $Img = ( $Checked ) ? 'cb_img_c_24.png' : 'cb_img_u_24.png';

    if ( $Full )
    {
        ?>
            <tr><td>
        <?
    }

    ?>

        <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td width="30px" style="vertical-align: top;"><img src="<?= HTTPHelper::GenerateUniversalBaseURL() ?>/esia/img/<?= $Img ?>" width="24px" height="24px"></td>
                <td style="vertical-align: top;"><?= $Text ?></td>
            </tr>
        </table>

    <?

    if ( $Full )
    {
        ?>
           </td></tr>
        <?
    }

    return $Checked;
}

function XRenderBR()
{
    ?>
        <tr>
            <td>
                <br>
            </td>
        </tr>
    <?
}

?>

<table style="width: 100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
            <h2>Системная информация:</h2>
        </td>
    </tr>

    <tr>
        <td>
            <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">

                <?
                    $DataX = [
                        'Время' => $CurrentDateTime->format('d.m.Y H:i:s'),
                        'ID' => $PersonID,
                        'CODE' => $PerconCode,
                    ];

                    XRenderInternalTable( $DataX );
                ?>

            </table>
        </td>
    </tr>

    <tr>
        <td>
            <hr>
        </td>
    </tr>
    <tr>
        <td>
            <h2>Данные входной страницы:</h2>
        </td>
    </tr>
    <tr>
        <td>
            <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">

                <?
                    $DataX = [
                        'Электронная почта' => ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_email' ),
                        'Телефон' => ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_phone' ),
                        'ФИО' => ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_name' ),
                        'Населенный пункт' => ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_city' ),
                        'Сообщение' => ArrayHelper::GetValuePath( $this->Preview, $PreviewKey . 'form_text' ),
                    ];

                    XRenderInternalTable( $DataX );

                ?>

            </table>
        </td>
    </tr>
    <tr>
        <td>
            <hr>
        </td>
    </tr>
    <tr>
        <td>
            <h2>Анкетные данные:</h2>
        </td>
    </tr>

    <tr>
        <td>
            <strong style="font-weight: bold">Метод подтверждения данных:</strong> <?= $RES_METHOD ?>
        </td>
    </tr>

    <tr>
        <td>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">
                <?
                    $DataX = [
                        'ФИО' => $RES_FIO,
                        'Телефон' => $arResult['mobile'],
                        'Электронная почта' => $arResult['email'],
                        'Паспорт' => $arResult['pass_seria'] . ' ' . $arResult['pass_number'] . ' выдан ' . $arResult['pass_dv'] . ' ' .$arResult['pass_who'] . ' код подразделения ' . $arResult['pass_kp'],
                        'ИНН' => $arResult['inn'],
                        'СНИЛС' => $arResult['snils'],
                        'Адрес регистрации' => $arResult['reg_adr'],
                        'Адрес почтовый' => $arResult['post_adr'],
                        'Место рождения' => $arResult['birth_place'],
                        'Дата рождения' => $RES_BD,
                        'Гражданство' => $arResult['citizen'],
                    ];

                    XRenderInternalTable( $DataX );

                ?>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <?
                $FakeT = [
                    'terr' => 'Y',
                ];

                $IsTerr = XRenderInternalCBTable(
                    $FakeT,
                    'terr',
                    'Y',
                    'Подтверждаю, что я и мои близкие родственники не являемся лицами, указанными в <a target="_blank" href="http://www.consultant.ru/document/cons_doc_LAW_32834/795290b8b6dc6cc4e65f785ccd81c607e4be507d/">пп.1 п.1 ст. 7.3 Федерального закона от 07.08.2001 N 115-ФЗ</a> \'О противодействии легализации (отмыванию) доходов, полученных преступным путем, и финансированию терроризма\''
                );
            ?>
        </td>
    </tr>

    <tr>
        <td>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <?
                $IsTax = XRenderInternalCBTable(
                        $this->Preview,
                        'agree/is_tax',
                        'N',
                        'Подтверждаю что не имею одновременно с гражданством Российской Федерации гражданство иностранного государства (за исключением гражданства государства - члена Таможенного союза)'
                );
            ?>
        </td>
    </tr>

    <? if ( !$IsTax ) { ?>

        <tr>
            <td>
                <br>
            </td>
        </tr>

        <tr>
            <td>
                <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Страна: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/tax' ) ?></td>
                    </tr>
                </table>
            </td>
        </tr>

    <? } ?>

    <tr>
        <td>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <?
                $IsResidence = XRenderInternalCBTable(
                    $this->Preview,
                    'agree/is_residence',
                    'N',
                    'Подтверждаю, что не имею вид на жительство в иностранном государстве'
                );
            ?>
        </td>
    </tr>

    <? if ( !$IsResidence ) { ?>

        <tr>
            <td>
                <br>
            </td>
        </tr>

        <tr>
            <td>
                <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Страна: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/residence' ) ?></td>
                    </tr>
                </table>
            </td>
        </tr>

    <? } ?>

    <? if ( !$IsTax || !$IsResidence ) { ?>

        <tr>
            <td>
                <br>
            </td>
        </tr>

        <tr>
            <td>
                <?
                $IsResidenceInfo = XRenderInternalCBTable(
                    $this->Preview,
                    'agree/is_residence_info',
                    'Y',
                    'Согласен согласие на передачу информации в иностранный налоговый орган и (или) иностранным налоговым агентам, уполномоченным иностранным налоговым органом. (данная информация может быть необходима при расчете и исчислении налогов, связанных с операциями на финансовом рынке)'
                );
                ?>
            </td>
        </tr>

    <? } ?>

    <tr>
        <td>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <?
                $IsNalogResidence = XRenderInternalCBTable(
                    $this->Preview,
                    'agree/a1',
                    'N',
                    'Подтверждаю, что не являюсь налоговым резидентом иностранных государств (территорий)'
                );
            ?>
        </td>
    </tr>

    <? if ( !$IsNalogResidence ) { ?>

        <tr>
            <td>
                <br>
            </td>
        </tr>

        <tr>
            <td>
                <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Государство: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a2' ) ?></td>
                    </tr>
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Иностранный идентификационный номер налогоплательщика: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a3' ) ?></td>
                    </tr>
                </table>
            </td>
        </tr>

    <? } ?>

    <tr>
        <td>
            <br>
        </td>
    </tr>

    <tr>
        <td>
            <?
                $IsNalogResidenceBeneficiary = XRenderInternalCBTable(
                    $this->Preview,
                    'agree/a4',
                    'N',
                    'Подтверждаю, что не имею выгодоприобретателей, являющихся налоговыми резидентами иностранных государств (территорий)'
                );
            ?>
        </td>
    </tr>

    <? if ( !$IsNalogResidenceBeneficiary ) { ?>

        <tr>
            <td>
                <br>
            </td>
        </tr>

        <tr>
            <td>
                <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">ФИО: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a7' ) ?></td>
                    </tr>
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Дата и место рождения: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a8' ) ?></td>
                    </tr>
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Адрес: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a9' ) ?></td>
                    </tr>
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Государство: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a5' ) ?></td>
                    </tr>
                    <tr>
                        <td width="30px" style="vertical-align: top;"></td>
                        <td style="vertical-align: top;">Иностранный идентификационный номер налогоплательщика: <?= ArrayHelper::GetValuePath( $this->Preview, 'agree/a6' ) ?></td>
                    </tr>
                </table>
            </td>
        </tr>

    <? } ?>

    <tr>
        <td>
            <hr>
        </td>
    </tr>

    <tr>
        <td>
            <h2>Коды подтверждения:</h2>
        </td>
    </tr>

    <tr>
        <td>
            <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="width: 30%">Согласие</td>
                    <td style="width: 40%"><?= ArrayHelper::Value( $arResult, 'SMS_CODE_CONFIRM_1' ) ?></td>
                    <td style="width: 40%"><?= ArrayHelper::Value( $arResult, 'date_kod_out' ) ?></td>
                </tr>
                <tr>
                    <td style="width: 30%">Подпись</td>
                    <td style="width: 40%"><?= ArrayHelper::Value( $arResult, 'SMS_CODE_CONFIRM_2' ) ?></td>
                    <td style="width: 40%"><?= ArrayHelper::Value( $arResult, 'date_kod_out1' ) ?></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td>
            <hr>
        </td>
    </tr>

    <tr>
        <td>
            <h2>Выбранные секторы обслуживания:</h2>
        </td>
    </tr>

    <?
        $IsMarketStock = XRenderInternalCBTable(
            $this->Detail,
            'RESULT/market_stock',
            1,
            'Рынок ценных бумаг',
            true
        );

//        XRenderBR();
//
//        $IsMarketOutStock = XRenderInternalCBTable(
//            $this->Detail,
//            'RESULT/market_outstock',
//            1,
//            'Внебиржевой рынок',
//            true
//        );

        XRenderBR();

        $IsMarketValuta = XRenderInternalCBTable(
            $this->Detail,
            'RESULT/market_valuta',
            1,
            'Валютный рынок и рынок драгметаллов',
            true
        );

        XRenderBR();

        $IsMarketOther = XRenderInternalCBTable(
            $this->Detail,
            'RESULT/market_other',
            1,
            'Иное (для желающих заключить дополнительный депозитарный договор)',
            true
        );

        if ( $IsMarketOther )
        {
            XRenderBR();

            ?>
                <tr>
                    <td>
                        <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="30px" style="vertical-align: top;"></td>
                                <td style="vertical-align: top;">

                                    <?
                                        $DepoTypeCH = ArrayHelper::GetValuePath( $this->Detail, 'RESULT/depo_dohod_ch' );

                                        if ( $DepoTypeCH === 1 )
                                        {
                                            ?>
                                                Доход переводить на брокерский счет:

                                                <br>
                                                <br>

                                                <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">

                                                <?
                                                    $DepoTypeDetail = [
                                                        'Номер договора' => 'RESULT/other_broker_doc_number',
                                                        'Дата договора' => 'RESULT/other_broker_doc_date',
                                                    ];

                                                    foreach ( $DepoTypeDetail as $DepoTypeDetailK => $DepoTypeDetailV )
                                                    {
                                                        ?>
                                                            <tr>
                                                                <td><?= $DepoTypeDetailK ?>:</td>
                                                                <td><?= ArrayHelper::GetValuePath( $this->Detail, $DepoTypeDetailV, '-' ) ?></td>
                                                            </tr>
                                                        <?
                                                    }
                                                ?>

                                                </table>
                                            <?
                                        }
                                        else
                                        {
                                            ?>
                                                Доход переводить по реквизитам:

                                                <br>
                                                <br>

                                                <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">

                                                    <?
                                                        $DepoTypeDetail = [
                                                            'Номер счета' => 'RESULT/shet',
                                                            'Корр. счет' => 'RESULT/k_shet',
                                                            'Банк' => 'RESULT/bank',
                                                            'БИК' => 'RESULT/bik',
                                                            'ИНН' => 'RESULT/other_requisites_inn',
                                                            'Прочие условия' => 'RESULT/other_requisites_other_conditions',
                                                        ];

                                                        foreach ( $DepoTypeDetail as $DepoTypeDetailK => $DepoTypeDetailV )
                                                        {
                                                            ?>
                                                                <tr>
                                                                    <td><?= $DepoTypeDetailK ?>:</td>
                                                                    <td><?= ArrayHelper::GetValuePath( $this->Detail, $DepoTypeDetailV, '-' ) ?></td>
                                                                </tr>
                                                            <?
                                                        }
                                                    ?>

                                                </table>
                                            <?
                                        }
                                    ?>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?
        }

        XRenderBR();

        $IsIIS = XRenderInternalCBTable(
            $this->Detail,
            'RESULT/iis',
            1,
            'Индивидуальный инвестиционный счет (ИИС)',
            true
        );

        if ( $IsIIS )
        {
            XRenderBR();

            ?>
                <tr>
                    <td>
                        <table style="width: 100% vertical-align: top;" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td width="30px" style="vertical-align: top;"></td>
                                <td style="vertical-align: top;">
                                    <?
                                        $IsIISHave = ArrayHelper::GetValuePath( $this->Detail, 'RESULT/iis_ch' );
                                        $IsIISCompany = ArrayHelper::GetValuePath( $this->Detail, 'RESULT/iis_company' );

                                        if ( $IsIISHave === 1 )
                                        {
                                            ?>Договор ИИС открыт в компании: `<?= $IsIISCompany ?>`<?
                                        }
                                        else
                                        {
                                            ?>Договора ИИС не имеется<?
                                        }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?
        }

    ?>

    <tr>
        <td>
            <hr>
        </td>
    </tr>

    <tr>
        <td>
            <h2>Сформированные документы:</h2>
        </td>
    </tr>

    <?
        $DocX = ArrayHelper::GetValuePath( $this->Detail, 'RESULT/PDF_DOCUMENTS_LIST', [] );
        $DocXG = ArrayHelper::Value( $DocX, 'GROUPS' );
        $DocXD = ArrayHelper::Value( $DocX, 'DOCS' );

        foreach ( $DocXD as $Group => $DocXL )
        {
            $GroupName = ArrayHelper::Value( $DocXG, $Group );

            if ( !empty( $GroupName ) )
            {
                ?>
                    <tr>
                        <td>
                            <hr>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <h3><?= $GroupName ?></h3>
                        </td>
                    </tr>
                <?
            }

            ?>
                <tr>
                    <td>
                        <table style="width: 100%" cellpadding="0" cellspacing="0" border="0">
                            <?
                                foreach ( $DocXL as $DocXLI => $DocXLD )
                                {
                                    ?>
                                        <tr>
                                            <td style="vertical-align: top; width: 10%"><?= $DocXLI+1 ?></td>
                                            <td style="vertical-align: top; width: 75%"><?= $DocXLD['NAME'] ?></td>
                                            <td style="vertical-align: top; width: 15%"><a href="<?= $DocXLD['PATH'] ?>" target="_blank">Скачать</a></td>
                                        </tr>
                                    <?
                                }
                            ?>
                        </table>
                    </td>
                </tr>
            <?
        }
    ?>


</table>
