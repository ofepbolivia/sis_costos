CREATE OR REPLACE FUNCTION cos.ft_tipo_costo_cuenta_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$
/**************************************************************************
 SISTEMA:		Sistemas de Costos
 FUNCION: 		cos.ft_tipo_costo_cuenta_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'cos.ttipo_costo_cuenta'
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

	v_consulta    		varchar;
	v_parametros  		record;
	v_nombre_funcion   	text;
	v_resp				varchar;
    v_filtro			varchar;
    v_gestion			varchar;
    v_id_gestion		integer;
    v_add_filtro		varchar;
    va_id_aux			integer[];
    v_id_aux			varchar;

BEGIN

	v_nombre_funcion = 'cos.ft_tipo_costo_cuenta_sel';
    v_parametros = pxp.f_get_record(p_tabla);

	/*********************************
 	#TRANSACCION:  'COS_CO_SEL'
 	#DESCRIPCION:	Consulta de datos
 	#AUTOR:		admin
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

if(p_transaccion='COS_CO_SEL')then

        begin
            --Sentencia de la consulta
            v_consulta:='select
                            id_tipo_costo_cuenta,
                            coc.estado_reg,
                            c.nro_cuenta,
                            array_to_string( coc.id_auxiliares,'','',''null'')::varchar,
                            (select list(a.codigo_auxiliar) from conta.tauxiliar a where a.id_auxiliar =ANY(coc.id_auxiliares))::varchar as codigo_auxiliares,
                            (select list(a.nombre_auxiliar) from conta.tauxiliar a where a.id_auxiliar =ANY(coc.id_auxiliares))::varchar as auxiliares,
                            coc.id_usuario_reg,
                            coc.fecha_reg,
                            coc.id_usuario_ai,
                            coc.usuario_ai,
                            coc.id_usuario_mod,
                            coc.fecha_mod,
                            usu1.cuenta as usr_reg,
                            usu2.cuenta as usr_mod,
                            coc.id_tipo_costo,
                            c.nombre_cuenta
                        from cos.ttipo_costo_cuenta coc
                        inner join conta.tcuenta c on c.nro_cuenta = coc.codigo_cuenta
                        --and c.id_gestion = 15
         				inner join segu.tusuario usu1 on usu1.id_usuario = coc.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = coc.id_usuario_mod
                        where  ';

            --Definicion de la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' GROUP BY coc.id_tipo_costo_cuenta,
                                coc.estado_reg,
                                c.nro_cuenta,
                                coc.id_usuario_reg,
                                coc.fecha_reg,
                                coc.id_usuario_ai,
                                coc.usuario_ai,
                                coc.id_usuario_mod,
                                coc.fecha_mod,
                                usu1.cuenta,
                                usu2.cuenta,
                                coc.id_tipo_costo,
                                 c.nombre_cuenta order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            --Devuelve la respuesta
            return v_consulta;

        end;

	/*********************************
 	#TRANSACCION:  'COS_CO_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

	elsif(p_transaccion='COS_CO_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_tipo_costo_cuenta)
					    from cos.ttipo_costo_cuenta coc
                        inner join conta.tcuenta c on c.nro_cuenta = coc.codigo_cuenta
                        --and c.id_gestion = 15
         				inner join segu.tusuario usu1 on usu1.id_usuario = coc.id_usuario_reg
                        left join segu.tusuario usu2 on usu2.id_usuario = coc.id_usuario_mod
                        where ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
 			v_consulta:=v_consulta||' GROUP BY coc.id_tipo_costo_cuenta,
                                coc.estado_reg,
                                c.nro_cuenta,
                                coc.id_usuario_reg,
                                coc.fecha_reg,
                                coc.id_usuario_ai,
                                coc.usuario_ai,
                                coc.id_usuario_mod,
                                coc.fecha_mod,
                                usu1.cuenta,
                                usu2.cuenta,
                                coc.id_tipo_costo,
                                 c.nombre_cuenta ';
			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'COS_CUE_SEL'
 	#DESCRIPCION:	Listar de cuentas
 	#AUTOR:		MMV
 	#FECHA:		21-02-2017
	***********************************/
    ELSIF(p_transaccion='COS_CUE_SEL')then

    	begin
    		--Sentencia de la consulta

			--05-03-2020 (may) modificacion debe ser segun el año que se desee, no poner automatico la actual

            /*v_gestion =  EXTRACT(YEAR FROM  now())::varchar;;

              select
               ges.id_gestion
              into
               v_id_gestion
              from param.tgestion ges
              where ges.gestion::varchar  = v_gestion and ges.estado_reg = 'activo';

        v_filtro = ' cta.nro_cuenta != ''1'' and  cta.nro_cuenta != ''11'' and cta.nro_cuenta != ''111'' and cta.nro_cuenta != ''1111'' and cta.id_gestion = '||v_id_gestion::varchar|| ' and ' ;
		*/

		v_filtro = ' cta.nro_cuenta != ''1'' and  cta.nro_cuenta != ''11'' and cta.nro_cuenta != ''111'' and cta.nro_cuenta != ''1111''  and ' ;

            v_consulta:='SELECT
                        cta.id_cuenta,
                       	cta.nro_cuenta,
                        cta.nombre_cuenta,
                        cta.desc_cuenta,
                        cta.nivel_cuenta,
                        cta.tipo_cuenta,
                        cta.sw_transaccional,
                        cta.sw_auxiliar,
                        cta.tipo_cuenta_pat,
                        mon.codigo as desc_moneda,
                        ges.gestion,
                        cta.sw_control_efectivo
                        from conta.tcuenta cta
						left join param.tmoneda mon on mon.id_moneda = cta.id_moneda
                        inner join param.tgestion ges on ges.id_gestion = cta.id_gestion
						where '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;
    /*********************************
 	#TRANSACCION:  'COS_CUE_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		30-12-2016 20:29:
	***********************************/

	elsif(p_transaccion='COS_CUE_CONT')then

		begin

            --05-03-2020 (may) modificacion debe ser segun el año que se desee, no poner automatico la actual

            /*v_gestion =  EXTRACT(YEAR FROM  now())::varchar;;

              select
               ges.id_gestion
              into
               v_id_gestion
              from param.tgestion ges
              where ges.gestion::varchar  = v_gestion and ges.estado_reg = 'activo';

        v_filtro = ' cta.nro_cuenta != ''1'' and  cta.nro_cuenta != ''11'' and cta.nro_cuenta != ''111'' and cta.nro_cuenta != ''1111'' and cta.id_gestion = '||v_id_gestion::varchar|| ' and ' ;
		*/

		v_filtro = ' cta.nro_cuenta != ''1'' and  cta.nro_cuenta != ''11'' and cta.nro_cuenta != ''111'' and cta.nro_cuenta != ''1111''  and ' ;


			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(id_cuenta)
						from conta.tcuenta cta
						left join param.tmoneda mon on mon.id_moneda = cta.id_moneda
                        inner join param.tgestion ges on ges.id_gestion = cta.id_gestion
						where '||v_filtro;

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;



    /*********************************
 	#TRANSACCION:  'COS_CHKCONFIG_SEL'
 	#DESCRIPCION:	Lista la combinacion de cuentas ya auxiiares que se usaron en contabilidad y que no fueron configurados
 	#AUTOR:		RAC
 	#FECHA:		17-08-2017
	***********************************/
    ELSIF(p_transaccion='COS_CHKCONFIG_SEL')then

    	begin
    		--Sentencia de la consulta


            v_consulta:='
                          select
                              DISTINCT
                              t.id_auxiliar,
                              t.id_cuenta,
                              cue.nro_cuenta,
                              cue.nombre_cuenta,
                              aux.codigo_auxiliar,
                              aux.nombre_auxiliar,
    						  (t.id_auxiliar::varchar|| t.id_cuenta::Varchar)::varchar  as rownum

                          from conta.tint_comprobante c
                          inner join param.tperiodo p on p.id_periodo = c.id_periodo
                          inner join  conta.tint_transaccion t on   c.id_int_comprobante = t.id_int_comprobante
                          inner join conta.tcuenta cue on cue.id_cuenta = t.id_cuenta
                          inner join conta.tauxiliar aux on aux.id_auxiliar = t.id_auxiliar
                          inner join conta.tconfig_tipo_cuenta ctc on cue.tipo_cuenta = ctc.tipo_cuenta
                          where p.id_gestion = '||v_parametros.id_gestion::varchar||'
                               and ctc.tipo_cuenta = ''gasto''


                              and  c.estado_reg = ''validado''
                              and  not  COALESCE(ARRAY [t.id_auxiliar] && (select  pxp.concat_array(tct.id_auxiliares)
                                                                  from cos.ttipo_costo_cuenta tct
                                                                  inner join conta.tcuenta cue
                                                                                      on      cue.nro_cuenta = tct.codigo_cuenta
                                                                                         and cue.id_gestion = '||v_parametros.id_gestion::varchar||'
                                                                                         and cue.estado_reg = ''activo''
                                                                                         and cue.id_cuenta = t.id_cuenta),FALSE)
							 AND ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;
			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

			--Devuelve la respuesta
			return v_consulta;

		end;

    /*********************************
 	#TRANSACCION:  'COS_CONFAUX_SEL'
 	#DESCRIPCION:	listado  de auxiliares configurados por cuenta
 	#AUTOR:		admin
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

    ELSEIF(p_transaccion='COS_CONFAUX_SEL')then

        begin



            select
               tct.id_auxiliares
            into
               va_id_aux
            from cos.ttipo_costo_cuenta tct
            where tct.id_tipo_costo_cuenta = v_parametros.id_tipo_costo_cuenta;

            v_id_aux = array_to_string(va_id_aux,',');


            --Sentencia de la consulta
            v_consulta:='SELECT
                              aux.id_auxiliar,
                              aux.codigo_auxiliar,
                              aux.nombre_auxiliar,
                              '||v_parametros.id_tipo_costo_cuenta||' as id_tipo_costo_cuenta
                            from conta.tauxiliar aux
                            where aux.id_auxiliar in ('||COALESCE(v_id_aux,'0')||')  AND ';

            --Definicion de la respuesta
            v_consulta:=v_consulta||v_parametros.filtro;
            v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

            --Devuelve la respuesta
            return v_consulta;

        end;

	/*********************************
 	#TRANSACCION:  'COS_CONFAUX_CONT'
 	#DESCRIPCION:	Conteo de registros
 	#AUTOR:		admin
 	#FECHA:		30-12-2016 20:29:17
	***********************************/

	elsif(p_transaccion='COS_CONFAUX_CONT')then

		begin
			--Sentencia de la consulta de conteo de registros
			v_consulta:='select count(aux.id_auxiliar)
					    from conta.tauxiliar aux
                            where aux.id_auxiliar in ('||COALESCE(v_id_aux,'0')||')  AND ';

			--Definicion de la respuesta
			v_consulta:=v_consulta||v_parametros.filtro;

			--Devuelve la respuesta
			return v_consulta;

		end;



	else

		raise exception 'Transaccion inexistente';

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
