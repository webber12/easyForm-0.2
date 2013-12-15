//<?php
/**
 * easyForm
 * 
 * display easyForm on front
 * 
 * @author	    webber (web-ber12@yandex.ru)
 * @category	snippet
 * @version 	0.2
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@guid easyForm
 * @internal	@modx_category Forms
 * @internal    @installset base, sample
 */
 
// пример совмещения с evoBabel для мультиязычности
// $eF->lang=$_SESSION['perevod']; перед строкой $out=$eF->Run();
// не забудьте создать соответствующие переводы в модуля управления переводами

 
$out='';
$eid=str_replace('f','',$params['formid']);

if((int)$eid!=0){
	include_once(MODX_BASE_PATH."assets/snippets/easyForm/easyForm.class.php");
	$eF=new easyForm($modx,$params,$eid);
	$out=$eF->Run();
}
else{
	$out.='<p>Неверно задан id формы. Проверьте параметр &formid</p>';
}

return $out;
