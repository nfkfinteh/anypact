<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:22
 */

/** @var \EsiaCore $this */

function GetVariantElementStatus( $AvaillableClassifiers, $Element )
{
    Logger::AddText('### STATUS ###', 'ESIA/DocumentsModel2');

    $Result = false;

    $Logic = ArrayHelper::Value( $Element, 'LOGIC' );
    $Classifiers = ArrayHelper::Value( $Element, 'CLASSIFIERS' );

    $AvaillableClassifiersProcessed = [];

    foreach ( $AvaillableClassifiers as $AvaillableClassifiers_Key => $AvaillableClassifiers_Value )
    {
        $AvaillableClassifiersProcessed[ $AvaillableClassifiers_Key ] = ( $AvaillableClassifiers_Value === true ) ? 'Y' : 'N';
    }

    Logger::AddText(
        [
            'AvaillableClassifiers' => $AvaillableClassifiers,
            'AvaillableClassifiersProcessed' => $AvaillableClassifiersProcessed,
            'Element' => $Element
        ],
                'ESIA/DocumentsModel2'
    );

    if ( is_array( $Classifiers ) and count( $Classifiers ) > 0 )
    {
        Logger::AddText('=== True 1 ===', 'ESIA/DocumentsModel2');

        switch ( $Logic )
        {
            case 'OR':
            {
                Logger::AddText('=== OR ===', 'ESIA/DocumentsModel2');

                foreach ( $Classifiers as $Classifier )
                {
                    $Value = ArrayHelper::Value( $AvaillableClassifiers, $Classifier );

                    Logger::AddText([$Classifier => ( $Value === true ) ? 'Y' : 'N'], 'ESIA/DocumentsModel2');

                    if ( $Value === true )
                    {
                        $Result = true;

                        Logger::AddText(['SUCCESS' => $Classifier], 'ESIA/DocumentsModel2');

                        break;
                    }
                }
            }
            break;

            case 'AND':
            {
                Logger::AddText('=== AND ===', 'ESIA/DocumentsModel2');

                $TmpResult = true;

                foreach ( $Classifiers as $Classifier )
                {
                    $Value = ArrayHelper::Value( $AvaillableClassifiers, $Classifier );

                    Logger::AddText([$Classifier => ( $Value === true ) ? 'Y' : 'N'], 'ESIA/DocumentsModel2');

                    if ( $Value !== true )
                    {
                        $TmpResult = false;

                        Logger::AddText(['FAIL' => $Classifier], 'ESIA/DocumentsModel2');

                        break;
                    }
                }

                $Result = $TmpResult;
            }
            break;
        }
    }

    Logger::AddText('STATUS RESULT = ' . Bool2YN($Result), 'ESIA/DocumentsModel2');

    return $Result;
}

function ConditionRelate( $IsRoot, $RootValue, &$Classificators, &$Values )
{
    Logger::AddText('============================== ConditionRelate ==============================', 'ESIA/ConditionRelate');

    Logger::AddText([
        'IsRoot' => $IsRoot,
        'RootValue' => $RootValue,
        'Classificators' => $Classificators,
        'Values' => $Values,
    ], 'ESIA/ConditionRelate');

    if ( !is_array( $Values ) )
    {
        return;
    }

    foreach ( $Values as $ValueKey => $ValueArray )
    {
        Logger::AddText( $ValueKey, 'ESIA/ConditionRelate' );

        $Value = ArrayHelper::Value( $Classificators, $ValueKey );

        $ValueFixed = ( $Value === true && $RootValue === true ) ? true : false;

        if ( !$IsRoot )
        {
            $Classificators[ $ValueKey . '_RELATIVE' ] = $ValueFixed;
        }

        if ( is_array( $ValueArray ) && count( $ValueArray ) > 0 )
        {
            ConditionRelate( false, $ValueFixed, $Classificators, $ValueArray );
        }
    }
}

### директория хранния документов
$doc_dir= HTTPHelper::GenerateUniversalBaseURL() . "/pdf/";

