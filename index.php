<?php

require_once 'config.php';

require_once 'database.php';



$todos = find('simbolos');
foreach ($todos as $key => $value) {
	$id = $value['id'];
	remove('simbolos',$id);
}



$arquivo = fopen('lexico.txt','r');
$atual = 0;
$saida;

$prepara_variavel = 0;
$tipo_temp;

$identificadores_temp = array();
$identificadores = array();






$tabela_simbolos = array('nome,tipo,escopo,simples,funcao,parametros,matriz,dimensoes');




while(!feof($arquivo) ){

	$linha = fgets($arquivo,1024);

	
	echo '[Linha '.$atual.']= '.$linha.'<br/>';
	$simbolo = array('nome'=> '','escopo' => '','simples' => '','funcao' => '','parametros' => '','matriz' => '','dimensoes' => '');
	checkLinha($linha);
	$atual++;

	
}


fclose($arquivo);















function checkLinha($str){	
	global $tipo_temp;
	global $prepara_variavel;
	global $saida;

	$er = '/^(\d){1,3}/';
	if(preg_match_all($er, $str,$result)){
		$cod = $result[0][0];
	}

	switch ($cod) {
		
		case '150':
		if(isIdentificador($str)){
			echo 'Identificador validado ['.$tipo_temp.']"'.$saida.'" <br><br>';
			$simbolo['nome'] = $saida;
			if($tipo_temp == 'void'){
				$simbolo['simples'] = 0;
				$simbolo['funcao'] = 1;
			}else{
				$simbolo['simples'] = 1;
			}
			$simbolo['tipo'] = $tipo_temp;
			$simbolo['escopo'] = 1;
			
 			save('simbolos',$simbolo);

		}
		break;

		case '2':
		echo 'Caractere "," <br><br>';
		break;

		case '1':
		echo 'Caractere ";" <br><br>';
		$tipo_temp = '';
		break;

		case '102' or '115':
		if(isTipo($str)){
			echo 'Tipo validado "'.$tipo_temp.'"<br><br>';	
		}else{
			echo 'Tipo invalido<br><br>';
		}
		break;

		
	}



}

function isIdentificador($str){
	global $identificadores_temp;
	global $saida;
	$pos = strpos($str, 'id')+3;
	if($pos > 0){
		$saida = substr($str, $pos);
		$saida = trim($saida);		
		return true;
		
		array_push($identificadores_temp, $saida);

	}else{
		return false;
	}


}



function isTipo($str){
	$padroes = ['void','char','int','float','double'];
	global $prepara_variavel;
	global $tipo_temp;

	$return = false;
	foreach ($padroes as $key => $value) {
		if(encontra($str,$value)){
			$tipo_temp = $value;
			$return = true;
			$prepara_variavel = 1;
			break;
		}
	}

	return $return;
	
	
}


function encontra($str,$procurada){

	if(preg_match_all('/'.$procurada.'/', $str)){
		return true;

	}else{
		return false;
	}

}


function addSimbolo($simbolo){

save('simbolos',$simbolo);


}





?>