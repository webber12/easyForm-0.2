<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

//список доступных форм
$formListTpl='
	<table class="fl">
		<thead>	
			<tr>
				<td>id</td>
				<td>Имя</td>
				<td>Описание</td>
				<td>Email</td>
				<td>Поля</td>
				<td>Изменить</td>
				<td>Удалить</td>
			</tr>
		</thead>
		<tbody>
			[+formRows+]
		</tbody>
	</table>
	<br><br>
	<!--форма для создания новой формы-->
	<form action="" method="post" class="actionButtons"> 
		<input type="hidden" name="action" value="newForm">
		Название: <br><input type="text" value="" name="name"><br>
		Описание: <br><input type="text" value="" name="title"><br>
		Email: <br><input type="text" value="" name="email"><br><br>
		<input type="submit" value="Добавить форму">
	</form>		
';

//строка формы в таблице списка форм
$formRowTpl='
	<tr>
		<td>[+id+]</td>
		<td>[+name+]</td>
		<td>[+title+]</td>
		<td>[+email+]</td>
		<td class="actionButtons"><a href="[+moduleurl+]&fid=[+id+]&action=pole" class="button choice"> <img src="[+iconfolder+]page_white_copy.png" alt=""> Список полей</a></td>
		<td class="actionButtons"><a href="[+moduleurl+]&fid=[+id+]&action=edit" class="button edit"> <img alt="" src="[+iconfolder+]page_white_magnify.png" > Изменить</a></td>
		<td class="actionButtons"><a onclick="document.delform.delform1.value=[+id+];document.delform.submit();" style="cursor:pointer;" class="button delete"> <img src="[+iconfolder+]delete.png" alt=""> удалить</a></td>
	</tr>
';

$formEditTpl='
	<form action="" method="post" class="actionButtons">
		<input type="hidden" name="action" value="updateForm">
		Название: <br><input type="text" value=\'[+name+]\' name="name" size="50"><br> 
		Описание: <br><input type="text" value=\'[+title+]\' name="title" size="50"><br>
		Email: <br><input type="text" value=\'[+email+]\' name="email" size="50"><br><br>
		<input type="submit" value="Сохранить">
	</form><br><br>
	<a href="[+moduleurl+]">К списку форм</a>
';

$fieldListTpl='
	<form id="sortpole" action="" method="post" class="actionButtons">
		<table class="fl">
			<thead>
				<tr>
					<td>Имя</td>
					<td>Тип</td>
					<td>Значение</td>
					<td>Порядок</td>
					<td>Изменить</td>
					<td>Удалить</td>
				</tr>
			</thead>
			<tbody>
				[+fieldRows+]	
			</tbody>
		</table>
		<br>
		<input type="submit" value="Сохранить порядок">
	</form>
	<br><br>
	<h2>Добавление нового поля</h2>
	<form action="" method="post" class="actionButtons">
		<input type="hidden" name="action" value="newField">
		Название <br><input type="text" value="" name="title"><br>
		Тип поля <br>	
		<select name="type">[+typeOptions+]</select><br>
		Значение (для типа "список","переключатель","флажок") в формате "значение==подпись" либо просто "подпись", если значение и подпись совпадают (каждый вариант - с новой строки):<br>
		<textarea name="value"></textarea>
		<br>
		Обязательно <input type="checkbox" name="require" value="1"><br><br>
		<input type="submit" value="Добавить поле">
	</form>
	<br><br>
	<a href="[+moduleurl+]">К списку форм</a>
';

$fieldRowTpl='
		<tr>
			<td>[+title+] [+required+]</td><td> [+type+] </td><td> [+value+] </td>
			<td><input type="text" name="sortpole[[+id+]]" value="[+sort+]" class="sort small"></td>
			<td> <a href="[+moduleurl+]&fid=[+parent+]&pid=[+id+]&action=pole" class="button edit"><img alt="" src="[+iconfolder+]page_white_magnify.png" > Изменить</a> </td>
			<td> <a onclick="document.delpole.delpole1.value=[+id+];document.delpole.submit();" style="cursor:pointer;" class="button delete"> <img src="[+iconfolder+]delete.png" alt=""> Удалить</a> </td>
		</tr>
';

$fieldEditTpl='
	<form action="" method="post" class="actionButtons">
		<input type="hidden" name="action" value="updateField">
		Название: <br><input type="text" value="[+title+]" name="title"><br> 
		Тип: <br>
		<select name="type">[+options+]</select>
		<br>
		Значение (для типа "список","переключатель","флажок") в формате "значение==подпись" либо просто "подпись", если значение и подпись совпадают (каждый вариант - с новой строки): 
		<br>
		<textarea name="value">[+value+]</textarea><br>
		Обязательно: <input type="checkbox" value="1" name="require" [+checked+]><br><br>
		<input type="submit" value="Сохранить изменения">
	</form>
	<br><br>
	<a href="[+moduleurl+]&fid=[+parent+]&action=pole">К списку полей</a>
';








?>