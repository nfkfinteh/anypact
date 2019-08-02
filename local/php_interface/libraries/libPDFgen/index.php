<?php
require_once('tcpdf.php');
require_once('class/createpdf.php');

function format_date($date){
	$arr_name_mons = array(
		1 => 'января',
		2 => 'февраля',
		3 => 'марта',
		4 => 'апреля',
		5 => 'мая',
		6 => 'июня',
		7 => 'июля',
		8 => 'августа',
		9 => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12  => 'декабря'
	);
	$day = substr($date, 0, 2);
	$ind_mons = substr($date, 3, 4);
	$ind_mons = (int) $ind_mons;
	echo 'mons '.$ind_mons;
	
	$Year = substr($date, 6, 8);
	$mons = $arr_name_mons[$ind_mons];
	$dateform = $day.' '.$mons.' '.$Year.' г.';

	return $dateform;
}

$data = format_date(date("d.m.Y"));

$array_terms = array(
	'EMITENT' => 'Контрагент по опционному договору: НФК-СИ, ООО ИНН: 2130207144 ОГРН: 1182130013669',
	'COUNT_SP' => 15,	
	'FIO_USER' => 'Соловьев Игорь Владимирович',
	'CONTRACT_USER' => 'Договор универсального инвестиционного счета У/1365',
	'DATA' => $data, 
	'PASS' => '9704 058308 выдан: Алатырский ГОВД Чувашской Республики'

);



$str = 45525;
echo '<br>';
echo sprintf("%08d", $str);


$CODE_TEMPLATE = 'option';

$dir_template = 'D:\xampp\htdocs\pdfgenerator\template\\'.$CODE_TEMPLATE.'\\';
$templates = scandir($dir_template, 0);

// обработка шаблонов
$arrTemplate = array();
$content_page_inst = file_get_contents('template/'.$CODE_TEMPLATE.'/01_page.html');
foreach ($array_terms as $key => $value) {
	$content_page_inst = str_replace('%'.$key.'%', $value, $content_page_inst);
}

$content_page_contr = file_get_contents('template/'.$CODE_TEMPLATE.'/02_page.html');
foreach ($array_terms as $key => $value) {
	$content_page_contr = str_replace('%'.$key.'%', $value, $content_page_contr);
}

$content_page  = $content_page_inst;
$content_page .= $content_page_contr;

$arrTemplate[] = $content_page;

$content_page = file_get_contents('template/'.$CODE_TEMPLATE.'/03_page.html');

$arrTemplate[] = $content_page;

// класс по формированию пдф
//$CreatePDF = new createPDF();

//$CreatePDF->createFile($arrTemplate);
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'logo_example.jpg';
		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
		$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(' NFK-Save');
$pdf->SetTitle('TCPDF Example 001');

$pdf->setPrintFooter('hiuhiuhiouh');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

foreach ($arrTemplate as $page) {
		
	// add a page
	$pdf->AddPage();	

	// set UTF-8 Unicode font
	$pdf->SetFont('freeserif', '');

	// output the HTML content
	$pdf->writeHTML($page, true, false, true, false, '');

}

// reset pointer to the last page
$pdf->lastPage();

$pdf->Output('D:\xampp\htdocs\pdfgenerator\example_001.pdf', 'F');
//============================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Просмотр </h1>

<div style="width: 960px; margin: 0 auto;">   
	<embed src="example_001.pdf" width="100%" height="800"  type="application/pdf">
</div>



</body>
</html>

