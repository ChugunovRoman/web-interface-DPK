<?php
include_once("ConectToMySQL.php");
require_once 'PHPExcel.php';
require_once 'PHPExcel/IOFactory.php';
require_once 'PHPExcel/Shared/XMLWriter.php';
error_reporting( E_ERROR ); // Отключаем предупреждения (Warning)

echo "<pre>";
print_r($_POST);
print_r($_FILES);
print "</pre>";

$table = ($_POST['ExpTable']) ? : "Основная_таблица";

function position($value, $rev = false)
{
	if($rev)
	{
		$sql = mysql_query("SELECT * FROM `Должности` WHERE `Должность`='".$value."'");
		$row = mysql_fetch_array($sql);
		return $row['Код'];
	}
	else
	{
		$value = (int) $value;
		$sql = mysql_query("SELECT * FROM `Должности` WHERE `Код`='".$value."'");
		$row = mysql_fetch_array($sql);
		return $row['Должность'];
	}
	
}
function subdivision($value, $rev = false)
{
	if($rev)
	{
		$sql = mysql_query("SELECT * FROM `Подразделения` WHERE `Подразделение`='".$value."'");
		$row = mysql_fetch_array($sql);
		return $row['Код'];
	}
	else
	{
		$value = (int) $value;
		$sql = mysql_query("SELECT * FROM `Подразделения` WHERE `Код`='".$value."'");
		$row = mysql_fetch_array($sql);
		return $row['Подразделение'];
	}
	
}
function category($value, $rev = false)
{
	if($rev)
	{
		$sql = mysql_query("SELECT * FROM `Категории` WHERE `Категория`='".$value."'");
		$row = mysql_fetch_array($sql);
		return $row['Код'];	
	}
	else
	{
		$value = (int) $value;
		$sql = mysql_query("SELECT * FROM `Категории` WHERE `Код`='".$value."'");
		$row = mysql_fetch_array($sql);
		return $row['Категория'];	
	}
	
}
//PHPExcel_Settings::setLocale('ru');

if(!empty($_POST['ExpTable']))
{
	$phpexcel = new PHPExcel();
	$phpexcel->getProperties()->setCreator("ThinkPHP")
				->setLastModifiedBy("Daniel Schlichtholz")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Test doc for Office 2007 XLSX, generated by PHPExcel.")
				->setKeywords("office 2007 openxml php")
				->setCategory("Test result file");
	$phpexcel->getActiveSheet()->setTitle('Minimalistic demo');
	$page = $phpexcel->setActiveSheetIndex(0);
	$query_result = mysql_query("SELECT * FROM `".$table."` ORDER BY 1");
	$arr_ABC = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");

	for($i = 2; $data = mysql_fetch_array($query_result); $i++)
	{
		for($j = 0; $j < mysql_num_fields($query_result); $j++)
		{
			$page->setCellValue($arr_ABC[$j]."1", mysql_field_name($query_result, $j));
			$page->getColumnDimension($arr_ABC[$j])->setAutoSize(true);
			switch(mysql_field_name($query_result, $j))
			{
				case "Принадлежность": $page->setCellValue($arr_ABC[$j].$i, subdivision($data[$j]));
				break;
				case "Должность": ($table == "Основная_таблица" or $table == "Основная_таблица_копия") ? $page->setCellValue($arr_ABC[$j].$i, position($data[$j])) : $page->setCellValueExplicit($arr_ABC[$j].$i, $data[$j],PHPExcel_Cell_DataType::TYPE_STRING);
				break;
				case "Подразделение": ($table == "Основная_таблица" or $table == "Основная_таблица_копия") ? $page->setCellValue($arr_ABC[$j].$i, subdivision($data[$j])) : $page->setCellValueExplicit($arr_ABC[$j].$i, $data[$j],PHPExcel_Cell_DataType::TYPE_STRING);
				break;
				case "Категория": ($table == "Основная_таблица" or $table == "Основная_таблица_копия") ? $page->setCellValue($arr_ABC[$j].$i, category($data[$j])) : $page->setCellValueExplicit($arr_ABC[$j].$i, $data[$j],PHPExcel_Cell_DataType::TYPE_STRING);
				break;
				default: $page->setCellValueExplicit($arr_ABC[$j].$i, $data[$j],PHPExcel_Cell_DataType::TYPE_STRING);
			}		
		}	
	}
	$page->setTitle($table);
	$objWriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel2007');
	//$sysFilename = iconv('UTF-8', 'CP1251//IGNORE', $table); only windows
	$objWriter->save("ExcelFiles/".$table.".xlsx");
}
if(!empty($_POST['TypeMethod']) and !empty($_POST['MAX_FILE_SIZE']))
{
	$up_load_files = "ExcelFiles/".basename(iconv('UTF-8', 'CP1251//IGNORE', $_FILES['excelfile']['name']));

	echo "<pre>";
	if(move_uploaded_file($_FILES['excelfile']['tmp_name'], $up_load_files))
	{
		if($_POST['TypeMethod'] == "Перезаписать")
		{
			mysql_query("TRUNCATE TABLE `".$_POST['ExpTable']."`;");
		}
		$result = mysql_query("SELECT * FROM `".$_POST['ExpTable']."`;");
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($up_load_files);
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
		{
			$worksheetTitle     = $worksheet->getTitle();
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
			$nrColumns = ord($highestColumn) - 64;
			for($row = 2; $row <= $highestRow; $row++)
			{
				$val = "";
				for($col = 0; $col < $highestColumnIndex; $col++)
				{
					$cell = $worksheet->getCellByColumnAndRow($col, $row);	
					if($col > 10)
					{
						switch(mysql_field_name($result, $col))
						{
							case "Должность": $val .= " '".position($cell->getValue(), true)."',";
							break;
							case "Подразделение": $val .= " '".subdivision($cell->getValue(), true)."',";
							break;
							case "Категория": $val .= " '".category($cell->getValue(), true)."',";
							break;
							default: $val .= " '".$cell->getValue()."',";
						}	
					}
					else
					{
						$val .= (mysql_field_name($result, $col) == "Принадлежность") ? " '".subdivision($cell->getValue(), true)."'," : " '".$cell->getValue()."',";
					}
					
				}
				$val = substr($val, 0, -1);
				echo "INSERT INTO `".$_POST['ExpTable']."` VALUES(".$val.");<br>";
				mysql_query("INSERT INTO `".$_POST['ExpTable']."` VALUES(".$val.");");
			}
		}
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);		
		
		$files = glob("ExcelFiles/*");
		$c = count($files);
		if(count($files) > 0)
		{
			foreach($files as $file)
			{      
				if(file_exists($file))
				{
					unlink($file);
				}   
			}
		}
	}
	else
	{
		echo "Ошибка при загрузки файла.\n";
	}	
}

?>