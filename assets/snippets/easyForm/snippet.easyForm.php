<?php
/* author webber   web-ber12@yandex.ru */
// version 0.1
// визуальное создание и редактирование простых форм на основе сниппета eForm
// создать сниппет с названием easyForm и кодом 
// return require_once MODX_BASE_PATH."assets/snippets/easyForm/snippet.easyForm.php";
// пример вызова на странице
// [!easyForm? &formid=`f1`!] - где цифра после префикса f - это id формы из модуля
// остальные параметры задаются аналогично eForm
// &tpl, &reportTpl и &to задавать не нужно - они формируются автоматом из того, что задано в модуле easyForm

if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}


$out='';
$eid=str_replace('f','',$params['formid']);

if((int)$eid!=0){
	include_once("easyForm.class.php");
	$eF=new easyForm($modx,$params,$eid);
	$out=$eF->Run();
}
else{
	$out.='<p>Неверно задан id формы. Проверьте параметр &formid</p>';
}

return $out;
?>