<?php

namespace App;


use Cezpdf;
use Throwable;
use App\Interfejsi\PdfInterfejs;

class PdfRos implements PdfInterfejs
{

    public function stampaj($podaci)
    {
        try {
            $pdf = new Cezpdf('a4', 'portrait');
            $pdf->isUnicode = true;
            $pdf->allowedTags .= '|uline';
            $mainFont = 'FreeSerif';
            $family = array(
                'b' => $mainFont . 'Bold'
            );
            $pdf->setFontFamily($mainFont, $family);

            $pdf->selectFont($mainFont, '', 1, true);

            $pdf->ezSetMargins(40, 40, 40, 40);
            $pdf->setLineStyle(2, 'round');
            $pdf->line(78, 765, 522, 765);
            $pdf->line(72, 60, 522, 60);


            $pdf->ezText('Putno osiguranje br:' . $podaci[0]['id'], 20, ['justification' => 'center']);
            $pdf->ezSetDy(-17);

            $pdf->ezTable($podaci, '', '', [
                'gridlines' => EZ_GRIDLINE_DEFAULT,
                'shadeHeadingCol' => [0.6, 0.6, 0.5],
                'alignHeadings' => 'center',
                'width' => 600,
                'cols' => [
                    'name' => ['bgcolor' => [0.9, 0.9, 0.7]],
                    'type' => ['bgcolor' => [0.6, 0.4, 0.2]]
                ]
            ]);


            //$pdf->ezStream();
            $file = $pdf->output();
            $imeFajla = "./polisePdf/polisa" . time() . ".pdf";
            $myfile = fopen($imeFajla, "w");
            $myfile = fwrite($myfile, $file);
            return $imeFajla;
        } catch (Throwable $e) {
            die("Doslo je do greske prilikom kreiranja PDF fajla");
        }
    }
}
