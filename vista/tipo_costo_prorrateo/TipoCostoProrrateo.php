<?php
/**
 * @package pXP
 * @file gen-TipoCostoCuenta.php
 * @author  (admin)
 * @date 30-12-2016 20:29:17
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.TipoCostoProrrateo = Ext.extend(Phx.gridInterfaz, {

            //hn
            constructor:function(config){
              this.maestro = config.maestro;

                Phx.vista.TipoCostoProrrateo.superclass.constructor.call(this,config);
                this.init();
                //this.loadValoresIniciales();
                this.addButton('btnVentana',
                    {
                        text: 'Prorrateo de Costos',
                        iconCls: 'blist',
                        disabled: true,
                        handler: this.onButtonProrrateo,
                        tooltip: '<b>Ventana Prorrateo Costos</b>'
                    }
                );



            },
        Atributos: [
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_tipo_costo_prorrateo'
                },
                type: 'Field',
                form: true
            },
            {
                config: {
                    name: 'codigo_prorrateo',
                    fieldLabel: 'Cod Prorrateo',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 90,
                    maxLength: 10,
                    style: {
                      background: '#D1FFBD',
                      color:'green',
                      fontWeight: 'bold'
            				},
                    renderer:function (value,p,record){
                      return String.format('<div ext:qtip="Cod. Prorrateo"><center><b><font color="green">{0}</font></b><br></center></div>', record.data['codigo_prorrateo']);
                    }
                },
                type: 'TextField',
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'nombre',
                    fieldLabel: 'Nombre Prorrateo',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 350,
                    maxLength: 200,
                    style: {
                              background: '#C5E5F5',
                              color: 'blue',
                              fontWeight: 'bold'
                              //maxLength: 10
                            },
                    renderer:function (value,p,record){

                      return String.format('<div ext:qtip="Nombre"><b><font color="blue">{0}</font></b><br></div>', record.data['nombre']);
                    }
                },
                type: 'TextField',
                id_grupo: 1,
                grid: true,
                form: true,

            },
            {
                config: {
                    name: 'descripcion',
                    fieldLabel: 'Descripcion Prorrateo',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 350,
                    renderer:function (value,p,record){
                      return String.format('<div ext:qtip="Descripcion"><b><font color="#1881FF">{0}</font></b><br></div>', record.data['descripcion']);
                    },
                    style: {
                              background: '#E6FC7B',
              								color: '#1881FF',
              								fontWeight: 'bold'
                              //maxLength: 10
                            }
                },
                type: 'TextArea',
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_tipo_costo',
                    fieldLabel: 'Id Padre',
                    allowBlank: true,
                    inputType: 'hidden',
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 10
                },
                type: 'Field',
                id_grupo: 1,
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'estado_reg',
                    fieldLabel: 'Estado Reg.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 10
                },
                type: 'TextField',
                filters: {pfiltro: 'prorra.estado_reg', type: 'string'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'usr_reg',
                    fieldLabel: 'Creado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'usu1.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_reg',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'prorra.fecha_reg', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'usr_mod',
                    fieldLabel: 'Modificado por',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'usu2.cuenta', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'fecha_mod',
                    fieldLabel: 'Fecha Modif.',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    format: 'd/m/Y',
                    renderer: function (value, p, record) {
                        return value ? value.dateFormat('d/m/Y H:i:s') : ''
                    }
                },
                type: 'DateField',
                filters: {pfiltro: 'prorra.fecha_mod', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_usuario_ai',
                    fieldLabel: 'Fecha creación',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'prorra.id_usuario_ai', type: 'numeric'},
                id_grupo: 1,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'usuario_ai',
                    fieldLabel: 'Funcionaro AI',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 300
                },
                type: 'TextField',
                filters: {pfiltro: 'prorra.usuario_ai', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            }
        ],
        tam_pag: 50,
        title: 'Crear Nuevo Tipo Costo Prorrateo',
        ActSave: '../../sis_costos/control/TipoCostoProrrateo/insertarTipoCostoProrrateo',
          ActDel: '../../sis_costos/control/TipoCostoProrrateo/eliminarTipoCostoProrrateo',
          ActList: '../../sis_costos/control/TipoCostoProrrateo/listarTipoCostoProrrateo',
          id_store: 'id_tipo_costo_prorrateo',

        fields: [
            {name: 'id_tipo_costo_prorrateo', type: 'numeric'},
            {name: 'codigo_prorrateo', type: 'string'},
            {name: 'nombre', type: 'string'},
            {name: 'descripcion', type: 'string'},
            {name: 'id_tipo_costo', type: 'numeric'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'id_tipo_costo', type: 'numeric'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'estado_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'}


        ],
        sortInfo: {
            field: 'prorra.id_tipo_costo',
            direction: 'DESC'
        },

        bdel: true,
        bsave: true,

        onButtonNew: function () {
            Phx.vista.TipoCostoProrrateo.superclass.onButtonNew.call(this);
            this.getComponente('id_tipo_costo_prorrateo').setValue(this.maestro.id_tipo_costo_prorrateo);

        },

        onReloadPage: function (m) {
                this.maestro=m;
                this.store.baseParams = {id_tipo_costo: this.maestro.id_tipo_costo};
                console.log('hola',this.maestro);
                this.load({params: {start: 0, limit: 50}})

        },
        loadValoresIniciales: function () {
            this.Cmp.id_tipo_costo.setValue(this.maestro.id_tipo_costo);
            Phx.vista.TipoCostoProrrateo.superclass.loadValoresIniciales.call(this);
        },
        onButtonProrrateo: function(){
            var rec = {maestro: this.sm.getSelected().data}
            rec.prorrateo='especifico';


            Phx.CP.loadWindows('../../../sis_costos/vista/prorrateo_cos/ProrrateoCos.php',
                'Cuenta Bancaria del Empleado',
                {
                    width:1000,
                    height:600
                },
                rec,
                this.idContenedor,
                'ProrrateoCos');
        },
    })
</script>
