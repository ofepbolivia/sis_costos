<?php
/**
 *fRnk: HR01014
 */

class ACTTipoProrrateo extends ACTbase
{

    function listarTipoProrrateo()
    {
        $this->objParam->defecto('ordenacion', 'id_tipo_prorrateo');
        $this->objParam->defecto('dir_ordenacion', 'asc');
        if ($this->objParam->getParametro('tipoReporte') == 'excel_grid' || $this->objParam->getParametro('tipoReporte') == 'pdf_grid') {
            $this->objReporte = new Reporte($this->objParam, $this);
            $this->res = $this->objReporte->generarReporteListado('MODTipoProrrateo', 'listarTipoProrrateo');
        } else {
            $this->objFunc = $this->create('MODTipoProrrateo');
            $this->res = $this->objFunc->listarTipoProrrateo($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function insertarTipoProrrateo()
    {
        $this->objFunc = $this->create('MODTipoProrrateo');
        if ($this->objParam->insertar('id_tipo_prorrateo')) {
            $this->res = $this->objFunc->insertarTipoProrrateo($this->objParam);
        } else {
            $this->res = $this->objFunc->modificarTipoProrrateo($this->objParam);
        }
        $this->res->imprimirRespuesta($this->res->generarJson());
    }

    function eliminarTipoProrrateo()
    {
        $this->objFunc = $this->create('MODTipoProrrateo');
        $this->res = $this->objFunc->eliminarTipoProrrateo($this->objParam);
        $this->res->imprimirRespuesta($this->res->generarJson());
    }
}

?>