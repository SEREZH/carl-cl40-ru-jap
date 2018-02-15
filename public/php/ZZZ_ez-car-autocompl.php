<?php
//ez-car-autocompl.php
//Подключаем БД = include ez_file.php
  $filename = 'ez_file.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  include $filename;
  putContentsLog("EZ-CAR-AUTOCOMPL - BEGIN ---------------------------------------------",100);
  putContentsLog("EZ-CAR-AUTOCOMPL - Included $filename",100);
  //Подключаем БД = include ez-conn.php
  $filename = 'ez-conn.php';
  if (file_exists($filename)) {$test = "Exist YES - $filename";} 
  else {$test = "Exist NOT - $filename";} 
  putContentsLog("EZ-CAR-AUTOCOMPL - $test",100);
  include $filename;
  putContentsLog("EZ-CAR-AUTOCOMPL - Included $filename",100);
  /*------------------------------------------------------------------------------------------------*/
//в js-файле в AJAX вызове изменяем тип данных с json на text и смотрим ошибки :)
//$test = array($dbName);//echo json_encode($test, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
//находим id_car_mark по переданному наименованию марки car_mar.name

// Формируем список Марки автомобилей
/*function getCarMarks()
{
	$arMarksRows = array(); // инициализируем индексный массив Марки
	//формируем текст запроса для выборки Моделей для Марки
	$sqlCommandCarMarks = "SELECT name FROM car_mark WHERE in_list = 1 AND in_prior = 1 ORDER BY name";
	$sqlResultCarMarks  = mysqli_query($connConnection, $sqlCommandCarMarks) or die (mysqli_error($connConnection));	
	while ($row = mysqli_fetch_array($sqlResultCarMarks, MYSQLI_NUM)) {
		array_push($arMarksRows, $row[0]);
	}
	echo json_encode($arMarksRows, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
}*/

function getCarModels($f_car_mark)
{
  	// инициализируем индексный массив Модели
	$arModelRows = array();
	//array_push($arModelRows, $f_car_mark);
	if ($f_car_mark!='') {
		//формируем текст запроса для выборки Моделей для Марки
		$sqlCommandCarModel = "select id_car_model, name from car_model where id_car_mark = (select id_car_mark from car_mark where name='$f_car_mark' limit 1) order by name";
		//array_push($arModelRows, $sqlCommandCarModel);
		$sqlResultCarModels  = mysqli_query($connConnection, $sqlCommandCarModel) or die (mysqli_error($connConnection));	
		//array_push($arModelRows, $sqlResultCarModels);
		while ($row = mysqli_fetch_array($sqlResultCarModels, MYSQLI_NUM)) {
			array_push($arModelRows, $row[1]);
		}
	}
	echo json_encode($arModelRows, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
}

if (isset($_POST["func"]))    	{ $func 	 = $_POST["func"];}      else { $func  	 = '';}
if (isset($_POST["car_mark"]))  { $car_mark  = $_POST["car_mark"];}  else{ $car_mark  = '';}
if (isset($_POST["car_model"])) { $car_model = $_POST["car_model"];} else{ $car_model = '';}
putContentsLog("EZ-CAR-AUTOCOMPL - func=$func",10);
putContentsLog("EZ-CAR-AUTOCOMPL - car_mark=$car_mark",10);
putContentsLog("EZ-CAR-AUTOCOMPL - car_model=$car_model",10);
/*print_r($func);
print_r($car_mark);
print_r($car_model);*/
putContentsLog("EZ-CAR-AUTOCOMPL - switch - BEFORE",100);
putContentsLog("EZ-CAR-AUTOCOMPL - func=$func",100);
switch ($func) {
	case 'getCarMarks':
        $arMarksRows = array();// инициализируем индексный массив Модели
		$sqlCommandCarMarks = "select id_car_mark, trim(name) from car_mark where name is not null and in_list = 1 and in_prior = 1 order by trim(name)";
		//array_push($arModelRows, $sqlCommandCarMarks);
		$sqlResultCarMarks  = mysqli_query($connConnection, $sqlCommandCarMarks) or die (mysqli_error($connConnection));	
		while ($row = mysqli_fetch_array($sqlResultCarMarks, MYSQLI_NUM)) {array_push($arMarksRows, $row[1]);}
		echo json_encode($arMarksRows, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
		//mysqli_free_result($sqlResultCarMarks); /* очищаем результаты выборки */
		//mysqli_close($connConnection); /* закрываем подключение */
		break;
    case 'getCarModels':
		$arModelRows = array();
		if ($car_mark!='') {
			$sqlCommandCarModel = "select id_car_model, name from car_model where id_car_mark = (select id_car_mark from car_mark where name='$car_mark' limit 1) order by name";
			$sqlResultCarModels  = mysqli_query($connConnection, $sqlCommandCarModel) or die (mysqli_error($connConnection));
			while ($row = mysqli_fetch_array($sqlResultCarModels, MYSQLI_NUM)) {array_push($arModelRows, $row[1]);}
		}
		echo json_encode($arModelRows, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
        break;
    case 'getCarGenerations':
		$arGeneratorRows = array();
		if ($car_mark!=''&&$car_model!='') {
			//$sqlCommandCarGeneration = "select id_car_mark, trim(name) from car_mark where name is not null and in_list = 1 and in_prior = 1 order by trim(name)";
			$sqlCommandCarGeneration = "select cmr.name as marka, cml.name as model,  cgr.name as generation, cgr.year_begin, cgr.year_end from car_mark cmr, car_model cml, car_generation cgr where cmr.id_car_mark = cml.id_car_mark and cgr.id_car_model = cml.id_car_model and cmr.name = '$car_mark' and cml.name = '$car_model' order by cmr.name,  cgr.year_begin, cgr.name";
			$sqlResultCarGenerations = mysqli_query($connConnection, $sqlCommandCarGeneration) or die (mysqli_error($connConnection));
			while ($row = mysqli_fetch_array($sqlResultCarGenerations, MYSQLI_NUM)) {array_push($arGeneratorRows, $row[2]);}
		}
		if (count($arGeneratorRows)==0) {
			array_push($arGeneratorRows, $car_model);	
		}
		echo json_encode($arGeneratorRows, JSON_UNESCAPED_UNICODE); // как бы руссификация :)
        break;
    default: //function not found, error or something
    	echo "PHP::GetCar::ERROR1::function not found, error or something - func = '$func'";
    	putContentsLog("EZ-CAR-AUTOCOMPL::GetCar::ERROR::function not found, func = '$func'",100);
        break;
}
?>