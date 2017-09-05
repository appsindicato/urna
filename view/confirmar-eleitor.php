<!--
* View para confirmar os dados do eleitor selecionado
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Confirmação</h3>
<form id="formEleitor"  method="post" action="confirmar-eleitor" onsubmit="return false;" autocomplete="off">
	<input type="hidden" name="btn" id="btn"/>
	<h4><?php echo $view->eleitor->nome; ?></h4>
	<ul>
		<li><b>RG</b>: <?php echo $view->eleitor->rg; ?></li>
		<li><b>Trânsito</b>: <?php echo !$view->eleitor->flag_transito ? "Liberado" : "Separado"; ?></li>
		<li><b>Situação</b>: <?php echo $view->eleitor->flag_situacao ? "Liberado" : "Pendente"; ?></li>
		<li><b>Local de Votação</b>: <?php echo $view->eleitor->nome_cidade." - ".$view->eleitor->nome_nucleo; ?></li>
		<li><b>Local desta Urna</b>: <?php echo $view->urna->nome_cidade." - ".$view->urna->nome_nucleo; ?></li>
	</ul>
	<div class="btn-group">
		<button type="submit" class="success" onclick="confirmarEleitor(); return false;">Sou Eu</button>
		<button type="submit" class="error" onclick="cancelarEleitor(); return false;">Não Sou Eu</button>
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