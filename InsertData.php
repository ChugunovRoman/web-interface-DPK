<?php
include_once("ConectToMySQL.php");
/*echo "<pre>";
print_r($_POST);*/

//-------------------------------------------------------------------------------------------------------------------------------
// 									¬ывод пустых строк дл¤ добавлени¤ новых записей
//-------------------------------------------------------------------------------------------------------------------------------
global $table;
$table = ($_POST['table']) ? : "ќсновна¤_таблица"; // определение названи¤ выбранной таблицы

echo '<table>';
echo '<thead>';
echo '<tr>';

$sql=mysql_query("SELECT * FROM ".$table." ORDER BY 2");
$num = mysql_num_fields($sql);
for ($i = 1; $i < $num; $i++)
{
	echo "<th>".mysql_field_name($sql, $i)."</th>";
}

echo '</tr>';
echo '</thead>';
echo '<tbody>';

$j1 = 0;
$combobox_edit = 14; // Ёти переменные нужны дл¤ проверки столбцов,
$combobox_edit1 = 15; // в которых нужно заменить число на текст из другой таблицы
$combobox_edit2 = 16; 
$combobox_edit3 = 1; 
$j = 1;
$sql = mysql_query("SELECT * FROM ".$table." ORDER BY 2") or die (mysql_error());

for($i1 = 0; $i1 < $_POST['rows']; $i1++)
{	
	echo '<tr>';
	while($j < mysql_num_fields($sql))
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
						echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
						echo ">".$row[1]."</option>";
					}
					echo "</select></td>";
				} break;
				case "Подразделение":
				{
					$query = mysql_query("SELECT * FROM `Подразделения` ") or die(mysql_error());
					echo '<td><select class="SaveText" name="Save_'.$j1.'">';
					while($row = mysql_fetch_array($query))
					{
						echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
						echo ">".$row[1]."</option>";
					}
					echo "</select></td>";
				} break;
				case "Категория":
				{
					$query = mysql_query("SELECT * FROM `Категории` ") or die(mysql_error());
					echo '<td><select class="SaveText" name="Save_'.$j1.'">';
					while($row = mysql_fetch_array($query))
					{
						echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
						echo ">".$row[1]."</option>";
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
						echo '<td><input class="SaveText" type="date" name="Save_'.$j1.'" value="'.date("Y-m-d").'"  /></td>';
					}
					else
					{
						echo '<td><input class="Date SaveText" type="text" name="Save_'.$j1.'" value="'.date("d.m.Y").'" onClick="this.style.border = \'0px\';" maxlength="10"></td>';
					}
				} break;
				case "ИНН":
				case "Индекс":
				{
					echo '<td><input class="numeric SaveText" type="number" name="Save_'.$j1.'" value='.$data[$j].'></td>';
				} break;
				default:
				{
					echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$_POST['Save_'.$j1].'"></td>';
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
						echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
						echo ">".$row[1]."</option>";
					}
					echo "</select></td>";
				} break;
				default:
				{
					echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$_POST['Save_'.$j1].'"></td>';
				}
			}
		}
			
			/*if($j1 == $combobox_edit)
			{
				$sql = mysql_query("SELECT * FROM `Должности`") or die(mysql_error());
				echo '<td><select class="SaveText" name="Save_'.$j1.'">';
				while($row = mysql_fetch_array($sql))
				{
					echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
					echo ">".$row[1]."</option>";
				}
				echo "</select></td>";
			}
			else
			{
				if($j1 == $combobox_edit1)
				{
					$sql = mysql_query("SELECT * FROM `Подразделения`") or die(mysql_error());
					echo '<td><select class="SaveText" name="Save_'.$j1.'">';
					while($row = mysql_fetch_array($sql))
					{
						echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
						echo ">".$row[1]."</option>";
					}
					echo "</select></td>";
				}
				else
				{
					if($j1 == $combobox_edit2)
					{
						$sql = mysql_query("SELECT * FROM `Категории`") or die(mysql_error());
						echo '<td><select class="SaveText" name="Save_'.$j1.'">';
						while($row = mysql_fetch_array($sql))
						{
							echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
							echo ">".$row[1]."</option>";
						}
						echo "</select></td>";
					}
					else
					{
						echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$_POST['Save_'.$j1].'"></td>';
					}
				}	
			}
		}
		else
		{
			if($table == "Должности")
			{
				if($j1 == $combobox_edit3)
				{
					$sql = mysql_query("SELECT * FROM `Подразделения`") or die(mysql_error());
					echo '<td><select class="SaveText" name="Save_'.$j1.'">';
					while($row = mysql_fetch_array($sql))
					{
						echo "<option value='".$row[0]."'"; if($row[0]==$data[$j]){echo "selected";}
						echo ">".$row[1]."</option>";
					}
					echo "</select></td>";
				}
				else
				{
					echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$_POST['Save_'.$j1].'"></td>';
				}
			}
			else
			{
				echo '<td><input class="SaveText" type=text name="Save_'.$j1.'" value="'.$_POST['Save_'.$j1].'"></td>';
			}	
		}*/
		
		$j1++;
		$j++;
	}
	$combobox_edit += 19;
	$combobox_edit1 += 19;
	$combobox_edit2 += 19;
	$combobox_edit3 += 2;
	echo '</tr>';
	$j = 1;
}
$cycle_var = $j1;
echo '</tbody>';
echo '</table>';
echo "<div id='savediv'></div>";
?>