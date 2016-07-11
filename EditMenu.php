<?php
// соединяемся с сервером базы данных
include("ConectToMySQL.php");

//---------------------------------------------------------------------------------
// 								 Главное меню       							 --
//---------------------------------------------------------------------------------

echo "<input class=button4 type='button' id='editbtn' onClick='EditTables(); return false;' value='Редактировать' />";
echo "<input class=button4 type='button' id='delete' onClick='DeleteData();' value='Удалить' />";
echo "<input class=inputtext1 type=text id=count_rows />";
echo "<input class=button4 type='button' id='insert' onClick='InsertData(); return false;' value='Добавить' />";
echo "<input class=button4 type='button' id='copy_table' onClick='CopyTable();' value='Создать копию' />";
echo "<input class=button4 type='button' id='export_to_csv' onClick='ExportToExcel();' value='Экспорт в Excel (.csv)'>";
echo "<a name='report' target='_blank' id='help' href='reports.php'>Отчеты</a>";
echo "<a name='export_to_csv' id='Save' href=''>сохранить в файл</a>"; // Не удалять, не отображается на странице с помощью js	

echo "<a id='help' target='_blank' style='position: Fixed; Left: 90%; Top: 5px; font-size: 24pt;' href='help/index.html'>Помощь</a>";
// Заполнение ComboBox'а полями выбранной таблицы
$query_result = mysql_query("SELECT * FROM `".$_POST['table']."`");
echo "<br><font class=text_margin>Поиск по:</font><select class=comboquery name='combobox_query'>";
for($i = 0; $i < mysql_num_fields($query_result); $i++)
{
	"<option value='".mysql_field_name($query_result, $i)."'>".mysql_field_name($query_result, $i)."</option>".
}
echo "</select>";

echo "<input class=inputtext type='text' id='search_field' />";
echo "<input class=button4 type='submit' name='button_search' onClick='ShowTables(true);' value='Поиск'/>";

// Выбор таблицы
$query_result = mysql_query("SHOW TABLES;");
echo "<font class=text_margin>Выбор таблицы:</font><select class=comboquery name='combobox_tables'>";
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
echo "</select>";
echo "<input class=button4 type='button' onClick='ShowTables();' name='button_tables' value='Ок'>";

?>