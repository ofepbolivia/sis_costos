<?php

//fRnk: reporte Costo Unitario de Producción HR01014
class RCostoUnitarioProduccion extends ReportePDF
{
    private $cantidad;
    private $datos_detalle;
    private $desde;
    private $hasta;

    function datosHeader($detalle, $desde, $hasta, $cantidad)
    {
        $this->ancho_hoja = $this->getPageWidth() - PDF_MARGIN_LEFT - PDF_MARGIN_RIGHT - 10;
        $this->datos_detalle = $detalle;
        $this->SetMargins(10, 10);
        $this->desde = $desde;
        $this->hasta = $hasta;
        $this->cantidad = $cantidad;
        //var_dump($this->desde);
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
                       <h4 style="font-size: 12px">DETERMINACIÓN DE COSTO UNITARIO DE PRODUCCIÓN</h4>
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
        $total_cd = 0;
        $total_ci = 0;
        $factor = 1;
        foreach ($this->datos_detalle as $item) {
            if ($item['nivel'] == '1') {
                $total += (float)$item['monto'];
            }
            if ($item['nivel'] == '2') {
                if ($item['nombre'] == 'COSTOS DIRECTOS') {
                    $totales_directo[$item["periodo"]] = $item['monto'];
                    $total_cd += (float)$item['monto'];
                } else {
                    $totales_indirecto[$item["periodo"]] = $item['monto'];
                    $total_ci += (float)$item['monto'];
                }
            }
        }
        if ($total_cd > 0) {
            $factor = $total_ci / $total_cd;
        }
        $mdesde = (int)explode('/', $this->desde)[1];
        $mhasta = (int)explode('/', $this->hasta)[1];
        $html = '<table>';
        $html .= '<tr><td width="115"><b>Periodo de costeo:</b></td><td> Del ' . $this->desde . ' al ' . $this->hasta . '</td></tr>';
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

                $monto_ci = (float)$totales_directo[$i] * $factor;
                $html_ci .= '<td style="text-align: right">' . number_format($monto_ci, 2, ',', '.') . '</td>';
                $total_ci += $monto_ci;
            } else {
                $html_cd .= '<td style="text-align: right">' . number_format(0, 2, ',', '.') . '</td>';
                $html_ci .= '<td style="text-align: right">' . number_format(0, 2, ',', '.') . '</td>';
            }
            /*if (isset($totales_indirecto[$i])) {
                $html_ci .= '<td style="text-align: right">' . number_format($totales_indirecto[$i], 2, ',', '.') . '</td>';
                $total_ci += (float)$totales_indirecto[$i];
            } else {
                $html_ci .= '<td style="text-align: right">' . number_format(0, 2, ',', '.') . '</td>';
            }*/
        }
        $html .= '<tr style="text-align: center;background-color: #ccc"><td><b>DESCRIPCIÓN</b></td>' . $html_meses . '<td><b>TOTAL</b></td></tr>';
        $html .= '<tr><td>Total Costo Directo</td>' . $html_cd . '<td style="text-align: right"><b>' . number_format($total_cd, 2, ',', '.') . '</b></td></tr>';
        $html .= '<tr><td>Total Costo Indirecto prorrateado</td>' . $html_ci . '<td style="text-align: right"><b>' . number_format($total_ci, 2, ',', '.') . '</b></td></tr>';
        $html .= '<tr><td>Cantidad de unidades producidas terminadas</td>' . $html_td . '<td style="text-align: right"><b>' . $this->cantidad . '</b></td></tr>';
        $html .= '<tr><td>Costo unitario</td>' . $html_td . '<td style="text-align: right"><b>' . number_format(($total / $this->cantidad), 2, ',', '.') . '</b></td></tr>';
        $html .= '</table>';
        $this->writeHTML($html, false, false, false, false, '');
    }
}