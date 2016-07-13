// Функция установки рамки
function BorderReset(Obj)
{
	Obj.style.border = "1px inset #886";
}
var Size, MTop, MLeft, MBottom, MRight, FSize, FFamily;
$(document).ready(function()
{
	// Вешаем события
	$('#search_field_rep').bind('keyup', function(event)
	{
		if(event.keyCode == 13)
		{
			CustomReport();
		}
	});
	$('#search_field').bind('keyup', function(event)
	{
		if(event.keyCode == 13)
		{
			ShowTables(true);
		}
	});
	$('#ComboExpInp').bind('change', function()
	{
		setTimeout(function()
		{
			if($('#ComboExpInp').val() == "Все таблицы")
			{
				$('#InportBtn').attr('disabled', true);
			}
			else
			{
				$('#InportBtn').removeAttr('disabled');
			}
		}, 50);
	});
	$('#RenameBtn').bind('click', function()
	{
		if($('#ComboExpInp').val() != "Все таблицы")
		{
			if($('#NewNameTable').val() != "")
			{
				$('#message').html('<strong style="font-size: 24px;">Переименование таблицы...</strong>').css('width', '270px');
				$.ajax({
				   type: "POST",
				   url: "copy.php",
				   data: {RenameTable: $('#ComboExpInp').val(), NewName: $('#NewNameTable').val()},
				   success: function(msg){
					 $('#container_d').html(msg);
					 $('#message').html('<strong style="font-size: 24px;">Таблица переименована.</strong>');
					 setTimeout(function()
					 {
						 document.getElementById("message").style.display = "none";
						 $.ajax(
						 {
							 type: 'POST',
							 url: 'ComboBoxFields.php',
							 data: {ComboBoxTables: 1},
							 success: function(msg)
							 {
								 $('#ComboBoxTable').html(msg);
								  ShowTables();
							 }
						 });
					 }, 1000);
				   }
				});		
			}
			else
			{
				alert("Введите название таблицы!");
			}			
		}
		else
		{
			alert("Выберите конкретную таблицу!");
		}		
	});
	$('#InportBtn').bind({
	click: function()
	{
		$('#message').css('display', 'block');
		document.getElementById("Save").style.display = "none";
		$('#message').html('<strong style="font-size: 24px;">Иморт файла...</strong>').css('width', '230px');
		$.ajax({
		   type: "POST",
		   url: "ExporToExcel.php",
		   data: {ExpTable: $('#ComboExpInp').val()},
		   success: function(msg){
			 $('#container_d').html(msg);
			 $('#message').html('<strong style="font-size: 24px;">Иморт завершен.</strong>');
			 setTimeout(function()
			 {
				 document.getElementById("message").style.display = "none";				 
				 ShowTables();
			 }, 1500);
		   }
		});
	}});
	$('#ClearBtn').bind('click', function()
	{
		$('#message').css('display', 'block');
		$('#message').html('<strong style="font-size: 24px;">Удаление данных...</strong>').css('width', '300px');
		$.ajax({
		   type: "POST",
		   url: "copy.php",
		   data: {ClearTable: $('#ComboExpInp').val()},
		   success: function(msg){
			 $('#container_d').html(msg);		
			 setTimeout(function()
			 {
				$('#message').html('<strong style="font-size: 24px;">Удаление завершено</strong>');
			 }, 1000);
			 setTimeout(function()
			 {
				document.getElementById("message").style.display = "none";
				ShowTables();
			 }, 2000);
		   }
		});
		
	});
	$('#CopyBtn').bind('click', function()
	{	
		$('#message').css('display', 'block');
		$('#message').html('<strong style="font-size: 24px;">Копирование данных...</strong>').css('width', '320px');
		$.ajax({
		   type: "POST",
		   url: "copy.php",
		   data: {InTable: $('#TabelIn').val(), OutTable: $('#TabelOut').val()},
		   success: function(msg){
			 $('#container_d').html(msg);		
			 setTimeout(function()
			 {
				 $('#message').html('<strong style="font-size: 24px;">Копирование завершено</strong>');
			 }, 1000);
			 setTimeout(function()
			 {
				 document.getElementById("message").style.display = "none";
				 ShowTables();
			 }, 2000);
		   }
		});		
	});
	$('#PrintOpt').bind('click', function()
	{
		$('#PrintWindow').css(
		{
			position: 'fixed',
			width: '232px',
			height: '255px',
			top: '6%',
			left: '40%',
			'background-color': '#cca',
			display: 'block',
			border: '10px solid #eec',
			'border-radius': '5px',
			padding: '6px'
		}).empty().append("<strong>Ориентация листа: </strong>"
				+ "<select id='PageOrientation'><option value='Альбомная'>Альбомная</option><option value='Портретная'>Портретная</option></select>"
				+ "<fieldset style='width: 223px; height: 120px;'>"
				+ "<legend style='margin: 0px 0 0 40px;'>Отступы от краев листа</legend>"
				+	"<input class='inputtext' type='text' style='position: absolute; top: 50px; left: 85px; width: 30px; height: 20px' id='MarginTop' value='30'/><font style='position: absolute; top: 50px; left: 118px;'>px</font>"
				+ 	"<input class='inputtext' type='text' style='position: absolute; top: 80px; left: 35px; width: 30px; height: 20px' id='MarginLeft' value='50'/><font style='position: absolute; top: 80px; left: 68px;'>px</font>"
				+ 	"<input class='inputtext' type='text' style='position: absolute; top: 80px; left: 135px; width: 30px; height: 20px' id='MarginRight' value='20'/><font style='position: absolute; top: 80px; left: 168px;'>px</font>"
				+ 	"<input class='inputtext' type='text' style='position: absolute; top: 115px; left: 85px; width: 30px; height: 20px' id='MarginBottom' value='30'/><font style='position: absolute; top: 115px; left: 118px;'>px</font>"
				+ "</fieldset>"
				+ "<fieldset style='width: 223px; height: 80px;'>"
				+ "<legend style='margin: 0px 0 0 90px;'>Текст</legend>"
				+ 	"<font>Шрифт: </font><select id='ComboFontFam'>"
				+ 	"<option value='Times New Roman'>Times New Roman</option>"
				+ 	"<option value='Arial'>Arial</option>"
				+ 	"<option value='Helvetica'>Helvetica</option>"
				+ 	"<option value='sans-serif'>sans-serif</option>"
				+ 	"<option value='Comic Sans MS'>Comic Sans MS</option>"
				+ 	"<option value='cursive'>cursive</option>"
				+ 	"<option value='Courier New'>Courier New</option>"
				+ 	"<option value='Georgia'>Georgia</option>"
				+ 	"<option value='serif'>serif</option>"
				+ 	"<option value='Lucida Console'>Lucida Console</option>"
				+ 	"<option value='Lucida Sans Unicode'>Lucida Sans Unicode</option>"
				+ 	"<option value='Palatino Linotype'>Palatino Linotype</option>"
				+ 	"<option value='Tahoma'>Tahoma</option>"
				+ 	"<option value='Trebuchet MS'>Trebuchet MS</option>"
				+ 	"<option value='Verdana'>Verdana</option>"
				+ 	"</select>"
				+ "<font>Размер шрифта: </focus>"
				+ "<input class='inputtext' id='FontSize' type='text' value='14' style='width: 30px;' /><font>pxt</font>"
				+ "</fieldset>"
				+ "<input style='margin-left: 83px' class='button4' type='button' onclick='SaveOptions();' id='SavePrintOpt' value='Сохранить' />"
				+ "<input class='button4' type='button' id='ClosePrintOpt' value='Закрыть' />");
		if(Size)
		{
			document.getElementById('PageOrientation').selectedIndex = (Size == "Альбомная") ? 0 : 1;	
			$('#PageOrientation').val(Size);
			$('#MarginTop').val(MTop);
			$('#MarginLeft').val(MLeft);
			$('#MarginBottom').val(MBottom);
			$('#MarginRight').val(MRight);
			$('#FontSize').val(FSize);
			$('#ComboFontFam').val(FFamily);
		}
	});
	if($('#ComboExpInp').val() == "Все таблицы")
	{
		$('#InportBtn').attr('disabled', true);
	}
	else
	{
		$('#InportBtn').removeAttr('disabled');
	}
	$('#Rewrite').attr('checked', true);
	// стиль для Google Chrome
	var user = detect.parse(navigator.userAgent);
	if (user.browser.family === 'Chrome')
	{
		$('#TablePanel').css('height', '195px');
		$('#CloseTablePanel').css('margin-left', '405px');
	}
	
});
// Ставим checked на RadioButton
function RedioClick(Radio)
{
	if($(Radio).attr('id') == "Rewrite")
	{
		$('#Rewrite').attr('checked', true);
		$('#Add').removeAttr('checked');
	}
	else
	{
		$('#Add').attr('checked', true);
		$('#Rewrite').removeAttr('checked');
	}
}
function SelectAllCheck()
{
	$('input[name$="SelRow"]').prop('checked', ($('#SelectAllcheck').prop('checked')) ? true : false);
}
function SaveOptions()
{
	Size = $('#PageOrientation').val();
	MTop = $('#MarginTop').val();
	MLeft = $('#MarginLeft').val();
	MBottom = $('#MarginBottom').val();
	MRight = $('#MarginRight').val();
	FSize = $('#FontSize').val();
	FFamily = $('#ComboFontFam').val();
	if(document.getElementById('PageStyle'))
	{
		document.getElementById('PageStyle').parentNode.removeChild(document.getElementById('PageStyle'));	
	}
	var PageStyle = document.createElement('style');
	PageStyle.setAttribute("id", "PageStyle");
	PageStyle.setAttribute("type", "text/css");
	PageStyle.innerHTML += '@page {';
	PageStyle.innerHTML += ($('#PageOrientation').val() == "Портретная") ? ' size: A4 portrait;' : ' size: A4 landscape;';
	PageStyle.innerHTML += " margin: "+$('#MarginTop').val()+"px "+$('#MarginRight').val()+"px "+$('#MarginBottom').val()+"px "+$('#MarginLeft').val()+"px ;}";
	PageStyle.innerHTML += " .example3 {";
	$('.example3').css('font-family', "'"+$('#ComboFontFam').val()+"'").each(function()
	{
		$(this).find("td").css('font-size', $('#FontSize').val()+"px");
	});
	PageStyle.innerHTML += '}';
	$('body').prepend(PageStyle);
}
// Функция заполнения других ComboBox'ов
function SelectTableRep()
{
	$('#CheckBoxPanel').empty();
	$('#search_field_rep').val("");
	$.ajax({
	   type: "POST",
	   url: "ComboBoxFields.php",
	   data: {table: $('select[name=ComboBoxTableRep] option:selected').val() || [], ReportFieldBox: '1' },
	   success: function(msg){
		 $('#ComboBoxFieldsRep').html(msg);
	   }
	 });
	$('#CaptionCheckBox').empty();
	setTimeout(function()
	{
		var BoxFieldsRep = document.getElementById("ComboBoxFieldsRep");
		$('#CheckBoxPanel').css(
		{
			height: "19px",
			background: "#cca"
		});
		$('#CheckBoxPanel').append("<label onmouseover='SelectCheck(this);' onmouseout='unSelectCheck(this);'><input type='checkbox' class='CheckList' onselectstart='return false' onmousedown='return false'  onclick='ClickCheckBox();' value='Номер'/>Номер</label><br>");
		for(i = 0; i < BoxFieldsRep.options.length; i++)
		{
			$('#CheckBoxPanel').append("<label onmouseover='SelectCheck(this);' onmouseout='unSelectCheck(this);'><input type='checkbox' class='CheckList' onselectstart='return false' onmousedown='return false'  onclick='ClickCheckBox();' value='" + BoxFieldsRep.options[i].value + "'/>" + BoxFieldsRep.options[i].value + "</label><br>");
			var ComboHeight = $('#CheckBoxPanel').css('height');
			ComboHeight = parseInt(ComboHeight) + 19.5;
			$('#CheckBoxPanel').css("height", ComboHeight+"px");
		}
	}, 100);
}
// Функция для настраиваемого отчета
function CustomReport()
{
	if($("input[class=CheckList]:checked").length == 0)
	{
		$('#ComboCheckBox').css({border: '1px solid red'});
		alert("Выберете столбцы, которые будут выведены!");
		return -1;
	}
	document.getElementById("container_r").style.display = "block";
	$('#message').css('display', 'block');
	$('#message').html('<strong style="font-size: 24px;">Создание отчета...</strong>').css('width', '240px');
	$('#container_r').html("");
	document.getElementById("container_h").style.display = "none";
	$.ajax(
	{
		type: 'POST',
		url: 'Report.php',
		data: {table: $('select[name=ComboBoxTableRep] option:selected').val() || [], namefield: $('#ComboBoxFieldsRep').val() || [], filtr: $('#search_field_rep').val() || [], arrfields: $('#CaptionCheckBox').text(), customereport: '1'},
		success: function(msg)
		{
			$('#message').html('<strong style="font-size: 24px;">Отчет готов</strong>').css('width', '180px');
			$('#container_r').html(msg);
			setTimeout(function()
			 {
				 $('#message').css('display', 'none');
				 
			 }, 1000);
		}
	});
}
// дфункции изменения цвета фона у ComboCheckBox
function SelectCheck(obj)
{
	obj.style.background = "#2896fb";
}
function unSelectCheck(obj)
{
	obj.style.background = "none";
}
// Скрытие ComboCheckBox
jQuery(function($)
{
	$(document).mouseup(function(e)
	{
		var div = $('#CheckBoxPanel'); 
		var div1 = $('#PrintWindow');
		var div2 = $('#TablePanel');
		if(!$('#ComboCheckBox').is(e.target) && $('#ComboCheckBox').has(e.target).length === 0 
		&& !div.is(e.target) && div.has(e.target).length === 0
		&& ($('#ClosePrintOpt').is(e.target) || $('#SavePrintOpt').is(e.target) || !div1.is(e.target) && div1.has(e.target).length === 0)
		&& ($('#CloseTablePanel').is(e.target) || !div2.is(e.target) && div2.has(e.target).length === 0))
		{
			div.hide();
			div1.hide();
			div2.hide();
		}
	});
});
// Заполнение списка выбранных полей
function ClickCheckBox()
{
	var list = null, res = '';
    list = $(':checkbox:checked');
    list.each( function(ind) {
        res += $(this).val();
        if (ind < list.length - 1) res +=',';
    });
	$('#CaptionCheckBox').text(res);
}
// Отображение ComboCheckBox	
function ViewComboCkeck()
{
	$('#ComboCheckBox').css({border: '1px inset #886'});
	document.getElementById("CheckBoxPanel").style.display = (document.getElementById("CheckBoxPanel").style.display == 'none') ? 'block' : 'none';
}
// Переход к панели отчетов
function ShowButtons()
{
	document.getElementById("header_buttun").style.display = "none";
	document.getElementById("container_h").style.display = "none";
	document.getElementById('no-print').style.display = "block";
	$('#header_h').css({height: '100px' });
}
// Функция для отображения таблиц
function ShowTables(search = false)
{
	console.time('test');
	$('#header_h').css('height', '55px');
	document.getElementById("header_buttun").style.display = "block";	
	document.getElementById("count_rows").value = "";
	document.getElementById("editbtn").value = "Редактировать";
	document.getElementById("Save").style.display = "none";
	document.getElementById("container_d").style.display = "none";
	document.getElementById("container_s").style.display = "none";
	document.getElementById("message").style.display = "none";
	document.getElementById("no-print").style.display = "none";
	document.getElementById("container_r").style.display = "none";
	document.getElementById("container_h").style.display = "block";
	document.getElementById("CheckBoxPanel").style.display = "none";
	document.getElementById("TablePanel").style.display = "none";
	if(document.getElementById("delete").disabled)
	{
		document.getElementById("delete").disabled = false;
		document.getElementById("count_rows").disabled = false;
		document.getElementById("insert").disabled = false;
		document.getElementById("export_to_csv").disabled = false;	
	}
		
	/*$('#delete').removeAttr("disabled");
	$('#count_rows').removeAttr("disabled");
	$('#insert').removeAttr("disabled");
	$('#export_to_csv').removeAttr("disabled");*/
	if(!search)
	{
		$('#search_field').val("");
		$.ajax({
		   type: "POST",
		   url: "ShowTables.php",
		   data: "table=" + $('select[name=combobox_tables] option:selected').val() || [],
		   success: function(msg){
			 $('#container_h').html(msg);
		   }
		 });
		$.ajax({
		   type: "POST",
		   url: "ComboBoxFields.php",
		   data: {table: $('select[name=combobox_tables] option:selected').val() || [], SearchField: '1' },
		   success: function(msg){
			 $('#ComboBoxFields').html(msg);
		   }
		 });
	}
	else
	{
		if($('#search_field').val() == '')
		{
			ShowTables();
		}
		else
		{
			$.ajax({
			   type: "POST",
			   url: "ShowTables.php",
			   data: {table: $('select[name=combobox_tables] option:selected').val() || [], combobox_query: $('#ComboBoxFields').val(), search_field: $('#search_field').val() },
			   success: function(msg){
				 $('#container_h').html(msg);
			   }
			 });		
		}		
	}	
	console.timeEnd('test');
}
// функция редактирования таблиц: редактирование и добавление данных
function EditTables()
{
	$('#delete').attr("disabled", "disabled");
	$('#count_rows').attr("disabled", "disabled");
	$('#insert').attr("disabled", "disabled");
	$('#export_to_csv').attr("disabled", "disabled");
	var cbx = $("input[name='SelRow']"), mas = [];
	for (i=0; i < cbx.length; i++)
	{
		if (cbx[i].type == "checkbox" && cbx[i].checked)
		{
			mas.push(cbx[i].value);
		}
	}
	if(document.getElementById("editbtn").value == "Редактировать")
	{	
		if(mas.length <= 0)
		{
			alert('Не вибрано ни одной строки!');
			return -1;
		}
		$('#editbtn').val('Сохранить');
		document.getElementById("container_d").style.display = "block";
		document.getElementById("container_h").style.display = "none";	
		document.getElementById("container_s").style.display = "none";	
		$.ajax({
		   type: "POST",
		   url: "EditTables.php",
		   data: {table: $('select[name=combobox_tables] option:selected').val() || [], checked: mas.join(',')},
		   success: function(msg){
			 $('#container_d').html(msg);
		   }
		 });
		return 0;
	}
	if(document.getElementById("editbtn").value == "Сохранить")
	{
		var flag = 0;
		$('.Date').each(function(indx){
			var valid = /(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d/;
			if(!valid.test(this.value))
			{
				$(this).css('border', '1px solid red');
				alert(this.value + " - неправильная дата!");
				falg++;
				return -1;
			}
		});
		if(flag == 0)
		{
			var cbx = $('.SaveText'), values = [];
			for (i=0; i < cbx.length; i++)
			{
				values.push(cbx[i].value);
			}
			$.ajax({
			   type: "POST",
			   url: "SaveData.php",
			   data: {table: $('select[name=combobox_tables] option:selected').val() || [],	checked: mas.join(','), values,	rows: $('#count_rows').val()},
			   success: function(msg){
				 $('#savediv').html(msg);
			   }
			 });
			$('#editbtn').val('Редактировать');
			//setTimeout('ShowTables()', 200);
			setTimeout('$(\'#count_rows\').val("")', 200);
			$('#delete').removeAttr("disabled");
			$('#count_rows').removeAttr("disabled");
			$('#insert').removeAttr("disabled");
			$('#export_to_csv').removeAttr("disabled");
			return 0;	
		}
		
	}	 
}
// функция для вставки новых строк в выбранную таблицу
function InsertData()
{
	$('#count_rows').val(!($('#count_rows').val()) ? "1" : $('#count_rows').val());
	document.getElementById("container_d").style.display = "block";
	document.getElementById("container_h").style.display = "none";	
	document.getElementById("container_s").style.display = "none";
	$('#delete').attr("disabled", "disabled");
	$('#count_rows').attr("disabled", "disabled");
	$('#insert').attr("disabled", "disabled");
	$('#export_to_csv').attr("disabled", "disabled");
	$.ajax({
	   type: "POST",
	   url: "InsertData.php",
	   data: {table: $('select[name=combobox_tables] option:selected').val() || [],	rows: ($('#count_rows').val()) ? $('#count_rows').val() : "1"},
	   success: function(msg){
		 $('#container_d').html(msg);
	   }
	 });
	$('#editbtn').val('Сохранить');
	return 0;
}
// Функция для удаления строк
function DeleteData()
{
	var cbx = document.getElementsByTagName("input"), mas = [];
	for (i=0; i < cbx.length; i++)
	{
		if (cbx[i].type == "checkbox" && cbx[i].checked)
		{
			mas.push(cbx[i].value);
		}
	}
	if(mas.length <= 0)
	{
		alert('Не вибрано ни одной строки!');
		return -1;
	}
	$.ajax({
	   type: "POST",
	   url: "DeleteData.php",
	   data: {table: $('select[name=combobox_tables] option:selected').val() || [],	checked: mas.join(',')},
	   success: function(msg){
		 $('#container_d').html(msg);
	   }
	 });	
	 setTimeout('ShowTables()', 100);
}
// Функция для получения id тега-родителя
function getParentId(a)
{
	$('.'+a.parentNode.className).prop('checked', true);
}
// функция для вывода отчетов
function Reports(obj)
{
	document.getElementById("container_r").style.display = "block";
	$('#container_r').html("");
	document.getElementById("container_h").style.display = "none";
	if(obj.id == "SelectAll")
	{
		$.ajax({
		   type: "POST",
		   url: "Report.php",
		   data: {SelectAll: "1"},
		   success: function(msg){
			 $('#container_r').html(msg);
		   }
		});
		return 0;
	}
	if(obj.id == "SelectPos")
	{
		$.ajax({
		   type: "POST",
		   url: "Report.php",
		   data: {SelectPos: "1", combobox_position: $('#combobox_position').val()},
		   success: function(msg){
			 $('#container_r').html(msg);
		   }
		});
		return 0;
	}
	if(obj.id == "SelectAge")
	{
		$.ajax({
		   type: "POST",
		   url: "Report.php",
		   data: {SelectAge: "1", text_age: $('#text_age').val()},
		   success: function(msg){
			 $('#container_r').html(msg);
		   }
		});
		return 0;
	}
}
// Функция для создания копии основной таблицы
function CopyTable()
{
	$('#message').css('display', 'block');	
	$('#message').html('<strong style="font-size: 24px;">Создание копии...</strong>').css('width', '240px');
	$.ajax({
	   type: "POST",
	   url: "copy.php",
	   data: {},
	   success: function(msg){
		 $('#container_d').html(msg);		
		 setTimeout(' $(\'#message\').html(\'<strong style="font-size: 24px;">Копия создана</strong>\')', 1000);
		 setTimeout('document.getElementById("message").style.display = "none"' , 2000);
	   }
	});
	return 0;
}
// Функция для экспорта в Excel
function ExportToExcel()
{
	$('#message').css('display', 'block');
	document.getElementById("Save").style.display = "none";
	$('#message').html('<strong style="font-size: 24px;">Создание файла...</strong>').css('width', '240px');
	$.ajax({
	   type: "POST",
	   url: "ExporToExcel.php",
	   data: "ExpTable=" + $('select[name=combobox_tables] option:selected').val() || [],
	   success: function(msg){
		 $('#container_d').html(msg);
		 $('#message').html('<strong style="font-size: 24px;">Экспорт завершен.</strong>');
		 setTimeout('document.getElementById("message").style.display = "none"' , 1000);
		 document.getElementById("Save").href='ExcelFiles/'+$('select[name=combobox_tables] option:selected').val()+".xlsx";
		 setTimeout('document.getElementById("Save").click();' , 1100);
	   }
	});
	return 0;
}
