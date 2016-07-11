<?php
    // соединяемся с сервером базы данных
	$connect_to_db = mysql_connect('localhost', 'root', 'typedef') or die("<div style='height: 40px; background: #ff9 linear-gradient(#FF0000, #D84848); text-align: center; margin: 40px 40px 0px 40px; padding: 20px 10px 10px 10px; font-size: 24px;'>Сервер недоступен: " . mysql_error()."</div>");
	$dpk = mysql_select_db('dpk', $connect_to_db) or die("<div style='height: 40px; background: #ff9 linear-gradient(#FF0000, #D84848); text-align: center; margin: 40px 40px 0px 40px; padding: 20px 10px 10px 10px; font-size: 24px;'>Невозможно подключится к базе данных, ошибка: " . mysql_error()."</div>");
	mysql_query("SET NAMES utf8");
?>
