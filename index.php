<!DOCTYPE html>
<html>
<?php
//phpinfo();
//exit;
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
//-------------------------------------------------------------------------------------------------------------------------------
//	Web-интерфейс для работы с базами данных и их таблицами MySQL. 2015г.
//	Разработчик: Чугунов Роман Владимирович
//	ГБОУ СПК "Дзержинский педагогический колледж"
//-------------------------------------------------------------------------------------------------------------------------------
error_reporting(E_ERROR); // Отключаем предупреждения (Warning)
//mb_internal_encoding("UTF-8"); // Устанока коировки скрипта
mysql_query("SET NAMES utf8");
// соединяемся с сервером базы данных
include("ConectToMySQL.php");
?>
<head>	
	<title>Преподаватели и сотрудники ДПК</title>
	<meta charset="UTF-8">
	<link rel="shortcut icon" href="/help/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="Style.css">
	<script type="text/javascript" src="jquery-2.0.0.js"></script>
	<script type="text/javascript" src="Detect.js"></script>
	<script type="text/javascript" src="scripts.js"></script>
	<style type="text/css">
		@media print
		{
			#no-print
			{
				display: none !important;
			}
			#header_h
			{
				display: none !important;	
			}
			#caption
			{
				margin-left: -100px;
				text-align: center;
			}
		}
		#no-print
		{
			width: 70%;
			//border: 1px solid black;
			padding: 1px;
			Position: absolute;
			margin: 0 auto;
			Top: 0px;
			left: 12%;
		}
		#CheckBoxPanel
		{
			width:250px;
			border: 1px outset #886;
			//padding: 1px;
			Position: absolute;
			//height: 16px;
			margin-left: 10.3%;
			margin-top: 22px;
		}
		#ComboCheckBox
		{
			width:580px;
			border: 1px inset #886;
			//padding: 1px;
			Position: absolute;
			background: #cca;
			height: 19px;
			margin-left: 10.3%;
			//margin-top: 22px;
		}
		@-moz-document url-prefix()
		{
			#ComboCheckBox
			{
				border: 1px inset #000;
			}
			#CheckBoxPanel
			{
				border: 1px outset #000;
			}
		}

		#example2 th
		{
			padding: 15px;
		}
		#example2 td
		{
			text-align: center;
			padding: 2px;
		}
	</style>

