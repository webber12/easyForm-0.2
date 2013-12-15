$(document).ready(function(){

$(".validation_message").css({'display':'none'});

function showInfo(msg){
	var inf=$("<div class='easyForm_errors'>"+msg+"</div>");
	inf.appendTo("body").fadeIn("fast",function(){
		setTimeout(function(){inf.fadeOut("slow")}, 3300,function(){})
	});
}

$(document).on("submit","form.easyForm",function(event){
	event.preventDefault();
	var fd=$(this).serialize();
	var url=furl+$(this).attr("action");
	var parent_id=$(this).parent("div").attr("id");
	$.ajax({
		url: url,                                   
		data: fd,
		type: "POST",   
		beforeSend:function(){
			$("div#"+parent_id).css({'opacity':'.8'});
		},                   
		success: function(msg){
			var response=$(msg).find("div#"+parent_id).html();
			var error=$(msg).find("div.validation_message .errors").html();
			if(error){
				showInfo(error);
				$("div#"+parent_id).html(response);
				$("div#"+parent_id).find("div.validation_message").css({'display':'none'});
				$("div#"+parent_id).css({'opacity':'1'});
				$("div#"+parent_id+" input[type='checkbox'].required").each(function(){
					$(this).parent().parent().addClass("required");
				})
				$("div#"+parent_id+" input[type='radio'].required").each(function(){
					$(this).parent().parent().addClass("required");
				})
			}
			else{
				$("div#"+parent_id).css({'opacity':'1'});
				$("div#"+parent_id).html('<h3>Спасибо!</h3><p>Ваша информация успешно отправлена.</p>');
			}
		}
	});
	
})

$(document).on("keyup change","input.required,textarea.required,input.invalid,textarea.invalid",function(e){
	$(this).removeClass("required").removeClass("invalid");
	$(this).parent().parent().removeClass("required");
});






})