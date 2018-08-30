<?php
/**
*@package pXP
*@file gen-MODTipoCostoProrrateo.php
*@author  (admin)
*@date 30-12-2016 20:29:17
*@description Clase que envia los parametros requeridos a la Base de datos para la ejecucion de las funciones, y que recibe la respuesta del resultado de la ejecucion de las mismas
*/

class MODTipoCostoProrrateo extends MODbase{

	function __construct(CTParametro $pParam){
		parent::__construct($pParam);
	}

	function listarTipoCostoProrrateo(){
		//Definicion de variables para ejecucion del procedimientp
		$this->procedimiento='cos.ft_tipo_costo_prorrateo_sel';
		$this->transaccion='COS_PRORRA_SEL';
		$this->tipo_procedimiento='SEL';//tipo de transaccion

		//Definicion de la lista del resultado del query
		$this->captura('id_tipo_costo_prorrateo','int4');
		$this->captura('codigo_prorrateo','varchar');
		$this->captura('nombre','varchar');
		$this->captura('descripcion','varchar');
		$this->captura('id_tipo_costo','int4');
		$this->captura('id_usuario_reg','int4');
		$this->captura('id_usuario_mod','int4');
		$this->captura('fecha_reg','timestamp');
		$this->captura('fecha_mod','timestamp');
		$this->captura('estado_reg','varchar');
		$this->captura('id_usuario_ai','int4');
		$this->captura('usuario_ai','varchar');
		$this->captura('usr_reg','varchar');
		$this->captura('usr_mod','varchar');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();
		//var_dump($this->respuesta); exit;
		//Devuelve la respuesta
		return $this->respuesta;
	}

	function insertarTipoCostoProrrateo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_tipo_costo_prorrateo_ime';
		$this->transaccion='COS_PRORRA_INS';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo_prorrateo','codigo_prorrateo','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('id_tipo_costo','id_tipo_costo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function modificarTipoCostoProrrateo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_tipo_costo_prorrateo_ime';
		$this->transaccion='COS_PRORRA_MOD';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_tipo_costo_prorrateo','id_tipo_costo_prorrateo','int4');
		$this->setParametro('estado_reg','estado_reg','varchar');
		$this->setParametro('codigo_prorrateo','codigo_prorrateo','varchar');
		$this->setParametro('nombre','nombre','varchar');
		$this->setParametro('descripcion','descripcion','varchar');
		$this->setParametro('id_tipo_costo','id_tipo_costo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}

	function eliminarTipoCostoProrrateo(){
		//Definicion de variables para ejecucion del procedimiento
		$this->procedimiento='cos.ft_tipo_costo_prorrateo_ime';
		$this->transaccion='COS_PRORRA_ELI';
		$this->tipo_procedimiento='IME';

		//Define los parametros para la funcion
		$this->setParametro('id_tipo_costo_prorrateo','id_tipo_costo_prorrateo','int4');

		//Ejecuta la instruccion
		$this->armarConsulta();
		$this->ejecutarConsulta();

		//Devuelve la respuesta
		return $this->respuesta;
	}


}
?>
