<?php
  
  //Подключаем ez_file.php
  $filename = 'ez_file.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  include_once $filename;
  putContentsLog("EZ-CONN - Included $filename",100);
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  putContentsLog("EZ-FORM-ZAKAZ-SIMPLE - $test",100);
  include $filename;
  putContentsLog("EZ-FORM-ZAKAZ-SIMPLE - Included $filename",100);
  /*$hostname = "localhost";  // название/путь сервера, с MySQL
  $username = "carl";       // имя пользователя
  $password = "carl";       // пароль пользователя
  $dbName   = "carl01";     // название базы данных
  $myConnection = mysqli_connect($hostname, $username, $password, $dbName) or die ("could not connect to mysql");
  if (!$myConnection) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    $err_conn = mysqli_connect_error();
    exit();
  }
  else{ 
    $err_conn = '0';
  }*/
  mysqli_query($myConnection, 'SET NAMES utf8') or header('Location: Error');
  //printf("Host information: %s\n", mysqli_get_host_info($myConnection));
  // заполяем переменные полей  
  if (isset($_POST["FormDraftUserName"]))           { $name       = $_POST["FormDraftUserName"];}          else{ $name  = '';}
  if (isset($_POST["FormDraftContactPhone"]))       { $phone      = $_POST["FormDraftContactPhone"];}      else{ $phone = '';}
  if (isset($_POST["FormDraftCarVIN"]))             { $vin        = $_POST["FormDraftCarVIN"];}            else{ $vin   = '';}
  if (isset($_POST["ez-car-autocompl-mark"]))       { $mark       = $_POST["ez-car-autocompl-mark"];}      else{ $mark  = '';}
  if (isset($_POST["ez-car-autocompl-model"]))      { $model      = $_POST["ez-car-autocompl-model"];}     else{ $model = '';}
  if (isset($_POST["ez-car-autocompl-generation"])) { $generation = $_POST["ez-car-autocompl-generation"];}else{ $generation='';}
  $part = "";  
  $cmt  = "Комментарий клиента."; //пока пишем эту хрень
  if ($name==''||$phone=='') {
      $result = array(
        'name'      => $name,
        'phone'     => $phone,
        'err_code'  => 2000,
        'err_msg_s' => "Поля 'Ваше имя' и 'Ваш телефон' обязательны для заполнения!",
        'err_msg_l' => "Поля 'Ваше имя' и 'Ваш телефон' обязательны для заполнения!",
      );
      echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
      exit;
  }

  //формируем текст запроса
  $sqlCommand = "select id from drafts where client='$name' and phone='$phone'";
  $sqlResultCount = mysqli_query($myConnection, $sqlCommand) or die (mysqli_error($myConnection));
  //не выдал ли нам запрос ошибки 
  //if (!mysqli_query($myConnection, "SET a=1")) {
  if (!$sqlResultCount) {
    $err_query1 = mysqli_error($myConnection);
    printf("Errormessage: %s\n", $err_query1);
  } else{ 
    $err_query1 = '0';
  }
  // цикл выборки, преобразованной в массив
  $output = array();
  while ($row = mysqli_fetch_array($sqlResultCount)) { $output[] = $row['id'];}
  $count = count($output); 
  print_r('Количество записей = '.$count.' - '); 
  print_r($output);
  $cmt = $cmt." Количество уникальных записей (имя,телефон) = ".$count; //пока пишем эту хрень
  // добавление новой записи (заказа) в таблицу DRAFTS
  $myQuery = "INSERT INTO drafts (id,client,phone,vin,mark,model,generation,part,cmt_client) 
              VALUES (NULL, '$name','$phone', '$vin, '$mark', '$model', '$generation', '$part', '$cmt')";
/*  $myQuery = "INSERT INTO test VALUES (NULL, '$name', '$phone', '$count')";*/
  $sqlResultInsert = mysqli_query($myConnection, $myQuery);
  //не выдал ли нам запрос ошибки 
  if (!$sqlResultInsert) {
    $err_query2 = mysqli_error($myConnection);
    printf("Errormessage: %s\n", $err_query2);
  } else { 
    $err_query2 = '0';
  }
  /*mysqli_commit($myConnection); mysqli_close($myConnection);*/
  $result = array(
        'name'        => $name
        'err_code'    => 0,
        'err_msg'     => "Заказ успешно принят! Оператор свяжется с Вами в течении 15 минут! Благодарим за обращение к нам!"
        
  );
  // ответ клиенту
  echo json_encode($result, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
?>
