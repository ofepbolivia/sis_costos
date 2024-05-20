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


	function listarCuentasProrrateo(){
		//fRnk: HR01014
		$this->objParam->addParametro('tipo_balance', 'resultado');
		$this->objParam->addParametro('incluir_cierre', 'todos');
		$this->objParam->addParametro('incluir_sinmov', 'no');
		$this->objParam->addParametro('nivel', '8');
		$this->objParam->parametros_consulta['filtro']=' 0 = 0 ';
		$this->objFunc = $this->create('sis_contabilidad/MODCuenta');
		$this->res = $this->objFunc->listarBalanceGeneral($this->objParam);
		$data=array();
		if(!empty($this->res->getDatos())){
			foreach ($this->res->getDatos() as $item){
				if(substr($item['nro_cuenta'], 0, 1 ) == '6' && $item['nivel']>4)
					$data[]=$item;
			}
			$tmp=$data;
			if(!empty($this->objParam->getParametro('query'))){
				$data=array();
				foreach ($tmp as $item){
					if(strpos($item['nro_cuenta'], $this->objParam->getParametro('query'))!==false){
						$data[]=$item;
					}
				}
			}
		}
		$this->res->imprimirRespuesta(json_encode(array('total'=>count($data), 'datos'=>$data)));
	}
}

?>
