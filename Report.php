<head>
<style type="text/css">
<?php
if(isset($_POST['SelectAll']))
{
	echo "@page
		{
			size: A4 landscape;
			margin-top: 15px;
			margin-left: 80px;	
		}";
}
if(isset($_POST['SelectPos']))
{
	echo "@page
		{
			size: A4 portrait;
			margin-left: 140px;
			counter-increment: page;
			@top-center
			{
				font-family: sans-serif;
				font-weight: bold;
				font-size: 2em;
				content: counter(page);
			}
		}";
}
if(isset($_POST['SelectAge']))
{
	echo "@page
		{
			size: A4 portrait;
			margin-left: 120px;
			counter-increment: page;
			@top-center
			{
				font-family: sans-serif;
				font-weight: bold;
				font-size: 2em;
				content: counter(page);
			}
		}";
}
/*if(isset($_POST['customereport']))
{
	echo "@page
		{
			size: A4 portrait;
			margin-left: 12%;
			counter-increment: page;
			@top-center
			{
				font-family: sans-serif;
				font-weight: bold;
				font-size: 2em;
				content: counter(page);
			}
		}";
}*/
?>
@media print
{
	.no-print
	{
		display: none !important;
	}
	#caption
	{
		margin-left: -100px;
		text-align: center;
	}
}
.no-print
{
	border: 2px solid #d1d113;
	height: 50px;
}

