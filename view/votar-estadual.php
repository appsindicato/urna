<!--
* View para entrada dos numeros da chapa estadual
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Votar Chapa Estadual</h3>
<h1 id="nomeChapa"></h1>
<form  id="formVotar" action="votar-estadual" method="post" onsubmit="votar();return false;" autocomplete="off">
	<h4>Digite o número da chapa Estadual</h4>

	<div class="form-group">
		<input type="text" id="nr1" name="nr1" required autocomplete="off" maxlength="1" autofocus onkeyup="carregarChapa(event);">
	</div>

	<div class="btn-group">
		<button type="submit" class="success" >CONFIRMA</button>
	</div>
</form>

<script>
	function carregarChapa(e){
		checarBranco(e);
		var n = document.getElementById("nr1").value;
		if(n!=""){
			//AJAX p/ carregar a chapa estadual
			ajax("ajax.php?chapa=estadual&num="+n,
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