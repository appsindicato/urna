<!--
* View para o mesário digitar a senha e iniciar um novo voto
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
-->
<h3>Iniciar Voto</h3>
<form action="iniciar-voto" method="post" autocomplete="off">
	<?php $view->escreveErros(); ?>
	<div class="form-group">
		<label for="senha">Senha </label>
		<input type="password" name="senha" name="senha" placeholder="Senha " required autocomplete="off" autofocus>
	</div>
	<div class="btn-group">
		<button type="submit" class="success">Iniciar Voto</button>
	</div>
</form>
<a href="finalizar-urna" class="btn-finalizar button">Finalizar Urna</a>