#example2 th
{
	//padding: 15px;
}
#example2 td
{
	text-align: center;
}
#example2 font
{
	//padding-left: 25%;
	font-size: 12pt;
}
</style>
</head>
<?php
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
// контент
// Функции замены значений
// Функция для замены числовых значений символическими
include("ConectToMySQL.php");
function position($value)
{
	$value = (int) $value;
	$sql = mysql_query("SELECT * FROM `Должности` WHERE `Код`='".$value."'");
	$row = mysql_fetch_array($sql);
	return $row['Должность'];
}
function subdivision($value)
{
	$value = (int) $value;
	$sql = mysql_query("SELECT * FROM `Подразделения` WHERE `Код`='".$value."'");
	$row = mysql_fetch_array($sql);
	return $row['Подразделение'];
}
function category($value)
{
	$value = (int) $value;
	$sql = mysql_query("SELECT * FROM `Категории` WHERE `Код`='".$value."'");
	$row = mysql_fetch_array($sql);
	return $row['Категория'];
}
$table = ($_POST['table']) ? : "Основная_таблица";
//---------------------------------------------------------------------------------
// 					функции нажатия на кнопки вывода отчета						 --
//---------------------------------------------------------------------------------
// вывод отчета о всех сотрудниках
if(isset($_POST['SelectAll']))
{
	$query = mysql_query("SELECT фамилия, Имя, Отчество, Должность, Адрес, ДомашнийТелефон, ДатаРождения FROM ".$table." ORDER BY 1");
	echo '<table id="example2">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Номер</th>';
	$num_fields = mysql_num_fields($query);
	// Выводим заголовки таблицы	
	for($i = 0; $i < $num_fields; $i++)
	{
		echo "<th>".mysql_field_name($query, $i)."</th>";		
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$i = 1;

	// Вывод данных в ячейки
	while($data = mysql_fetch_array($query))
	{
		echo "<tr>";
		echo "<td>".$i."</td>";
		echo "<td>".$data['фамилия']."</td>";
		echo "<td>".$data['Имя']."</td>";
		echo "<td>".$data['Отчество']."</td>";
		echo "<td>".position($data['Должность'])."</td>";
		echo "<td>".$data['Адрес']."</td>";
		echo "<td>".$data['ДомашнийТелефон']."</td>";
		echo "<td>".date("d.m.Y", strtotime($data['ДатаРождения']))."</td>";
		echo "</tr>";
		$i++;
	}
	echo "</tbody>";
	echo "</table>";
	$_POST['SelectAll'] = NULL;
}

// Вывод отчета по должностям
if(isset($_POST['SelectPos']))
{
	if($_POST['combobox_position'] == "Не назначено")
	{
		$query = "SELECT фамилия, Имя, Отчество, Должность FROM `Основная_таблица` WHERE `Должность` IN (SELECT `Код` FROM `Должности` WHERE `Принадлежность` IN (SELECT `Код` FROM `Подразделения` WHERE `Подразделение`='".$_POST['combobox_position']."') OR `Принадлежность`='0' OR `Принадлежность`='' OR `Принадлежность`='NULL') ORDER BY 1";
	}
	else
	{
		$query = "SELECT фамилия, Имя, Отчество, Должность FROM `Основная_таблица` WHERE `Должность` IN (SELECT `Код` FROM `Должности` WHERE `Подразделение` IN (SELECT `Код` FROM `Подразделения` WHERE `Подразделение`='".$_POST['combobox_position']."')) ORDER BY 1";
	}
	$query = mysql_query($query);
	echo "<h2 id=caption>".$_POST['combobox_position']."</h2>";
	echo '<table id="example2">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Номер</th>';
	$num_fields = mysql_num_fields($query);
	// Выводим заголовки таблицы
	$i = 0;
	while($i < $num_fields)
	{
		echo "<th>".mysql_field_name($query, $i)."</th>";
		$i++;
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	// Вывод данных в ячейки
	$i = 1;
	while($data = mysql_fetch_array($query))
	{
		echo "<tr>";
		echo "<td>".$i."</td>";
		echo "<td>".$data['фамилия']."</td>";
		echo "<td>".$data['Имя']."</td>";
		echo "<td>".$data['Отчество']."</td>";
		echo "<td>".position($data['Должность'])."</td>";
		echo "</tr>";
		$i++;
	}
	echo "</tbody>";
	echo "</table>";
	$data = NULL;
	$_POST['SelectPos'] = NULL;	
	$_POST['combobox_position'] = NULL;	
}

// Отчет по возрасту
if(!empty($_POST['SelectAge']))
{
	$text_age = $_POST['text_age'];
	if(strripos($_POST['text_age'], '-') == NULL)
	{
		//$query = "SELECT `Фамилия`, `Имя`, `Отчество`, `ДатаРождения`, YEAR(NOW())-CONVERT(SUBSTRING(`ДатаРождения`, 7, 4), SIGNED) AS `Возраст` FROM `Основная_таблица` WHERE YEAR(NOW())-CONVERT(SUBSTRING(`ДатаРождения`, 7, 4), SIGNED) = '".$_POST['text_age']."';";
		$query = "SELECT `Фамилия`, `Имя`, `Отчество`, `ДатаРождения`, YEAR(NOW())-YEAR(`ДатаРождения`) AS `Возраст` FROM `Основная_таблица` WHERE YEAR(NOW())-YEAR(`ДатаРождения`) = '".$_POST['text_age']."';";
	}
	else
	{
		//$query = "SELECT `Фамилия`, `Имя`, `Отчество`, `ДатаРождения`, YEAR(NOW())-CONVERT(SUBSTRING(`ДатаРождения`, 7, 4), SIGNED) AS `Возраст` FROM `Основная_таблица` WHERE YEAR(NOW())-CONVERT(SUBSTRING(`ДатаРождения`, 7, 4), SIGNED) BETWEEN '".substr($_POST['text_age'], 0, -3)."' AND '".substr($_POST['text_age'], strripos($_POST['text_age'], '-')+1)."';";
		$query = "SELECT `Фамилия`, `Имя`, `Отчество`, `ДатаРождения`, YEAR(NOW())-YEAR(`ДатаРождения`) AS `Возраст` FROM `Основная_таблица` WHERE YEAR(NOW())-YEAR(`ДатаРождения`) BETWEEN '".substr($_POST['text_age'], 0, -3)."' AND '".substr($_POST['text_age'], strripos($_POST['text_age'], '-')+1)."';";
	}
	$query = mysql_query($query);
	echo '<table id="example2">';
	echo '<thead>';
	echo '<tr>';
	echo '<th>Номер</th>';
	$num_fields = mysql_num_fields($query);
	// Выводим заголовки таблицы
	$i = 0;
	while($i < $num_fields)
	{
		echo "<th>".mysql_field_name($query, $i)."</th>";
		$i++;
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	// Вывод данных в ячейки
	$i = 1;
	while($data = mysql_fetch_array($query))
	{
		echo "<tr>";
		echo "<td>".$i."</td>";
		echo "<td>".$data['Фамилия']."</td>";
		echo "<td>".$data['Имя']."</td>";
		echo "<td>".$data['Отчество']."</td>";
		echo "<td>".date("d.m.Y", strtotime($data['ДатаРождения']))."</td>";
		echo "<td>".$data['Возраст']."</td>";
		echo "</tr>";
		$i++;
	}
	echo "</tbody>";
	echo "</table>";
	$data = NULL;
	$_POST['SelectAge'] = NULL;
	$_POST['text_age'] = NULL;
}
if(isset($_POST['customereport']))
{
	$result = "";
	if(isset($_POST['filtr']))
	{
		$like = '';
		if(mysql_num_fields(mysql_query("SELECT * FROM `".$_POST['table']."`;")) > 10)
		{
			switch($_POST['namefield'])
			{
				case "Должность": $like = "WHERE `Должность` IN (SELECT `Код` FROM `Должности` WHERE `Должность` LIKE '".$_POST['filtr']."')"; break;
				case "Подразделение": $like = "WHERE `Подразделение` IN (SELECT `Код` FROM `Подразделения` WHERE `Подразделение` LIKE '".$_POST['filtr']."')"; break;
				case "Категория": $like = "WHERE `Категория` IN (SELECT `Код` FROM `Категории` WHERE `Категория` LIKE '".$_POST['filtr']."')"; break;
				default: $like = "WHERE `".$_POST['namefield']."` LIKE '".$_POST['filtr']."'";
			}
		}
		else
		{
			switch($_POST['namefield'])
			{
				case "Принадлежность": $like = "WHERE `Принадлежность` IN (SELECT `Код` FROM `Подразделения` WHERE `Подразделение` LIKE '".$_POST['filtr']."')"; break;
				default: $like = "WHERE `".$_POST['namefield']."` LIKE '".$_POST['filtr']."'";
			}
		}
		if(!strcasecmp(substr($_POST['arrfields'], 0, 10), "Номер"))
		{
			$result = "SELECT ".substr($_POST['arrfields'], 11)." FROM `".$_POST['table']."` ".$like;
		}		
		else
		{
			$result = "SELECT ".$_POST['arrfields']." FROM `".$_POST['table']."` ".$like;
		}
	}
	else
	{
		if(!strcasecmp(substr($_POST['arrfields'], 0, 10), "Номер"))
		{
			$result = "SELECT ".substr($_POST['arrfields'], 11)." FROM `".$_POST['table']."` ";
		}
		else
		{
			$result = "SELECT ".$_POST['arrfields']." FROM `".$_POST['table']."` ";
		}
	}
	$result = mysql_query($result);
	echo '<table class="example3" id="example2">';
	echo '<thead>';
	echo '<tr>';
	echo (!strcasecmp(substr($_POST['arrfields'], 0, 10), "Номер")) ? '<th>Номер</th>' : "";
	$num_fields = mysql_num_fields($result);
	// Выводим заголовки таблицы
	$i = 0;
	while($i < $num_fields)
	{
		echo "<th>".mysql_field_name($result, $i)."</th>";
		$i++;
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	// Вывод данных в ячейки
	$i = 1;
	while($data = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo (!strcasecmp(substr($_POST['arrfields'], 0, 10), "Номер")) ? "<td>".$i."</td>" : "";
		for($j = 0; $j < mysql_num_fields($result); $j++)
		{
			if(mysql_num_fields(mysql_query("SELECT * FROM `".$_POST['table']."`;")) > 10)
			{
				switch(mysql_field_name($result, $j))
				{
					case "Дата_выдачи":
					case "Дата_приема_на_работу":
					case "ДатаРождения": echo '<td>'.date("d.m.Y", strtotime($data[$j])).'</td>'; break;
					case "Должность":
					{
						echo "<td>".position($data['Должность'])."</td>";
					}
					break;
					case "Подразделение":
					{
						echo "<td>".subdivision($data['Подразделение'])."</td>";
					}
					break;
					case "Категория":
					{
						echo "<td>".category($data['Категория'])."</td>";
					}
					break;
					default: echo "<td>".$data[$j]."</td>";
				}		
			}
			else
			{
				switch(mysql_field_name($result, $j))
				{
					case "Принадлежность":
					{
						echo "<td>".subdivision($data['Принадлежность'])."</td>";
					}
					break;
					default: echo "<td>".$data[$j]."</td>";
				}	
			}
		}
		echo "</tr>";
		$i++;
	}
	echo "</tbody>";
	echo "</table>";
	
}

echo "</body>";
echo "</html>";
?>