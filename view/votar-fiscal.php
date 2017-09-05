<!--
* View para entrada dos numeros da chapa do conselho fiscal
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Votar Chapa Conselho Fiscal</h3>
<h1 id="nomeChapa"></h1>
<form  id="formVotar" action="votar-fiscal" method="post" onsubmit="votar();return false;" autocomplete="off">
	<h4>Digite o número da chapa do Conselho Fiscal</h4>
	<div class="form-group">
		<input type="text" id="nr1" name="nr1" required autocomplete="off" maxlength="1" autofocus onkeyup="carregarChapa(event); return nextInput(event)">
	</div>

	<div class="btn-group">
		<button type="submit" class="success">CONFIRMA</button>
	</div>
</form>

<script>
	function carregarChapa(e){
		checarBranco(e);
		var n = document.getElementById("nr1").value;
		if(n!=""){
			//AJAX p/ carregar a chapa regional
			ajax("ajax.php?chapa=fiscal&num="+n,
				function(response){ //success
				  	if(response && response !=""){
				  		var r = JSON.parse(response);
					 	document.getElementById("nomeChapa").innerHTML = r.nome;
					   document.getElementById("nomeChapa").classList.remove('helper');
					   document.getElementById("nomeChapa").classList.remove('helper-danger');
				  	}
				  	else{
					   document.getElementById("nr1").value = '';
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