if ($_REQUEST['market_stock']=='on'){$arResult['market_stock']=1;}else{$arResult['market_stock']=0;}
if ($_REQUEST['market_outstock']=='on'){$arResult['market_outstock']=1;}else{$arResult['market_outstock']=0;}
if ($_REQUEST['market_valuta']=='on'){$arResult['market_valuta']=1;}else{$arResult['market_valuta']=0;}
if ($_REQUEST['market_other']=='on'){$arResult['market_other']=1;}else{$arResult['market_other']=0;}

$arResult['iis_multiply'] = ( $_REQUEST['iis_multiply'] == 'Y' ) ? 1 : 0;

$arResult['type_iis'] = ( $_REQUEST['iis_multiply'] == 'Y' ) ? $_REQUEST['type_iis'] : false;
$arResult['type_iis_option'] = ArrayHelper::Value( $_REQUEST, 'type_iis' );

if (isset($_REQUEST['iis_company'])){$arResult['iis_company']=$_REQUEST['iis_company'];}
if ($_REQUEST['iis']=='on'){$arResult['iis']=1;}else{$arResult['iis']=0;}
if ($_REQUEST['iis_ch']=='yes'){$arResult['iis_ch']=1;}else{$arResult['iis_ch']=0;}

if ($_REQUEST['depo']=='on'){$arResult['depo']=1;}else{$arResult['depo']=0;}
if ($_REQUEST['depo_type_ch']=='1'){$arResult['depo_type_ch']=1;}else{$arResult['depo_type_ch']=0;}
if ($_REQUEST['depo_dohod_ch']=='1'){$arResult['depo_dohod_ch']=1;}else{$arResult['depo_dohod_ch']=0;}

if (isset($_REQUEST['schet'])){$arResult['shet']=$_REQUEST['schet'];}
if (isset($_REQUEST['k_schet'])){$arResult['k_shet']=$_REQUEST['k_schet'];}
if (isset($_REQUEST['bank'])){$arResult['bank']=$_REQUEST['bank'];}
if (isset($_REQUEST['bik'])){$arResult['bik']=$_REQUEST['bik'];}
if (isset($_REQUEST['other_requisites_inn'])){$arResult['other_requisites_inn']=$_REQUEST['other_requisites_inn'];}
if (isset($_REQUEST['other_requisites_other_conditions'])){$arResult['other_requisites_other_conditions']=$_REQUEST['other_requisites_other_conditions'];}

if ( $arResult['inn'] != $arResult['other_requisites_inn'] )
{
    $arResult['inn'] = $arResult['other_requisites_inn'];
}

if (isset($_REQUEST['other_broker_doc_number'])){$arResult['other_broker_doc_number']=$_REQUEST['other_broker_doc_number'];}
if (isset($_REQUEST['other_broker_doc_date'])){$arResult['other_broker_doc_date']=$_REQUEST['other_broker_doc_date'];}
if (isset($_REQUEST['other_broker_bank_requisites'])){$arResult['other_broker_bank_requisites']=$_REQUEST['other_broker_bank_requisites'];}

$market=$arResult['market_stock'].";".$arResult['market_outstock'].";".$arResult['market_valuta'].";".$arResult['market_other'];
//$option_market=$arResult['iis'].";".$arResult['iis_company'].";".$arResult['depo'].";".$arResult['depo_type_ch'].";".$arResult['depo_dohod_ch'];

$options_market = [
    $arResult['iis'],
    $arResult['iis_company'],
    $arResult['depo'],
    $arResult['depo_type_ch'],
    $arResult['depo_dohod_ch'],
];

$option_market = implode( ';', $options_market );

$depo_shet_list = [
    $arResult['shet'],
    $arResult['k_shet'],
    $arResult['bank'],
    $arResult['bik'],
    $arResult['other_requisites_inn'],
    $arResult['other_requisites_other_conditions'],
];

$depo_shet = implode( ';', $depo_shet_list );

$broker_shet_list = [
    $arResult['other_broker_doc_number'],
    $arResult['other_broker_doc_date'],
    $arResult['other_broker_bank_requisites'],
];

$broker_shet = implode( ';', $broker_shet_list );

