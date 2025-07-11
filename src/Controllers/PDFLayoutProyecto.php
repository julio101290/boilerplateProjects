<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace julio101290\boilerplateprojects\Controllers;

/**
 * Description of PDFLayout
 *
 * @author hp
 */
class PDFLayoutProyecto extends \TCPDF {

    public $nombreEmpresa = "";
    public $direccion = "";
    public $usuario = "";
    public $nombreProyecto = "";
    public $logo = "";
    public $telefono = "";
    public $email = "";
    public $nombreResponsable = "";

    public function __construct() {
        parent::__construct();
        helper('utilerias');
    }

    public function Header() {
        $fecha = date('d/m/Y');

        $fecha = fechaHumanizada($fecha);

        /*
          $documento = "Hoja de Inspeccion";
          $logo = ROOTPATH."public/logo.png";

          $direccion1 = "Ave. Estrella Sadhala No. 204, Santiago";
          $telefono1 = "(809)-724-4300";

          $direccion2 = "Ave. Charles Summer No. 21, Santo Domingo";
          $telefono2 = "(809)-566-6191";

          $email = "ohtsudelcaribe@gmail.com";
         */

        $direccion = $this->direccion;
        $telefono = $this->telefono;
        $email = $this->email;
        $logo = $this->logo;

        //$this->writeHTML($fecha, false, false, false, false, 'L');
        $this->SetFont('dejavusans', 'B', 14);
        $this->writeHTML($this->nombreEmpresa, true, false, false, false, 'C');

        $this->SetFont('dejavusans', '', 12);

        $this->writeHTML($this->direccion, true, false, false, false, 'C');
        $this->Image($logo, 15, 6, 25, '', 'png', '', 'T', false, 300, '', false, false, 0, false, false, false);

        $this->SetY(16);
        $this->writeHTML($this->telefono, true, false, false, false, 'C');

        $this->SetFont('dejavusans', 'B', 12);
        $this->writeHTML("Presupuesto", true, false, false, false, 'C');

        $this->Image($logo, 15, 6, 25, '', 'png', '', 'T', false, 250, '', false, false, 0, false, false, false);

        $this->SetY(35);
        $this->SetFont('dejavusans', 'B', 10);
        $this->writeHTML("NOMBRE PROYECTO:", true, false, false, false, 'L');

        $this->SetY(40);
        $this->SetFont('dejavusans', '', 10);
        $this->writeHTML($this->nombreProyecto, true, false, false, false, 'L');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, $this->nombreDocumento . " " . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        $this->SetY(-40);
        // Set font
        $this->SetFont('dejavusans', '', 10);
        // Page number
        $this->Cell(0, 10, "____________________________________________", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
          $this->SetY(-60);
         $this->Cell(0, 10, "ATENTAMENTE", 0, false, 'C', 0, '', 0, false, 'T', 'M');
        
        $this->SetY(-35);
        $this->Cell(0, 10, $this->nombreResponsable, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
