<?php
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

$outerTpl='
	<div class="f_zagol">[+form_name+]</div>
	<div class="f_description">[form_description]</div>
	<div class="f_form f_form[+id+]" id="f_form[+id+]">
		<div class="validation_message">[+validationmessage+]</div>
		<form id="f[+id+]" class="easyForm" action="[~[*id*]~]" method="post">		

			[+fields+]
			
			<div class="f_sendbutton"><input type="submit" value="Отправить"</div>
		</form>
	</div>
';			
$rowTpl='
	<div class="f_row f_row[+num+]">
		<div class="f_title">[+title+] [+req_text+]</div>
		<div class="field">
				[+field+]
			</div>
		</div>
';
$capchaTpl='
	<div class="f_capcha">
		<div class="f_title">Введите код с картинки: </div>
		<div class="f_field"><input type="text" class="f_ver" name="vericode" /><div class="f_image_capcha"><img class="feed" id="capcha[+id+]" src="[+verimageurl+]" alt="Введите код" /></div><div class="f_renew_capcha"><a href="javascript:;" onclick="document.getElementById(\'capcha[+id+]\').src=\'[+capcha_dir+]/includes/veriword.php?rand=\'+Math.random();">обновить картинку</a></div></div>
	</div>
';
?>