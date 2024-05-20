<?php
/**
 *fRnk: HR01014
 */

class MODTipoProrrateo extends MODbase
{

    function __construct(CTParametro $pParam)
    {
        parent::__construct($pParam);
    }

    function listarTipoProrrateo()
    {
        $this->procedimiento = 'cos.ft_tipo_prorrateo_sel';
        $this->transaccion = 'COS_TP_SEL';
        $this->tipo_procedimiento = 'SEL';
        $this->captura('id_tipo_prorrateo', 'int4');
        $this->captura('estado_reg', 'varchar');
        $this->captura('nombre', 'varchar');
        $this->captura('id_usuario_reg', 'int4');
        $this->captura('fecha_reg', 'timestamp');
        $this->captura('id_usuario_mod', 'int4');
        $this->captura('fecha_mod', 'timestamp');
        $this->captura('usr_reg', 'varchar');
        $this->captura('usr_mod', 'varchar');
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function insertarTipoProrrateo()
    {
        $this->procedimiento = 'cos.ft_tipo_prorrateo_ime';
        $this->transaccion = 'COS_TP_INS';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('nombre', 'nombre', 'varchar');
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function modificarTipoProrrateo()
    {
        $this->procedimiento = 'cos.ft_tipo_prorrateo_ime';
        $this->transaccion = 'COS_TP_MOD';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('id_tipo_prorrateo', 'id_tipo_prorrateo', 'int4');
        $this->setParametro('estado_reg', 'estado_reg', 'varchar');
        $this->setParametro('nombre', 'nombre', 'varchar');
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;
    }

    function eliminarTipoProrrateo()
    {
        $this->procedimiento = 'cos.ft_tipo_prorrateo_ime';
        $this->transaccion = 'COS_TP_ELI';
        $this->tipo_procedimiento = 'IME';
        $this->setParametro('id_tipo_prorrateo', 'id_tipo_prorrateo', 'int4');
        $this->armarConsulta();
        $this->ejecutarConsulta();
        return $this->respuesta;
    }
}

?>