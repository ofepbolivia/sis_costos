<?php
/**
*@package pXP
*@file gen-ACTTipoCostoProrrateo.php
*@author  (admin)
*@date 30-12-2016 20:29:17
*@description Clase que recibe los parametros enviados por la vista para mandar a la capa de Modelo
*/

class ACTTipoCostoProrrateo extends ACTbase{

	function listarTipoCostoProrrateo(){
		$this->objParam->defecto('ordenacion','id_tipo_costo_prorrateo');
        $this->objParam->defecto('dir_ordenacion','asc');

        if($this->objParam->getParametro('id_tipo_costo') != '') {
            $this->objParam->addFiltro(" prorra.id_tipo_costo = " . $this->objParam->getParametro('id_tipo_costo'));
        }
        if($this->objParam->getParametro('tipoReporte')=='excel_grid' || $this->objParam->getParametro('tipoReporte')=='pdf_grid'){
			$this->objReporte = new Reporte($this->objParam,$this);
			$this->res = $this->objReporte->generarReporteListado('MODTipoCostoProrrateo','listarTipoCostoProrrateo');
		} else{
			$this->objFunc=$this->create('MODTipoCostoProrrateo');

			$this->res=$this->objFunc->listarTipoCostoProrrateo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function insertarTipoCostoProrrateo(){
		$this->objFunc=$this->create('MODTipoCostoProrrateo');
		if($this->objParam->insertar('id_tipo_costo_prorrateo')){
			$this->res=$this->objFunc->insertarTipoCostoProrrateo($this->objParam);
		} else{
			$this->res=$this->objFunc->modificarTipoCostoProrrateo($this->objParam);
		}
		$this->res->imprimirRespuesta($this->res->generarJson());
	}

	function eliminarTipoCostoProrrateo(){
			$this->objFunc=$this->create('MODTipoCostoProrrateo');
		$this->res=$this->objFunc->eliminarTipoCostoProrrateo($this->objParam);
		$this->res->imprimirRespuesta($this->res->generarJson());
	}





}

?>
