<!--
* View com os campos das chaves para finalizar a urna
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Finalizar Urna</h3>
<form action="finalizar-urna" method="post" autocomplete="off">
	<?php $view->escreveErros(); ?>
	<div class="form-group">
		<label for="senha1">Senha 1</label>
		<input type="password" name="senha1" name="senha1" placeholder="Senha 1" required  autocomplete="off" autofocus>
	</div>
	<div class="form-group">
		<label for="senha2">Senha 2</label>
		<input type="password" name="senha2" name="senha2" placeholder="Senha 2" required  autocomplete="off">
	</div>
	<div class="btn-group">
		<button class="error" type="submit">Finalizar Urna</button>
	</div>
</form>
<a class="button info" href="iniciar-voto">VOLTAR</a>