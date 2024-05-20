<?php
/**
 * @package pXP
 * @file gen-ProrrateoCos.php
 * @author  (franklin.espinoza)
 * @date 25-08-2017 19:34:27
 * @description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    var id_deptos=[];
    var codigos=[];
    Phx.vista.ProrrateoCos = Ext.extend(Phx.gridInterfaz, {
        //desc_gestion: '',
        fheight : '60%',
        fwidth : '670',
        constructor: function (config) {
            this.maestro = config.maestro;
            var prorrateo = config.prorrateo;
            //llama al constructor de la clase padre
            Phx.vista.ProrrateoCos.superclass.constructor.call(this, config);
            this.init();
            console.log('config', config);
            var that = this;
            Ext.Ajax.request({
                url: '../../sis_parametros/control/Gestion/obtenerGestionByFecha',
                params: {fecha: new Date()},
                success: function (resp) {
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    if (!reg.ROOT.error) {
                        console.log('config', that.prorrateo);
                        if (that.prorrateo == undefined) {
                            this.store.baseParams.prorrateo = 'general';
                        } else {
                            this.store.baseParams.prorrateo = that.prorrateo;
                            this.store.baseParams.id_tipo_costo_prorrateo = that.maestro.id_tipo_costo_prorrateo;
                            console.log('config', that.prorrateo, that.maestro.id_tipo_costo_prorrateo, this.maestro.id_tipo_costo_prorrateo);
                        }
                        this.load({params: {start: 0, limit: this.tam_pag}});
                    } else {

                        alert('Ocurrio un error al obtener la Gestión')
                    }
                },
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
            Ext.Ajax.request({
                url: '../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
                params: {"start":"0","limit":"100","sort":"deppto.nombre","dir":"ASC","par_filtro":"deppto.nombre#deppto.codigo","estado":"activo","codigo_subsistema":"CONTA","query":""},
                success: function (resp) {
                    var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
                    if (typeof reg != "undefined") {
                        reg.datos.forEach((item) => {
                            id_deptos.push(item.id_depto);
                            codigos.push(item.codigo);
                        });
                    }
                },
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
            this.iniciarEventos();
            //this.cmbGestion.on('select',this.capturarEventos, this);
        },
        Grupos: [
            {
                layout: 'column',
                labelWidth: 80,
                labelAlign: 'top',
                border: false,
                items: [
                    {
                        columnWidth: .50,
                        border: false,
                        layout: 'fit',
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'Codigo y Nombre',

                                autoHeight: true,
                                items: [
                                    {
                                        layout: 'form',
                                        anchor: '100%',
                                        border: false,
                                        padding: '0 5 0 5',
                                        //bodyStyle: 'padding-left:5px;',
                                        id_grupo: 1,
                                        items: []
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        columnWidth: .50,
                        border: false,
                        layout: 'fit',
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'Tipo Cálculo y Pertenece',

                                autoHeight: true,
                                items: [
                                    {
                                        layout: 'form',
                                        anchor: '100%',
                                        //bodyStyle: 'padding-right:10px;',
                                        border: false,
                                        padding: '0 5 0 5',
                                        //bodyStyle: 'padding-left:5px;',
                                        id_grupo: 2,
                                        items: []
                                    }
                                ]
                            }
                        ]
                    },
                    /*{ //fRnk: comentado prorrateo
                        columnWidth: .50,
                        border: false,
                        layout: 'fit',
                        bodyStyle: 'padding-right:10px;',
                        items: [
                            {
                                xtype: 'fieldset',
                                title: 'Importe a distribuir',

                                autoHeight: true,
                                items: [
                                    {
                                        layout: 'form',
                                        anchor: '100%',
                                        border: false,
                                        padding: '0 5 0 5',
                                        id_grupo: 3,
                                        items: []
                                    }
                                ]
                            }
                        ]
                    },*/
                ]
            }
        ],

        Atributos: [
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_prorrateo'
                },
                type: 'Field',
                form: true
            },

            {
                config: {
                    name: 'codigo',
                    fieldLabel: 'Código',
                    labelStyle: 'font-weight:bold; color:#005300;',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 20,
                    style: {
                        background: '#D1FFBD',
                        color: 'green',
                        fontWeight: 'bold'
                    },
                    renderer: function (value, p, record) {

                        return String.format('<div ext:qtip="Codigo"><b><font color="green">{0}</font></b><br></div>', record.data['codigo']);
                    }
                },
                type: 'TextField',
                bottom_filter: true,
                filters: {pfiltro: 'pro_cos.codigo', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'nombre_prorrateo',
                    fieldLabel: 'Nombre Prorrateo',
                    labelStyle: 'font-weight:bold; color:#005300;',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 300,
                    style: {
                        background: '#D1FFBD',
                        color: 'green',
                        fontWeight: 'bold'
                    },
                    maxLength: 200
                },
                type: 'TextField',
                bottom_filter: true,
                filters: {pfiltro: 'pro_cos.nombre_prorrateo', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: { //fRnk: HR01014
                    name: 'descripcion',
                    fieldLabel: 'Descripción Prorrateo',
                    labelStyle: 'font-weight:bold; color:#005300;',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 350,
                    style: {
                        background: '#D1FFBD',
                        color: 'green',
                        fontWeight: 'bold'
                    }
                },
                type: 'TextArea',
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'tipo_calculo',
                    fieldLabel: 'Tipo Calculo',
                    labelStyle: 'font-weight:bold; color:#001A8E;',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 200,
                    typeAhead: true,
                    forceSelection: false,
                    triggerAction: 'all',
                    mode: 'local',
                    store: ['Horas trabajadas en la jornada', 'Cantidad de empleados del centro de costo', 'Número de equipos ocupados'],
                    style: 'text-transform:uppercase; background:#C5E5F5; color:blue; font-weight:bold'
                },
                type: 'ComboBox',
                bottom_filter: true,
                filters: {pfiltro: 'pro_cos.tipo_calculo', type: 'string'},
                id_grupo: 2,
                grid: false,
                form: false
            },
            {
                config: {
                    name: 'id_tipo_prorrateo',
                    fieldLabel: 'Tipo de prorrateo',
                    labelStyle: 'font-weight:bold; color:#001A8E;',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 200,
                    mode: 'remote',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_costos/control/TipoProrrateo/listarTipoProrrateo',
                        id: 'id_tipo_prorrateo',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_prorrateo', 'nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'prorra.id_tipo_prorrateo'}
                    }),
                    valueField: 'id_tipo_prorrateo',
                    displayField: 'nombre',
                    gdisplayField: 'tipo_prorrateo_nombre',
                    style: 'background:#C5E5F5; color:blue; font-weight:bold',
                    hiddenName: 'id_tipo_prorrateo',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    pageSize: 15,
                    queryDelay: 1000
                },
                type: 'ComboBox',
                bottom_filter: true,
                filters: {pfiltro: 'prorra.nombre', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_tipo_costo_prorrateo',
                    fieldLabel: 'Pertenece',
                    labelStyle: 'font-weight:bold; color:#001A8E;',
                    allowBlank: true,
                    style: {
                        background: '#C5E5F5',
                        color: 'blue',
                        fontWeight: 'bold'
                    },
                    emptyText: 'Elija Prorrateo...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_costos/control/TipoCostoProrrateo/listarTipoCostoProrrateo',
                        id: 'id_tipo_costo_prorrateo',
                        root: 'datos',
                        sortInfo: {
                            field: 'nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_tipo_costo_prorrateo', 'nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'prorra.id_tipo_costo_prorrateo#prorra.nombre'}
                    }),
                    valueField: 'id_tipo_costo_prorrateo',
                    displayField: 'nombre',
                    gdisplayField: 'nombre',
                    hiddenName: 'id_tipo_costo_prorrateo',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 15,
                    queryDelay: 1000,
                    anchor: '80%',
                    gwidth: 300,
                    minChars: 2,
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<div class="awesomecombo-item {checked}">',
                        '<p><b>Id Prorrateo:{id_tipo_costo_prorrateo}</b></p>',
                        '</div><p><b>Nombre:</b> <span style="color: green;">{nombre}</span></p>',
                        '</div></tpl>'
                    ]),
                    renderer: function (value, p, record) {

                        return String.format('<div ext:qtip="Bueno"><b><font color="green">{0}</font></b><br></div>', record.data['id_tipo_costo_prorrateo']);
                    },
                },
                type: 'ComboBox',
                id_grupo: 2,
                //bottom_filter: true,
                filters: {pfiltro: 'prorra.nombre', type: 'string'},
                grid: false,
                form: true
            },
            {
                config: {
                    name: 'nombre',
                    fieldLabel: 'Pertenece',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 200,
                    maxLength: 200,
                    renderer: function (value, p, record) {

                        return String.format('<div ext:qtip="Pertenece"><b><font color="blue">{0}</font></b><br></div>', record.data['nombre']);
                    }
                },
                type: 'TextField',
                bottom_filter: true,
                filters: {pfiltro: 'prorra.nombre', type: 'string'},
                id_grupo: 2,
                grid: true,
                form: false
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
                filters: {pfiltro: 'pro_cos.estado_reg', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config: {
                    name: 'id_usuario_ai',
                    fieldLabel: '',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 4
                },
                type: 'Field',
                filters: {pfiltro: 'pro_cos.id_usuario_ai', type: 'numeric'},
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
                filters: {pfiltro: 'pro_cos.usuario_ai', type: 'string'},
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
                filters: {pfiltro: 'pro_cos.fecha_reg', type: 'date'},
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
                type: 'Field',
                filters: {pfiltro: 'usu1.cuenta', type: 'string'},
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
                filters: {pfiltro: 'pro_cos.fecha_mod', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            {
                config:{
                    name: 'desde',
                    fieldLabel: 'Acumulado desde',
                    allowBlank: true,
                    format: 'd/m/Y',
                    width: 150,
                },
                type: 'DateField',
                id_grupo: 3,
                form: true
            },
            {
                config:{
                    name: 'hasta',
                    fieldLabel: 'Acumulado hasta',
                    allowBlank: true,
                    format: 'd/m/Y',
                    width: 150
                },
                type: 'DateField',
                id_grupo: 3,
                form: true
            },
            {
                config: {
                    name: 'id_cuenta',
                    fieldLabel: 'Importe Cuenta',
                    labelStyle: 'font-weight:bold; color:#001A8E;',
                    allowBlank: true,
                    style: {
                        background: '#C5E5F5',
                        color: 'blue',
                        fontWeight: 'bold'
                    },
                    emptyText: 'Elija una Cuenta...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_costos/control/TipoCostoProrrateo/listarCuentasProrrateo',
                        id: 'id_cuenta',
                        root: 'datos',
                        sortInfo: {
                            field: 'deppto.nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_cuenta', 'nro_cuenta', 'nombre_cuenta', 'monto'],
                        remoteSort: true,
                        //baseParams: {par_filtro: 'prorra.id_cuenta#prorra.nro_cuenta'}
                    }),
                    valueField: 'id_cuenta',
                    displayField: 'nro_cuenta',
                    gdisplayField: 'nro_cuenta',
                    hiddenName: 'id_cuenta',
                    forceSelection: true,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    //mode: 'remote',
                    pageSize: 0,
                    queryDelay: 1000,
                    anchor: '80%',
                    gwidth: 300,
                    minChars: 2,
                    tpl: new Ext.XTemplate([
                        '<tpl for=".">',
                        '<div class="x-combo-list-item">',
                        '<p><b>Cuenta: {nro_cuenta}</b></p>',
                        '<p><b>Nombre: </b> <span style="color: green;">{nombre_cuenta}</span></p>',
                        '<p><b>Acumulado: </b> <span style="color: green;">{monto:number("0,000.00")}</span></p>',
                        '</div></tpl>'
                    ]),
                    renderer: function (value, p, record) {
                        return String.format('<div ext:qtip=""><b><font color="green">{0}</font></b><br></div>', record.data['id_cuenta']);
                    },
                },
                type: 'ComboBox',
                id_grupo: 3,
                filters: {pfiltro: 'prorra.nro_cuenta', type: 'string'},
                grid: false,
                form: true
            },
            {
                config:{
                    name: 'acumulado_cuenta',
                    labelSeparator: '',
                    inputType: 'hidden'
                },
                valueField: 'acumulado_cuenta',
                id_grupo: 3,
                type:'Field',
                form:true
            },
        ],
        tam_pag: 50,
        title: 'Prorrateo Costos',
        ActSave: '../../sis_costos/control/ProrrateoCos/insertarProrrateoCos',
        ActDel: '../../sis_costos/control/ProrrateoCos/eliminarProrrateoCos',
        ActList: '../../sis_costos/control/ProrrateoCos/listarProrrateoCos',
        id_store: 'id_prorrateo',
        fields: [
            {name: 'id_prorrateo', type: 'numeric'},
            {name: 'codigo', type: 'string'},
            {name: 'nombre_prorrateo', type: 'string'},
            {name: 'tipo_calculo', type: 'string'},
            {name: 'estado_reg', type: 'string'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'usuario_ai', type: 'string'},
            {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            {name: 'id_tipo_costo_prorrateo', type: 'numeric'},
            {name: 'nombre', type: 'string'},
            {name: 'descripcion', type: 'string'},
            {name: 'id_tipo_prorrateo', type: 'numeric'},
            {name: 'tipo_prorrateo_nombre', type: 'string'},
            {name: 'desde', type: 'date', dateFormat: 'Y-m-d'},
            {name: 'hasta', type: 'date', dateFormat: 'Y-m-d'},
            {name: 'acumulado_cuenta', type: 'date', dateFormat: 'Y-m-d'},
            {name: 'nro_cuenta', type: 'string'}

        ],
        sortInfo: {
            field: 'id_prorrateo',
            direction: 'ASC'
        },
        bdel: true,
        bsave: false,
        btest: false,
        tabsouth: [
            {
                url: '../../../sis_costos/vista/prorrateo_cos_det/ProrrateoCosDet.php',
                title: 'Detalle Prorrateo',
                height: '50%',
                cls: 'ProrrateoCosDet'
            }
        ],
        iniciarEventos: function () { //fRnk. HR01014
            this.Cmp.desde.on('change', function (cmb, rec, ind) {
                this.Cmp.id_cuenta.reset();
                this.Cmp.id_cuenta.store.baseParams.desde = cmb.getValue();
                this.Cmp.id_cuenta.store.baseParams.hasta = this.Cmp.hasta.getValue();
                this.Cmp.id_cuenta.store.baseParams.id_deptos = id_deptos.join(',');
                this.Cmp.id_cuenta.store.baseParams.codigos = codigos.join(',');
                this.Cmp.id_cuenta.modificado = true;
            }, this);
            this.Cmp.hasta.on('change', function (cmb, rec, ind) {
                this.Cmp.id_cuenta.reset();
                this.Cmp.id_cuenta.store.baseParams.desde = this.Cmp.desde.getValue();
                this.Cmp.id_cuenta.store.baseParams.hasta = cmb.getValue();
                this.Cmp.id_cuenta.store.baseParams.id_deptos = id_deptos.join(',');
                this.Cmp.id_cuenta.store.baseParams.codigos = codigos.join(',');
                this.Cmp.id_cuenta.modificado = true;
            }, this);
            this.Cmp.id_cuenta.on('focus', function (cmb, rec, ind) {
                this.Cmp.id_cuenta.reset();
                this.Cmp.id_cuenta.store.baseParams.desde = this.Cmp.desde.getValue();
                this.Cmp.id_cuenta.store.baseParams.hasta = this.Cmp.hasta.getValue();
                this.Cmp.id_cuenta.store.baseParams.id_deptos = id_deptos.join(',');
                this.Cmp.id_cuenta.store.baseParams.codigos = codigos.join(',');
                this.Cmp.id_cuenta.modificado = true;
            }, this);
            this.Cmp.id_cuenta.on('select', function (cmb, rec, ind) {
                this.Cmp.acumulado_cuenta.setValue(rec.data.monto);
            }, this);
        },
        onButtonNew: function () {
            Phx.vista.ProrrateoCos.superclass.onButtonNew.call(this);
            this.Cmp.id_prorrateo.setValue(null);
            this.getComponente('id_tipo_costo_prorrateo').setValue(this.maestro.id_tipo_costo_prorrateo);
        },
    });
</script>
