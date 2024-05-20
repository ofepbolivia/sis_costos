<?php

//fRnk: reporte nuevo Costo Unitario por órdenes de trabajo HR01008
class RCostoUnitarioOTPDF extends ReportePDF
{
    var $datos_titulo;
    var $datos_detalle;
    var $desde;
    var $hasta;
    var $nivel;
    var $ancho_hoja;
    var $gerencia;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;
    var $codigos;

    function datosHeader($detalle)
    {
        $this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT - 10;
        $this->datos_detalle = $detalle;
        $this->SetMargins(10, 10);
        //var_dump($detalle);exit;
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
                       <h4 style="font-size: 12px">DETERMINACIÓN DE COSTO UNITARIO POR ORDENES DE TRABAJO</h4>
                       <span style="font-size: 11px">(Expresado en Bolivianos)</span>
                    </td>
                    <td></td>
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
        $html = '<table width="550" cellpadding="2">';
        foreach ($this->datos_detalle as $val) {
            $f1 = new DateTime($val['fecha_inicio']);
            $f2 = new DateTime($val['fecha_final']);
            $dif = $f2->diff($f1)->format('%a');
            $costo_acumulado = empty($val['costo_acumulado']) ? 0 : $val['costo_acumulado'];
            $cantidad_ot = empty($val['cantidad_ot']) ? 1 : $val['cantidad_ot'];
            $html .= '<tr><td width="25%"><b>Código de la orden:</b></td><td> ' . $val['codigo'] . '</td></tr>';
            $html .= '<tr><td width="25%"><b>Descripción:</b></td><td> '. $val['motivo_orden'] . '</td></tr>';
            $html .= '<tr><td><b>Tiempo de elaboración:</b></td><td> ' . $dif . ' días &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <table>
                     <tr><td><b>Inicio:</b></td><td>'.implode('/', array_reverse(explode('-', $val['fecha_inicio']))).'</td></tr>
                     <tr><td><b>Conclusión:</b></td><td>'.implode('/', array_reverse(explode('-', $val['fecha_final']))).'</td></tr>
                    </table>
                </td></tr>';
            $html .= '<tr><td><b>Costo acumulado:</b></td><td> ' . number_format($costo_acumulado, 2, ',', '.') . '</td></tr>';
            $html .= '<tr><td><b>Cantidad del pedido:</b></td><td> ' . $cantidad_ot . '</td></tr>';
            $html .= '<tr><td><b>Costo unitario:</b></td><td> ' . number_format(($costo_acumulado / $cantidad_ot), 2, ',', '.') . '<br><br><br><br></td></tr>';
        }
        $html .= '</table>';
        $this->writeHTML($html, false, false, false, false, '');
    }
}