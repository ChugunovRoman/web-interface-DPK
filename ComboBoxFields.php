<?php
// соединяемся с сервером базы данных
include("ConectToMySQL.php");
if(isset($_POST['SearchField']))
{
	if(isset($_POST['table']))
	{
		$query_result = mysql_query("SELECT * FROM `".$_POST['table']."`");
	}
	else
	{
		$query_result = mysql_query("SELECT * FROM `Основная_таблица`");
	}
	for($i = 0; $i < mysql_num_fields($query_result); $i++)
	{
		echo "<option value='".mysql_field_name($query_result, $i)."'>".mysql_field_name($query_result, $i)."</option>";
	}	
}
if(isset($_POST['ReportFieldBox']))
{
	if(isset($_POST['table']))
	{
		$query_result = mysql_query("SELECT * FROM `".$_POST['table']."`");
	}
	else
	{
		$query_result = mysql_query("SELECT * FROM `Основная_таблица`");
	}
	for($i = 0; $i < mysql_num_fields($query_result); $i++)
	{
		echo "<option value='".mysql_field_name($query_result, $i)."'>".mysql_field_name($query_result, $i)."</option>";
	}
}
if(isset($_POST['ComboBoxTables']))
{
	$query_result = mysql_query("SHOW TABLES;");
	while($data = mysql_fetch_array($query_result))
	{
		if($data[0] == "Основная_таблица")
		{
			echo "<option value='".$data[0]."' selected>".$data[0]."</option>";
		}
		else
		{
			echo "<option value='".$data[0]."'>".$data[0]."</option>";
		}				
	}
}

?>