</head>
<body onLoad='ShowTables(); SelectTableRep();'>
	<div id="header_h">
		<div>
			<a href="" onClick="ShowTables(); return false;"><img src="img/logo.png"/></a>
	
			<div id='header_buttun'>
			<?php
			//---------------------------------------------------------------------------------
			// 								 Главное меню       							 --
			//---------------------------------------------------------------------------------
			echo "<input class=button4 type='button' id='editbtn' onClick='EditTables(); return false;' value='Редактировать' />";
			echo "<input class=button4 type='button' id='delete' onClick='DeleteData();' value='Удалить' />";
			echo "<input class=inputtext1 type=text id='count_rows' onclick='BorderReset(this);' />";
			echo "<input class=button4 type='button' id='insert' onClick='InsertData(); return false;' value='Добавить' />";
			//echo "<input class=button4 type='button' id='copy_table' onClick='CopyTable();' value='Создать копию' />";
			echo "<input class=button4 type='button' id='copy_table' onClick='document.getElementById(\"TablePanel\").style.display = \"block\";' value='Управление данными' />";
			echo "<input class=button4 type='button' id='export_to_csv' onClick='ExportToExcel();' value='Экспорт в Excel'>";
			echo "<input class=button4 type='button' id='BtnReport' onClick='ShowButtons();' value='Отчеты'>";
			echo "<a name='export_to_csv' id='Save' href=''>сохранить в файл</a>"; // Не удалять, не отображается на странице с помощью js	

			echo "<a id='help' target='_blank' style='position: Fixed; Left: 90%; Top: 5px; font-size: 24pt;' href='help/index.html'>Помощь</a>";
			
			// Заполнение ComboBox'а полями выбранной таблицы			
			echo "<br><strong class=text_margin>Поиск по:</strong><select id='ComboBoxFields' name='combobox_query'>";
			if(isset($_POST['table']))
			{
				$query_result = "SELECT * FROM `".$_POST['table']."`";
			}
			else
			{
				$query_result = "SELECT * FROM `Основная_таблица`";
			}
			$query_result = mysql_query($query_result);
			for($i = 0; $i < mysql_num_fields($query_result); $i++)
			{
				echo "<option value='".mysql_field_name($query_result, $i)."'>".mysql_field_name($query_result, $i)."</option>";
			}
			echo "</select>";

			echo "<input class=inputtext type='text' id='search_field' onClick='BorderReset(this);'/>";
			echo "<input class=button4 type='submit' name='button_search' onClick='ShowTables(true);' value='Поиск'/>";

			// Выбор таблицы
			$query_result = mysql_query("SHOW TABLES;");
			echo "<strong class=text_margin>Выбор таблицы:</strong><select id='ComboBoxTable' onChange='ShowTables();' name='combobox_tables'>";
			//echo "<option value='Выберете таблицу'>Выберете таблицу</option>";
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
				
			</div>
			<div id="no-print">
				<input type="button" class="button4" id="btnBack" onClick="ShowTables();" value="Назад" />
				<input class="button4" type="button" id="SelectAll" onclick="Reports(this);" style="margin-top: 20px;" value="Вывести всех" onclick=""/>
				<strong style="position: absolute; left: 200px;">Подразделение</strong>
				<?php
				// Делаем запрос по категориям пренадлежности должностей и их выводи в combobox
				$query_resulte = mysql_query("SELECT * FROM `Подразделения`");
				echo "<select class=comboquery id='combobox_position'>";
				for($i = 1; $data = mysql_fetch_array($query_resulte); $i++)
				{
					echo "<option value='".$data['Подразделение']."'>".$data['Подразделение']."</option>";
				}
				?>
				</select>
				<input class="button4" type='button' id='SelectPos' onclick="Reports(this);" value='Вывести' />
				<strong style="position: absolute; left: 500px;">Возраст</strong>
				<input type='text' id='text_age' value='50-60' style='margin-left: 10px;'/>
				<input class="button4" type='button' id='SelectAge' onclick="($('#text_age').val()) ? Reports(this) : alert('введите возраст!')" value='Вывести' />
				<input type="button" class="button4" id="PrintOpt" value="Настройки печати" />
				<input type="button" class="button4" id="btnPrint" onClick="window.print();" value="Печать" />
				<br>
				<?php
				// Выбор таблицы
				$query_result = mysql_query("SHOW TABLES;");
				echo "<strong class=text_margin>Выбор таблицы:</strong><select onChange='SelectTableRep();' id='ComboBoxTableRep' name='ComboBoxTableRep'>";
				while($data = mysql_fetch_array($query_result))
				{
					if($data[0] == "Основная_таблица")
					{
						echo "<option value='".iconv('UTF-8', 'CP1251//IGNORE', $data[0])."' selected>".$data[0]."</option>";
					}
					else
					{
						echo "<option value='".$data[0]."'>".$data[0]."</option>";
					}
					
				}
				echo "</select>";
				echo "<select id='ComboBoxFieldsRep' name='combobox_query'>";
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
				echo "</select>";
				//echo "</div>";

				echo "<input class=inputtext type='text' id='search_field_rep' />";
				echo "<input class=button4 type='button' name='button_search' onClick='CustomReport();' value='Фильтровать'/>";
				?>
				<br>
				<strong style="Position: absolute;" class=text_margin>Выбор столбцов:</strong>
				<div onClick="ViewComboCkeck();"  onselectstart="return false" onmousedown="return false" style="overflow: hidden; cursor: default;" id="ComboCheckBox">
					<font style="float:left; overflow: hidden; cursor: default;" onselectstart="return false" onmousedown="return false" id="CaptionCheckBox"></font>
				</div>
				<div id="CheckBoxPanel"></div>
				<a id='help' target='_blank' style='position: Fixed; Left: 90%; Top: 5px; font-size: 24pt;' href='help/index.html'>Помощь</a>
			</div>

		</div>
	</div>
	<div id="container_h"></div>
	<div id="container_d"></div>
	<div id="container_s"></div>
	<div id='container_r'></div>
	<div id="PrintWindow"></div>
	<div id="TablePanel">
		<fieldset style="padding: 4px">
			<legend style='margin: 0 0 0 80px'>Перенос данных из одной таблицы в другую</legend>
			<select id='TabelIn'>
				<?php $result = mysql_query("SHOW TABLES;");
				while($data = mysql_fetch_array($result))
				{
					if($data[0] == "Основная_таблица")
					{
						echo "<option value='".$data[0]."' selected>".$data[0]."</option>";
					}
					else
					{
						echo "<option value='".$data[0]."'>".$data[0]."</option>";
					}
				} ?>
			</select>
			<strong>&rArr;</strong>
			<select id='TabelOut'>
				<?php $result = mysql_query("SHOW TABLES;");
				while($data = mysql_fetch_array($result))
				{
					if($data[0] == "Основная_таблица_копия")
					{
						echo "<option value='".$data[0]."' selected>".$data[0]."</option>";
					}
					else
					{
						echo "<option value='".$data[0]."'>".$data[0]."</option>";
					}
				} ?>
			</select>
			<input class="button4" type="button" id="CopyBtn" value="ок" />
		</fieldset>
		<fieldset style="padding: 4px;">
			<legend style='margin: 0 0 0 120px'>Очистка и импорт данных</legend>
			<form enctype="multipart/form-data" action="ExporToExcel.php" method="POST" id="othdetphotoform" target="hiddenframe">
				<select id='ComboExpInp' name="ExpTable">
					<option value="Все таблицы">Все таблицы</option>
					<?php $result = mysql_query("SHOW TABLES;");
					while($data = mysql_fetch_array($result)){ echo "<option value='".$data[0]."' >".$data[0]."</option>"; } ?>
				</select>
				<!-- <input class="button4" type="button" id="ExportBtn" onClick='ExportToExcel();' value="Экспорт" /> -->
				<input class="button4" type="button" id="ClearBtn" value="Очистить" />
				<!-- <input class="button4" type="button" id="OpenFile" value="файл" />-->
				<br>
				<label><input id="Rewrite" name="TypeMethod" class="button4" type="radio" value="Перезаписать" name="1" onClick="RedioClick(this);" />Перезаписать</label>
				<label><input id="Add" name="TypeMethod" class="button4" type="radio" value="Добавить" name="1" onClick="RedioClick(this);" />Добавить</label>
				<br>
				<input type="hidden" name="MAX_FILE_SIZE" value="300000" />
				<font>Импорт: </font><input  name="excelfile" class="button4" type="file" accept=" application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
				<input type="submit" class="button4" id="InportBtn" value="Импорт" />
			</form>
			<iframe id="hiddenframe" name="hiddenframe" style="width:0px;height:0px;border:0px"></iframe>
		</fieldset>
		<input class="button4" type="button" id="CloseTablePanel" value="Закрыть" style="margin-left: 396px" />
	</div>
	
	<div id="message"></div>
</body>
</html>
   