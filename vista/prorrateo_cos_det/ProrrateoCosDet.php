<?php
/**
*@package pXP
*@file gen-ProrrateoCosDet.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:35:31
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ProrrateoCosDet=Ext.extend(Phx.gridInterfaz,{

	constructor:function(config){

		this.maestro=config.maestro;
    	//llama al constructor de la clase padre
		Phx.vista.ProrrateoCosDet.superclass.constructor.call(this,config);
		this.init();

		this.iniciarEventos();



	},

	iniciarEventos: function () {
		this.Cmp.id_tipo_costo.on('select',function (cmb, record, index) {
			this.Cmp.cuenta_nro.reset();
			this.Cmp.cuenta_nro.modificado = true;
			this.Cmp.cuenta_nro.setDisabled(false);
			this.Cmp.cuenta_nro.store.baseParams = {par_filtro: 'c.nro_cuenta#c.nombre_cuenta', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};
			this.Cmp.id_auxiliar.reset();
			this.Cmp.id_tipo_costo
		},this);

		this.Cmp.cuenta_nro.on('select', function (cmb, rec, index) {
			console.log('cmb_cuenta_nro :',cmb);
			console.log('rec_cuenta_nro :',rec.data.nro_cuenta);
			console.log('this.Cmp.id_tipo_costo.getValue() :',this.Cmp.id_tipo_costo.getValue());
			this.Cmp.id_auxiliar.reset();
			this.Cmp.id_auxiliar.modificado = true;
			this.Cmp.id_auxiliar.setDisabled(false);
			this.Cmp.id_auxiliar.store.baseParams = {par_filtro: 'aux.codigo_auxiliar#aux.nombre_auxiliar', id_tipo_costo: this.Cmp.id_tipo_costo.getValue(), nro_cuenta: rec.data.nro_cuenta};
		}, this);



	},
	Grupos: [
			{
					layout: 'column',
					labelWidth: 80,
					labelAlign: 'top',
					border: false,
					items: [
							{
									columnWidth: 1,
									border: false,
									layout: 'fit',
									bodyStyle: 'padding-right:10px;',
									items: [
											{
													xtype: 'fieldset',
													title: 'DATOS DEL DETALLE DEL PRORRATEO',

													autoHeight: true,
													items: [
															{
																	layout: 'form',
																	anchor: '100%',
																	//bodyStyle: 'padding-right:10px;',
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
					]
			}
	],
	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_prorrateo_det'
			},
			type:'Field',
			form:true
		},
		{

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
				name: 'id_tipo_costo',
				fieldLabel: 'Tipo de Costo',
				labelStyle: 'font-weight:bold; color:#005300;',
				allowBlank: false,
				emptyText: 'Elija una opción...',
				store: new Ext.data.JsonStore({
					url: '../../sis_costos/control/TipoCosto/listarTipoCosto',
					id: 'id_tipo_costo',
					root: 'datos',
					sortInfo: {
						field: 'codigo',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_tipo_costo', 'nombre', 'codigo', 'descripcion'],
					remoteSort: true,
					baseParams: {par_filtro: 'tco.nombre#tco.codigo', sw_trans:'movimiento'}
				}),
				valueField: 'id_tipo_costo',
				displayField: 'nombre',
				gdisplayField: 'desc_tipo_costo',
				hiddenName: 'id_tipo_costo',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 300,
				style: {
								background: '#D1FFBD',
								color:'green',
								fontWeight: 'bold'
				},
				minChars: 2,
				resizable:true,
				tpl: new Ext.XTemplate([
					'<tpl for=".">',
					'<div class="x-combo-list-item">',
					'<div class="awesomecombo-item {checked}">',
					'<p><b>Codigo: {codigo}</b></p>',
					'</div><p><b>Nombre:</b> <span style="color: green;">{nombre}</span></p>',
					'</div></tpl>'
				]),
				renderer : function(value, p, record) {
					var cadena = "<b style='color: green'>("+record.data['codigo']+") - </b>"+record.data['desc_tipo_costo'];
					return String.format('{0}',cadena);
				}
			},
			type: 'ComboBox',
			id_grupo: 1,
			bottom_filter: true,
			filters: {pfiltro: 'tc.nombre#tc.codigo',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'cuenta_nro',
				fieldLabel: 'Nro Cuenta',
				labelStyle: 'font-weight:bold; color:#005300;',
				allowBlank: false,
				emptyText: 'Elija una cuenta...',
				disabled: true,
				store: new Ext.data.JsonStore({
					url: '../../sis_costos/control/ProrrateoCosDet/listarProrrateoCosCuenta',
					id: 'cuenta_nro',
					root: 'datos',
					sortInfo: {
						field: 'nro_cuenta',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: [ 'nro_cuenta','nombre_cuenta'],
					remoteSort: true,
					baseParams: {par_filtro: 'c.nro_cuenta#c.nombre_cuenta'}
				}),
				valueField: 'cuenta_nro',
				displayField: 'nombre_cuenta',
				gdisplayField: 'desc_cuenta',
				hiddenName: 'cuenta_nro',
				forceSelection: true,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 15,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 300,
				style: {
								background: '#C5E5F5',
								color: 'blue',
								fontWeight: 'bold'
				},
				minChars: 2,
				resizable:true,
				tpl: new Ext.XTemplate([
					'<tpl for=".">',
					'<div class="x-combo-list-item">',
					'<div class="awesomecombo-item {checked}">',
					'<p><b>Nro. Cuenta: {nro_cuenta}</b></p>',
					'</div><p><b>Nombre Cuenta:</b> <span style="color: green;">{nombre_cuenta}</span></p>',
					'</div></tpl>'
				]),
				renderer : function(value, p, record) {
					var cadena = "<b style='color: blue'>("+record.data['nro_cuenta']+") - </b>"+record.data['desc_cuenta'];
					return String.format('{0}', cadena);
				}
			},
			type: 'ComboBox',
			id_grupo: 1,
			bottom_filter: true,
			filters: {pfiltro: 'c.nombre_cuenta#procosde.cuenta_nro',type: 'string'},
			grid: true,
			form: true
		},
		{
			config: {
				name: 'id_auxiliar',
				fieldLabel: 'Auxiliar',
				labelStyle: 'font-weight:bold;',
				enableMultiSelect: true,
				allowBlank: false,
				emptyText: 'Elija un auxiliar...',
				disabled: true,
				store: new Ext.data.JsonStore({
					url: '../../sis_costos/control/ProrrateoCosDet/listarProrrateoCosAuxiliares',
					id: 'id_auxiliar',
					root: 'datos',
					sortInfo: {
						field: 'codigo_auxiliar',
						direction: 'ASC'
					},
					totalProperty: 'total',
					fields: ['id_auxiliar', 'codigo_auxiliar', 'nombre_auxiliar'],
					remoteSort: true,
					baseParams: {par_filtro: 'aux.codigo_auxiliar#aux.nombre_auxiliar'}
				}),
				valueField: 'id_auxiliar',
				displayField: 'nombre_auxiliar',
				gdisplayField: 'desc_auxiliar',
				hiddenName: 'id_auxiliar',
				forceSelection: false,
				typeAhead: false,
				triggerAction: 'all',
				lazyRender: true,
				mode: 'remote',
				pageSize: 50,
				queryDelay: 1000,
				anchor: '100%',
				gwidth: 300,
				style: {
								background: '#E6FC7B',
								color: 'black',
								fontWeight: 'bold'
				},
				minChars: 2,
				resizable:true,
				itemSelector: 'div.awesomecombo-5item',
				tpl: new Ext.XTemplate([
					'<tpl for=".">',
					'<div class="awesomecombo-5item {checked}">',
					'<p><b>Cod. Auxiliar: {codigo_auxiliar}</b></p>',
					'</div><p><b>Nombre Auxiliar:</b> <span style="color: green;">{nombre_auxiliar}</span></p>',
					'</div></tpl>'
				]),
				renderer : function(value, p, record) {
					var cadena = "<b style='color: #FF7400; font-weight:bold;'>("+record.data['codigo_auxiliar']+") - </b>"+record.data['desc_auxiliar'];
					return String.format('{0}', cadena);
				}
			},
			type: 'AwesomeCombo',
			id_grupo: 1,
			bottom_filter: true,
			filters: {pfiltro: 'aux.nombre_auxiliar#aux.codigo_auxiliar',type: 'string'},
			grid: true,
			form: true
		},
		{
			config:{
				name: 'estado_reg',
				fieldLabel: 'Estado Reg.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:10
			},
				type:'TextField',
				filters:{pfiltro:'procosde.estado_reg',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'id_usuario_ai',
				fieldLabel: '',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'procosde.id_usuario_ai',type:'numeric'},
				id_grupo:1,
				grid:false,
				form:false
		},
		{
			config:{
				name: 'usuario_ai',
				fieldLabel: 'Funcionaro AI',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:300
			},
				type:'TextField',
				filters:{pfiltro:'procosde.usuario_ai',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_reg',
				fieldLabel: 'Fecha creación',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'procosde.fecha_reg',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_reg',
				fieldLabel: 'Creado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu1.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'fecha_mod',
				fieldLabel: 'Fecha Modif.',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
							format: 'd/m/Y',
							renderer:function (value,p,record){return value?value.dateFormat('d/m/Y H:i:s'):''}
			},
				type:'DateField',
				filters:{pfiltro:'procosde.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		},
		{
			config:{
				name: 'usr_mod',
				fieldLabel: 'Modificado por',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength:4
			},
				type:'Field',
				filters:{pfiltro:'usu2.cuenta',type:'string'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,
	title:'Prorrateo Costo Detalle',
	ActSave:'../../sis_costos/control/ProrrateoCosDet/insertarProrrateoCosDet',
	ActDel:'../../sis_costos/control/ProrrateoCosDet/eliminarProrrateoCosDet',
	ActList:'../../sis_costos/control/ProrrateoCosDet/listarProrrateoCosDet',
	id_store:'id_prorrateo_det',
	fields: [
		{name:'id_prorrateo_det', type: 'numeric'},
		{name:'id_prorrateo', type: 'numeric'},
		{name:'id_tipo_costo', type: 'numeric'},
	//	{name:'id_cuenta', type: 'numeric'},
		{name:'id_auxiliar', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		{name:'desc_tipo_costo', type: 'string'},
		{name:'desc_cuenta', type: 'string'},
		{name:'desc_auxiliar', type: 'string'},
		{name:'codigo', type: 'string'},
		{name:'nro_cuenta', type: 'string'},
		{name:'cuenta_nro', type: 'string'},
		{name:'codigo_auxiliar', type: 'string'}


	],
	sortInfo:{
		field: 'id_prorrateo_det',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	btest:false,

	onButtonEdit:function () {
		this.Cmp.cuenta_nro.store.baseParams = {par_filtro: 'c.nro_cuenta#c.nombre_cuenta', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};
		this.Cmp.id_auxiliar.store.baseParams = {par_filtro: 'aux.codigo_auxiliar#aux.nombre_auxiliar', id_tipo_costo: this.Cmp.id_tipo_costo.getValue()};
		this.Cmp.cuenta_nro.setDisabled(true);
		this.Cmp.id_auxiliar.setDisabled(true);
		console.log('value update',	this.Cmp.id_tipo_costo);
		this.Cmp.cuenta_nro.on('select', function(combo, record, index){
						//Carga el resumen del activo fijo seleccionado
						this.llenarcampo(record.data);
					},this);
		Phx.vista.ProrrateoCosDet.superclass.onButtonEdit.call(this);

		//console.log('value update',	this.Cmp.cuenta_nro.reset());
	},

onButtonNew:function () {
	this.Cmp.cuenta_nro.on('select', function(combo, record, index){
					//Carga el resumen del activo fijo seleccionado
					this.llenarcampo(record.data);
				},this);

		Phx.vista.ProrrateoCosDet.superclass.onButtonNew.call(this);
		//console.log('value INSERT	',this.Cmp.id_prorrateo_det.getValue());
	 // console.log('value INSERT	',	this.Cmp.cuenta_nro);

			this.Cmp.cuenta_nro.reset();
			this.Cmp.id_auxiliar.reset();
			this.Cmp.cuenta_nro.setDisabled(true);
			this.Cmp.id_auxiliar.setDisabled(true);
			this.Cmp.id_prorrateo_det.setValue(null);
			//console.log('FORZAR ID',this.Cmp.id_prorrateo_det.setValue(null));
	},
	llenarcampo: function(data){
		//console.log('RESPUESTA 2',data);
	 this.Cmp.cuenta_nro.setValue(data.nro_cuenta);
 },

	onReloadPage: function (m) {
		this.maestro = m;
	  this.store.baseParams = {id_prorrateo:this.maestro.id_prorrateo};
		//this.store.baseParams = {id_gestion:this.maestro.id_gestion};
		this.load({params: {start: 0, limit: 50}});
	},

	loadValoresIniciales: function () {
		this.Cmp.id_prorrateo.setValue(this.maestro.id_prorrateo);
		Phx.vista.ProrrateoCosDet.superclass.loadValoresIniciales.call(this);
	}
});
</script>
