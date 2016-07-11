<?php
// соединяемся с сервером базы данных
include("ConectToMySQL.php");
error_reporting( E_ERROR ); // Отключаем предупреждения (Warning)
//echo "<pre>";
//print_r($_POST);

//-------------------------------------------------------------------------------------------------------------------------------
//										Функция вывода таблицы
//-------------------------------------------------------------------------------------------------------------------------------

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

global $table;
$table = ($_POST['table']) ? : "Основная_таблица";

// mysql_query("UPDATE `Основная_таблица` SET `Дата_выдачи`='', ");
//$table = "";
//$table = $_GET['table'];
/*if(empty($_GET['table']))
{
	$table = "maintable";
}*/
$table_update = mysql_query("ALTER TABLE `".$table."` AUTO_INCREMENT=1") or die(mysql_error());
//Поиск
$el = '';
if(isset($_POST['combobox_query']) and isset($_POST['search_field']))
{
	if(mysql_num_fields(mysql_query("SELECT * FROM `".$table."`;")) > 10)
	{
		switch($_POST['combobox_query'])
		{
			case "Должность":
			{
				$el = "WHERE `Должность` IN (SELECT `Код` FROM `Должности` WHERE `Должность` LIKE '".$_POST['search_field']."')";
			} break;
			case "Подразделение":
			{
				$el = "WHERE `Подразделение` IN (SELECT `Код` FROM `Подразделения` WHERE `Подразделение` LIKE '".$_POST['search_field']."')";
			} break;
			case "Категория":
			{
				$el = "WHERE `Категория` IN (SELECT `Код` FROM `Категории` WHERE `Категория` LIKE '".$_POST['search_field']."')";
			} break;
			default:
			{
				$el = "WHERE `".$_POST['combobox_query']."` LIKE '".$_POST['search_field']."'";
			}
		}	
	}
	else
	{
		switch($_POST['combobox_query'])
		{
			case "Принадлежность":
			{
				$el = "WHERE `Принадлежность` IN (SELECT `Код` FROM `Подразделения` WHERE `Подразделение` LIKE '".$_POST['search_field']."')";
			} break;
			default:
			{
				$el = "WHERE `".$_POST['combobox_query']."` LIKE '".$_POST['search_field']."'";
			}
		}		
	}	
}
else
{
	$el = '';
}

//Конец поиска
$qr_result = "SELECT * FROM ".$table."  ".$el." ORDER BY 2";
$glob_query_export = $qr_result;
//$qr_result = mysql_query($qr_result) or die(mysql_error());



// выводим на страницу сайта заголовки HTML-таблицы
$i = 0;
//echo "<form action='' method='POST'>";
echo '<table id="example1">';
echo '<thead>';
echo '<tr>';
if(!empty($table))
{
	$StatusTable = mysql_fetch_assoc(mysql_query("CHECK TABLE `".$table."`"));
	//echo "Msg_type = ".$StatusTable['Msg_type']." Msg_text = ".$StatusTable['Msg_text'];
	if($StatusTable['Msg_type'] != "error" and $StatusTable['Msg_text'] != "Table 'dpk.".$table."' doesn't exist")
	{
		$qr_result = "SELECT * FROM `".$table."` ".$el." ORDER BY 2";
		//$glob_query_export = $qr_result;
		$qr_result = mysql_query($qr_result);
		echo '<th><label><input type="checkbox" id="SelectAllcheck" onClick="SelectAllCheck();" value="">Выдел.</label></th>';
		for($i = 0; $i < mysql_num_fields($qr_result); $i++)
		{
			echo '<th>'.mysql_field_name($qr_result, $i).'</th>'; // Берем название столбца по i
		}
		//echo '<font style="position: relative; z-index: 0; margin-left: 30%; font-size: 14px;">'.$table.'</font>';
		$i = 0;
		while($data = mysql_fetch_array($qr_result)) // Он выполняется столько раз, сколько записей в таблице
		{
			echo '<tr>';
			echo '<td class="checkbox_'.$i.'"><input class="checkbox_'.$i.'" type="checkbox" name="SelRow" value="'.$data[0].'"> <a class=tooltip href="" onClick="getParentId(this); EditTables(); return false;">Редак.</a></input></td>'; // Вывод столбца чекбоксов
			for($j = 0; $j < mysql_num_fields($qr_result); $j++)
			{
				if(mysql_num_fields($qr_result) >= 10)
				{
					switch(mysql_field_name($qr_result, $j))
					{
						case "Дата_выдачи":
						case "Дата_приема_на_работу":
						case "ДатаРождения": echo '<td>'.date("d.m.Y", strtotime($data[$j])).'</td>';
						break;
						case "Должность": echo '<td>'.position($data['Должность']).'</td>';
						break;
						case "Подразделение": echo '<td>'.subdivision($data['Подразделение']).'</td>';
						break;
						case "Категория": echo '<td>'.category($data['Категория']).'</td>';
						break;
						default: echo '<td>'.$data[$j].'</td>';
					}
				}
				else
				{
					echo (mysql_field_name($qr_result, $j) == "Принадлежность") ? '<td>'.subdivision($data[$j]).'</td>' : '<td>'.$data[$j].'</td>';
				}
				
			}
			echo '</tr>';
			$i++;
		}				
	}
	else
	{
		echo "<font class='errorz'>Таблицы ".$table." не существует!<br>Ошибка: ".mysql_error()."</font>";
	}
	
}
else
{
	echo "Таблица Для вывода не определена!";
}

echo '</tr>';
echo '</thead>';
echo '<tbody>';
// 14,33,52,71,90
// выводим в HTML-таблицу все данные клиентов из таблицы MySQL
$i = 0;





echo '</tbody>';
echo '</table>';
 
// Перенесено сюда, потому что значение переменной $glob_query_export не выходит за пределы функции table_out()
if(isset($_POST['export_to_csv'])) // Нажатие на кнопку "Сохранить таблицу в Excel (.csv)"
{
	$query = $glob_query_export; 
	$kar = mysql_query($query); 
	if(!$kar) exit("Ошибка ".mysql_error());
	$j = 0;
	if(mysql_num_rows($kar)) 
	{ 
		
		$fd = fopen("file.csv", "w"); 
		while($kart = mysql_fetch_array($kar)) // Цикл по записям в таблице, он выполняется сттолько раз, сколько записей в таблице
		{ 
			$j = 0;
			while($j < count($kart)/2)
			{
				
				if($j == count($kart)/2-1)
				{
					$order = $kart[$j]."\r\n";
					fwrite($fd, $order);
				}
				else
				{
					$order = " ".$kart[$j]." ;";
					fwrite($fd, $order);
				}
				
				$j++;
			}
			
		} 
	fclose($fd); 
	}
}
?>
