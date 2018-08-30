CREATE OR REPLACE FUNCTION cos.ft_tipo_costo_prorrateo_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Costos
 FUNCION: 		orga.ft_tipo_costo_prorrateo_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'orga.tautos'
 AUTOR: 		 (admin)
 FECHA:	        08-08-2018 20:39:30
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				08-08-2018 20:39:30								Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'orga.tautos'
 #
 ***************************************************************************/

 DECLARE

 	v_nro_requerimiento    	integer;
 	v_parametros           	record;
 	v_id_requerimiento     	integer;
 	v_resp		            varchar;
 	v_nombre_funcion        text;
 	v_mensaje_error         text;
 	v_id_tipo_costo_prorrateo	integer;

 BEGIN

     v_nombre_funcion = 'cos.ft_tipo_costo_prorrateo_ime';
     v_parametros = pxp.f_get_record(p_tabla);

 	/*********************************
  	#TRANSACCION:  'COS_PRORRA_INS'
  	#DESCRIPCION:	Insercion de registros
  	#AUTOR:		admin
  	#FECHA:		08-08-2018 20:39:30
 	***********************************/

 	if(p_transaccion='COS_PRORRA_INS')then

         begin
         	--Sentencia de la insercion
         	insert into cos.ttipo_costo_prorrateo(
 			estado_reg,
 			codigo_prorrateo,
 			nombre,
 			descripcion,
 			usuario_ai,
 			fecha_reg,
 			id_usuario_reg,
 			id_usuario_ai,
 			id_usuario_mod,
 			fecha_mod,
             id_tipo_costo
           	) values(
 			'activo',
 			v_parametros.codigo_prorrateo,
 			v_parametros.nombre,
 			v_parametros.descripcion,
 			v_parametros._nombre_usuario_ai,
 			now(),
 			p_id_usuario,
 			v_parametros._id_usuario_ai,
 			null,
 			null,
 			v_parametros.id_tipo_costo


     )RETURNING id_tipo_costo_prorrateo into v_id_tipo_costo_prorrateo;

 			--Definicion de la respuesta
 			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Prorrateo almacenado(a) con exito (id_tipo_costo_prorrateo'||v_id_tipo_costo_prorrateo||')');
             v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_costo_prorrateo',v_id_tipo_costo_prorrateo::varchar);

             --Devuelve la respuesta
             return v_resp;

 		end;

 	/*********************************
  	#TRANSACCION:  'COS_PRORRA_MOD'
  	#DESCRIPCION:	Modificacion de registros
  	#AUTOR:		admin
  	#FECHA:		08-08-2018 20:39:30
 	***********************************/

 	elsif(p_transaccion='COS_PRORRA_MOD')then

 		begin
 			--Sentencia de la modificacion
 			update cos.ttipo_costo_prorrateo set
 			codigo_prorrateo = v_parametros.codigo_prorrateo,
 			nombre = v_parametros.nombre,
 			descripcion = v_parametros.descripcion,
 			id_usuario_mod = p_id_usuario,
 			fecha_mod = now(),
 			id_usuario_ai = v_parametros._id_usuario_ai,
 			usuario_ai = v_parametros._nombre_usuario_ai
 			where id_tipo_costo_prorrateo=v_parametros.id_tipo_costo_prorrateo;

 			--Definicion de la respuesta
             v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Prorrateo modificado(a)');
             v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_costo_prorrateo',v_parametros.id_tipo_costo_prorrateo::varchar);

             --Devuelve la respuesta
             return v_resp;

 		end;

 	/*********************************
  	#TRANSACCION:  'COS_PRORRA_ELI'
  	#DESCRIPCION:	Eliminacion de registros
  	#AUTOR:		admin
  	#FECHA:		08-08-2018 20:39:30
 	***********************************/

 	elsif(p_transaccion='COS_PRORRA_ELI')then

 		begin
 			--Sentencia de la eliminacion
 			delete from cos.ttipo_costo_prorrateo
             where id_tipo_costo_prorrateo=v_parametros.id_tipo_costo_prorrateo;

             --Definicion de la respuesta
             v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Autos eliminado(a)');
             v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_costo_prorrateo',v_parametros.id_tipo_costo_prorrateo::varchar);

             --Devuelve la respuesta
             return v_resp;

 		end;

 	else

     	raise exception 'Transaccion inexistente: %',p_transaccion;

 	end if;

 EXCEPTION

 	WHEN OTHERS THEN
 		v_resp='';
 		v_resp = pxp.f_agrega_clave(v_resp,'mensaje',SQLERRM);
 		v_resp = pxp.f_agrega_clave(v_resp,'codigo_error',SQLSTATE);
 		v_resp = pxp.f_agrega_clave(v_resp,'procedimientos',v_nombre_funcion);
 		raise exception '%',v_resp;

 END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;
