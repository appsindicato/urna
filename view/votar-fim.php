<!--
* View que confirma o voto e mostra o codigo de finalizacao
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Conclusão de votos</h3>
<h2>Parabéns, sua votação foi concluída</h2>
<form id="formFim" action="iniciar-voto" autocomplete="off">
	<p>
	</p>
	<p>
		Essa tela se encerrará em <b><span id="timer">30 segundos</span></b>
	</p>
	<h5>Código de confirmação de sua votação</h5>
	<div class="codigo-confirmacao">
		<?php echo $_SESSION["eleitorSelecionado"]->finish_code;?>
	</div>
	<div class="btn-group">
		<button type="submit"  class="error">FINALIZAR</button>
	</div>
</form>
<script>
	new Audio('audio/end.wav').play();

	var time = 30;
	setInterval(function(){
		time = time-1;
		if(time<=0){
			document.getElementById("formFim").submit();
		}
		else if(time==1){
			document.getElementById("timer").innerHTML=time+" segundo";
		}
		else{
			document.getElementById("timer").innerHTML=time+" segundos";
		}
	},1000);
</script>