--------------- SQL ---------------

CREATE OR REPLACE FUNCTION cos.ft_tipo_costo_cuenta_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistemas de Costos
 FUNCION: 		cos.ft_tipo_costo_cuenta_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'cos.ttipo_costo_cuenta'
 AUTOR: 		 (admin)
 FECHA:	        30-12-2016 20:29:17
 COMENTARIOS:	
***************************************************************************
 HISTORIAL DE MODIFICACIONES:

 DESCRIPCION:	
 AUTOR:			
 FECHA:		
***************************************************************************/

DECLARE

	v_nro_requerimiento    	integer;
	v_parametros           	record;
	v_id_requerimiento     	integer;
	v_resp		            varchar;
	v_nombre_funcion        text;
	v_mensaje_error         text;
	v_id_tipo_costo_cuenta	integer;
    v_registros				record;
    
    va_id_auxiliares		integer[];
			    
BEGIN

    v_nombre_funcion = 'cos.ft_tipo_costo_cuenta_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************    
 	#TRANSACCION:  'COS_CO_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		admin	
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

	if(p_transaccion='COS_CO_INS')then
					
        begin
        
            va_id_auxiliares = string_to_array(v_parametros.id_auxiliares,',')::integer[];
            
            
            select
               tc.sw_trans
              into
               v_registros
            from cos.ttipo_costo tc
            where tc.id_tipo_costo = v_parametros.id_tipo_costo;
            
            IF v_registros.sw_trans != 'movimiento' THEN
               raise exception 'solo puede relacionar cuentas a  tipos costos del tipo movimiento';
            END IF;
            
           --validar que la combinación  no se duplique antes de isnertar
           v_registros = NULL;
            
           select 
               tcc.id_tipo_costo,
               tc.nombre
          into
             v_registros
          from cos.ttipo_costo_cuenta tcc
          inner join  cos.ttipo_costo tc on tc.id_tipo_costo = tcc.id_tipo_costo
          where       tcc.estado_reg = 'activo'
                 and  tcc.codigo_cuenta = v_parametros.codigo_cuenta
                 and  COALESCE(va_id_auxiliares && tcc.id_auxiliares, false);
            
            IF v_registros is not null THEN
                  raise exception 'La cuenta se duplica para el tipo de costo: %',v_registros.nombre;
            END IF;     
            
            
            
        	--Sentencia de la insercion
        	insert into cos.ttipo_costo_cuenta(
			estado_reg,
			codigo_cuenta,
			id_auxiliares,
			id_usuario_reg,
			fecha_reg,
			id_usuario_ai,
			usuario_ai,
			id_usuario_mod,
			fecha_mod,
            id_tipo_costo
          	) values(
			'activo',
			v_parametros.codigo_cuenta,
			va_id_auxiliares,
			p_id_usuario,
			now(),
			v_parametros._id_usuario_ai,
			v_parametros._nombre_usuario_ai,
			null,
			null,
            v_parametros.id_tipo_costo
							
			
			
			)RETURNING id_tipo_costo_cuenta into v_id_tipo_costo_cuenta;
			
			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Costo Cuenta almacenado(a) con exito (id_tipo_costo_cuenta'||v_id_tipo_costo_cuenta||')'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_costo_cuenta',v_id_tipo_costo_cuenta::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************    
 	#TRANSACCION:  'COS_CO_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		admin	
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

	elsif(p_transaccion='COS_CO_MOD')then

		begin
        
            va_id_auxiliares = string_to_array(v_parametros.id_auxiliares,',')::integer[];
            
            
            v_registros = NULL;
            
            select 
               tcc.id_tipo_costo,
               tc.nombre
            into
             v_registros
            from cos.ttipo_costo_cuenta tcc
            inner join  cos.ttipo_costo tc on tc.id_tipo_costo = tcc.id_tipo_costo
            where       tcc.estado_reg = 'activo'
                 and  tcc.codigo_cuenta = v_parametros.codigo_cuenta
                 and  tcc.id_tipo_costo_cuenta != v_parametros.id_tipo_costo_cuenta
                 and  COALESCE(va_id_auxiliares && tcc.id_auxiliares, false);
            
            IF v_registros is not null THEN
                  raise exception 'La cuenta se duplica para el tipo de costo: %',v_registros.nombre;
            END IF; 
            
            
            
			--Sentencia de la modificacion
			update cos.ttipo_costo_cuenta set
                codigo_cuenta = v_parametros.codigo_cuenta,
                id_usuario_mod = p_id_usuario,
                fecha_mod = now(),
                id_usuario_ai = v_parametros._id_usuario_ai,
                usuario_ai = v_parametros._nombre_usuario_ai,
                id_auxiliares =  va_id_auxiliares
			where id_tipo_costo_cuenta=v_parametros.id_tipo_costo_cuenta;
               
			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Costo Cuenta modificado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_costo_cuenta',v_parametros.id_tipo_costo_cuenta::varchar);
               
            --Devuelve la respuesta
            return v_resp;
            
		end;

	/*********************************    
 	#TRANSACCION:  'COS_CO_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		admin	
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

	elsif(p_transaccion='COS_CO_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from cos.ttipo_costo_cuenta
            where id_tipo_costo_cuenta=v_parametros.id_tipo_costo_cuenta;
               
            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','Costo Cuenta eliminado(a)'); 
            v_resp = pxp.f_agrega_clave(v_resp,'id_tipo_costo_cuenta',v_parametros.id_tipo_costo_cuenta::varchar);
              
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