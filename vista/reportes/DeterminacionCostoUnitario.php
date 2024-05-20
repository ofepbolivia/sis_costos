<?php
/**
 * fRnk: nuevo reporte HR01014
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.DeterminacionCostoUnitario = Ext.extend(Phx.frmInterfaz, {
        constructor: function (config) {
            this.panelResumen = new Ext.Panel({html: ''});
            this.Grupos = [{
                xtype: 'fieldset',
                border: false,
                autoScroll: true,
                layout: 'form',
                items: [],
                id_grupo: 0
            },
                this.panelResumen
            ];
            Phx.vista.DeterminacionCostoUnitario.superclass.constructor.call(this, config);
            this.init();
            this.iniciarEventos();
        },

        Atributos: [
            {
                config: {
                    name: 'desde',
                    fieldLabel: 'Desde',
                    allowBlank: false,
                    format: 'd/m/Y',
                    width: 150
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'hasta',
                    fieldLabel: 'Hasta',
                    allowBlank: false,
                    format: 'd/m/Y',
                    width: 150
                },
                type: 'DateField',
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'id_deptos',
                    fieldLabel: 'Depto',
                    typeAhead: false,
                    forceSelection: true,
                    allowBlank: false,
                    disableSearchButton: true,
                    emptyText: 'Depto Contable',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_parametros/control/Depto/listarDeptoFiltradoDeptoUsuario',
                        id: 'id_depto',
                        root: 'datos',
                        sortInfo: {
                            field: 'deppto.nombre',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_depto', 'nombre', 'codigo'],
                        remoteSort: true,
                        baseParams: {
                            par_filtro: 'deppto.nombre#deppto.codigo',
                            estado: 'activo',
                            codigo_subsistema: 'CONTA'
                        }
                    }),
                    valueField: 'id_depto',
                    displayField: 'codigo',
                    hiddenName: 'id_depto',
                    enableMultiSelect: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 20,
                    queryDelay: 200,
                    anchor: '80%',
                    listWidth: '280',
                    resizable: true,
                    minChars: 2
                },
                type: 'AwesomeCombo',
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'tipo_costo',
                    fieldLabel: 'Costo Directo Indirecto',
                    allowBlank: false,
                    emptyText: 'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    gwidth: 100,
                    store: ['Centro', 'Orden']
                },
                type: 'ComboBox',
                id_grupo: 0,
                valorInicial: 'Centro',
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'cantidad_centro',
                    fieldLabel: 'Cantidad producida',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 350,
                    maxLength: 200
                },
                type: 'TextField',
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'cantidad_ot',
                    fieldLabel: 'Cantidad de la Orden de Trabajo',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 350,
                    maxLength: 200,
                    hidden: true
                },
                type: 'TextField',
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'id_orden_trabajo',
                    fieldLabel: 'Código de la Orden',
                    allowBlank: false,
                    emptyText: 'Seleccione una Orden...',
                    hidden: true,
                    store: new Ext.data.JsonStore({
                        url: '../../sis_contabilidad/control/OrdenTrabajo/listarOrdenTrabajo',
                        id: 'id_orden_trabajo',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_orden_trabajo', 'codigo', 'descripcion'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'codigo'}
                    }),
                    valueField: 'id_orden_trabajo',
                    displayField: 'codigo',
                    forceSelection: false,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 20,
                    queryDelay: 500,
                    width: 180,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        //return String.format('{0}', value?record.data['codigo']:'');
                        return String.format('<b>{0}</b><br>{1}', value, record.data.descripcion);
                    }
                },
                type: 'ComboBox',
                filters: {
                    pfiltro: 'mon.codigo',
                    type: 'string'
                },
                id_grupo: 0,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'formato',
                    fieldLabel: 'Formato del Reporte',
                    allowBlank: false,
                    emptyText: 'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    gwidth: 100,
                    store: ['PDF']
                },
                type: 'ComboBox',
                id_grupo: 0,
                valorInicial: 'PDF',
                grid: true,
                form: true
            }
        ],
        title: 'Reporte Determinación de Costo Unitario',
        topBar: true,
        botones: false,
        labelSubmit: 'Generar',
        clsSubmit: 'bprint',
        iniciarEventos: function () {
            this.Cmp.tipo_costo.on('select', function (cmb, rec, ind) {
                console.log(cmb.getValue());
                if (cmb.getValue() == 'Centro') {
                    this.Cmp.id_orden_trabajo.hide();
                    this.Cmp.cantidad_centro.show();
                } else {
                    this.Cmp.cantidad_centro.hide();
                    this.Cmp.id_orden_trabajo.show();
                }
            }, this);
        },
        onSubmit: function (o) {
            var me = this;
            var parametros = me.getValForm()
            Phx.CP.loadingShow();
            var deptos = this.Cmp.id_deptos.getValue('object');
            console.log('deptos', deptos)
            var sw = 0, codigos = ''
            deptos.forEach(function (entry) {
                if (sw == 0) {
                    codigos = entry.codigo;
                } else {
                    codigos = codigos + ', ' + entry.codigo;
                }
                sw = 1;
            });
            Ext.Ajax.request({
                url: '../../sis_costos/control/TipoCosto/reporteCostoUnitario',
                params: Ext.apply(parametros, {'codigos': codigos, 'tipo_balance': 'todos'}),
                success: this.successExport,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            })
        }
    })
</script>