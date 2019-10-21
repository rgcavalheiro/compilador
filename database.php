<?php

mysqli_report(MYSQLI_REPORT_STRICT);
function open_database() {
  # Aqui está o segredo

	try {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		return $conn;
	} catch (Exception $e) {
		echo $e->getMessage();
		return null;
	}
}
function close_database($conn) {
	try {
		mysqli_close($conn);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function get_lembretes($id){

global $lembretes;
$lembretes = getwhere('lembretes','id_vendedor',$id);
if($lembretes!=null){  

  return true;
}else{
  return false;
}

}



function getwhere($table,$search= null,$what = null){

  $database = open_database();
  $found = null;
  try {


if($search != null){

    $sql = "SELECT * FROM ".$table." WHERE ".$search." = '" . $what . "'";
    }else{
     $sql = "SELECT * FROM ".$table; 
    }




    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


      $found = array();
      while ($row = $result->fetch_assoc()) {
        $row = array_map('utf8_encode', $row); 
        array_push($found, $row);
      } 
    }
    
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;

}

function convert_date($data,$tipo = null){
  if($tipo == 'db'){

    $val1 = explode('/', $data);
    $value = $val1[2].'-'.$val1[1].'-'.$val1[0];


  }else{
    $val1 = explode('-', $data);
    $value = $val1[2].'/'.$val1[1].'/'.$val1[0];

  }
return $value;
}


function find( $table = null, $id = null ) {

  $database = open_database();
  $found = null;
  try {
    if ($id) {
      $sql = "SELECT * FROM " . $table . " WHERE id = " . $id;
      $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        $found = $result->fetch_assoc();
        $found = array_map('utf8_encode', $found); 
      }
      
    } else {

      $sql = "SELECT * FROM " . $table;
      $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


        $found = array();
        while ($row = $result->fetch_assoc()) {
          $row = array_map('utf8_encode', $row); 
          array_push($found, $row);
        } 
      }
    }
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}

function find2( $table = null, $id = null ) {

  $database = open_database();
  $found = null;
  try {
    if ($id) {
      $sql = "SELECT * FROM " . $table . " WHERE id = " . $id ;
      $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        $found = $result->fetch_assoc();
        $found = array_map('utf8_encode', $found); 
      }
      
    } else {

      $sql = "SELECT * FROM " . $table . " ORDER BY sequencia asc";
      $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


        $found = array();
        while ($row = $result->fetch_assoc()) {
          $row = array_map('utf8_encode', $row); 
          array_push($found, $row);
        } 
      }
    }
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}
function find3() {

  $database = open_database();
  $found = null;
  try {


    $sql = "SELECT * FROM usuarios WHERE grupo < 2";
    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


      $found = array();
      while ($row = $result->fetch_assoc()) {
        $row = array_map('utf8_encode', $row); 
        array_push($found, $row);
      } 

    }
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}

function find_all( $table ) {
  return find($table);
}
function save($table = null, $data = null) {
  $database = open_database();
  $columns = null;
  $values = null;
  //print_r($data);

  foreach ($data as $key => $value) {
    $columns .= trim($key, "'") . ",";
    $value = utf8_decode($value);
    $values .= "'$value',";
  }


  // remove a ultima virgula
  $columns = rtrim($columns, ',');
  $values = rtrim($values, ',');
  
  $sql = "INSERT INTO " . $table . "($columns)" . " VALUES " . "($values);";

  try {
    $database->query($sql);
    $_SESSION['message'] = 'Registro cadastrado com sucesso.';
    $_SESSION['type'] = 'success';


  } catch (Exception $e) { 

    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  } 
  close_database($database);
}
function update($table = null, $id = 0, $data = null) {
  $database = open_database();
  $items = null;
  foreach ($data as $key => $value) {
    $value = utf8_decode($value);
    $items .= trim($key, "'") . "='$value',";
  }
  // remove a ultima virgula
  $items = rtrim($items, ',');
  $sql  = "UPDATE " . $table;
  $sql .= " SET $items";
  $sql .= " WHERE id=" . $id . ";";
  try {
    $database->query($sql);
    $_SESSION['message'] = 'Registro atualizado com sucesso.';
    $_SESSION['type'] = 'success';
  } catch (Exception $e) { 
    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';
  } 
  close_database($database);
}
function remove( $table = null, $id = null ) {
  $database = open_database();
  
  try {
    if ($id) {
      $sql = "DELETE FROM " . $table . " WHERE id = " . $id;
      $result = $database->query($sql);
      if ($result = $database->query($sql)) {     
        $_SESSION['message'] = "Registro Removido com Sucesso.";
        $_SESSION['type'] = 'success';
      }
    }
  } catch (Exception $e) { 
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  close_database($database);
}

function find_por_vendedor() {

  $database = open_database();
  $found = null;
  try {



    if($_SESSION['UsuarioNivel'] < 2){
      $id = $_SESSION['UsuarioID'];
      
      $sql = "SELECT * FROM clientes WHERE id_vendedor = " . $id . " ORDER BY id desc";
    }else{
      $sql = "SELECT * FROM clientes ORDER BY id desc ";
    }



    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


      $found = array();
      while ($row = $result->fetch_assoc()) {
        $row = array_map('utf8_encode', $row); 
        array_push($found, $row);
      } 
    }
    
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}

function find_all_hist() {

  $database = open_database();
  $found = null;
  try {



    $sql = "SELECT * FROM historico order by id desc";



    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


      $found = array();
      while ($row = $result->fetch_assoc()) {
        $row = array_map('utf8_encode', $row); 
        array_push($found, $row);
      } 
    }
    
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}



function next_ai() {

  $database = open_database();
  $found = null;
  try {


    $sql = "SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES
    WHERE table_name = 'historico'";
    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


      $found = array();

      while ($row = $result->fetch_assoc()) {           
        array_push($found, $row);
      } 

      $found = $found[0]['auto_increment'];

    }

  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}
function find_por_tipo($tipo) {

  $database = open_database();
  $found = null;
  try {




    $sql = "SELECT * FROM propostas WHERE tipo = '" . $tipo . "' ORDER BY sequencia asc ";




    $result = $database->query($sql);

    if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


      $found = array();
      while ($row = $result->fetch_assoc()) {
        $row = array_map('utf8_encode', $row); 
        array_push($found, $row);
      } 
    }
    
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}

function find_por_vendedor_h($id,$data1= null,$data2 = null) {

  $database = open_database();
  $found = null;
  try {

    if($data1 != null and $data2 != null){


          $sql = "SELECT * FROM historico WHERE id_vendedor = " . $id . " AND data BETWEEN '" . $data1 . "' AND '" . $data2 ."'";
      

       $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);
        
       
        $found = array();
        while ($row = $result->fetch_assoc()) {
          $row = array_map('utf8_encode', $row); 
          array_push($found, $row);
        } 
      }


    }else{


      $sql = "SELECT * FROM historico WHERE id_vendedor = " . $id;




      $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);


        $found = array();
        while ($row = $result->fetch_assoc()) {
          $row = array_map('utf8_encode', $row); 
          array_push($found, $row);
        } 
      }

    }
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}


