<?php

//fRnk: reporte Costo Unitario de OT por periodos HR01014
class RCostoUnitarioOTPeriodosPDF extends ReportePDF
{
    private $datos_cabecera;
    private $datos_detalle;
    private $desde;
    private $hasta;

    function datosHeader($cabecera, $detalle, $desde, $hasta)
    {
        $this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT - 10;
        $this->datos_cabecera = $cabecera;
        $this->datos_detalle = $detalle;
        $this->SetMargins(10, 10);
        $this->desde = $desde;
        $this->hasta = $hasta;
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
                       <h4 style="font-size: 12px">DETERMINACIÓN DE COSTO UNITARIO POR ÓRDENES DE TRABAJO</h4>
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
        $total = 0;
        $totales_directo = array();
        $totales_indirecto = array();
        foreach ($this->datos_detalle as $item) {
            //balance: v_resp_mayor = COALESCE(v_sum_debe,0) - COALESCE(v_sum_haber,0);
            $monto = (float)$item['importe_debe_mb'] - (float)$item['importe_haber_mb'];
            /*$total += $monto; //válido sin prorrateo
            $total += (float)$item['monto'];
            if ($item['tipo_costo'] == 'DIRECTO') {
                $totales_directo[$item["mes"]] = $monto;
            } else {
                $totales_indirecto[$item["mes"]] = $monto;
            }*/
            if ($item['tipo_costo'] == 'DIRECTO') { // valido con prorrateo
                $totales_directo[$item["mes"]] = $monto;
                $totales_indirecto[$item["mes"]] = round( $monto*$item['factor'], 2);
                $total += $monto;
                $total += $totales_indirecto[$item["mes"]];
            }
        }
        $mdesde = (int)explode('/', $this->desde)[1];
        $mhasta = (int)explode('/', $this->hasta)[1];
        $html = '<table>';
        $html .= '<tr><td width="115"><b>Código OT:</b></td><td>' . $this->datos_cabecera[0]['codigo'] . '</td></tr>';
        $html .= '<tr><td><b>Descripción Orden:</b></td><td>' . $this->datos_cabecera[0]['desc_orden'] . '</td></tr>';
        $html .= '<tr><td><b>Motivo Orden:</b></td><td>' . $this->datos_cabecera[0]['motivo_orden'] . '</td></tr>';
        $html .= '</table><p></p>';
        $html .= '<table border="1" cellpadding="2" style="font-size: 9px">';
        $mes = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
        $html_meses = '';
        $html_cd = '';
        $html_ci = '';
        $html_td = '';
        $total_cd = 0;
        $total_ci = 0;
        for ($i = $mdesde; $i <= $mhasta; $i++) {
            $html_meses .= '<td><b>' . $mes[$i - 1] . '</b></td>';
            $html_td .= '<td></td>';
            if (isset($totales_directo[$i])) {
                $html_cd .= '<td style="text-align: right">' . number_format($totales_directo[$i], 2, ',', '.') . '</td>';
                $total_cd += (float)$totales_directo[$i];
            } else {
                $html_cd .= '<td style="text-align: right">' . number_format(0, 2, ',', '.') . '</td>';
            }
            if (isset($totales_indirecto[$i])) {
                $html_ci .= '<td style="text-align: right">' . number_format($totales_indirecto[$i], 2, ',', '.') . '</td>';
                $total_ci += (float)$totales_indirecto[$i];
            } else {
                $html_ci .= '<td style="text-align: right">' . number_format(0, 2, ',', '.') . '</td>';
            }
        }
        $cantidad_ot = empty($this->datos_cabecera[0]['cantidad_ot']) ? 1 : $this->datos_cabecera[0]['cantidad_ot'];
        $html .= '<tr style="text-align: center;background-color: #ccc"><td><b>DESCRIPCIÓN</b></td>' . $html_meses . '<td><b>TOTAL</b></td></tr>';
        $html .= '<tr><td>Total Costo Directo</td>' . $html_cd . '<td style="text-align: right"><b>' . number_format($total_cd, 2, ',', '.') . '</b></td></tr>';
        $html .= '<tr><td>Total Costo Indirecto prorrateado</td>' . $html_ci . '<td style="text-align: right"><b>' . number_format($total_ci, 2, ',', '.') . '</b></td></tr>';
        $html .= '<tr><td>Cantidad de unidades producidas terminadas</td>' . $html_td . '<td style="text-align: right"><b>' . $cantidad_ot . '</b></td></tr>';
        $html .= '<tr><td>Costo unitario</td>' . $html_td . '<td style="text-align: right"><b>' . number_format(($total / $cantidad_ot), 2, ',', '.') . '</b></td></tr>';
        $html .= '</table>';
        $this->writeHTML($html, false, false, false, false, '');
    }
}