<?php
/**
 *fRnk: vista reporte HR01008
 */
header("content-type: text/javascript; charset=UTF-8");
?>

<script>
    Phx.vista.FormFiltroCostoUnitarioOT = Ext.extend(Phx.frmInterfaz, {
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
            Phx.vista.FormFiltroCostoUnitarioOT.superclass.constructor.call(this, config);
            this.init();
            this.iniciarEventos();
        },

        Atributos: [
            {
                config: {
                    name: 'id_orden_trabajo_padre',
                    fieldLabel: 'Orden Padre',
                    emptyText: 'Seleccione una Orden Padre...',
                    typeAhead: false,
                    forceSelection: false,
                    allowBlank: false,
                    store: new Ext.data.JsonStore({
                        url: '../../sis_contabilidad/control/OrdenTrabajo/listarOrdenTrabajoRama',
                        id: 'id_orden_trabajo',
                        root: 'datos',
                        sortInfo: {
                            field: 'motivo_orden',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_orden_trabajo', 'motivo_orden', 'desc_orden', 'motivo_orden'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'desc_orden#motivo_orden', raiz: 'no'} //fRnk: HR00552
                    }),
                    valueField: 'id_orden_trabajo',
                    displayField: 'desc_orden',
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    origen: 'OT',
                    gdisplayField: 'desc_otp',
                    hiddenName: 'id_orden_trabajo_fk',
                    pageSize: 20,
                    width: 180,
                    queryDelay: 200,
                    listWidth: 280,
                    minChars: 2
                },
                type: 'ComboBox',
                id_grupo: 0,
                form: true
            },
            {
                config: {
                    name: 'id_orden_trabajo',
                    fieldLabel: 'Código de la Orden',
                    allowBlank: false,
                    emptyText: 'Seleccione una Orden...',
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
                    enableMultiSelect: true, //fRnk: HR00552
                    pageSize: 20,
                    queryDelay: 500,
                    width: 180,
                    minChars: 2,
                    renderer: function (value, p, record) {
                        //return String.format('{0}', value?record.data['codigo']:'');
                        return String.format('<b>{0}</b><br>{1}', value, record.data.descripcion);
                    }
                },
                type: 'AwesomeCombo',
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
                    name: 'id_subordenes',
                    fieldLabel: 'Sub ordenes',
                    allowBlank: false,
                    emptyText: 'Seleccione una suborden...',
                    store: new Ext.data.JsonStore({
                        url: '../../sis_contabilidad/control/Suborden/listarSuborden',
                        id: 'id_suborden',
                        root: 'datos',
                        sortInfo: {
                            field: 'codigo',
                            direction: 'ASC'
                        },
                        totalProperty: 'total',
                        fields: ['id_suborden', 'codigo', 'nombre'],
                        remoteSort: true,
                        baseParams: {par_filtro: 'nombre'}
                    }),
                    valueField: 'id_suborden',
                    displayField: 'nombre',
                    forceSelection: false,
                    typeAhead: false,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'remote',
                    pageSize: 20,
                    queryDelay: 500,
                    width: 180,
                    minChars: 2
                },
                type: 'ComboBox',
                filters: {
                    pfiltro: 'sub.nombre',
                    type: 'string'
                },
                id_grupo: 0,
                grid: true,
                form: true
            }
        ],
        topBar: true,
        botones: false,
        labelSubmit: 'Generar',
        clsSubmit: 'bprint',
        title: 'Filtro de mayores',

        onSubmit: function (o) {
            var me = this;
            if (me.form.getForm().isValid()) {
                var parametros = me.getValForm()
                Phx.CP.loadingShow();
                //console.log('------------->', parametros);
                Ext.Ajax.request({
                    url: '../../sis_costos/control/TipoCosto/reporteCostoUnitarioOT',
                    params: Ext.apply(parametros, {'id_orden_trabajo': this.Cmp.id_orden_trabajo.getValue()}),
                    success: this.successExport,
                    failure: this.conexionFailure,
                    timeout: this.timeout,
                    scope: this
                })
            }
        },
        iniciarEventos: function () {
            this.Cmp.id_orden_trabajo_padre.on('select', function (cmb, rec, ind) {
                this.Cmp.id_orden_trabajo.reset();
                this.Cmp.id_orden_trabajo.store.baseParams.id_orden_trabajo_fk = cmb.getValue();
                this.Cmp.id_orden_trabajo.modificado = true;
            }, this);
        },
        loadValoresIniciales: function () {
            Phx.vista.FormFiltroCostoUnitarioOT.superclass.loadValoresIniciales.call(this);
        }
    })
</script>