<?php
/**
* Responsável por fazer as chamadas ajax da urna
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
*/
header('Content-Type: text/html; charset=utf-8');
/**
 * Adiciona a biblioteca da Chapa
 */
include ("library/Chapa.php");
/**
 * Busca no objeto Chapa a istancia conforme o número
 */
if(isset($_GET["chapa"])){
	$c = new Chapa();
	$chapas = $c->find($_GET["chapa"],$_GET["num"]);
	if(count($chapas)>0){
		$chapa = $chapas[0];
		echo json_encode($chapa);
	}
}
