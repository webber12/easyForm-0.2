<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

class easyForm{

protected $params=array();
protected $modx;
protected $id=0;
public $forms_table='';
public $fields_table='';
public $base_url;
public $lang=array();

public function __construct($modx,$params,$eid){
	$this->modx=$modx;
	$this->params=$params;
	$this->id=$eid;
	$this->forms_table=$this->modx->getFullTableName('forms');
	$this->fields_table=$this->modx->getFullTableName('form_fields');
	$this->base_url=MODX_BASE_URL;
}

public function eParseTpl($arr1,$arr2,$output){
	return str_replace($arr1,$arr2,$output);
}

public function getLang($a){
	return isset($this->lang[$a])?$this->lang[$a]:$a;
}

public function regClient(){
	$this->modx->regClientCSS($this->base_url.'assets/snippets/easyForm/css/easyForm.css');
	if(isset($this->params['ajaxMode'])&&$this->params['ajaxMode']!='0'){
		$src=$this->base_url.'assets/snippets/easyForm/js/easyForm.js';
		$this->modx->regClientStartupScript($src);
		echo '<script type="text/javascript">var furl="'.$this->base_url.'";</script>';
	};
}

public function getFormInfo(){
	$info=array();
	if(isset($this->id)){
		$info=$this->modx->db->getRow($this->modx->db->query("SELECT name,email FROM ".$this->forms_table." WHERE id=".$this->id." LIMIT 0,1"));
	}
	return $info;
}

public function checkCapcha(){
	return (isset($this->params['vericode'])&&$this->params['vericode']!='0');
}


public function makeTpl($config='default'){

	$outer='';
	$fields='';
	$capcha=$this->checkCapcha();
	
	if($this->modx->db->getRecordCount($this->modx->db->query("SELECT * FROM ".$this->forms_table." WHERE id=".$this->id." LIMIT 0,1"))==1)
	{
		//подключаем файл конфигурации с шаблонами вывода формы
		if(is_file(dirname(__FILE__).'/config/config.'.$config.'.php')){
			include(dirname(__FILE__).'/config/config.'.$config.'.php');
		}
		else{
			include(dirname(__FILE__).'/config/config.default.php');
		}
		
		$forma=$this->modx->db->query("SELECT * FROM ".$this->fields_table." WHERE parent=".$this->id." ORDER BY sort ASC");
		$forma_info=$this->modx->db->getRow($this->modx->db->query("SELECT * FROM ".$this->forms_table." WHERE id=".$this->id." LIMIT 0,1"));
		
		if($this->modx->db->getRecordCount($forma)>0){
			$form=array();
			while($row=$this->modx->db->getRow($forma)){
				$form[$row['id']]['title']=$this->getLang($row['title']);
				$form[$row['id']]['type']=$row['type'];
				$form[$row['id']]['value']=$row['value'];
				$form[$row['id']]['required']=$row['required'];
			}
			
			$fields='';
			
			foreach($form as $k=>$v){
				$req=$v['required']==1?1:0;
				$type=$v['type']==3?'email':'string';
				
				switch($v['type']){
				  case 2:
					$field="<textarea name='param".$k."' class='f_txtarea' eform='".$v['title'].":".$type.":".$req."'></textarea>";
					break;
				
				  case 5:
					$opts=explode("\n",$v['value']);
					$opt='';
					foreach($opts as $k1=>$v1){
						$v1=trim($v1);
						$arr=explode("==",$v1);
						$key=$arr[0];
						$val=isset($arr[1])&&$arr[1]!=''?$arr[1]:$arr[0];
						$opt.="<option value='".$this->getLang($key)."'>".$this->getLang($val)."</option>";
					}
					$field="<div class='selector'><select name='param".$k."' class='f_selector' eform='".$v['title']."::".$req."'>".$opt."</select></div>";
					break;
				
				  case 6:
					$opts=explode("\n",$v['value']);
					$opt='';
					foreach($opts as $k1=>$v1){
						$v1=trim($v1);
						$arr=explode("==",$v1);
						$key=$arr[0];
						$val=isset($arr[1])&&$arr[1]!=''?$arr[1]:$arr[0];
						$opt.="<label>".$this->getLang($val)." <input type='radio' name='param".$k."' value='".$this->getLang($key)."' ".($k1==0?"eform='".$v['title']."::".$req."'":"")."></label>";
					}
					$field="<div class='radio'>".$opt."</div>";
					break;
					
				  case 7:
					$opts=explode("\n",$v['value']);
					$opt='';
					foreach($opts as $k1=>$v1){
						$v1=trim($v1);
						$arr=explode("==",$v1);
						$key=$arr[0];
						$val=isset($arr[1])&&$arr[1]!=''?$arr[1]:$arr[0];
						$opt.="<label>".$this->getLang($val)." <input type='checkbox' name='param".$k."[]' value='".$this->getLang($key)."' ".($k1==0?"eform='".$v['title']."::".$req."'":"")."></label>";
					}
					$field="<div class='checkbox'>".$opt."</div>";
					break;
					
				  case 8:
					$field="<input type='file' name='param".$k."' class='f_file' eform='".$v['title'].":".$type.":".$req."'>";
					break;
					
				  case 9:
					$opts=explode("\n",$v['value']);
					$opt='';
					foreach($opts as $k1=>$v1){
						$v1=trim($v1);
						$arr=explode("==",$v1);
						$key=$arr[0];
						$val=isset($arr[1])&&$arr[1]!=''?$arr[1]:$arr[0];
						$opt.="<option value='".$this->getLang($key)."'>".$this->getLang($val)."</option>";
					}
					$field="<div class='multiselector'><select name='param".$k."[]' class='f_multiselector' multiple='multiple' size='5' eform='".$v['title']."::".$req."'>".$opt."</select></div>";
					break;
				
				  case 10:
					$field="<input type='hidden' name='param".$k."' value='' class='f_hidden' eform='".$v['title'].":".$type.":".$req."'>";
					break;
					
				  default:
					$field="<input type='text' name='param".$k."' value='' class='f_txt' eform='".$v['title'].":".$type.":".$req."'>";
					break;
				}
	
				$req_text=($req==1?'<span class="red">*</span>':'');
				$fields.=$this->eParseTpl(
					array('[+num+]','[+title+]','[+req_text+]','[+field+]'),
					array($k,$v['title'],$req_text,$field),
					$rowTpl
				);
			}
			if($capcha){
				$fields.=$this->eParseTpl(
					array('[+id+]','[+capcha_dir+]'),
					array($this->id,MODX_BASE_URL.MGR_DIR),
					$capchaTpl
				);
			}
		
			$outer=$this->eParseTpl(
				array('[+form_name+]','[form_description]','[+id+]','[+fields+]'),
				array($this->getLang($forma_info['name']),$this->getLang($forma_info['title']),$this->id,$fields),
				$outerTpl
			);
		}
	return $outer;
	}
}

public function makeReportTpl(){
	$f='';
	if($this->modx->db->getRecordCount($this->modx->db->query("SELECT * FROM ".$this->forms_table." WHERE id=".$this->id." LIMIT 0,1"))==1)
	{
		$forma=$this->modx->db->query("SELECT * FROM ".$this->fields_table." WHERE parent=".$this->id." ORDER BY sort ASC");
		$forma_info=$this->modx->db->getRow($this->modx->db->query("SELECT * FROM ".$this->forms_table." WHERE id=".$this->id." LIMIT 0,1"));
		if($this->modx->db->getRecordCount($forma)>0){
			$form=array();
			while($row=$this->modx->db->getRow($forma)){
				$form[$row['id']]['title']=$row['title'];
				$form[$row['id']]['type']=$row['type'];
				$form[$row['id']]['value']=$row['value'];
				$form[$row['id']]['required']=$row['required'];
			}
			$f.='<table>';
			foreach($form as $k=>$v){
				$f.='<tr><td style="padding-right:10px;"><b>'.$v['title'].':</b></td><td>[+param'.$k.'+]</td></tr>';
			}
			$f.='</table>';
		}
	return $f;
	}
}

public function prepareRun(){
	$this->params['tpl']=$this->makeTpl();
	$this->params['report']=$this->makeReportTpl();
	$formInfo=$this->getFormInfo();
	$this->params['to']=$formInfo['email'];
	$this->params['subject']='Обратная связь: '.$formInfo['name'];
	return $this->params;
}

public function Run(){
	if($this->id!=0){
		$this->regClient();
		$this->prepareRun();
		if($this->params['tpl']!=''&&$this->params['report']!=''){
			return $this->modx->runSnippet("eForm",$this->params);
		}
		else{
			return '<p>Не созданы необходимые компоненты для показа формы. Проверьте параметр &formid</p>';
		}
	}
}


}//end class
?>