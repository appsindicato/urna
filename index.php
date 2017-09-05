<?php
/**
* Urna eletrônica, controlador inicial. Adiciona a biblioteca, instancia a urna e chama a view
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
*/
header('Content-Type: text/html; charset=utf-8');
/**
 * Adiciona biblioteca
 */
include ("library/Chapa.php");
include ("library/View.php");
include ("library/Crypt.php");
session_start();
/**
 * Inicia o banco de dados
 */
$db = new PDO('sqlite:baseUrna.sqlite');

/**
 * A partir do arquivo de configuração inicial da urna, verifica no banco os dados e istancia a urna
 */
$urna = json_decode(file_get_contents("config.ini"));
$q = $db->prepare('SELECT * FROM client_urna WHERE id=:id');
$q->bindParam(":id",$urna->urna);
$q->execute();
$urna = $q->fetchAll(PDO::FETCH_OBJ);

if(count($urna)==0){
	echo "Urna invalida"; die();
}

/**
 * Busca na url qual a página a ser executada e chama a respectiva ação na View
 */
$view = new View(str_replace("#!","",$_GET["_url"]));
$view->db = $db;
$view->urna = $urna[0];
$view->show();

/**
 * Limpa a memória
 */
$db = null;
$view->db = null;