Logger::AddText(
    [
        'SESSION' => $arResult,
        'options_market' => $options_market,
        '$depo_shet_list' => $depo_shet_list,

        'broker_shet_list' => $broker_shet_list],
    'ESIA/Work1DocsAgreeDebug'
);

$SQL3 = "UPDATE persons SET market='$market', option_market='$option_market', depo_shet='$depo_shet', broker_shet='$broker_shet', inn='{$arResult['inn']}' WHERE id='$arResult[id_person]'";

Logger::AddText(['SQL3' => $SQL3], 'ESIA/SQL');

$modx->query( $SQL3 );

//xDump($arResult);

$IsOuterStockPrimary = ( $arResult['market_outstock'] == 1 );
$IsStockPrimary = ( $arResult['market_stock'] == 1 );

if ( $arResult['iis_multiply'] == 1 )
{
    $IsOuterStock = ( $IsOuterStockPrimary && $arResult['type_iis'] == 'IIS_OUTSTOCK' );
}
else
{
    $IsOuterStock = $IsOuterStockPrimary;
}

if ( $arResult['iis_multiply'] == 1 )
{
    $IsStock = ( $IsStockPrimary && $arResult['type_iis'] == 'IIS_STOCK' );
}
else
{
    $IsStock = $IsStockPrimary;
}

$IsIIS = ( $arResult['iis'] == 1 );

$IsIISDoc = ( $arResult['iis_ch'] == 1 );


$Classificators = [
    'IS_OUTER_STOCK' => ( $arResult['market_outstock'] === 1 ),
    'IS_STOCK' => ( $arResult['market_stock'] === 1 ),
    'IS_CURRENCY' => ( $arResult['market_valuta'] === 1 ),
    'IS_OTHER' => ( $arResult['market_other'] === 1 ),

    'IS_OTHER_OWNER_TRADE' => ( $arResult['depo_type_ch'] === 1 ),
    'IS_OTHER_OWNER' => ( $arResult['depo_type_ch'] === 0 ),
    'IS_OTHER_BROKER' => ( $arResult['depo_dohod_ch'] === 1 ),
    'IS_OTHER_REQUISITES' => ( $arResult['depo_dohod_ch'] === 0 ),

    'IS_IIS' => ( $arResult['iis'] == 1 ),
    'IS_IIS_CONTRACT_LESS' => ( $arResult['iis_ch'] === 0 ),
    'IS_IIS_CONTRACT_HAVE' => ( $arResult['iis_ch'] === 1 ),

    'IS_IIS_TYPE_STORE' => ( $arResult['type_iis_option'] == 'IIS_STOCK' ),
    'IS_IIS_TYPE_OUTSTORE' => ( $arResult['type_iis_option'] == 'IIS_OUTSTOCK' ),

    'IS_USER_RESIDENCE' => ( ArrayHelper::GetValuePath( $this->Preview, 'agree/is_residence' ) == 'N' ),
    'IS_AGREE_DOCUMENTS_TAX' => ( ArrayHelper::GetValuePath( $this->Preview, 'agree/is_residence_info' ) == 'Y' ),
    'IS_USER_CITIZENSHIP' => ( ArrayHelper::GetValuePath( $this->Preview, 'agree/is_tax' ) == 'N' ),

    'NOT_USER_RESIDENCE' => ( ArrayHelper::GetValuePath( $this->Preview, 'agree/is_residence' ) != 'N' ),
    'NOT_AGREE_DOCUMENTS_TAX' => ( ArrayHelper::GetValuePath( $this->Preview, 'agree/is_residence_info' ) != 'Y' ),
    'NOT_USER_CITIZENSHIP' => ( ArrayHelper::GetValuePath( $this->Preview, 'agree/is_tax' ) != 'N' ),
];

$ClassificatorsRelation = [
    'IS_OTHER' => [
        'IS_OTHER_OWNER_TRADE' => [],
        'IS_OTHER_OWNER' => [],
        'IS_OTHER_BROKER' => [],
        'IS_OTHER_REQUISITES' => [],
    ],

    'IS_IIS' => [
        'IS_IIS_CONTRACT_LESS' => [],
        'IS_IIS_CONTRACT_HAVE' => [],
    ],
];

