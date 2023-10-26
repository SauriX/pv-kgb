$(function() {
    document.addEventListener('keyup', logkey);
});

function logkey(e) {

    if(e.keyCode==13){
        Login();
    }
}


function Login(){
    $('#error').hide('Fast');
	$('.ocultar').hide();
	$('#loader').show();
	var usuario = $('#usuario').val();
    var password = $('#password').val();
 	$.post('iniciar_sesion','usuario='+usuario+'&password='+password,function(data){
        if(data.status){
			window.location = 'venta';
		}else{
            $('.ocultar').show();
			$('#usuario').focus();
			$('#error').html(data.mensaje);
			$('#error').show('Fast');
			$('#loader').hide();
		}
	});
}