<?php
namespace App;

class MyPDF extends \setasign\Fpdi\Fpdi
{

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-16.6);
        // Arial italic 8
        $this->SetFont('Times','',7.5);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'C');
    }
}
