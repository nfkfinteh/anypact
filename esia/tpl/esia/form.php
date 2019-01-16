<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 25.04.2017
 * Time: 1:09
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/esia/HTTPHelper.class.php';

ob_start(); require $this->DIRRECTORY_ESIA_TPL . 'steep.progress.cc.php'; $out .= ob_get_contents(); ob_end_clean();

$efFilterFrom = [
    '\'',
];

$efFilterTo = [
    '',
];

$EmailValue = ArrayHelper::Value($arResult, 'in_email');

$EmailValue = str_ireplace( $efFilterFrom, $efFilterTo, $EmailValue );

$PhoneValue = ArrayHelper::Value($arResult, 'in_phone');

$PhoneValue = str_ireplace( $efFilterFrom, $efFilterTo, $PhoneValue );

$PhoneValue = PhoneHelper::FormatPhone( $PhoneValue );

$CurrentDateTime = new \DateTime();

$DBAIR = "js-air-datepicker='" . json_encode( ['maxDate' => $CurrentDateTime->format('d.m.Y')] ) . "'";

$out .= "<form js-ubu='".\HTTPHelper::GenerateUniversalBaseURL()."' js-agree-form method='get' action='".$this->URL('agree')."' id='adr'><div class='colwpp col-xs-12 information'>Пожалуйста, внимательно заполните все поля. На основании полученных данных мы подготовим электронные документы для открытия счёта в нашей компании.<br/>Информация необходима для дальнейшего комплексного обслуживания Вас на финансовых рынках, в частности реализации Ваших прав как инвестора. Введение некорректных данных может повлечь за собой отсутствие доступа к ряду услуг компании.
При возникновении вопросов обратитесь по телефону 8 800 200-84-84</div>
         <div class='colwpp col-xs-12'><h4>Личные данные</h4>
		 <div class='colwpl col-xs-4'><label for='last_name'>Фамилия <span></span></label><br/><input type='text' class='form-control' id='last_name' name='last_name' value='".$arResult['last_name']."' disabled></div>
		 <div class='colwpl col-xs-4'><label for='first_name'>Имя <span></span></label><br/><input type='text' class='form-control' id='first_name' name='first_name' value='".$arResult['first_name']."' disabled></div>
		 <div class='colwpl col-xs-4'><label for='second_name'>Отчество <span></span></label><br/><input type='text' class='form-control' id='second_name' name='second_name' value='".$arResult['second_name']."' disabled></div>
         <div class='clearfix'></div>
		 
		 <div class='colwpl col-xs-12'><label for='birth_place'>Место рождения <span></span></label><br/><input type='text' class='form-control' id='birth_place' name='birth_place' value='".ArrayHelper::Value( $this->Current, 'birth_place', '' )."' ></div>
		 <div class='colwpl col-xs-4'><label for='birth_day'>Дата рождения <span></span></label><br/><input type='text' class='form-control' id='birth_day' name='birth_day' value='".ArrayHelper::Value( $this->Current, 'birth_day', '' )."' ".$DBAIR." ></div>
		 <div class='colwpl col-xs-4'><label for='citizen'>Гражданство <span></span></label><br/><input type='text' class='form-control' id='citizen' name='citizen' value='".$arResult['citizen']."' disabled></div>
		 </div><div class='clearfix'></div>";

$out .= "<div class='colwpp col-xs-12'><h4>Контактные данные</h4>
        <div class='colwpl col-xs-4'><label for='email'>Email <span></span></label><br/><input type='text' class='form-control' id='email' name='email' value='".$EmailValue."'></div>
		<div class='colwpl col-xs-4'><label for='phone'>Мобильный телефон <span></span></label><br/><input type='text' class='form-control' id='phone' name='phone' value='".$PhoneValue."'></div>
		</div><div class='clearfix'></div>";

