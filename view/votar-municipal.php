<!--
* View para entrada dos numeros da chapa municipal
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Votar Chapa Representante Municipal</h3>
<h1 id="nomeChapa"></h1>
<form  id="formVotar" action="votar-municipal" method="post" onsubmit="votar();return false;" autocomplete="off">
	<h4>Digite o número do representante</h4>
	<div class="form-group">
		<input type="text" id="nr1" name="nr1" required autocomplete="off" maxlength="1"  onkeyup="carregarChapa(event); return nextInput(event)" autofocus>
		<input type="text" id="nr2" name="nr2" required autocomplete="off" maxlength="1"  onkeyup="carregarChapa(event); return nextInput(event)">
		<input type="text" id="nr3" name="nr3" required autocomplete="off" maxlength="1"  onkeyup="carregarChapa(event); return nextInput(event)">
	</div>
	<div class="btn-group">
		<button type="submit" class="success">CONFIRMA</button>
	</div>
</form>
<script>
	function carregarChapa(e){
		checarBranco(e);
		var n = document.getElementById("nr1").value;
		var n2 = document.getElementById("nr2").value;
		var n3 = document.getElementById("nr3").value;
		if(n!="" && n2!="" && n3!=""){
			//AJAX p/ carregar a chapa regional
			ajax("ajax.php?chapa=municipal&num="+n+''+n2+''+n3,
				function(response){ //success
				  	if(response && response !=""){
				  		var r = JSON.parse(response);
					 	document.getElementById("nomeChapa").innerHTML = r.nome;
					   document.getElementById("nomeChapa").classList.remove('helper');
					   document.getElementById("nomeChapa").classList.remove('helper-danger');
				  	}
				  	else{
					   document.getElementById("nr1").value = '';
					   document.getElementById("nr2").value = '';
					   document.getElementById("nr3").value = '';
					   document.getElementById("nomeChapa").innerHTML = 'Chapa não identificada';
					   document.getElementById("nomeChapa").classList.add('helper');
					   document.getElementById("nomeChapa").classList.add('helper-danger');
					   document.getElementById("nr1").focus();
				  	}
				});
		}
		else{
		    document.getElementById("nomeChapa").classList.remove('helper');
		    document.getElementById("nomeChapa").classList.remove('helper-danger');
			document.getElementById("nomeChapa").innerHTML = "";
		}
	}
	function votar(){
		// if(confirm("Confirmar voto na chapa "+document.getElementById("nomeChapa").innerHTML+"?")){
			var form = document.getElementById("formVotar");
			form.submit();
		// }
	}
</script>