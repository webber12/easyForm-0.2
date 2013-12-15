<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

class easyFormModule{

public $moduleid;
public $moduleurl;
public $iconfolder;
public $theme;
public $info_type=1;
public $eBlock=''; //тут будет главный инфоблок в зависимости от значения $info_type
public $info=''; //информационная надпись в случае удачного/неудачного действия
public $zagol='Список доступных форм';
public $forms_table='';
public $fields_table='';
public $type=array(//доступные типы полей в форме
				"1"=>"Строка",
				"2"=>"Текст",
				"3"=>"Email",
				"5"=>"Список (select)",
				"6" =>"Флажок (radio)",
				"7"=>"Переключатель (checkbox)",
				"8"=>"Файл",
				"9"=>"Мультиселект",
				"10"=>"Скрытое поле hidden"
			);
public $form_info;
public $pole_info;
			

public function __construct($modx){
	$this->modx=$modx;
	$this->moduleid=(int)$_GET['id'];
	$this->moduleurl='index.php?a=112&id='.$this->moduleid;
	$this->forms_table=$this->modx->getFullTableName('forms');
	$this->fields_table=$this->modx->getFullTableName('form_fields');
	$this->theme=$this->modx->config['manager_theme'];
	$this->iconfolder='media/style/'.$this->theme.'/images/icons/';
}

public function parseTpl($arr1,$arr2,$tpl){
	return str_replace($arr1,$arr2,$tpl);
}

public function createTables(){
	//создаем таблицу форм, если ее нет
	$sql="
	CREATE TABLE IF NOT EXISTS ".$this->forms_table." (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(255) NOT NULL DEFAULT '',
		`sort` int(5) NOT NULL DEFAULT '0',
		`title` text NOT NULL DEFAULT '',
		`email` varchar(255) NOT NULL DEFAULT '',
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	$q=$this->modx->db->query($sql);

	//создаем таблицу полей форм, если ее нет
	$sql="
	CREATE TABLE IF NOT EXISTS ".$this->fields_table." (
		`id` int(5) NOT NULL AUTO_INCREMENT,
		`parent` int(5) NOT NULL DEFAULT '0',
		`title` varchar(255) NOT NULL DEFAULT '',
		`type` int(2) NOT NULL DEFAULT '0',
		`value` text NOT NULL DEFAULT '',
		`sort` int(5) NOT NULL DEFAULT '0',
		`required` int(1) NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
	";
	$q=$this->modx->db->query($sql);
}


public function escape($a){
	return $this->modx->db->escape($a);
}

public function getRow($table,$id){
	$row=$this->modx->db->getRow($this->modx->db->query("SELECT * FROM ".$table." WHERE id=".$id." LIMIT 0,1"));
	return $row;
}

public function addForm($fields,$table){
	$query=$this->modx->db->insert($fields,$table);
	if($query){$this->info='<p class="info">Форма успешно добавлена</p>';}
	else{$this->info='<p class="info error">Не удалось добавить форму</p>';}
}

public function updateForm($fields,$table,$where){
	$query=$this->modx->db->update($fields,$table,$where);
	if($query){$this->info='<p class="info">Форма успешно изменена</p>';}
	else{$this->info='<p class="info error">Не удалось изменить форму</p>';}
}

public function delForm($id){
	$query=$this->modx->db->query("DELETE FROM ".$this->forms_table." WHERE id=".$id);
	if($query){$this->info='<p class="info">Форма успешно удалена</p>';}
	else{$this->info='<p class="info error">Не удалось удалить форму</p>';}
}


public function addField($fields,$table){
	$query=$this->modx->db->insert($fields,$table);
	if($query){$this->info='<p class="info">Поле успешно добавлено</p>';}
	else{$this->info='<p class="info error">Не удалось добавить поле</p>';}
}

public function updateField($fields,$table,$where){
	$query=$this->modx->db->update($fields,$table,$where);
	if($query){$this->info='<p class="info">Поле успешно изменено</p>';}
	else{$this->info='<p class="info error">Не удалось изменить поле</p>';}
}

public function sortFields($order){
	$ok=1;
	foreach($order as $k=>$v){//сохраняем порядок сортировки
		$query=$this->modx->db->query("UPDATE ".$this->fields_table." SET `sort`='".(int)$v."' WHERE id=".(int)$k);
		if(!$query){$ok=1;}
	}
	if($ok==1){$this->info='<p class="info">Поля успешно отсортированы</p>';}
	else{$this->info='<p class="info error">Ошибка при изменении порядка полей</p>';}
}

public function delField($id){
	$query=$this->modx->db->query("DELETE FROM ".$this->fields_table." WHERE id=".$id);
	if($query){$this->info='<p class="info">Поле успешно удалено</p>';}
	else{$this->info='<p class="info error">Не удалось удалить поле</p>';}
}



public function makeActions(){
	if(isset($_POST['delform1'])){//удаление формы
		$this->delForm((int)$_POST['delform1']);
	}
	
	if(isset($_POST['delpole1'])){//удаление поля
		$this->delField((int)$_POST['delpole1']);
	}

	if(isset($_POST['newformname'])&&isset($_POST['newformtitle'])){//добавляем новую форму
		$newformname=$this->escape($_POST['newformname']);
		$newformtitle=$this->escape($_POST['newformtitle']);
		$newformemail=$this->escape($_POST['newformemail']);
		$newformsort=1;
		$maxformsort=$this->modx->db->getValue($this->modx->db->query("SELECT MAX(sort) FROM ".$this->forms_table." LIMIT 0,1"));
		if($maxformsort){
			$newformsort=(int)$maxformsort+1;
		}
		$flds=array(
			'name'=>$newformname,
			'title'=>$newformtitle,
			'sort'=>$newformsort,
			'email'=>$newformemail
		);
		$this->addForm($flds,$this->forms_table);
	}

	if(isset($_GET['fid'])&&isset($_GET['action'])&&$_GET['action']=='edit'){//редактирование формы
		$this->info_type=2;
		$this->zagol='Редактирование формы';
		if(isset($_POST['curformname'])&&isset($_POST['curformtitle'])){
			$curformname=$this->escape($_POST['curformname']);
			$curformtitle=$this->escape($_POST['curformtitle']);
			$curformemail=$this->escape($_POST['curformemail']);
			$flds=array(
				'name'=>$curformname,
				'title'=>$curformtitle,
				'email'=>$curformemail
			);
			
			$this->updateForm($flds,$this->forms_table,"id=".(int)$_GET['fid']);
		}
		
		//обновляем информацию о форме для вывода
		$this->form_info=$this->getRow($this->forms_table,(int)$_GET['fid']);
	}


	//список полей формы
	if(isset($_GET['fid'])&&isset($_GET['action'])&&$_GET['action']=='pole'&&!isset($_GET['pid'])){
		$this->info_type=3;
		$this->zagol='Список полей формы';
		$parent=(int)$_GET['fid'];
		$require=isset($_POST['newpolerequire'])?1:0;
		if(isset($_POST['sortpole'])){//сортируем поля
		
			$this->sortFields($_POST['sortpole']);
		}
		
		if(isset($_POST['newpoletitle'])&&isset($_POST['newpoletype'])&&isset($_POST['newpolevalue'])){//добавляем новое поле
			$newpoletitle=$this->escape($_POST['newpoletitle']);
			$newpoletype=$this->escape($_POST['newpoletype']);
			$newpolevalue=$this->escape($_POST['newpolevalue']);
			$newpolesort=1;
			$maxpolesort=$this->modx->db->getValue($this->modx->db->query("SELECT MAX(sort) FROM ".$this->fields_table." WHERE parent=".$parent." LIMIT 0,1"));
			if($maxpolesort){
				$newpolesort=(int)$maxpolesort+1;
			}
			$flds=array(
				'parent'=>$parent,
				'title'=>$newpoletitle,
				'type'=>$newpoletype,
				'value'=>$newpolevalue,
				'sort'=>$newpolesort,
				'required'=>$require
			);
			$this->addField($flds,$this->fields_table);
		}
	}//конец список полей


	//редактирование поля формы
	if(isset($_GET['fid'])&&isset($_GET['action'])&&$_GET['action']=='pole'&&isset($_GET['pid'])){
		$this->info_type=4;
		$this->zagol='Редактирование поля формы';
		$parent=(int)$_GET['fid'];
		$require=isset($_POST['curpolerequire'])?1:0;
		if(isset($_POST['curpoletitle'])&&isset($_POST['curpoletype'])&&isset($_POST['curpolevalue'])){//редактируем поле
			$curpoletitle=$this->escape($_POST['curpoletitle']);
			$curpoletype=$this->escape($_POST['curpoletype']);
			$curpolevalue=$this->escape($_POST['curpolevalue']);
			$flds=array(
				'title'=>$curpoletitle,
				'type'=>$curpoletype,
				'value'=>$curpolevalue,
				'required'=>$require
			);
			
			$this->updateField($flds,$this->fields_table,"id=".(int)$_GET['pid']);
		}
		
		//обновляем информацию о поле для вывода
		$this->pole_info=$this->getRow($this->fields_table,(int)$_GET['pid']);
	}
}


public function getFormList(){
	include_once('config/config.php');
	$form_list=$this->modx->db->query("SELECT * FROM ".$this->forms_table." ORDER BY sort ASC");
	$formRows='';
	$out='';
	while($row=$this->modx->db->getRow($form_list)){
		$formRows.=$this->parseTpl(
			array('[+id+]','[+name+]','[+title+]','[+email+]','[+moduleurl+]','[+iconfolder+]'),
			array($row['id'],$row['name'],$row['title'],$row['email'],$this->moduleurl,$this->iconfolder),
			$formRowTpl
		);
	}
	$out=$this->parseTpl(
				array('[+formRows+]'),
				array($formRows),
				$formListTpl
				);
	return $out;
}

public function getFormEdit(){
	include_once('config/config.php');
	$out='';
	$out.=$this->parseTpl(
		array('[+name+]','[+title+]','[+email+]','[+moduleurl+]'),
		array($this->form_info['name'],$this->form_info['title'],$this->form_info['email'],$this->moduleurl),
		$formEditTpl
	);
	return $out;
}

public function getFieldList(){
	include_once('config/config.php');
	$out='';
	$rows='';
	$form_list=$this->modx->db->query("SELECT * FROM ".$this->fields_table." WHERE parent=".(int)$_GET['fid']." ORDER BY sort ASC");
	while($row=$this->modx->db->getRow($form_list)){
		$required=$row['required']==1?'<b>(+)</b>':'';
		$rows.=$this->parseTpl(
			array('[+id+]','[+parent+]','[+title+]','[+required+]','[+type+]','[+value+]','[+sort+]','[+moduleurl+]','[+iconfolder+]'),
			array($row['id'],$row['parent'],$row['title'],$required,$this->type[$row['type']],nl2br($row['value']),$row['sort'],$this->moduleurl,$this->iconfolder),
			$fieldRowTpl
		);
	}
	$options=''; //опции в селект выбора типа поля
	foreach($this->type as $k=>$v){
		$options.="<option value='".$k."'>".$v."</option>";
	}
	$out=$this->parseTpl(
		array('[+fieldRows+]','[+typeOptions+]','[+moduleurl+]'),
		array($rows,$options,$this->moduleurl),
		$fieldListTpl
	);
	return $out;
}

public function getFieldEdit(){
	include_once('config/config.php');
	$options='';
	foreach($this->type as $k=>$v){
		$options.="<option value='".$k."' ".($k==$this->pole_info['type']?" selected=selected":"").">".$v."</option>";
	}
	$checked=$this->pole_info['required']==1?' checked="checked"':'';
		
	$this->eBlock.=$this->parseTpl(
		array('[+title+]','[+options+]','[+value+]','[+checked+]','[+parent+]','[+moduleurl+]'),
		array($this->pole_info['title'],$options,$this->pole_info['value'],$checked,$this->pole_info['parent'],$this->moduleurl),
		$fieldEditTpl
	);
	return $out;
}

public function show(){
	//блок вывода списка форм
	switch ($this->info_type){
		case '1':
			$this->eBlock.=$this->getFormList();
		break;
		
		case '2':
			$this->eBlock.=$this->getFormEdit();
		break;
		
		case '3':
			$this->eBlock.=$this->getFieldList();
		break;
		
		case '4':
			$this->eBlock.=$this->getFieldEdit();
		break;
		
		default:
			$this->eBlock.=$this->getFormList();
		break;		
	}
}


public function Run(){
	$this->createTables();
	$this->makeActions();
	$this->show();
}

















}
?>