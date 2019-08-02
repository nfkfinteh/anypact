<?php

class createPDF  extends TCPDF
{
    
    public function createFile($arrTemplate){       

        // Extend the TCPDF class to create custom Header and Footer

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
    }
    
}

