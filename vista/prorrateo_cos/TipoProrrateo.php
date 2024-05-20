<?php
/**
 *fRnk: nueva vista HR01014
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.TipoProrrateo = Ext.extend(Phx.gridInterfaz, {
            constructor: function (config) {
                this.maestro = config.maestro;
                Phx.vista.TipoProrrateo.superclass.constructor.call(this, config);
                this.init();
                this.load({params: {start: 0, limit: 50}})
            },
            Atributos: [
                {
                    config: {
                        labelSeparator: '',
                        inputType: 'hidden',
                        name: 'id_tipo_prorrateo'
                    },
                    type: 'Field',
                    form: true
                },
                {
                    config: {
                        name: 'nombre',
                        fieldLabel: 'Nombre',
                        allowBlank: true,
                        anchor: '80%',
                        gwidth: 250,
                        maxLength: 150
                    },
                    type: 'TextField',
                    filters: {pfiltro: 'emp.nombre', type: 'string'},
                    id_grupo: 1,
                    grid: true,
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
                    filters: {pfiltro: 'emp.estado_reg', type: 'string'},
                    id_grupo: 1,
                    grid: true,
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
                    type: 'NumberField',
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
                    filters: {pfiltro: 'emp.fecha_reg', type: 'date'},
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
                    type: 'NumberField',
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
                    filters: {pfiltro: 'emp.fecha_mod', type: 'date'},
                    id_grupo: 1,
                    grid: true,
                    form: false
                }
            ],

            preparaMenu: function (n) {
                var tb = Phx.vista.TipoProrrateo.superclass.preparaMenu.call(this, n);
                return tb
            },
            liberaMenu: function (n) {
                var tb = Phx.vista.TipoProrrateo.superclass.liberaMenu.call(this, n);
                return tb
            },
            title: 'Tipo de Prorrateo',
            ActSave: '../../sis_costos/control/TipoProrrateo/insertarTipoProrrateo',
            ActDel: '../../sis_costos/control/TipoProrrateo/eliminarTipoProrrateo',
            ActList: '../../sis_costos/control/TipoProrrateo/listarTipoProrrateo',
            id_store: 'id_tipo_prorrateo',
            fields: [
                {name: 'id_tipo_prorrateo', type: 'numeric'},
                {name: 'estado_reg', type: 'string'},
                {name: 'nombre', type: 'string'},
                {name: 'id_usuario_reg', type: 'numeric'},
                {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
                {name: 'id_usuario_mod', type: 'numeric'},
                {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
                {name: 'usr_reg', type: 'string'},
                {name: 'usr_mod', type: 'string'}, 'codigo'

            ],
            sortInfo: {
                field: 'id_tipo_prorrateo',
                direction: 'ASC'
            },
            bdel: true,
            bsave: true
        }
    )
</script>		
		
