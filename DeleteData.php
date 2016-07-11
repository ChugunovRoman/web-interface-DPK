<?php
include_once("ConectToMySQL.php");
/*echo "<pre>";
print_r($_POST);*/
//-------------------------------------------------------------------------------------------------------------------------------
// 											Нажатие на кнопку "удалить"
//-------------------------------------------------------------------------------------------------------------------------------
global $table;
$table = ($_POST['table']) ? : "Основная_таблица";
/*$q = mysql_query("SELECT * FROM ".$table." ORDER BY 1") or die(mysql_error());
$MaxValueColumn = mysql_result(mysql_query("SELECT MAX(`Код`) FROM `".$table."` ORDER BY `Код` DESC LIMIT 1"), 0); // Получаем максимальное значение в столбце 'Код'
for($i = 0; $i <= $MaxValueColumn; $i++)
{
	if($_POST['chec_'.$i] == true) // Получение чекнутых(:D) чекбоксов, наверно
	{
		$array_string[] = $i; // Запоминаем значения чекнутых в массив
	}
}
//print_r($array_string);
for($i = 0;$i < count($array_string); $i++) // Цикл, в котором запоминаем в переменную "del" номер выделенного чекбокса,
{
	$del.= $array_string[$i].",";			// а потом приписываем ",". Строка типа 2,5,12,
}
$qr_result = mysql_query("SELECT * FROM ".$table." ORDER BY 2") or die(mysql_error());
$del = explode(",", $del);*/
$ids = explode(',', $_POST['checked']);
for($i = 0; $i < count($ids); $i++)
{
	$query = "DELETE FROM ".$table." WHERE `Код`=".$ids[$i];
	//echo "<br>".$query;
	$query_del_rows = mysql_query($query);
}
$table_update = mysql_query("ALTER TABLE ".$table." AUTO_INCREMENT=1") or die(mysql_error());
	

?>