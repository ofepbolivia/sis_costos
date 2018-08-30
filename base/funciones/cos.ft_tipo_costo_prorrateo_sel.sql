CREATE OR REPLACE FUNCTION cos.ft_tipo_costo_prorrateo_sel (
  p_administrador integer,
  p_id_usuario integer,
  p_tabla varchar,
  p_transaccion varchar
)
RETURNS varchar AS
$body$

/**************************************************************************
 SISTEMA:		Sistema de Costos
 FUNCION: 		cos.ft_tipo_costo_prorrateo_sel
 DESCRIPCION:   Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tautos'
 AUTOR: 		 (admin)
 FECHA:	        08-08-2018 20:39:30
 COMENTARIOS:
***************************************************************************
 HISTORIAL DE MODIFICACIONES:
#ISSUE				FECHA				AUTOR				DESCRIPCION
 #0				08-08-2018 20:39:30								Funcion que devuelve conjuntos de registros de las consultas relacionadas con la tabla 'orga.tautos'
 #
 ***************************************************************************/

 DECLARE

 	v_consulta    		varchar;
 	v_parametros  		record;
 	v_nombre_funcion   	text;
 	v_resp				varchar;
     v_filtro			varchar;

 BEGIN

 	v_nombre_funcion = 'cos.ft_tipo_costo_prorrateo_sel';
     v_parametros = pxp.f_get_record(p_tabla);

 	/*********************************
  	#TRANSACCION:  'COS_PRORRA_SEL'
  	#DESCRIPCION:	Consulta de datos
  	#AUTOR:		admin
  	#FECHA:		08-08-2018 20:39:30
 	***********************************/

 	if(p_transaccion='COS_PRORRA_SEL')then

     	begin
     		--Sentencia de la consulta
 			v_consulta:='select
 						prorra.id_tipo_costo_prorrateo,
                         prorra.codigo_prorrateo,
                         prorra.nombre,
                         prorra.descripcion,
                         prorra.id_tipo_costo,
                         prorra.id_usuario_reg,
                         prorra.id_usuario_mod,
                         prorra.fecha_reg,
                         prorra.fecha_mod,
                         prorra.estado_reg,
                         prorra.id_usuario_ai,
                         prorra.usuario_ai,
 						usu1.cuenta as usr_reg,
 						usu2.cuenta as usr_mod
 						from cos.ttipo_costo_prorrateo prorra
 						inner join segu.tusuario usu1 on usu1.id_usuario = prorra.id_usuario_reg
 						left join segu.tusuario usu2 on usu2.id_usuario = prorra.id_usuario_mod
                         inner join cos.ttipo_costo cos on cos.id_tipo_costo = prorra.id_tipo_costo
 				        where  ';

 			--Definicion de la respuesta
 			v_consulta:=v_consulta||v_parametros.filtro;
 			v_consulta:=v_consulta||' order by ' ||v_parametros.ordenacion|| ' ' || v_parametros.dir_ordenacion || ' limit ' || v_parametros.cantidad || ' offset ' || v_parametros.puntero;

 			--Devuelve la respuesta
 			return v_consulta;

 		end;

 	/*********************************
  	#TRANSACCION:  'COS_PRORRA_CONT'
  	#DESCRIPCION:	Conteo de registros
  	#AUTOR:		admin
  	#FECHA:		08-08-2018 20:39:30
 	***********************************/

 	elsif(p_transaccion='COS_PRORRA_CONT')then

 		begin
 			--Sentencia de la consulta de conteo de registros
 			v_consulta:='select count(id_tipo_costo_prorrateo)
                   from cos.ttipo_costo_prorrateo prorra
                   inner join segu.tusuario usu1 on usu1.id_usuario = prorra.id_usuario_reg
                   left join segu.tusuario usu2 on usu2.id_usuario = prorra.id_usuario_mod
                   inner join cos.ttipo_costo cos on cos.id_tipo_costo = prorra.id_tipo_costo
           		  where  ';

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