$out .= "<div class='colwpp col-xs-12'><h4>Документ</h4>
		<div class='colwpl col-xs-4'><label for='type_doc'>Вид документа <span></span></label><br/>
		
		<select class='form-control' id='type_doc' name='type_doc'>
			 <option value='RF_PASSPORT' selected>Российский паспорт</option>
		     <option value='IN_PASSPORT'>Иностранный паспорт</option>
		</select>
		
		</div>
		<div class='colwpl col-xs-2'><label for='pass_seria'>Серия <span></span></label><br/><input type='text' class='form-control' id='pass_seria' name='pass_seria' value='".$arResult['pass_seria']."' disabled></div>
		<div class='colwpl col-xs-2'><label for='pass_number'>Номер <span></span></label><br/><input type='text' class='form-control' id='pass_number' name='pass_number' value='".$arResult['pass_number']."' disabled></div>
		<div class='colwpl col-xs-2'><label for='pass_dv'>Дата выдачи <span></span></label><br/><input type='text' class='form-control' id='pass_dv' name='pass_dv' value='".$arResult['pass_dv']."' disabled></div>
		<div class='colwpl col-xs-2'><label for='pass_kp'>Код подразделения <span></span></label><br/><input type='text' class='form-control' id='pass_kp' name='pass_kp' value='".$arResult['pass_kp']."' disabled></div>
		<div class='colwpl col-xs-12'><label for='pass_who'>Кем выдан <span></span></label><br/><input type='text' class='form-control' id='pass_who' name='pass_who' value='".$arResult['pass_who']."' disabled></div>
		";


$out .= "<div class='colwpp col-xs-12 address_information'><h4>Адрес регистрации</h4>Данные вводятся в соответствии с паспортом гражданина РФ.<br/>
Информация необходима для дальнейшего комплексного обслуживания Вас на фондовом рынке, в частности реализации Ваших прав как инвестора.
Введение некорректных данных может повлечь за собой отсутствие доступа к ряду услуг брокера.<br/><br/>
Все поля обязательны для заполнения.<br/><br/>
Если в Вашем адресе регистрации отсутствует название района, улицы, корпуса и др., нажмите на кнопку «нет» рядом с соответствующем полем.<br/><br/>


        <div class='colwpl col-xs-4'><label for='country' class = 'country_label_block'>Страна<span></span></label><br/><input type='text' class='form-control' size='61' name='country' maxLength=80 value='Российская Федерация' disabled></div>
		<div class='colwpl col-xs-7'><label for='oblast'>Республика, край, область, автономный округ, город федерального значения<span></span></label><br/><input type='text' class='form-control' id='oblast' size='61' name='oblast'  value='".ArrayHelper::Value( $this->Current, 'oblast', '' )."' maxLength=80 placeholder='название' js-federal-city='#city'></div>
		<div class='colwpl col-xs-1 set_no oblast_clear'><input type='hidden' id='data-oblast' name='data-oblast'><a onclick='set_no(\"oblast\");'>нет</a></div><div class='clearfix'></div>

		
        <div class='colwpl col-xs-8'><label for='raion'>Название района <span></span></label><br/><input type='text' class='form-control' size='61' id='raion' name='raion'  value='".ArrayHelper::Value( $this->Current, 'raion', '' )."' maxLength=80 placeholder='нет или название района'></div>
		<div class='colwpl col-xs-1 set_no'><input type='hidden' id='data-raion' name='data-raion'><a onclick='set_no(\"raion\");'>нет</a></div>
		<div class='clearfix'></div>
		
		<div class='colwpl col-xs-8'><label for='city'>Название населенного пункта <span></span></label><br/><input type='text' class='form-control' size='61' id='city' name='city'  value='".ArrayHelper::Value( $this->Current, 'city', '' )."' maxLength=80 placeholder='нет или название населеный пункта'></div>
		<div class='colwpl col-xs-1 set_no'><input type='hidden' id='data-city' name='data-city'><a onclick='set_no(\"city\");'>нет</a></div>
		<div class='clearfix'></div>
		
		<div class='colwpl col-xs-5'><label for='street'>Название улицы <span></span></label><br/><input type='text' class='form-control' size='61' id='street' name='street'  value='".ArrayHelper::Value( $this->Current, 'street', '' )."' maxLength=80 placeholder='нет или название улицы'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"street\");'>нет</a></div>
        <div class='colwpl col-xs-2'><label for='house'>Дом <span></span></label><br/><input type='text' class='form-control' size='61' id='house' name='house'  value='".ArrayHelper::Value( $this->Current, 'house', '' )."' maxLength=80 placeholder='24'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"house\");'>нет</a></div>
		<div class='colwpl col-xs-2'><label for='korp'>Корпус строение <span></span></label><br/><input type='text' class='form-control' size='61' id='korp' name='korp'  value='".ArrayHelper::Value( $this->Current, 'korp', '' )."' maxLength=80 placeholder='корп.2 стр.5'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"korp\");'>нет</a></div><div class='clearfix'></div>
		
		<div class='colwpl col-xs-2'><label for='flat'>Квартира <span></span></label><br/><input type='text' class='form-control' size='61' id='flat' name='flat'  value='".ArrayHelper::Value( $this->Current, 'flat', '' )."' maxLength=80 placeholder='165'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"flat\");'>нет</a></div>
		
		<div class='colwpl col-xs-2'><label for='ind_reg'>Индекс <span></span></label><br/><input type='text' class='form-control' size='6' id='ind_reg' name='ind_reg'  value='".ArrayHelper::Value( $this->Current, 'ind_reg', '' )."' maxLength=6 placeholder='000000'></div>
        <div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"ind_reg\");'>нет</a></div>
        
        <div class='clearfix'></div>";

