<?php
/**
 *@package pXP
 *@file Cuenta.php
 *@author  Gonzalo Sarmiento Sejas
 *@date 21-02-2013 15:04:03
 *@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
 */
header("content-type: text/javascript; charset=UTF-8");
?>
<script>
    Phx.vista.TipoCosto =function (config) {
        this.Atributos=[
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_tipo_costo'
                },
                type: 'Field',
                form: true

            },
            {
                //configuracion del componente
                config: {
                    labelSeparator: '',
                    inputType: 'hidden',
                    name: 'id_tipo_costo_fk'
                },
                type: 'Field',
                form: true

            },
            // Start NMQ 2024 01237
            {
                config: {
                    name: 'digito',
                    fieldLabel: 'Digito',
                    allowBlank: true,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 200
                },
                type: 'TextField',
                filters: {pfiltro: 'tco.codigo', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            // End NMQ 2024 01237
            {
                config: {
                    name: 'codigo',
                    fieldLabel: 'Codigo',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 150,
                    maxLength: 200
                },
                type: 'TextField',
                filters: {pfiltro: 'tco.codigo', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },
            {
                config: {
                    name: 'nombre',
                    fieldLabel: 'Nombre',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 300,
                    maxLength: 500
                },
                type: 'TextField',
                filters: {pfiltro: 'tco.nombre', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },

            {
                config: {
                    name: 'descripcion',
                    fieldLabel: 'Descripcion',
                    allowBlank: false,
                    anchor: '80%',
                    gwidth: 100,
                    maxLength: 500
                },
                type: 'TextField',
                filters: {pfiltro: 'tco.descripcion', type: 'string'},
                id_grupo: 1,
                grid: true,
                form: true
            },


            {
                config: {
                    name: 'sw_trans',
                    fieldLabel: 'Operación',
                    allowBlank: false,
                    emptyText: 'Tipo...',
                    typeAhead: true,
                    triggerAction: 'all',
                    lazyRender: true,
                    mode: 'local',
                    gwidth: 100,
                    store: ['movimiento', 'titular']
                },
                type: 'ComboBox',
                filters: {pfiltro: 'tco.sw_trans', type: 'string'},
                id_grupo: 0,
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
                filters: {pfiltro: 'tco.estado_reg', type: 'string'},
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
                filters: {pfiltro: 'tco.id_usuario_ai', type: 'numeric'},
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
                filters: {pfiltro: 'tco.usuario_ai', type: 'string'},
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
                filters: {pfiltro: 'tco.fecha_reg', type: 'date'},
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
                filters: {pfiltro: 'tco.fecha_mod', type: 'date'},
                id_grupo: 1,
                grid: true,
                form: false
            },
            // Start NMQ 2024 01237
            {
                config: {
                    name: 'id_gestion',
                    inputType: 'hidden'
                },
                type: 'Field',
                form: true
            }// End NMQ 2024 01237
        ];


        Phx.vista.TipoCosto.superclass.constructor.call(this,config);

        // add buton print and replicate
        this.addButton('btnImprimir', {
            text: 'Imprimir',
            iconCls: 'bprint',
            disabled: false,
            handler: this.imprimirCbte,
            tooltip: '<b>Imprimir Plan de Cuentas</b><br/>Imprime el Clasificación de Costos.'
        });
        this.addButton('btnReCla', {
            text: 'Replicar Clasificador de Costos',
            iconCls: 'bchecklist',
            disabled: false,
            handler: this.replicarCostos,
            tooltip: '<b>Replica el Clasificador de Costos por gestion</b>'
        });
        // End NMQ 2024 01237
        this.tbar.items.get('b-new-' + this.idContenedor).enable(); // fRnk: habilitado, es necesario hacer un análisis
        // Add items in the toolbar NMQ 2024 01237
        this.tbar.insert(0, this.cmbGestion);
        this.tbar.add(this.getBoton('btnImprimir'));
        this.tbar.add(this.getBoton('btnReCla'));
        this.iniciarEventos();
        this.init();
        // End NMQ 2024 01237

    }
    Ext.extend(Phx.vista.TipoCosto,Phx.arbInterfaz,{
        title: 'Clasificacición de Costos',
        ActSave: '../../sis_costos/control/TipoCosto/insertarTipoCosto',
        ActDel: '../../sis_costos/control/TipoCosto/eliminarTipoCosto',
        ActList: '../../sis_costos/control/TipoCosto/listarTipoCostoArb',
        enableDD:false,
        expanded:false,
        useArrows: true,
        nombreVista: 'Abastecimientos',

        id_store: 'id_tipo_costo',
        textRoot: 'CLASIFICADOR ',
        id_nodo: 'id_tipo_costo',
        id_nodo_p: 'id_tipo_costo_fk',
        id_gestion: 'id_gestion',

        fields: [
            {name: 'id_tipo_costo', type: 'numeric'},
            {name: 'codigo', type: 'string'},
            {name: 'nombre', type: 'string'},
            {name: 'sw_trans', type: 'string'},
            {name: 'descripcion', type: 'string'},
            {name: 'id_tipo_costo_fk', type: 'numeric'},
            {name: 'estado_reg', type: 'string'},
            {name: 'id_usuario_ai', type: 'numeric'},
            {name: 'usuario_ai', type: 'string'},
            {name: 'fecha_reg', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'id_usuario_reg', type: 'numeric'},
            {name: 'id_usuario_mod', type: 'numeric'},
            {name: 'fecha_mod', type: 'date', dateFormat: 'Y-m-d H:i:s.u'},
            {name: 'usr_reg', type: 'string'},
            {name: 'usr_mod', type: 'string'},
            {name: 'id_gestion', type: 'numeric'}

        ],

        sortInfo:
            {
                field: 'id_tipo_costo',
                direction: 'ASC'
            },

        bdel:true,
        bsave: false,
        rootVisible: false,
        getTipoCuentaPadre: function (n) {
            //var direc
            var padre = n.parentNode;

            if (padre) {
                if (padre.attributes.id != 'id') {
                    return this.getTipoCuentaPadre(padre);
                } else {
                    return n.attributes.tipo_cuenta;
                }
            } else {
                return undefined;
            }
        },

        preparaMenu:function (n) {

            if (n.attributes.tipo_nodo == 'hijo' || n.attributes.tipo_nodo == 'raiz' || n.attributes.id == 'id') {
                this.tbar.items.get('b-new-' + this.idContenedor).enable()
            }
            else {
                this.tbar.items.get('b-new-' + this.idContenedor).disable()
            }

            if (n.attributes.sw_trans == 'movimiento') {
                //this.getBoton('bCuentas').enable();
            }

            // llamada funcion clase padre
            return Phx.vista.TipoCosto.superclass.preparaMenu.call(this, n);
        },

        tabeast:[
            {
                url: '../../../sis_costos/vista/tipo_costo_cuenta/TipoCostoCuenta.php',
                title: 'Cuentas y Auxiliares',
                width: '50%',
                cls: 'TipoCostoCuenta'
            },
            {
                url: '../../../sis_costos/vista/tipo_costo_cuenta/CheckConfig.php',
                title: 'Cuentas/Auxiliares Pendientes de Configurar',
                width: '50%',
                cls: 'CheckConfig'
            },
            {
                url: '../../../sis_costos/vista/tipo_costo_prorrateo/TipoCostoProrrateo.php',
                title: 'Clasificación de Costo',
                width: '50%',
                cls: 'TipoCostoProrrateo'
            }
        ],

        iniciarEventos:function(){
            this.cmpSwTransaccional=this.getComponente('sw_trans');
            //this.cmpTipoCuentaPat=this.getComponente('tipo_cuenta_pat')
            this.cmbGestion.on('select', this.capturarEventos, this);
            // this.cmpSwTransaccional.setValue(Ext.util.Format.capitalize(record.data.codigo));

            this.loaderTree.baseParams.id_gestion = this.cmbGestion.getValue();
            Phx.vista.TipoCosto.superclass.onButtonAct.call(this);
            this.root.attributes.loaded = false;
            this.root.collapse(true);
        },

        cmbGestion: new Ext.form.ComboBox({
            name: 'gestion',
            id: 'gestion',
            fieldLabel: 'Gestion',
            allowBlank: true,
            emptyText:'Gestion...',
            blankText: 'Año',
            store:new Ext.data.JsonStore(
                {
                    url: '../../sis_parametros/control/Gestion/listarGestion',
                    id: 'id_gestion',
                    root: 'datos',
                    sortInfo:{
                        field: 'gestion',
                        direction: 'DESC'
                    },
                    totalProperty: 'total',
                    fields: ['id_gestion','gestion'],
                    // turn on remote sorting
                    remoteSort: true,
                    baseParams:{par_filtro:'gestion'}
                }),
            valueField: 'id_gestion',
            triggerAction: 'all',
            displayField: 'gestion',
            hiddenName: 'id_gestion',
            mode:'remote',
            pageSize:50,
            queryDelay:500,
            listWidth:'280',
            hidden:false,
            width:80
        }),

        capturarEventos: function () {
            if(this.validarFiltros()){
                this.capturaFiltros();
            }
        },

        capturaFiltros:function(combo, record, index){

            console.log('llega',this.loaderTree.baseParams.id_gestion = this.cmbGestion.getValue());
            this.loaderTree.baseParams.id_gestion = this.cmbGestion.getValue();
            this.root.reload();

            // Parámetros para cargar el árbol
            Phx.vista.TipoCosto.superclass.onButtonAct.call(this);
            this.loaderTree.baseParams.id_gestion = this.cmbGestion.getValue();

        },

        validarFiltros:function(){
            if(this.cmbGestion.isValid()){
                return true;
            }
            else{
                return false;
            }

        },

        onButtonAct:function(){
            if(!this.validarFiltros()){
                Ext.Msg.alert('ATENCION!!!','Especifique los filtros antes')
            }
            else{
                this.loaderTree.baseParams.id_gestion=this.cmbGestion.getValue();
                Phx.vista.TipoCosto.superclass.onButtonAct.call(this);
            }
        },
        // Add function NMQ 2024 01237
        replicarCostos: function() {
            var id_gestion = this.cmbGestion.getValue();

            if (!id_gestion) {
                Ext.Msg.alert('ATENCIÓN', 'Primero debe seleccionar la gestión.');
                return;
            }

            Phx.CP.loadingShow();

            Ext.Ajax.request({
                url: '../../sis_costos/control/TipoCosto/replicarCostos',
                params: {
                    id_gestion: id_gestion
                },
                success: this.successRep,
                failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        },

        // Callback cuando termina correctamente
        successRep: function(resp) {
            Phx.CP.loadingHide();
            var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));

            Ext.Msg.alert('ÉXITO', reg.ROOT.detalle.mensaje);

            // Recargar el árbol después de replicar
            this.loaderTree.baseParams.id_gestion = this.cmbGestion.getValue();
            Phx.vista.TipoCosto.superclass.onButtonAct.call(this);
        },

        conexionFailure: function(response, request) {
            Phx.CP.loadingHide();

            var errorMsg = 'Ocurrió un error en la conexión.';

            try {
                var resp = Ext.util.JSON.decode(response.responseText);
                if (resp.ROOT && resp.ROOT.detalle && resp.ROOT.detalle.mensaje) {
                    errorMsg = resp.ROOT.detalle.mensaje;
                }
            } catch (e) {
                // mantiene error por defecto
            }

            Ext.Msg.alert('ERROR', errorMsg);
        },

        timeout: function() {
            Phx.CP.loadingHide();
            Ext.Msg.alert('TIEMPO AGOTADO', 'El servidor tardó demasiado en responder.');
        },

        onButtonNew: function() {
            Phx.vista.TipoCosto.superclass.onButtonNew.call(this);

            // Asigna automáticamente la gestión seleccionada al formulario
            var id_gestion = this.cmbGestion.getValue();

            if (!id_gestion) {
                Ext.Msg.alert('ATENCIÓN', 'Debe seleccionar una gestión antes de crear un nuevo registro.');
                this.window.hide();
                return;
            }

            // Setea el valor en el campo oculto del formulario
            this.Cmp.id_gestion.setValue(id_gestion);

            // Obtener nodo seleccionado del árbol
            var node = this.sm.getSelectedNode ? this.sm.getSelectedNode() : null;

            if (node && node.attributes) {
                // Heredar jerarquía: establecer el id del padre
                if (node.attributes.id_tipo_costo) {
                    this.Cmp.id_tipo_costo_fk.setValue(node.attributes.id_tipo_costo);
                }

                // Si deseas heredar otros campos (como 'codigo' o 'digito')
                if (node.attributes.codigo) {
                    this.Cmp.digito.setValue(node.attributes.codigo);
                }
            } else {
                // Mostrar advertencia
                Ext.Msg.show({
                    title: 'Advertencia',
                    msg: 'Está creando un nodo raíz sin padre. ¿Desea continuar?',
                    buttons: Ext.Msg.YESNO,
                    icon: Ext.Msg.WARNING,
                    fn: function(btn){
                        if (btn === 'yes') {
                            // Permitir creación del nodo raíz
                            Phx.vista.TipoCosto.superclass.onButtonNew.call(this);
                            this.Cmp.id_tipo_costo_fk.setValue(null);
                            this.Cmp.codigo.setValue('');
                        }
                    },
                    scope: this
                });
            }

            // Bloquear el campo 'digito' para que no se edite
            this.Cmp.digito.setReadOnly(true);
        },

        onButtonEdit: function() {
            Phx.vista.TipoCosto.superclass.onButtonEdit.call(this);
            var id_gestion = this.cmbGestion.getValue();

            if (id_gestion) {
                this.Cmp.id_gestion.setValue(id_gestion);
            }
        },

        onSubmit: function(o) {
            // Antes de enviar los datos al backend, asegura que id_gestion esté presente
            this.Cmp.id_gestion.setValue(this.cmbGestion.getValue());
            Phx.vista.TipoCosto.superclass.onSubmit.call(this, o);
        },

        imprimirCbte: function() {
            var id_gestion = this.cmbGestion.getValue();

            if (!id_gestion) {
                Ext.Msg.alert('ATENCIÓN', 'Primero debe seleccionar una gestión para imprimir.');
                return;
            }

            // Aquí continúa tu lógica de impresión
            Phx.CP.loadingShow();

            Ext.Ajax.request({
                url: '../../sis_costos/control/TipoCosto/imprimirClasificador',
                params: {
                    id_gestion: id_gestion
                },
                success: this.successExport,
                // failure: this.conexionFailure,
                timeout: this.timeout,
                scope: this
            });
        }

    })
</script>
