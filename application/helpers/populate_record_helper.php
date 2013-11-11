<?php
/**
 * Atribui os dados de entrada (POST) ao recordset que será enviado ao BD.
 *
 * @param array $record recebe por referência o recordset.
 * @param array $input_post recebe os dados enviado por POST.
 */
function populate_record(&$record, $input_post){
	if($input_post != false && is_array($input_post)){
		foreach($input_post as $k => $v){
			if(array_key_exists($k, $record))
				$record[$k] = $v;
		}
	} else
		throw new Exception('Nenhum dado POST foi atribuído.');
}