$out .= "<div class='colwpp col-xs-12 element_control address_registration'>";

$adress_same_checked = XCheckedCurrent($this, 'is_adress_fact_same', 'Y', true );

$adress_same_visible = ( $adress_same_checked != '' ) ? 'style=\'display: none\'' : '';

//$adress_same_checked = ( ArrayHelper::Value( $this->Current, 'is_adress_fact_same' ) == 'Y' ) ? " checked='checked' " : "";

$out .= "
          
		
		
		
		<input id='ch_post_adr' class='checkbox' type='checkbox' name='adress-fact-same' value='Y' " . $adress_same_checked . " js-ideal-checkbox=\"input[name='is_adress_fact_same']\">
        <label for='ch_post_adr'>Адрес регистрации совпадает с почтовым адресом</label>
        <input type='hidden' name='is_adress_fact_same' value='Y'>

            
        </div>";

$out .= "<div class='colwpp col-xs-12' id='post_adr' $adress_same_visible><br/><h4>Почтовый адрес</h4>
Все поля обязательны для заполнения.<br/><br/>
Если в Вашем почтовом адресе отсутствует название района, улицы, корпуса и др., нажмите на кнопку «нет» рядом с соответствующем полем.<br/><br/>


        <div class='colwpl col-xs-4'><label for='country'>Страна<br><span></span></label><input type='text' class='form-control' size='61' name='country_post' maxLength=80 value='Российская Федерация' disabled></div>
		<div class='colwpl col-xs-7'><label for='oblast'>Республика, край, область, автономный округ, город федерального значения<span></span></label><br/><input type='text' class='form-control' id='oblast_post' size='61' name='oblast_post' value='" . ArrayHelper::Value( $this->Current, 'oblast_post', ArrayHelper::Value( $this->Current, 'oblast' ) ) . "' maxLength=80 placeholder='название' js-federal-city='#city_post'></div>
		<div class='colwpl col-xs-1 set_no'><input type='hidden' id='data-oblast_post' name='data-oblast'></div><div class='clearfix'></div>

		
        <div class='colwpl col-xs-8'><label for='raion_post'>Название района <span></span></label><br/><input type='text' class='form-control' size='61' id='raion_post' name='raion_post' value='" . ArrayHelper::Value( $this->Current, 'oblast_post', ArrayHelper::Value( $this->Current, 'oblast' ) ) . "' maxLength=80 placeholder='нет или название района'></div>
		<div class='colwpl col-xs-1 set_no'><input type='hidden' id='data-raion_post' name='data-raion'><a onclick='set_no(\"raion_post\");'>нет</a></div>
		
		<div class='clearfix'></div>
		
		
		<div class='colwpl col-xs-8'><label for='city_post'>Название населенного пункта <span></span></label><br/><input type='text' class='form-control' id='city_post' size='61' name='city_post' value='" . ArrayHelper::Value( $this->Current, 'city_post', ArrayHelper::Value( $this->Current, 'city' ) ) . "' maxLength=80 placeholder='Населеный пункт'>
		<input type='hidden' id='data-city_post' name='data-city'></div>
		<div class='clearfix'></div>
		
		<div class='colwpl col-xs-5'><label for='street_post'>Название улицы <span></span></label><br/><input type='text' class='form-control' size='61' id='street_post' name='street_post' value='" . ArrayHelper::Value( $this->Current, 'street_post', ArrayHelper::Value( $this->Current, 'street' ) ) . "' maxLength=80 placeholder='нет или название улицы'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"street_post\");'>нет</a></div>
        <div class='colwpl col-xs-2'><label for='house_post'>Дом <span></span></label><br/><input type='text' class='form-control' size='61' id='house_post' name='house_post' value='" . ArrayHelper::Value( $this->Current, 'house_post', ArrayHelper::Value( $this->Current, 'house' ) ) . "' maxLength=80 placeholder='24'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"house_post\");'>нет</a></div>
		<div class='colwpl col-xs-2'><label for='korp_post'>Корпус строение <span></span></label><br/><input type='text' class='form-control' size='61' id='korp_post' name='korp_post' value='" . ArrayHelper::Value( $this->Current, 'korp_post', ArrayHelper::Value( $this->Current, 'korp' ) ) . "' maxLength=80 placeholder='корп.2 стр.5'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"korp_post\");'>нет</a></div><div class='clearfix'></div>
		
		<div class='colwpl col-xs-2'><label for='flat_post'>Квартира <span></span></label><br/><input type='text' class='form-control' size='61' id='flat_post' name='flat_post' value='" . ArrayHelper::Value( $this->Current, 'flat_post', ArrayHelper::Value( $this->Current, 'flat' ) ) . "' maxLength=80 placeholder='165'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"flat_post\");'>нет</a></div>
		
		<div class='colwpl col-xs-2'><label for='ind_reg_post'>Индекс <span></span></label><br/><input type='text' class='form-control' size='6' id='ind_reg_post' name='ind_reg_post' value='" . ArrayHelper::Value( $this->Current, 'ind_reg_post', ArrayHelper::Value( $this->Current, 'ind_reg' ) ) . "' maxLength=6 placeholder='00000'></div>
		<div class='colwpl col-xs-1 set_no'><a onclick='set_no(\"ind_reg_post\");'>нет</a></div>
		
		</div>
        
        <div class='clearfix' id='test111222'></div>";

