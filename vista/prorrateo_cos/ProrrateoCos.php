<?php
/**
*@package pXP
*@file gen-ProrrateoCos.php
*@author  (franklin.espinoza)
*@date 25-08-2017 19:34:27
*@description Archivo con la interfaz de usuario que permite la ejecucion de todas las funcionalidades del sistema
*/

header("content-type: text/javascript; charset=UTF-8");
?>
<script>
Phx.vista.ProrrateoCos=Ext.extend(Phx.gridInterfaz,{

	//desc_gestion: '',
	constructor:function(config){


		this.maestro=config.maestro;
		var prorrateo = config.prorrateo;
    	//llama al constructor de la clase padre
		Phx.vista.ProrrateoCos.superclass.constructor.call(this,config);
		this.init();
	  console.log('config',config);


var that = this;

		Ext.Ajax.request({
			url:'../../sis_parametros/control/Gestion/obtenerGestionByFecha',
			params:{fecha:new Date()},
			success:function (resp) {
				var reg = Ext.util.JSON.decode(Ext.util.Format.trim(resp.responseText));
				if(!reg.ROOT.error){
					console.log('config',that.prorrateo);
					if(that.prorrateo==undefined){
							this.store.baseParams.prorrateo = 'general';
						}else {
							this.store.baseParams.prorrateo = that.prorrateo;
							this.store.baseParams.id_tipo_costo_prorrateo = that.maestro.id_tipo_costo_prorrateo;
							console.log('config',that.prorrateo, that.maestro.id_tipo_costo_prorrateo, this.maestro.id_tipo_costo_prorrateo);
						}
					this.load({params:{start:0, limit:this.tam_pag}});
				}else{

					alert('Ocurrio un error al obtener la Gestión')
				}
			},
			failure: this.conexionFailure,
			timeout:this.timeout,
			scope:this
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
							{
									columnWidth: .50,
									border: false,
									layout: 'fit',
									bodyStyle: 'padding-right:10px;',
									items: [
											{
													xtype: 'fieldset',
													title: 'Tipo Calculo y Pertenece',

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
					]
			}
	],

	Atributos:[
		{
			//configuracion del componente
			config:{
					labelSeparator:'',
					inputType:'hidden',
					name: 'id_prorrateo'
			},
			type:'Field',
			form:true
		},

		{
			config:{
				name: 'codigo',
				fieldLabel: 'Codigo',
				labelStyle: 'font-weight:bold; color:#005300;',
				allowBlank: false,
				anchor: '80%',
				gwidth: 100,
				maxLength:20,
				style: {
								background: '#D1FFBD',
								color:'green',
								fontWeight: 'bold'
				},
				renderer:function (value,p,record){

					return String.format('<div ext:qtip="Codigo"><b><font color="green">{0}</font></b><br></div>', record.data['codigo']);
				}
			},
				type:'TextField',
				bottom_filter:true,
				filters:{pfiltro:'pro_cos.codigo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'nombre_prorrateo',
				fieldLabel: 'Nombre Prorrateo',
				labelStyle: 'font-weight:bold; color:#005300;',
				allowBlank: false,
				anchor: '80%',
				gwidth: 300,
				style: {
								background: '#D1FFBD',
								color:'green',
								fontWeight: 'bold'
				},
				maxLength:200
			},
				type:'TextField',
				bottom_filter:true,
				filters:{pfiltro:'pro_cos.nombre_prorrateo',type:'string'},
				id_grupo:1,
				grid:true,
				form:true
		},
		{
			config:{
				name: 'tipo_calculo',
				fieldLabel: 'Tipo Calculo',
				labelStyle: 'font-weight:bold; color:#001A8E;',
				allowBlank: true,
				anchor: '80%',
				gwidth: 100,
				maxLength: 200,
				typeAhead:true,
				forceSelection: true,
				triggerAction:'all',
				mode:'local',
				//store:[ 'Hrs Vuelo ATO', 'ASK, RPK', 'Nro. Vuelos', 'Hrs. Vuelo Flota','Hrs. Vuelo Nave', 'NroPasajeros', 'ASK'],
				store:[ 'Horas trabajadas en la jornada', 'Cantidad de empleados del centro de costo','Número de equipos ocupados'], //fRnk

				style:'text-transform:uppercase; background:#C5E5F5; color:blue; font-weight:bold'
				},
				type:'ComboBox',
				bottom_filter:true,
				filters:{pfiltro:'pro_cos.tipo_calculo',type:'string'},
				id_grupo:2,
				grid:true,
				form:true
		},
		{
			config: {
				name: 'id_tipo_costo_prorrateo',
				fieldLabel: 'Pertenece',
				labelStyle: 'font-weight:bold; color:#001A8E;',
				allowBlank: true,
				style: {
								background: '#C5E5F5',
								color:'blue',
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
					fields: ['id_tipo_costo_prorrateo','nombre'],
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
				renderer:function (value,p,record){

					return String.format('<div ext:qtip="Bueno"><b><font color="green">{0}</font></b><br></div>', record.data['id_tipo_costo_prorrateo']);
				},
			},
			type: 'ComboBox',
			id_grupo: 2,
			//bottom_filter: true,
			filters: {pfiltro: 'prorra.nombre',type: 'string'},
			grid: false,
			form: true
		},
		{
			config:{
				name: 'nombre',
				fieldLabel: 'Pertenece',
				allowBlank: false,
				anchor: '80%',
				gwidth: 200,
				maxLength:200,
				renderer:function (value,p,record){

					return String.format('<div ext:qtip="Pertenece"><b><font color="blue">{0}</font></b><br></div>', record.data['nombre']);
				}
			},
				type:'TextField',
				bottom_filter:true,
				filters:{pfiltro:'prorra.nombre',type:'string'},
				id_grupo:2,
				grid:true,
				form:false
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
				filters:{pfiltro:'pro_cos.estado_reg',type:'string'},
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
				filters:{pfiltro:'pro_cos.id_usuario_ai',type:'numeric'},
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
				filters:{pfiltro:'pro_cos.usuario_ai',type:'string'},
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
				filters:{pfiltro:'pro_cos.fecha_reg',type:'date'},
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
				filters:{pfiltro:'pro_cos.fecha_mod',type:'date'},
				id_grupo:1,
				grid:true,
				form:false
		}
	],
	tam_pag:50,
	title:'Prorrateo Costos',
	ActSave:'../../sis_costos/control/ProrrateoCos/insertarProrrateoCos',
	ActDel:'../../sis_costos/control/ProrrateoCos/eliminarProrrateoCos',
	ActList:'../../sis_costos/control/ProrrateoCos/listarProrrateoCos',
	id_store:'id_prorrateo',
	fields: [
		{name:'id_prorrateo', type: 'numeric'},
		{name:'codigo', type: 'string'},
		{name:'nombre_prorrateo', type: 'string'},
		{name:'tipo_calculo', type: 'string'},
		{name:'estado_reg', type: 'string'},
		{name:'id_usuario_ai', type: 'numeric'},
		{name:'usuario_ai', type: 'string'},
		{name:'fecha_reg', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'id_usuario_reg', type: 'numeric'},
		{name:'id_usuario_mod', type: 'numeric'},
		{name:'fecha_mod', type: 'date',dateFormat:'Y-m-d H:i:s.u'},
		{name:'usr_reg', type: 'string'},
		{name:'usr_mod', type: 'string'},
		//{name:'id_gestion', type: 'numeric'},
		//{name:'gestion', type: 'numeric'},
		{name:'id_tipo_costo_prorrateo', type: 'numeric'},
		{name:'nombre', type: 'string'}

	],
	sortInfo:{
		field: 'id_prorrateo',
		direction: 'ASC'
	},
	bdel:true,
	bsave:false,
	btest:false,
	tabsouth :[
		{
			url:'../../../sis_costos/vista/prorrateo_cos_det/ProrrateoCosDet.php',
			title:'Detalle Prorrateo',
			height:'50%',
			cls:'ProrrateoCosDet'
		}
	],

	onButtonNew:function () {
		Phx.vista.ProrrateoCos.superclass.onButtonNew.call(this);
		this.Cmp.id_prorrateo.setValue(null);
		this.getComponente('id_tipo_costo_prorrateo').setValue(this.maestro.id_tipo_costo_prorrateo);

		//console.log('RESPUESTA',this);
	},

});
</script>
