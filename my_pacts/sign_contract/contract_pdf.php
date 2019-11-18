<?require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/libraries/libPDFgen/tcpdf.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/response/ajax/class/get_pdf.php");

$id = intval($_GET['ID']);
#получаем текст контракта
$contract = new GetPdf();
$contractText =  $contract->getSendContractText($id);
if(!empty($contractText['UF_CANTRACT_IMG'])){
    foreach ($contractText['UF_CANTRACT_IMG'] as $img){
        $arImgContract[] = CFile::GetPath($img);
    }
    $html .= $contract->getSendContractText($id);
}
else{
    $html .= $contractText['UF_TEXT_CONTRACT'];
}
$signHtml = $contract->getSendContractItem($id)['TEXT'];

class MYPDF extends TCPDF {
    // Page footer

    public function Footer() {
        global $signHtml;
        // Position at 15 mm from bottom
        $this->SetY(-25);
        // Set font
        $this->SetFont('dejavusans', '', 10);

        $this->writeHTML($signHtml, true, false, true, 0);
    }
}



$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
/*$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 021');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');*/

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Договор', PDF_HEADER_STRING);
$pdf->SetPrintHeader(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);


if(!empty($arImgContract)){
    foreach ($arImgContract as $url){
        //new dBug($url);
        $arParmaImg = [
            'EXTENSION' => pathinfo($_SERVER['DOCUMENT_ROOT'].$url, PATHINFO_EXTENSION),
            'SIZE' => getimagesize($_SERVER['DOCUMENT_ROOT'].$url)
        ];

        // add a page
        $pdf->AddPage();
        $pdf->Image($_SERVER['DOCUMENT_ROOT'].$url, 15, 15, $arParmaImg['SIZE'][0], $arParmaImg['SIZE'][1], $arParmaImg['EXTENSION'], '', '', false, 300, '', false, false, 0, false, false, true);
    }
}
else{
    $pdf->AddPage();
    // output the HTML content
    $pdf->writeHTML($html, true, 0, true, 0);
}

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('dogovor.pdf', 'I');
exit();
