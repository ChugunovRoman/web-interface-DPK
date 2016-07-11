<?php
// Вывод таблицы под отчет
// определяем начальные данные
//echo "<pre>";
//print_r($_POST);
include_once("ConectToMySQL.php");

if(!empty($_POST['InTable']) and !empty($_POST['OutTable']))
{
	// Удаляем копию таблицы
	$result = mysql_query("DROP TABLE `".$_POST['OutTable']."`;") or die("Таблица не удалена: ".mysql_error());
	$result = mysql_query("SHOW CREATE TABLE `".$_POST['InTable']."`;");	// посылаем запрос
	$row = mysql_fetch_row($result);
	$creat_table = mysql_query(substr($row[1], 0, 14).$_POST['OutTable'].substr($row[1], 14+strlen($row[0]), strlen($row[1])));

	$result = mysql_query("SELECT * FROM `".$_POST['InTable']."`;");
	$num_columns = mysql_num_fields($result);
	// Формируем запросы на вставку данных
	while($data = mysql_fetch_array($result))
	{
		for($i = 0; $i < $num_columns; $i++)
		{
			$tmp .= " '".$data[$i]."',";
		}
		$tmp = substr($tmp, 0, -1);
		$result1 = mysql_query("INSERT INTO `".$_POST['OutTable']."` VALUES(".$tmp.");"); // посылаем запрос на вставку
		$tmp = "";
	}
	$err = mysql_query("SELECT * FROM `".$_POST['OutTable']."`") or die("Таблица не создана: ".mysql_error());	
}
if(!empty($_POST['ClearTable']))
{
	$tables = "";
	if($_POST['ClearTable'] == "Все таблицы")
	{
		$result = mysql_query("SHOW TABLES;");
		while($data = mysql_fetch_array($result, MYSQL_NUM))
		{
			mysql_query("TRUNCATE TABLE `".$data[0]."`;");
		}
	}
	else
	{
		mysql_query("TRUNCATE TABLE `".$_POST['ClearTable']."`;");
	}
	
}
if(!empty($_POST['RenameTable']))
{
	echo "RENAME TABLE `".$_POST['RenameTable']."` TO `".$_POST['NewName']."`;";
	mysql_query("RENAME TABLE `".$_POST['RenameTable']."` TO `".$_POST['NewName']."`;");
}

?>