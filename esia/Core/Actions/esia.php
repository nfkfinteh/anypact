<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 3:53
 */

/** @var \EsiaCore $this */

/** @var \EsiaOmniAuth $esia */

EsiaLogger::DumpEnviroment( 'CORE/' . $this->Action );

$arResult = ArrayHelper::Value( $this->Detail, 'RESULT', [] );

$ID_ESIA = ArrayHelper::Value( $arResult, 'id_esia' );

if ( empty( $ID_ESIA ) )
{
    require_once $this->DIRRECTORY_ESIA_CORE . 'esia.config.php';

    $token = $esia->get_token( $_REQUEST['code'] );
    $info = $esia->get_info( $token );
	
	\Logger::AddText( $info, 'ESIA/InfoFrom' );

    $this->Merge( $this->Detail, 'ESIA', $info );

    ### проверка подтверждена или нет запись
    $arResult['first_name']=$info["user_info"]["firstName"];
    $arResult['second_name']=$info["user_info"]["middleName"];
    $arResult['last_name']=$info["user_info"]["lastName"];
    $inn=$info["user_info"]["inn"];
    $snils=$info["user_info"]["snils"];
    $arResult['birth_day_esia']=$info["user_info"]["birthDate"];
    $birth_day=ret_date($info["user_info"]["birthDate"]);
    $arResult['birth_place']=$info["user_info"]["birthPlace"];
    $arResult['citizen']=$info["user_info"]["citizenship"];

    $arResult['fio']=$arResult['last_name']." ".$arResult['first_name']." ".$arResult['second_name'];


    for ($i=0;$i<count($info["user_docs"]["elements"]);$i++)
    {
        if ($info["user_docs"]["elements"][$i]["type"]=="RF_PASSPORT")
        {
            $pass_seria=$info["user_docs"]["elements"][$i]["series"];
            $pass_number=$info["user_docs"]["elements"][$i]["number"];

            \Logger::AddText( $info["user_docs"]["elements"][$i]["issueDate"], 'ESIA/Debug' );

            $pass_date_obj = \DateHelper::GetDateFromString( $info["user_docs"]["elements"][$i]["issueDate"] );

            \Logger::AddText( $pass_date_obj, 'ESIA/Debug' );

            $pass_date = ( is_object($pass_date_obj) && ( $pass_date_obj instanceof \DateTime) )
                ? $pass_date_obj->format( \DateHelper::Y_m_d )
                : '';

            \Logger::AddText( $pass_date, 'ESIA/Debug' );

            $arResult['PASS_ID']=$info["user_docs"]["elements"][$i]["issueId"];
            $arResult['PASS_WHO']=$info["user_docs"]["elements"][$i]["issuedBy"];
            $arResult['PASS_VRF']=$info["user_docs"]["elements"][$i]["vrfStu"];
        }
    }

    $arFrom = array("(", ")");
    $arTo = array("", "");
    $arFrom_to_crm = array("(", ")", "+");
    $arTo_to_crm= array("", "", "");


    if ($info["user_info"]["trusted"]==1)
    {
        $st_amo='10701663';
    }
    else
    {
        $st_amo='10701666';
    }

    if (isset($arResult['id_esia']) and  $arResult['id_esia']>0)
    {
        $sql="UPDATE persons_esia SET add_date=now(), ";
    }
    else
    {
        $sql="INSERT INTO persons_esia SET add_date=now(), ";
    }
	
	require_once $this->DIRRECTORY_ESIA_CORE . 'modx.config.php';

    $sql .=' klient_vrfStu='.$modx->quote($info["user_info"]["trusted"]).' ';
    $sql .=', firstName='.$modx->quote($info["user_info"]["firstName"]).' ';
    $sql .=', middleName='.$modx->quote($info["user_info"]["middleName"]).' ';
    $sql .=', lastName='.$modx->quote($info["user_info"]["lastName"]).' ';
    $sql .=', inn='.$modx->quote($info["user_info"]["inn"]).' ';
    $sql .=', snils="'.$modx->quote($info["user_info"]["snils"]).'" ';
    $sql .=', gender="'.$modx->quote($info["user_info"]["gender"]).'" ';
    $sql .=', updatedOn="'.td_date($info["user_info"]["updatedOn"]).'" ';
    $sql .=', birthDate='.$modx->quote(ret_date($info["user_info"]["birthDate"])).' ';
    $sql .=', birthPlace='.$modx->quote($info["user_info"]["birthPlace"]).' ';
    $sql .=', citizenship='.$modx->quote($info["user_info"]["citizenship"]).' ';

    for ($i=0;$i<count($info["user_docs"]["elements"]);$i++)
    {

        #### выбираем только паспорт РФ
        if ($info["user_docs"]["elements"][$i]["type"]=="RF_PASSPORT")
        {
            $arResult['pass_seria']=$info["user_docs"]["elements"][$i]["series"];
            $arResult['pass_number']=$info["user_docs"]["elements"][$i]["number"];
            $arResult['pass_dv']=$info["user_docs"]["elements"][$i]["issueDate"];
            $arResult['pass_kp']=$info["user_docs"]["elements"][$i]["issueId"];
            $arResult['pass_who']=$info["user_docs"]["elements"][$i]["issuedBy"];

            $to_crm = array('"');
            $to_crm1= array("'");

            $y=$i+1;
            $sql .=', doc_type_'.$y.'='.$modx->quote($info["user_docs"]["elements"][$i]["type"]).' ';
            $sql .=', series_'.$y.'='.$modx->quote($info["user_docs"]["elements"][$i]["series"]).' ';
            $sql .=', number_'.$y.'='.$modx->quote($info["user_docs"]["elements"][$i]["number"]).' ';
            $sql .=', issueDate_'.$y.'='.$modx->quote(ret_date($info["user_docs"]["elements"][$i]["issueDate"])).' ';
            $sql .=', issueId_'.$y.'='.$modx->quote($info["user_docs"]["elements"][$i]["issueId"]).' ';
            $sql .=', issuedBy_'.$y.'='.$modx->quote(str_replace($to_crm, $to_crm1, $info["user_docs"]["elements"][$i]["issuedBy"])).' ';
            $sql .=', expiryDate_'.$y.'='.$modx->quote(ret_date($info["user_docs"]["elements"][$i]["expiryDate"])).' ';
            $sql .=', doc'.$y.'_vrfStu='.$modx->quote($info["user_docs"]["elements"][$i]["vrfStu"]).' ';
        }
    }

    for ($i=0;$i<count($info["user_contacts"]["elements"]);$i++)
    {
        if ($info["user_contacts"]["elements"][$i]["type"]=="EML")
        {
            $sql .=', email='.$modx->quote($info["user_contacts"]["elements"][$i]["value"]).' ';
            $sql .=', email_vrfStu='.$modx->quote($info["user_contacts"]["elements"][$i]["vrfStu"]).' ';
        }

        if ($info["user_contacts"]["elements"][$i]["type"]=="MBT")
        {
            $sql .=', mobile='.$modx->quote(str_replace($arFrom_to_crm, $arTo_to_crm, $info["user_contacts"]["elements"][$i]["value"])).' ';
            $sql .=', mob_vrfStu='.$modx->quote($info["user_contacts"]["elements"][$i]["vrfStu"]).' ';
        }

        if ($info["user_contacts"]["elements"][$i]["type"]=="PHN")
        {
            $sql .=', phone='.$modx->quote(str_replace($arFrom_to_crm, $arTo_to_crm, $info["user_contacts"]["elements"][$i]["value"])).' ';
            $sql .=', phone_vrfStu='.$modx->quote($info["user_contacts"]["elements"][$i]["vrfStu"]).' ';
        }

        if ($info["user_contacts"]["elements"][$i]["type"]=="CEM")
        {
            $sql .=', smail='.$modx->quote($info["user_contacts"]["elements"][$i]["value"]).' ';
            $sql .=', smail_vrfStu='.$modx->quote($info["user_contacts"]["elements"][$i]["vrfStu"]).' ';
        }
    }

    if (isset($arResult['id_esia']) and  intval($arResult['id_esia'])>0)
    {
        $sql .=" WHERE id='".intval($arResult['id_esia'])."'";
    }

    $modx->query($sql);
    $arResult['id_esia']=$modx->lastInsertId();
}
else
{
    $info = ArrayHelper::Value(  $this->Detail, 'ESIA', [] );
}

require_once $this->DIRRECTORY_ESIA_TPL . 'esia' . DIRECTORY_SEPARATOR . 'page.php';