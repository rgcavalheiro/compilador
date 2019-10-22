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
    $res = $database->query($sql);
    $_SESSION['message'] = 'Registro cadastrado com sucesso.';
    $_SESSION['type'] = 'success';


  } catch (Exception $e) { 

    $_SESSION['message'] = 'Nao foi possivel realizar a operacao.';
    $_SESSION['type'] = 'danger';

  } 
if (!$res) {
   printf("Errormessage: %s\n", $mysqli->error);
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

