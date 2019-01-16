<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 7:10
 */

/** @var \EsiaCore $this */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/DateHelper.class.php';

$arResult = &$this->Detail['RESULT'];

$IS_SMEV = ( ArrayHelper::Value( $arResult, 'IS_SMEV' ) === 'Y' );

if ( $IS_SMEV )
{
    $this->Detail['RESULT'] = array_merge( $this->Detail['RESULT'], $_REQUEST );

    $ID_PERSON = ArrayHelper::Value( $_SESSION, 'id_person' );

    $this->Detail['RESULT']['id_person'] = $ID_PERSON;

    $this->Save();

    $arResult = &$this->Detail['RESULT'];
}

//else
//{
    $arResult['id_esia']=7;

    if (isset($arResult['id_esia']) and  $arResult['id_esia']>0)
    {

        $PassportType = ArrayHelper::Value( $_REQUEST, 'type_doc' );

        switch ( $PassportType )
        {
            case 'RF_PASSPORT':
                {
                    $PassportModel = [
                        'snils' => [
                            'SOURCE' => 'snils',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    11,
                                ],
                                'XModelPregReplace' => [
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1-$2-$3 $4',
                                ],
                            ],
                        ],
                        'inn' => [
                            'SOURCE' => 'inn',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    12,
                                ],
                            ],
                        ],
                        'pass_number' => [
                            'SOURCE' => 'pass_number',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    6,
                                ],
                            ],
                        ],
                        'pass_seria' => [
                            'SOURCE' => 'pass_seria',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    4,
                                ],
                            ],
                        ],
                        'pass_date_v' => [
                            'SOURCE' => 'pass_dv',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'xDate2MySQLDate' => [],
                            ],
                        ],
                        'pass_who' => [
                            'SOURCE' => 'pass_who',
                            'CALLS' => [
                                'XModelTrim' => [],
                            ],
                        ],
                        'pass_code' => [
                            'SOURCE' => 'pass_kp',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    6,
                                ],
                                'XModelPregReplace' => [
                                    '/(\d{3})(\d{3})/',
                                    '$1-$2',
                                ],
                            ],
                        ],
                    ];
                }
                break;

            case 'UA_PASSPORT':
                {
                    $PassportModel = [
                        'snils' => [
                            'SOURCE' => 'snils',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    11,
                                ],
                                'XModelPregReplace' => [
                                    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                                    '$1-$2-$3 $4',
                                ],
                            ],
                        ],
                        'inn' => [
                            'SOURCE' => 'inn',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    12,
                                ],
                            ],
                        ],
                        'pass_number' => [
                            'SOURCE' => 'pass_number',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    6,
                                ],
                            ],
                        ],
                        'pass_seria' => [
                            'SOURCE' => 'pass_seria',
                            'CALLS' => [
                                'XModelTrim' => [],
                            ],
                        ],
                        'pass_date_v' => [
                            'SOURCE' => 'pass_dv',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'xDate2MySQLDate' => [],
                            ],
                        ],
                        'pass_who' => [
                            'SOURCE' => 'pass_who',
                            'CALLS' => [
                                'XModelTrim' => [],
                            ],
                        ],
                        'pass_code' => [
                            'SOURCE' => 'pass_kp',
                            'CALLS' => [
                                'XModelTrim' => [],
                                'XModelExtractDigits' => [
                                    6,
                                ],
                                'XModelPregReplace' => [
                                    '/(\d{3})(\d{3})/',
                                    '$1-$2',
                                ],
                            ],
                        ],
                    ];
                }
                break;
        }

        $PassportModelRes = XModel( $arResult, $PassportModel );

        $PassportModelSQL = XMySQLUpdateSimple( $modx, 'persons', $PassportModelRes, $arResult['id_person'] );

        $PassportModelSQLQR = $modx->query( $PassportModelSQL );

        $arFrom = array(',', '"', "'",'нет', 'Нет', 'НЕТ' );
        $arTo = array(" ", "", "","", "", "");
        $arFrom = array("(", ")", "+");
        $arTo = array("", "", "");

        $arResult['mobile'] = PhoneHelper::FormatPhone( str_replace($arFrom, $arTo, $_REQUEST['phone']), '+7$1$2$3$4' );
        $arResult['email'] = $_REQUEST['email'];
        $ind_reg=str_replace($arFrom, $arTo, trim($_REQUEST['ind_reg']));
        $oblast=str_replace($arFrom, $arTo, trim($_REQUEST['oblast']));
        $raion=str_replace($arFrom, $arTo, trim($_REQUEST['raion']));
        $city=str_replace($arFrom, $arTo, trim($_REQUEST['city']));
        $street=str_replace($arFrom, $arTo, trim($_REQUEST['street']));
        $house=str_replace($arFrom, $arTo, trim($_REQUEST['house']));
        $korp=str_replace($arFrom, $arTo, trim($_REQUEST['korp']));
        $flat=str_replace($arFrom, $arTo, trim($_REQUEST['flat']));

        if ( $_REQUEST['is_adress_fact_same'] == 'Y' )
        {
            $ind_reg_post = $ind_reg;
            $oblast_post = $oblast;
            $raion_post = $raion;
            $city_post = $city;
            $street_post = $street;
            $house_post = $house;
            $korp_post = $korp;
            $flat_post = $flat;
        }
        else
        {
            $ind_reg_post=str_replace($arFrom, $arTo, trim($_REQUEST['ind_reg_post']));
            $oblast_post=str_replace($arFrom, $arTo, trim($_REQUEST['oblast_post']));
            $raion_post=str_replace($arFrom, $arTo, trim($_REQUEST['raion_post']));
            $city_post=str_replace($arFrom, $arTo, trim($_REQUEST['city_post']));
            $street_post=str_replace($arFrom, $arTo, trim($_REQUEST['street_post']));
            $house_post=str_replace($arFrom, $arTo, trim($_REQUEST['house_post']));
            $korp_post=str_replace($arFrom, $arTo, trim($_REQUEST['korp_post']));
            $flat_post=str_replace($arFrom, $arTo, trim($_REQUEST['flat_post']));
        }

        $arResult['inn']=trim($_REQUEST['inn']);

        if (isset($_REQUEST['birth_day']))
        {
            $q_date = DateHelper::GetDateString( $_REQUEST['birth_day'], DateHelper::Y_m_d );

            //$q=explode(".", $_REQUEST['birth_day']);
            //$q_date=$q[2]."-".$q[1]."-".$q[0];
            //$arResult['birth_day']=$q_date;

            $BDQ = "UPDATE persons SET b_date='$q_date' WHERE id='$arResult[id_person]'";
            $modx->query( $BDQ );
        }

        $mobile_in = $modx->quote( $arResult['mobile'] );
        $email_in = $modx->quote( $arResult['email'] );

        $SX = "UPDATE persons SET phone_in=$mobile_in, email_in=$email_in WHERE id='$arResult[id_person]'";

        $XX = $modx->query( $SX );

        Logger::AddText([
            'ind_reg' => '`' . $ind_reg . '`',
            'oblast' => '`' . $oblast . '`',
            'raion' => '`' . $raion . '`',
            'city' => '`' . $city . '`',
            'street' => '`' . $street . '`',
            'house' => '`' . $house . '`',
            'korp' => '`' . $korp . '`',
            'flat' => '`' . $flat . '`',
            'ind_reg_post' => '`' . $ind_reg_post . '`',
            'oblast_post' => '`' . $oblast_post . '`',
            'raion_post' => '`' . $raion_post . '`',
            'city_post' => '`' . $city_post . '`',
            'street_post' => '`' . $street_post . '`',
            'house_post' => '`' . $house_post . '`',
            'korp_post' => '`' . $korp_post . '`',
            'flat_post' => '`' . $flat_post . '`',
        ], 'ESIA/AddrParts');

        Logger::AddText($arResult, 'ESIA/AddrParts');

        $reg_addr_parts = [
            $ind_reg,
            $oblast,
            $raion,
            $city,
            $street,
            $house,
            $korp,
            $flat,
        ];

        $reg_addr_parts_fixed = [];

        foreach ( $reg_addr_parts as $reg_addr_part )
        {
            $reg_addr_part_old = $reg_addr_part;

            $reg_addr_part = trim($reg_addr_part);

            $reg_addr_part_low = strtolower( $reg_addr_part );

            $reg_addr_part_add = true;

            if ( $reg_addr_part_low == '' )
            {
                $reg_addr_part_add = 'EMPTY';
            }

            if ( $reg_addr_part_low == 'нет' )
            {
                $reg_addr_part_add = 'NO';
            }

            Logger::AddText([
                'OLD' => '`' . $reg_addr_part_old . '`',
                'PART' => '`' . $reg_addr_part . '`',
                'LOW' => '`' . $reg_addr_part_low . '`',
                'ADD' => '`' . $reg_addr_part_add . '`',
            ], 'ESIA/AddrParts');

            if ( $reg_addr_part_add !== true )
            {
                continue;
            }

            $reg_addr_parts_fixed[] = $reg_addr_part;
        }

        $reg_addr_parts_string = implode( ', ', $reg_addr_parts_fixed );

        Logger::AddText([
            '$reg_addr_parts' => $reg_addr_parts,
            '$reg_addr_parts_fixed' => $reg_addr_parts_fixed,
            '$reg_addr_parts_string' => $reg_addr_parts_string,
        ], 'ESIA/AddrParts');

        $post_addr_parts = [
            $ind_reg_post,
            $oblast_post,
            $raion_post,
            $city_post,
            $street_post,
            $house_post,
            $korp_post,
            $flat_post,
        ];

        $post_addr_parts_fixed = [];

        foreach ( $post_addr_parts as $post_addr_part )
        {
            $post_addr_part_old = $post_addr_part;

            $post_addr_part = trim($post_addr_part);

            $post_addr_part_low = strtolower( $post_addr_part );

            $post_addr_part_add = true;

            if ( $post_addr_part_low == '' )
            {
                $post_addr_part_add = 'EMPTY';
            }

            if ( $post_addr_part_low == 'нет' )
            {
                $post_addr_part_add = 'NO';
            }

            Logger::AddText([
                'OLD' => '`' . $post_addr_part_old . '`',
                'PART' => '`' . $post_addr_part . '`',
                'LOW' => '`' . $post_addr_part_low . '`',
                'ADD' => '`' . $post_addr_part_add . '`',
            ], 'ESIA/AddrParts');

            if ( $post_addr_part_add !== true )
            {
                continue;
            }

            $post_addr_parts_fixed[] = $post_addr_part;
        }

        $post_addr_parts_string = implode( ', ', $post_addr_parts_fixed );

        Logger::AddText([
            '$post_addr_parts' => $post_addr_parts,
            '$post_addr_parts_fixed' => $post_addr_parts_fixed,
            '$post_addr_parts_string' => $post_addr_parts_string,
        ], 'ESIA/AddrParts');

        //$arResult['reg_adr']=$ind_reg.", ".$oblast.", ".$raion.", ".$city.", ".$street.", ".$house.", ".$korp.", ".$flat;
        //$arResult['post_adr']=$ind_reg_post.", ".$oblast_post.", ".$raion_post.", ".$city_post.", ".$street_post.", ".$house_post.", ".$korp_post.", ".$flat_post;

        $arResult['reg_adr'] = $reg_addr_parts_string;
        $arResult['post_adr'] = $post_addr_parts_string;

        $SQL = "UPDATE persons SET reg_adr='$arResult[reg_adr]', post_adr='$arResult[post_adr]'";

        $SQLData['ind_reg'] = $ind_reg;
        $SQLData['oblast'] = $oblast;
        $SQLData['raion'] = $raion;
        $SQLData['city'] = $city;
        $SQLData['street'] = $street;
        $SQLData['house'] = $house;
        $SQLData['korp'] = $korp;
        $SQLData['flat'] = $flat;
        $SQLData['ind_reg_post'] = $ind_reg_post;
        $SQLData['oblast_post'] = $oblast_post;
        $SQLData['raion_post'] = $raion_post;
        $SQLData['city_post'] = $city_post;
        $SQLData['street_post'] = $street_post;
        $SQLData['house_post'] = $house_post;
        $SQLData['korp_post'] = $korp_post;
        $SQLData['flat_post'] = $flat_post;

        foreach ( $SQLData as $SQLDataKey => $SQLDataValue )
        {
            $SQL .= ', ' . $SQLDataKey . '=\'' . $SQLDataValue . '\'';
        }

        if ( $_REQUEST['is_residence'] == 'Y'  )
        {
            $residence = trim($_REQUEST['residence']);
            $residence = $modx->quote($residence);

            $SQL = $SQL . ", residence=$residence";
        }

        if ( $_REQUEST['is_tax'] == 'Y'  )
        {
            $tax = trim($_REQUEST['tax']);
            $tax = $modx->quote($tax);

            $SQL = $SQL . ", tax=$tax";
        }

        //

        //16.07.2018 Добавление двух переключателей
        $arCheckBoxes1 = [
            'is_nalog_residence'                                => 'N',
            'state_of_tax_residence'                            => '',
            'foreign_tax_identification_number'                 => '',
            'is_beneficiar_nalog_residence'                     => 'N',
            'state_of_tax_residence_beneficiary'                => '',
            'foreign_tax_identification_number_beneficiary'     => '',
            'fio_of_tax_residence_beneficiary'                  => '',
            'bd_of_tax_residence_beneficiary'                   => '',
            'adr_of_tax_residence_beneficiary'                  => '',
        ];

        $arCheckBoxesAlt1 = [
            'is_nalog_residence'                                => 'a1',
            'state_of_tax_residence'                            => 'a2',
            'foreign_tax_identification_number'                 => 'a3',
            'is_beneficiar_nalog_residence'                     => 'a4',
            'state_of_tax_residence_beneficiary'                => 'a5',
            'foreign_tax_identification_number_beneficiary'     => 'a6',
            'fio_of_tax_residence_beneficiary'                  => 'a7',
            'bd_of_tax_residence_beneficiary'                   => 'a8',
            'adr_of_tax_residence_beneficiary'                  => 'a9',
        ];

        foreach ( $arCheckBoxes1 as $arCheckBoxes1_item => $arCheckBoxes1_default )
        {
            $arCheckBoxes1_item_alt = ArrayHelper::Value( $arCheckBoxesAlt1, $arCheckBoxes1_item );

            $arCheckBoxes1_item_val = ArrayHelper::Value( $_REQUEST, $arCheckBoxes1_item_alt, $arCheckBoxes1_default );

            $arCheckBoxes1_item_val_trimmed = trim( $arCheckBoxes1_item_val );

            $arCheckBoxes1_item_val_trimmed_quoted = $modx->quote( $arCheckBoxes1_item_val_trimmed );

            //

            $SQL = $SQL . ', ' .$arCheckBoxes1_item. '=' . $arCheckBoxes1_item_val_trimmed_quoted;
        }

        //

        $SQL = $SQL . " WHERE id='$arResult[id_person]'";

        Logger::AddText($SQL, 'ESIA/SQL');

        $modx->query( $SQL );

        $arResult[birth_place] = $_REQUEST['birth_place'];

        $SQL2 = "UPDATE persons SET birth_date='$q_date', id_esia='$arResult[id_esia]', first_name='$arResult[first_name]', second_name='$arResult[second_name]', last_name='$arResult[last_name]', birth_place='$arResult[birth_place]', citizenship='$arResult[citizen]' WHERE id='$arResult[id_person]'";

        Logger::AddText($SQL2, 'ESIA/SQL');

        $modx->query($SQL2);

        $out = '';

        ob_start(); require $this->DIRRECTORY_ESIA_TPL . 'steep.progress.cc.php'; $out .= ob_get_contents(); ob_end_clean();

        $out .=  "<div class='colwpp col-xs-12'><p class = 'title_block'>Спасибо!</p><p class = 'description_block'>Для того, чтобы Вы могли дистанционно подписывать документы, требуется заключить Соглашение о признании и использовании простой электронной подписи. Это позволит Вам подписать документы через смс-код.
        </p><p><a target='_blank' href='http://nfksber.ru/pdf/agreement_edp.pdf?id=".$arResult['id_person']."'><b>Соглашение о признании и использовании простой электронной подписи</b></a></p>";
        $out .="<p><a id='butt_send_code' onclick='js_show_form_send_code();'  class='submit btn btn_green btn-md btn_green-fix'>Принимаю и подписываю</a></p>";

        $out .= '<form action="'.$this->URL('docs').'" name="sms_send" sms-validate id="sms_send_new" style="display:none;" method="get" action="javascript:void(null);">';
        #$out .= '<p>Получение PIN-кода</p>';

        unset($arResult['sms_kod']);
        unset($arResult['sms_kod_right_esia']);

        $val_sms="";
        if (isset($arResult['sms_kod_right_esia'])){  $val_sms=$arResult['sms_kod'];}
        $out .=  "<p>На номер телефона ".$arResult['mobile']." направлен СМС-код. Введите СМС-код, чтобы подтвердить ознакомление и согласие с текстом Документа.</p>";
        $out .=  "<div id='res'></div><div class='colwpp col-xs-3 sms_send'><label for='sms'>Код подтверждения <span></span></label><br/><input class='form-control' type='text' name='sms' id='sms' value='".$val_sms."' size='4' />";
        if (!isset($arResult['sms_kod_right_esia'])){
            $out .=  "<a class='send_kod_again dotted' onclick='js_show_form_send_code();'>повторно направить СМС-код</a>";
        }
        $out .= "</div><div class='clearfix'></div><div class = 'action_button'><input type='submit' class='submit btn btn_green btn-md btn_green-fix' value='Подписываю' /></div></p>
        <p>Нажимая кнопку «Подписываю», Вы заключаете с АО «НФК-Сбережения» Соглашение о признании и использовании простой электронной подписи</p>";
        $out .= '</form><br/></div></div>';

        $tmp = file_get_contents( $this->DIRRECTORY_ESIA_TPL . 'sms_code.js' );
        $tmp = str_ireplace( '#URL#', $this->URL('sms_agree_send'), $tmp );
        $out .= '<script>' . PHP_EOL . $tmp . PHP_EOL . '</script>';

        $tmp = file_get_contents( $this->DIRRECTORY_ESIA_TPL . 'sms_validate.js' );
        $tmp = str_ireplace( '#URL#', $this->URL('sms_agree_check'), $tmp );
        $out .= '<script>' . PHP_EOL . $tmp . PHP_EOL . '</script>';
    //}
}

require_once $this->DIRRECTORY_ESIA_CORE . 'out.php';