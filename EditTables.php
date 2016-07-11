<?php
/*echo "<pre>";
print_r($_POST);*/
include_once("ConectToMySQL.php");
//-------------------------------------------------------------------------------------------------------------------------------
// 													Вывод строк под редактирование
//-------------------------------------------------------------------------------------------------------------------------------
global $table;
$table = ($_POST['table']) ? : "Основная_таблица";
// выводим на страницу сайта заголовки HTML-таблицы

// Выводим заголовки таблицы
echo '<table id="main">';
echo '<thead>';
echo '<tr>';

$sql=mysql_query("SELECT * FROM ".$table." ORDER BY 2");
$num = mysql_num_fields($sql);
for ($i = 1; $i < $num; $i++)
{
	echo "<th>".mysql_field_name($sql, $i)."</th>";
}

// выводим в HTML-таблицу все данные клиентов из таблицы $table 
$i = 0;
$mas1 = explode(',', $_POST['checked']);

//$ins1 = (int)$_GET['ins']; // Получаем значение переменной $ins из файла edit_header.php строка 72
$j = 0;
$j1 = 0;
if(isset($mas1) and !empty($mas1))// Если нажали "редактировать" при нескольких выделенных чеках
{
	
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	$j = 1;
	$j1 = 0;	
	for($i = 0; $i < count($mas1); $i++)
	{
		$sql = mysql_query("SELECT * FROM ".$table." WHERE `Код`='".$mas1[$i]."'") or die (mysql_error());
		
		while($data = mysql_fetch_array($sql, MYSQL_NUM))
		{
			//print_r($data);
			echo '<tr>';
			for($j = 1; $j < mysql_num_fields($sql); $j++)
			{
				if(mysql_num_fields($sql) > 10)
				{
					switch(mysql_field_name($sql, $j))
					{
						case "Должность":
						{
							$query = mysql_query("SELECT * FROM `Должности` ") or die(mysql_error());
							echo '<td><select class="SaveText" name="Save_'.$j1.'">';
							while($row = mysql_fetch_array($query))
							{
								echo "<option value='".$row['Код']."' ";
								if($row['Код'] == $data[$j])
								{
									echo "selected";
								}
								echo ">".$row['Должность']."</option>";
							}
							echo "</select></td>";
						} break;
						case "Подразделение":
						{
							$query = mysql_query("SELECT * FROM `Подразделения` ") or die(mysql_error());
							echo '<td><select class="SaveText" name="Save_'.$j1.'">';
							while($row = mysql_fetch_array($query))
							{
								echo "<option value='".$row['Код']."' ";
								if($row['Код'] == $data[$j])
								{
									echo "selected";
								}
								echo ">".$row['Подразделение']."</option>";
							}
							echo "</select></td>";
						} break;
						case "Категория":
						{
							$query = mysql_query("SELECT * FROM `Категории` ") or die(mysql_error());
							echo '<td><select class="SaveText" name="Save_'.$j1.'">';
							while($row = mysql_fetch_array($query))
							{
								echo "<option value='".$row['Код']."' ";
								if($row['Код'] == $data[$j])
								{
									echo "selected";
								}
								echo ">".$row['Категория']."</option>";
							}
							echo "</select></td>";
						} break;
						case "Дата_выдачи":
						case "Дата_приема_на_работу":
						case "ДатаРождения":
						{
							$user_agent = $_SERVER["HTTP_USER_AGENT"];
							if(strpos($user_agent, "Chrome") !== false or strpos($user_agent, "Opera") !== false)
							{
								echo '<td><input class="SaveText" type="date" name="Save_'.$j1.'" value="'.date("Y-m-d", strtotime($data[$j])).'"  /></td>';
							}
							else
							{
								echo '<td><input class="Date SaveText" type="text" name="Save_'.$j1.'" value="'.date("d.m.Y", strtotime($data[$j])).'" onClick="this.style.border = \'0px\';" maxlength="10"></td>';
							}
						} break;
						case "ИНН":
						case "Индекс":
						{
							echo '<td><input class="numeric SaveText" type="number" name="Save_'.$j1.'" value='.$data[$j].'></td>';
						} break;
						default:
						{
							echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$data[$j].'"></td>';
						}						
					}
				}
				else
				{
					
					switch(mysql_field_name($sql, $j))
					{
						case "Принадлежность":
						{
							$query = mysql_query("SELECT * FROM `Подразделения` ") or die(mysql_error());
							echo '<td><select class="SaveText" name="Save_'.$j1.'">';
							while($row = mysql_fetch_array($query))
							{
								echo "<option value='".$row['Код']."' ";
								if($row['Код'] == $data[$j] || ($row['Подразделение'] == "Не назначено" && $data[$j] == 0))
								{
									echo "selected";
								}
								echo ">".$row['Подразделение']."</option>";
							}
							echo "</select></td>";
						} break;
						default:
						{
							echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$data[$j].'"></td>';
						}
					}
				}
				$j1++;
			}
			echo '</tr>';	
		}
		$j = 1;
	}
	echo '</tbody>';
	echo '</table>';
}
echo "<div id='savediv'></div>";
?>
