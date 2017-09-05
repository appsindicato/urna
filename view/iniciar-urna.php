<!--
* View com os campos das chaves para iniciar a urna
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Iniciar Urna</h3>
<form action="iniciar-urna" method="post" autocomplete="off">
	<?php $view->escreveErros(); ?>
	<p>Urna #<?php echo $view->urna->id;?> ainda não iniciada</p>
	<h2> 0 votos computados</h2><br/>
	<div class="form-group">
		<label for="senha1">Chave 1</label>
		<input type="password" name="senha1" name="senha1" placeholder="Chave 1" required autocomplete="off" autofocus>
	</div>
	<div class="form-group">
		<label for="senha2">Chave 2</label>
		<input type="password" name="senha2" name="senha2" placeholder="Chave 2" required autocomplete="off">
	</div>
	<div class="btn-group">
		<button class="success" type="submit">Iniciar Urna</button>
	</div>
</form>