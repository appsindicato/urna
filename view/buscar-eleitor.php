<!--
* View para buscar e selecionar eleitores
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Selecionar Eleitor</h3>
<form id="formSelecionarEleitor" action="buscar-eleitor" method="post" autocomplete="off">
	<?php $view->escreveErros(); ?>
	<input type="hidden" name="eleitorSelecionado" id="eleitorSelecionado"/>
	<div class="form-group">
		<label for="search">Documento (RG) ou Nome do Eleitor</label>
		<input type="text" name="search" name="search" placeholder="Preencha com o RG ou o Nome" autocomplete="off" autofocus>
	</div>
	<div class="btn-group">
		<button type="submit" class="success">Pesquisar</button>
		<a class="button info" href="iniciar-voto">VOLTAR</a>
	</div>
</form>
<?php 
if(count($view->eleitores)){ ?>
	<table class="tabela-eleitores">
		<thead>
			<tr>
				<th>Rg</th>
				<th width="60%">Nome</th>
				<th>Separado</th>
				<th>Situação</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
<?php foreach($view->eleitores AS $e){?>
		<tr>
			<td><?php echo $e->rg;?></td>
			<td><?php echo $e->nome;?></td>
			<td><?php echo !$e->flag_transito ? "Não" : "Sim";?></td>
			<td><?php echo $e->flag_situacao ? "Liberado" : "Pendente";?></td>
			<td>
				<?php if($e->flag_transito || $e->id_nucleo==$view->urna->id_nucleo){ ?>
				<button onclick="selecionarEleitor('<?php echo $e->id;?>');" type="button" class="warn btn-small">Selecionar</button>
				<?php } else { ?>
				Eleitor pertence ao núcleo <?php echo $e->nome_nucleo; ?>
				<?php } ?>
			</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>

<script>
	function selecionarEleitor(id){
		var form = document.getElementById("formSelecionarEleitor");
		var inp = document.getElementById("eleitorSelecionado");
		inp.value = id;
		form.submit();
	}
</script>