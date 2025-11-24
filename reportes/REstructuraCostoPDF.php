<?php

//NMQ: new report by HR 2024-01237
class REstructuraCostoPDF extends ReportePDF
{
    var $datos_titulo;
    var $datos_detalle;
    var $ancho_hoja;
    var $gestion;

    function datosHeader($detalle)
    {
        $this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT - 10;
        $this->datos_detalle = $detalle;
        $this->gestion = $detalle[0]['id_gestion'];
        $this->SetMargins(10, 10);
    }

    function Header()
    {
        if ($this->page == 1) {
            $content = '<table border="0" cellpadding="1" style="font-size: 10px;">
                <tr>
                    <td style="width: 23%; color: #222;">
                        &nbsp;<img  style="width: 120px;" src="./../../../lib/' . $_SESSION['_DIR_LOGO'] . '">
                    </td>		
                    <td style="width: 54%; color: #222;text-align: center">
                       <h4 style="font-size: 12px">ESTRUCTURA DE COSTOS</h4>
                       <span style="font-size: 11px">GESTION ' . $this->gestion . '</span>
                    </td>
                    
                </tr>
            </table>';
            $this->writeHTMLCell(0, 10, 5, 4, $content, 0, 0, 0, true, 'L', true);
        }
    }

    function generarReporte()
    {
        $this->SetFontSize(9);
        $this->AddPage();
        $this->Ln(24);
        $this->SetMargins(10, 10);
        $html = '<table border="0" cellpadding="1" style="font-size: 10px;">';
        $html .= '<tr><td width="8%"><b>CODIGO</b></td>';
        $html .= '<td width="30%"><b>DESCRIPCION</b></td>';
        $html .= '<td width="8%"><b>TIPO</b></td>';
        $html .= '<td width="30%"><b>RELACION CTA CONTABLE</b></td>';
        $html .= '<td width="24%"><b>AUXILIAR</b></td></tr>';
        foreach ($this->datos_detalle as $val) {
            $html .= '<tr><td>'. $val['codigo'] . '</td>';
            $html .= '<td>'. $val['nombre'] . '</td>';
            $html .= '<td>'. $val['sw_trans'] . '</td>';
            $html .= '<td>'. $val['cta_contable'] . '</td>';
            $html .= '<td>'. $val['auxiliar'] . '</td></tr>';
        }
        $html .= '</table>';
        $this->writeHTML($html, false, false, false, false, '');
    }
}