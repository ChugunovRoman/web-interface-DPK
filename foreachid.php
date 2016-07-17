<?php
	mb_internal_encoding("UTF-8"); // Устанока коировки скрипта
	mysql_query("SET NAMES utf8");
	// соединяемся с сервером базы данных
	$connect_to_db = mysql_connect('localhost', 'root', 'typedef') or die("<div style='height: 40px; background: #ff9 linear-gradient(#FF0000, #D84848); text-align: center; margin: 40px 40px 0px 40px; padding: 20px 10px 10px 10px; font-size: 24px;'>Сервер недоступен: " . mysql_error()."</div>");
	$dpk = mysql_select_db('dpk', $connect_to_db) or die("<div style='height: 40px; background: #ff9 linear-gradient(#FF0000, #D84848); text-align: center; margin: 40px 40px 0px 40px; padding: 20px 10px 10px 10px; font-size: 24px;'>Невозможно подключится к базе данных, ошибка: " . mysql_error()."</div>");
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Перебор ID</title>
	</head>
	
	<body>
		<?php
			echo "<p>Передайте название таблицы. Например: http://interface/foreachid.php?table=Основная_таблица</p>";
			echo "<pre>";
			print_r($_GET);
			echo "</pre>";
			$query_result = mysql_query("SELECT `Код` FROM `".$_GET['table']."`;");
			echo "<p>";
			echo $query_result;
			while($data = mysql_fetch_array($query_result))
			{
				echo $data[0];
			}
			echo "</p>";
			// Выбор таблицы
			$query_result = mysql_query("SHOW TABLES;");
			echo "<select id='ComboBoxTable' onChange='ShowTables();' name='combobox_tables'>";
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
		?>
	</body>
</html>