ConditionRelate( true,true, $Classificators, $ClassificatorsRelation );

$out = '';

include $this->DIRRECTORY_ESIA . 'ToolDocumentsConfigUTF8.php';

if ( ArrayHelper::Value( $_REQUEST, 'C_DEBUG' ) == 'Y' )
{
    ob_start(); require_once $this->DIRRECTORY_ESIA_TPL . 'debug.classifier.values.php'; $out .= ob_get_contents(); ob_end_clean();
}

$Documents = [];
$DocumentsX = [];

$Documents[''] = [];

$DocumentPathVariables = [
    'DOCUMENT_DIR' => $doc_dir,
    //'ID_PERSON' => $arResult['id_person'],
    'ID_PERSON' => $this->Code,
    'DOCS_DIR' =>  HTTPHelper::GenerateUniversalBaseURL() . '/docs/',
];

foreach ( $DocumentsModel as $DocumentsModelRec )
{
    Logger::AddText('============================== DOCUMENT ==============================', 'ESIA/DocumentsModel2');

    $Code       = ArrayHelper::Value( $DocumentsModelRec, 'CODE' );
    $File       = ArrayHelper::Value( $DocumentsModelRec, 'FILE' );
    $Name       = ArrayHelper::Value( $DocumentsModelRec, 'NAME' );
    $Variants   = ArrayHelper::Value( $DocumentsModelRec, 'VARIANTS' );

    Logger::AddText($DocumentsModelRec, 'ESIA/DocumentsModel2');

    if (
        !$Code
        || !$File
        || !$Variants
    )
    {
        Logger::AddText('Fuck 1', 'ESIA/DocumentsModel2');

        continue;
    }

    foreach ( $Variants as $Variant )
    {
        Logger::AddText('========== VARIANT ==========', 'ESIA/DocumentsModel2');

        Logger::AddText($Variant, 'ESIA/DocumentsModel2');

        $VariantInclude = ArrayHelper::Value( $Variant, 'INCLUDE' );
        $VariantExclude = ArrayHelper::Value( $Variant, 'EXCLUDE' );

        if ( !is_array( $VariantInclude ) || !is_array( $VariantExclude ) )
        {
            Logger::AddText('Fuck 2', 'ESIA/DocumentsModel2');

            continue;
        }

        $VariantIncludeStatus = GetVariantElementStatus( $Classificators, $VariantInclude );
        $VariantExcludeStatus = GetVariantElementStatus( $Classificators, $VariantExclude );

        $VariantExcludeStatus = ( $VariantExcludeStatus === true ) ? false : true;

        $VariantGrandStatus = ( $VariantIncludeStatus === true && $VariantExcludeStatus === true );

        Logger::AddText([
            '$VariantIncludeStatus' => Bool2YN($VariantIncludeStatus),
            '$VariantExcludeStatus' => Bool2YN($VariantExcludeStatus),
            '$VariantGrandStatus' => Bool2YN($VariantGrandStatus),
        ], 'ESIA/DocumentsModel2');

        $VariantGrandStatusForce = ( ArrayHelper::Value( $_REQUEST, 'X_V_G_S_F', 'N' ) === 'Y' );

        if ( $VariantGrandStatus === true || $VariantGrandStatusForce === true )
        {
            $DocumentPath = $File;

            foreach ( $DocumentPathVariables as $DocumentPathVariablesSearch => $DocumentPathVariablesReplace )
            {
                $DocumentPath = str_ireplace( '#' . $DocumentPathVariablesSearch . '#', $DocumentPathVariablesReplace, $DocumentPath );
            }

            $DocumentGroup = ArrayHelper::Value( $DocumentsModelRec, 'GROUP', '' );

            $Documents[ $DocumentGroup ][] = "<a target='_blank' class = 'docs_agree_doc_href' href='$DocumentPath'>$Name</a><br/>";

            $DocumentsX[ $DocumentGroup ][] = [
                'NAME' => $Name,
                'PATH' => $DocumentPath,
            ];


            break;
        }
    }
}

