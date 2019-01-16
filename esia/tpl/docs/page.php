<?php
/**
 * Created by PhpStorm.
 * User: Yakov
 * Date: 24.04.2017
 * Time: 8:10
 */

/** @var \EsiaCore $this */

$out = '';

ob_start(); require $this->DIRRECTORY_ESIA_TPL . 'steep.progress.cc.php'; $outx .= ob_get_contents(); ob_end_clean();

$out = $outx;

$out .= "<form method='get' action='" . $this->URL('docsagree') . "' id='docs'><div class='colwpp col-xs-12 docs_descr'>Пожалуйста, выберите опции для торговли</div>

         <div class='colwpp col-xs-12 docs_title'><h4>Выберите сектор обслуживания</h4></div>";


$out .= "
		<div class='colwpp col-xs-5 docs-marg-r'>
		<input id='market_stock' name='market_stock' class='checkbox' type='checkbox' js-iis-change-handle ".XChecked($this, 'market_stock', 1, true ).">
		<label for='market_stock'>Рынок ценных бумаг</label> 
        <p class='ht30' align='justify'>Подтверждаю, что ознакомлен со всеми положениями <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/tipovyie_reglamentyi_opredelyayushhie_poryadok_okazaniya_uslug_na_ryinke_czennyix_bumag/'>Регламента брокерского обслуживания</a>, <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/usloviya_osushhestvleniya_depozitarnoj_deyatelnosti/'>Условиями осуществления депозитарной деятельностии</a>, образцами <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/obrazczyi_dogovorov,_predlagaemyie_klientam_pri_predostavlenii_im_uslug/'>договоров</a>, 
		предлагаемых клиентам при предоставлении им услуг АО «НФК-Сбережения» и обязуюсь соблюдать их.</p>		
        </div>";
/**
$out .= "
		<div class='colwpp col-xs-6'>
		<input id='market_outstock' name='market_outstock' class='checkbox' type='checkbox' js-iis-change-handle  ".XChecked($this, 'market_outstock', 1, true ).">
		<label for='market_outstock'>Внебиржевой рынок</label> 
        <p class='ht30'>Подтверждаю, что ознакомлен со всеми положениями <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/tipovyie_reglamentyi_opredelyayushhie_poryadok_okazaniya_uslug_na_ryinke_czennyix_bumag/'>Регламента о порядке осуществления действий на внебиржевом рынке ценных бумаг</a> и образцами <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/obrazczyi_dogovorov,_predlagaemyie_klientam_pri_predostavlenii_im_uslug/'>договоров</a>, 
		предлагаемых клиентам при предоставлении им услуг АО «НФК-Сбережения» и обязуюсь соблюдать их. </p> 		
        </div><div class='clearfix'><br/></div>"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;;
*/

$out .= "
		<div class='colwpp col-xs-5 docs-marg-l'>
		<input id='market_valuta' name='market_valuta' class='checkbox' type='checkbox'  ".XChecked($this, 'market_valuta', 1, true ).">
		<label for='market_valuta'>Валютный рынок и рынок драгметаллов</label>
        <p class='ht30' align='justify'>Подтверждаю, что ознакомлен со всеми положениями <a target='_blank'  href='http://nfksber.ru/company/raskrytie_informacii/tipovyie_reglamentyi_opredelyayushhie_poryadok_okazaniya_uslug_na_ryinke_czennyix_bumag/'>Регламента обслуживания на валютном рынке и рынке драгоценных металлов</a> и образцами <a target='_blank'  href='http://nfksber.ru/company/raskrytie_informacii/obrazczyi_dogovorov,_predlagaemyie_klientam_pri_predostavlenii_im_uslug/'>договоров</a>, предлагаемых клиентам при предоставлении им услуг АО &laquo;НФК-Сбережения&raquo; и обязуюсь соблюдать их.</p>
        </div><div class='clearfix'><br/></div>";

$out .= "
		<div class='colwpp col-xs-5 docs-marg-r'>
		<input id='market_other' name='market_other' class='checkbox' type='checkbox' ".XChecked($this, 'market_other', 1, false ).">
		<label for='market_other'>Иное (для желающих заключить дополнительный депозитарный &nbsp;&nbsp;&nbsp; договор)</label>  
		<div class='style'>
		<p class='ht30' align='justify' padding-right='20px'>Подтверждаю, что ознакомлен со всеми положениями <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/usloviya_osushhestvleniya_depozitarnoj_deyatelnosti/'>Условий осуществления депозитарной деятельности</a> и образцами <a target='_blank' href='http://nfksber.ru/company/raskrytie_informacii/obrazczyi_dogovorov,_predlagaemyie_klientam_pri_predostavlenii_im_uslug/'>договоров</a>, предлагаемых клиентам при предоставлении им услуг АО &laquo;НФК-Сбережения&raquo; и обязуюсь соблюдать их.</p>
		</div>
        </div><div class='clearfix'></div>";

$out .= '<script>' . PHP_EOL . file_get_contents( $this->DIRRECTORY_ESIA_TPL . 'js-docks-update.js' ) . PHP_EOL . '</script>';

ob_start();

require_once $this->DIRRECTORY_ESIA_TPL . 'other.document.types.php';

$out .= ob_get_contents();

ob_end_clean();

ob_start(); require_once $this->DIRRECTORY_ESIA_TPL . 'contract.iis.php'; $out .= ob_get_contents(); ob_end_clean();

$out .= "<div class='clearfix'><br/></br/></div><div class='colwpp col-xs-12'><input id='btn_sfdocs' type='submit' class='btn btn_green btn-primary btn-md' value='Сформировать документы' style='display: none' /></div>";

$out .= "</form>";

require_once $this->DIRRECTORY_ESIA_CORE . 'out.php';