$out .= '<script>' . PHP_EOL . file_get_contents( $_SERVER['DOCUMENT_ROOT'] . '/esia/tpl/js-federal-city.js' ) . PHP_EOL . '</script>';


$out .= "<p style='margin-top:20px;'></p><input id='ch_terr' class='checkbox' type='checkbox' checked='checked'>
		<label for='ch_terr'>Подтверждаю, что я и мои близкие родственники не являемся лицами, указанными в <a target='_blank' href='http://www.consultant.ru/document/cons_doc_LAW_32834/795290b8b6dc6cc4e65f785ccd81c607e4be507d/'>пп.1 п.1 ст. 7.3 Федерального закона от 07.08.2001 N 115-ФЗ</a> 'О противодействии легализации (отмыванию) доходов, полученных преступным путем, и финансированию терроризма'</label>";

ob_start(); require_once $this->DIRRECTORY_ESIA_TPL . 'checkbox.residence.php'; $out .= ob_get_contents(); ob_end_clean();

$out .= "<div id='agr_des_butt' style='margin-top:20px;'>";
$out .= "
        <input type='button' class='submit btn btn_green btn-primary btn-md' value='Подтверждаю' js-agree-form-submit-btn style='display: none;'/>
		 <input type='submit' class='submit btn btn_green btn-primary btn-md' value='Подтверждаю' js-agree-form-submit />
		 <a js-agree-form-submit-manager class='submit btn btn_blue btn-primary btn-md' href='http://nfksber.ru/esia/work1.php?action=desagree_gomanager&code=".$_GET['code']."&state=".$_GET['state']."'>Обратиться к менеджеру</a></p></form></div></div></div>
		 
		 </div>";