ob_start(); require $this->DIRRECTORY_ESIA_TPL . 'steep.progress.cc.php'; $out .= ob_get_contents(); ob_end_clean();

$out .= "<div class='colwpp col-xs-12 docs_agree_descr'>Для Вас сформирован комплект документов. Просим ознакомиться и подписать путем ввода СМС-кода, при необходимости вы можете вернуться к заполненным формам, чтобы внести правки. После этого вам будет открыт соответствующий счет (счета). Обращаем Ваше внимание на то, что исполнение АО «НФК-Сбережения» поручений осуществляется только при условии, что все расчеты осуществляются исключительно в безналичной форме по счетам, открытым в российской кредитной организации. В случае, если Вы пожелаете осуществлять расчеты в наличной форме, Вам нужно будет обратиться в офис компании с паспортом.</div>";

$out .="<div class='colwpp col-xs-12'><h4>Сформированные документы</h4></div>	

<div class='colwpp col-xs-12'>";

$arResult['PDF_DOCUMENTS_LIST'] = [
    'GROUPS' => $GroupsAvaillabled,
    'DOCS' => $DocumentsX,
];

foreach ( $Documents as $DocumentGroupID => $DocumentGrouped )
{
    if ( !empty( $DocumentGroupID ) )
    {
        $DocumentGroupName = ArrayHelper::Value( $GroupsAvaillabled, $DocumentGroupID );

        if ( !empty( $DocumentGroupName ) )
        {
            $out .="<div class='colwpp col-xs-12'><p class='document-group-name' style='font-weight: bold; margin-top: 16px;'>$DocumentGroupName</p></div>";
        }
    }

    foreach ( $DocumentGrouped as $Document )
    {
        $out .= $Document;
    }
}


$out .= "<br/><br/></div>";



$out .="<div class='colwpp col-xs-12'><p><a id='butt_send_code' onclick='js_show_form_send_code();'  class='submit btn btn_green btn-md btn_green-fix'>Подтверждаю</a></p>";
$out .= '<form action="'.$this->URL('end').'" name="sms_send" sms-validate id="sms_send_new" style="display:none;" method="get" action="javascript:void(null);">';
#$out .= '<p>Получение PIN-кода</p>';

unset($arResult['sms_kod_right_esia1']);
unset($arResult['sms_kod1']);

$val_sms="";
if (isset($arResult['sms_kod_right_esia1'])){  $val_sms=$arResult['sms_kod1'];}
$out .=  "<p>На номер телефона ".$arResult['mobile']." направлен СМС-код. Введите СМС-код, чтобы подтвердить ознакомление и согласие с текстом документов, и подписать их.</p>";
$out .=  "<div class='colwpp col-xs-3 sms_send'><label for='sms'>Код подтверждения <span></span></label><br/><input class='form-control' type='text' name='sms' id='sms' value='".$val_sms."' size='4' />";
if (!isset($arResult['sms_kod_right_esia1'])){
    $out .=  "<a class='send_kod_again dotted' onclick='js_show_form_send_code();'>повторно направить СМС-код</a>";
}
$out .= "</div><div class='clearfix'></div><input type='submit' class='submit btn btn_green btn-md btn_green-fix' value='Подписываю'/></p>
	<p>Нажимая кнопку «Подписываю», Вы подтверждаете ознакомление и полное согласие с документами подписанных вами</p>";
$out .= '</form><br/></div></div>';

$tmp = file_get_contents( $this->DIRRECTORY_ESIA_TPL . 'sms_code.js' );
$tmp = str_ireplace( '#URL#', $this->URL('sms_docs_agree_send'), $tmp );
$out .= '<script>' . PHP_EOL . $tmp . PHP_EOL . '</script>';

$tmp = file_get_contents( $this->DIRRECTORY_ESIA_TPL . 'sms_validate.js' );
$tmp = str_ireplace( '#URL#', $this->URL('sms_docs_agree_check'), $tmp );
$out .= '<script>' . PHP_EOL . $tmp . PHP_EOL . '</script>';

require_once $this->DIRRECTORY_ESIA_CORE . 'out.php';