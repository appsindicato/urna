<?php
/**
* Responsável por gerar as istancias das chapas
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
*/
class Chapa{
	/**
	 * Ao costruir a chapa, busca inicialmente a urna configurada e adiciona os dados da urna no objeto
	 */
	public function __construct(){
		$this->db = new PDO('sqlite:baseUrna.sqlite');
		$this->urna = json_decode(file_get_contents("config.ini"));
		$q = $this->db->prepare('SELECT * FROM client_urna WHERE id=:id');
		$q->bindParam(":id",$this->urna->urna);
		$q->execute();
		$this->urna = $q->fetchAll(PDO::FETCH_OBJ);

		if(count($this->urna)==0){
			echo json_encode("Urna invalida"); die();
		}
		else{
			$this->urna=$this->urna[0];
		}
	}
	/**
	 * Busca a chapa conforme o tipo
	 */
	public function find($tipoChapa,$numero){
		switch($tipoChapa){
			case "estadual":
				$id_tipo = 9;
			break;
			case "regional":
				$id_tipo = 7;
			break;
			case "fiscal":
				$id_tipo = 5;
			break;
			case "municipal":
				$id_tipo = 6;
			break;
		}
		$id_nucleo = $this->urna->id_nucleo;
		$id_cidade = $this->urna->id_cidade;
		if($id_tipo==6){
			$q = $this->db->prepare("SELECT * FROM client_chapa WHERE id_tipo=:idtipo AND numero_chapa=:id AND id_cidade=:idcidade");
			$q->bindValue(":idcidade",$id_cidade);
		}
		elseif($id_tipo==7){
			$q = $this->db->prepare("SELECT * FROM client_chapa WHERE id_tipo=:idtipo AND numero_chapa=:id AND id_nucleo=:idnucleo");
			$q->bindValue(":idnucleo",$id_nucleo);
		}
		else{
			$q = $this->db->prepare("SELECT * FROM client_chapa WHERE id_tipo=:idtipo AND numero_chapa=:id");
		}
		$q->bindValue(":idtipo",$id_tipo);
		$q->bindValue(":id",$numero);
		$q->execute();
		return $q->fetchAll(PDO::FETCH_OBJ);
	}
}