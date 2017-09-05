<!--
* View para confirmar os dados do eleitor em separado
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3 class="error">Confirmação de Eleitor em Separado</h3>
<form id="formEleitor" action="confirmar-eleitor" autocomplete="off" onsubmit="return false;" method="post">
	<input type="hidden" name="btn" id="btn"/>
	<h4><?php echo $view->eleitor->nome; ?></h4>
	<ul>
		<li><b>RG</b>: <?php echo $view->eleitor->rg; ?></li>
		<li><b>Trânsito</b>: <?php echo !$view->eleitor->flag_transito ? "Liberado" : "Separado"; ?></li>
		<li><b>Situação</b>: <?php echo $view->eleitor->flag_situacao ? "Liberado" : "Pendente"; ?></li>
		<li><b>Local de Votação</b>: <?php echo $view->eleitor->nome_cidade." - ".$view->eleitor->nome_nucleo; ?></li>
		<li><b>Local desta Urna</b>: <?php echo $view->urna->nome_cidade." - ".$view->urna->nome_nucleo; ?></li>
	</ul>
	<div class=" ">*AUTORIZO O VOTO PARA CASOS JÁ PREESENTES NO ART. 60</div><BR>
	<div class="btn-group">
		<button type="button" class="success" onclick="confirmarEleitor();">AUTORIZAR</button>
		<button type="button" class="info" onclick="cancelarEleitor(); return false;">PESQUISAR OUTRO</button>
	</div>
</form>

<script>
	function confirmarEleitor(){
		var form = document.getElementById("formEleitor");
		document.getElementById("btn").value = 'confirmar';
		form.submit();
	}
	function cancelarEleitor(){
		var form = document.getElementById("formEleitor");
		document.getElementById("btn").value = 'voltar';
		form.submit();
	}
</script>