function delay($h_inicial,$qnde){
  $val1 = explode(':', $h_inicial);
  $val2 = explode(':', $qnde);

  $tohr = $val1[0]+$val2[0];
  $tomin = $val1[1]+$val2[1];


if($tomin >= 60){
  $tohr = $tohr+1;
  $tomin = $tomin-60;

}

if($tohr >= 24){
  $tohr = $tohr-24; 
}

$saida = $tohr.':'.$tomin;

if($saida == '0:0'){
  $saida = "00:00";
}


return $saida;
}

function somaHora($hora1,$hora2){
 
  $h1 = explode(":",$hora1);
  $h2 = explode(":",$hora2);
  
  $segundo = $h1[2] + $h2[2] ;
  $minuto  = $h1[1] + $h2[1] ;
  $horas   = $h1[0] + $h2[0] ;
  $dia    = 0 ;
  
  if($segundo > 59){
  
    $segundodif = $segundo - 60;
    $segundo = $segundodif;
    $minuto = $minuto + 1;
  }
  
  if($minuto > 59){
    
    $minutodif = $minuto - 60;
    $minuto = $minutodif;
    $horas = $horas + 1;
  }
  
  if($horas > 24){
    
    $num = 0;
    
    (int)$num = $horas / 24;
      $horaAtual = (int)$num * 24;
    $horasDif = $horas - $horaAtual;
    
    $horas = $horasDif;       
    
    for($i = 1; $i <= (int)$num; $i++){
          
      $dia +=  1 ;
    }
        
  }
       if($horas == 24){
      $horas = $horas-24;
    }
    
  if(strlen($horas) == 1){

  
    $horas = "0".$horas;
  }

  
  if(strlen($minuto) == 1){
  
    $minuto = "0".$minuto;
  }
  
  if(strlen($segundo) == 1){
  
    $segundo = "0".$segundo;
  }
  
  return  $horas.":".$minuto.":".$segundo;
 
}



function find_graficos_propostas($vendedor,$mes) {

  $database = open_database();
  $found = null;
  try {

    if($mes != null ){
      $y = date('Y');

      $data1  = convert_date('01-'.$mes.'-'.$y);
      $data2  = convert_date('31-'.$mes.'-'.$y);



          $sql = "SELECT * FROM historico WHERE id_vendedor = " . $vendedor . " AND data BETWEEN '" . $data1 . "' AND '" . $data2 ."'";
         
      

       $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);
        
       
        $found = array();
        while ($row = $result->fetch_assoc()) {
          $row = array_map('utf8_encode', $row); 
          array_push($found, $row);
        } 
      }


    }
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}
function find_graficos_valores($vendedor,$mes) {

  $database = open_database();
  $found = null;
  try {

    if($mes != null ){
      $y = date('Y');

      $data1  = convert_date('01-'.$mes.'-'.$y);
      $data2  = convert_date('31-'.$mes.'-'.$y);



          $sql = "SELECT * FROM historico WHERE id_vendedor = " . $vendedor . " AND data BETWEEN '" . $data1 . "' AND '" . $data2 ."'";
         
      

       $result = $database->query($sql);
      
      if ($result->num_rows > 0) {
        // $found = $result->fetch_all(MYSQLI_ASSOC);
        
       
        $found = array();
        while ($row = $result->fetch_assoc()) {
          $row = array_map('utf8_encode', $row); 
          array_push($found, $row);
        } 
      }


    }
  } catch (Exception $e) {
    $_SESSION['message'] = $e->GetMessage();
    $_SESSION['type'] = 'danger';
  }
  
  close_database($database);

  return $found;
}
