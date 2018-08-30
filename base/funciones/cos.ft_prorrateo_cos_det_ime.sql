CREATE OR REPLACE FUNCTION cos.ft_prorrateo_cos_det_ime (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistema de Costos
 FUNCION: 		cos.ft_prorrateo_cos_det_ime
 DESCRIPCION:   Funcion que gestiona las operaciones basicas (inserciones, modificaciones, eliminaciones de la tabla 'cos.tprorrateo_cos_det'
 AUTOR: 		 (franklin.espinoza)
 FECHA:	        25-08-2017 19:35:31
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
	v_id_prorrateo_det		integer;
    v_contador				integer;
    v_bandera				boolean;

    v_editar				integer;
    contador				record;
  	v_arreglo				INTEGER[];
    v_length				integer;
BEGIN

    v_nombre_funcion = 'cos.ft_prorrateo_cos_det_ime';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'COS_PROCOSDE_INS'
 	#DESCRIPCION:	Insercion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

	if(p_transaccion='COS_PROCOSDE_INS')then

        begin



            --RAISE EXCEPTION 'auxiliares: %',v_parametros.id_auxiliar;
            --v_arreglo = v_parametros.id_auxiliar;
         	--RAISE EXCEPTION 'auxiliares: %, %, %',v_arreglo,v_length,i;
                --validar detalle del prorrateo




                      v_arreglo = string_to_array(v_parametros.id_auxiliar,',');
                      v_length = array_length(v_arreglo,1);
                      for i in 1..v_length
                          loop

                          			v_contador=0;

                          			select count(tpcd.id_prorrateo_det)
                                    INTO v_contador
                                    from cos.tprorrateo_cos_det tpcd
                                    where tpcd.id_tipo_costo = v_parametros.id_tipo_costo AND
                                          tpcd.cuenta_nro = v_parametros.cuenta_nro and
                                          tpcd.id_auxiliar = v_arreglo[i]::INTEGER;
                                         -- raise exception 'v_editar: %, %', v_contador, p_transaccion;
                                    IF(v_contador=0)THEN
                                            insert into cos.tprorrateo_cos_det(
                                            id_prorrateo,
                                            id_tipo_costo,
                                            --id_cuenta,
                                            cuenta_nro,
                                            id_auxiliar,
                                            estado_reg,
                                            id_usuario_ai,
                                            usuario_ai,
                                            fecha_reg,
                                            id_usuario_reg,
                                            fecha_mod,
                                            id_usuario_mod
                                            )
                                             values(
                                            v_parametros.id_prorrateo,
                                            v_parametros.id_tipo_costo,
                                            --v_parametros.id_cuenta,
                                            v_parametros.cuenta_nro,
                                            v_arreglo[i]::INTEGER,
                                            'activo',
                                            v_parametros._id_usuario_ai,
                                            v_parametros._nombre_usuario_ai,
                                            now(),
                                            p_id_usuario,
                                            null,
                                            null
                                            )RETURNING id_prorrateo_det into v_id_prorrateo_det;

                                      END IF;

        				end loop;






        	--Sentencia de la insercion


			--Definicion de la respuesta
			v_resp = pxp.f_agrega_clave(v_resp,'mensaje','ProrrateoCosDet almacenado(a) con exito (id_prorrateo_det'||v_id_prorrateo_det||')');
            v_resp = pxp.f_agrega_clave(v_resp,'id_prorrateo_det',v_id_prorrateo_det::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'COS_PROCOSDE_MOD'
 	#DESCRIPCION:	Modificacion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

	elsif(p_transaccion='COS_PROCOSDE_MOD')then

		begin
         			  v_arreglo = string_to_array(v_parametros.id_auxiliar,',');
                      v_length = array_length(v_arreglo,1);
                      for i in 1..v_length
                          loop

                          v_editar=0;

                          			select count(tpcd.id_prorrateo_det)
                                    INTO v_editar
                                    from cos.tprorrateo_cos_det tpcd
                                    where tpcd.id_tipo_costo = v_parametros.id_tipo_costo AND
                                          tpcd.cuenta_nro = v_parametros.cuenta_nro and
                                          tpcd.id_auxiliar = v_arreglo[i]::INTEGER;
                                    --raise exception 'v_editar: %, %', v_editar, p_transaccion;
                                    IF(v_editar=0)THEN

                          				--Sentencia de la modificacion
                                        update cos.tprorrateo_cos_det set
                                        id_prorrateo = v_parametros.id_prorrateo,
                                        id_tipo_costo = v_parametros.id_tipo_costo,
                                        --id_cuenta = v_parametros.id_cuenta,
                                        cuenta_nro = v_parametros.cuenta_nro,
                                        id_auxiliar = v_arreglo[i]::INTEGER,
                                        fecha_mod = now(),
                                        id_usuario_mod = p_id_usuario,
                                        id_usuario_ai = v_parametros._id_usuario_ai,
                                        usuario_ai = v_parametros._nombre_usuario_ai
                                        where id_prorrateo_det=v_parametros.id_prorrateo_det;

                                    ELSE
                                    	raise EXCEPTION 'La Combinacion (Tipo Costo, Nro Cuenta y Auxiliar) ya existe, revise los prorrateos registrados.';
                                    END IF;
                          end loop;


			--Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','ProrrateoCosDet modificado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_prorrateo_det',v_parametros.id_prorrateo_det::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;

	/*********************************
 	#TRANSACCION:  'COS_PROCOSDE_ELI'
 	#DESCRIPCION:	Eliminacion de registros
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:35:31
	***********************************/

	elsif(p_transaccion='COS_PROCOSDE_ELI')then

		begin
			--Sentencia de la eliminacion
			delete from cos.tprorrateo_cos_det
            where id_prorrateo_det=v_parametros.id_prorrateo_det;

            --Definicion de la respuesta
            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','ProrrateoCosDet eliminado(a)');
            v_resp = pxp.f_agrega_clave(v_resp,'id_prorrateo_det',v_parametros.id_prorrateo_det::varchar);

            --Devuelve la respuesta
            return v_resp;

		end;
    /*********************************
 	#TRANSACCION:  'COS_PROCOSDE_VALIDAR'
 	#DESCRIPCION:	Validamos el detale del prorrateo de costos
 	#AUTOR:		franklin.espinoza
 	#FECHA:		25-08-2017 19:34:27
	***********************************/

	elsif(p_transaccion='COS_PROCOSDE_VALIDAR')then

		begin
			--validar detalle del prorrateo
            select count(tpcd.id_prorrateo_det)
            INTO v_contador
            from cos.tprorrateo_cos_det tpcd
            where tpcd.id_tipo_costo = v_parametros.id_tipo_costo AND
            tpcd.cuenta_nro = v_parametros.cuenta_nro AND tpcd.id_auxiliar = v_parametros.id_auxiliar AND
            tpcd.id_prorrateo = v_parametros.id_prorrateo;

            IF(v_contador>=1)THEN
        		v_bandera = true;
            ELSE
            	v_bandera = false;
			END IF;

            v_resp = pxp.f_agrega_clave(v_resp,'mensaje','El ProrrateoCosDet que esta tratando de  insertar, ya se cuenta con un registro de las mismas caracteristicas para esta gestión');
            v_resp = pxp.f_agrega_clave(v_resp,'v_bandera',v_bandera::varchar);

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
