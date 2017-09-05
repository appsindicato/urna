<?php
/**
* Classe principal da urna, define quais as regras que devem ser executadas, qual método, os tramites e os resultados
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
*/

//TRAMITES BÁSICO
// 1. '/' -> iniciar urna
// 2. 'iniciar-voto' -> mesário digita a senha para liberar voto
// 3. 'buscar-eleitor' -> mesário digita o nome ou o rg do eleitor
// 4. 'selecionar-eleitor' -> mesário seleciona nos resultados o eleitor que vai fotar
// 5. -> ao selecionar eleitor, verifica se é um eleitor normal ou em separado
// 5.1 'confirmar-eleitor' -> eleitor confirma se é ele mesmo e assume a urna
// 5.2 'confirmar-eleitor-separado' -> eleitor confirma se é ele, mesário libera voto em separado
// 6. 'votar-estadual' -> eleitor vota estadual
// 7. 'votar-regional' -> eleitor vota regional
// 8. 'votar-fiscal' -> eleitor (não separado) vota cons fiscal
// 9. 'votar-municipal' -> eleitor (não separado) vota no representante municipal
// 10. 'votar-fim' -> eleitor recebe confirmação de voto, anota o código e devolve computador
// 11. volta para '2'
//////////////////////
class View {
	private $url;
	private $method;
	private $erros;
	public $eleitores=array();
	public $urna;
	public $db;
	private $crypt;
	/**
	 * Define a partir da url qual método deve ser executado
	 * A partir da situacao da urna define se deve ser inciada, seguir para o metodo ou se esta finalziada
	 */
	public function __construct($url){
		$url = explode("/",$url);
		$url = $url[1];

		$this->crypt = new Crypt();
		$status = $this->crypt->getStatus();
		if(!$status){
			$url="iniciar-urna";
			$this->crypt->writeStatus("init");
		}
		else{
			if($status=="init"){
				$url="iniciar-urna";
			}
			elseif($status=="end"){
				$url="urna-finalizada";
			}
			else{
				$url = $url ? $url : "iniciar-voto";
			}
		}
		$this->url = $url;
		$arrMethod = explode("-",$url);
		$method = $arrMethod[0];
		for($i=1;$i<count($arrMethod);$i++){
			$method .= ucwords($arrMethod[$i]);
		}
		$this->method = $method;
		$this->erros = array();
		if(isset($_SESSION["eleitorSelecionado"])){
			$this->eleitor = $_SESSION["eleitorSelecionado"];
		}
	}

	/**
	 * Caso exista algume erro é mostrado 
	 */
	public function escreveErros(){
		foreach ($this->erros as $key => $value) {
			echo '<div class="helper helper-danger">'.$value.'</div>';
		}
	}

	/**
	 * A partir das chaves digitadas inicia a urna
	 */
	public function iniciarUrna(){
		$this->url = 'iniciar-urna';
		if(count($_POST)){
			$s1 = $_POST["senha1"];
			$s2 = $_POST["senha2"];
			$senhas = [$this->urna->senha_mesario_abertura,$this->urna->senha_diretoria_abertura];
			if(in_array(sha1($s1),$senhas) && in_array(sha1($s2),$senhas)){
				$urna = ["urna"=>$this->urna->id];
				$this->crypt->writeUrnaInicio(json_encode($urna));

				$this->crypt->writeStatus("started");

				$this->url = 'iniciar-voto';
			}
			else{
				$this->erros[] = "Senha 1 e Senha 2 não conferem com a base de dados";
			}
		}
		return $this->url;
	}

	/**
	 * Abre a tela de iniciar voto.
	 * Recebe a senha do mesário vai para a selecao de eleitor
	 */
	public function iniciarVoto(){
		$this->url = 'iniciar-voto';
		if(count($_POST)){
			$s1 = $_POST["senha"];
			if(sha1($s1)==$this->urna->senha_mesario_operacao){
				$this->url = 'buscar-eleitor';
			}
			else{
				$this->erros[] = "Senha não confere com a base de dados";
			}
		}
		return $this->url;
	}

	/**
	 * Abre a tela de selecao do eleitor.
	 * Busca na base de dados os eleitores conforme entrada
	 */
	public function buscarEleitor(){
		$this->url = 'buscar-eleitor';
		$_SESSION["eleitorSelecionado"] = null;
		if(count($_POST)){
			/**
			 * Caso já tenha sido listado o eleitor e tenha sido selecionado um é feita uma confirmação no banco de dados e retornado os dados do eleitor.
			 * Abre a tela de confirmação do eleitor
			 */
			if(isset($_POST["eleitorSelecionado"])&&$_POST["eleitorSelecionado"]>0){
				$q = $this->db->prepare("SELECT * FROM client_eleitor WHERE id=:id ");
				$q->bindValue(":id",$_POST["eleitorSelecionado"]);
				$q->execute();
				$this->eleitores = $q->fetchAll(PDO::FETCH_OBJ);
				if(count($this->eleitores)>0){
					$this->eleitor = $this->eleitores[0];
					if(!in_array($this->crypt->encryptVoter(json_encode(array($this->eleitor->id=>array($this->eleitor->finish_code,$this->eleitor->flag_transito)))),$this->crypt->getAllVoters())){
						if($this->eleitor->flag_transito || !$this->eleitor->flag_situacao){
							$this->url = "confirmar-eleitor-separado";
						}
						else{
							$this->url = "confirmar-eleitor";
						}
						$_SESSION["eleitorSelecionado"] = $this->eleitor;
					}
					else{
						$this->erros[] = "Eleitor já votou nessa eleição ";
					}
				}
			}
			else{
				/**
				 * Caso tenha sido buscado um eleitor, traz os resultados
				 */
				if(isset($_POST["search"]) && $_POST["search"]!=""){
					$q = $this->db->prepare("SELECT * FROM client_eleitor WHERE (rg=:rg OR nome LIKE :nomebind) AND (id_nucleo=:idnucleo OR flag_transito=1) LIMIT 5 ");
					$search = strtoupper($_POST["search"]);
					$q->bindValue(":rg",$search);
					$q->bindValue(":nomebind","%".$search."%");
					$q->bindValue(":idnucleo",$this->urna->id_nucleo);
					$q->execute();
					$this->eleitores = $q->fetchAll(PDO::FETCH_OBJ);
					if(count($this->eleitores)>4){
						$this->erros[] = "Foram encontrados 5 ou mais eleitores nessa pesquisa, por favor refine a busca.";
					}
					else if(count($this->eleitores)==0){
						$_SESSION["eleitorSelecionado"] = null;
						$this->erros[] = "Nenhum Eleitor encontrado ";
					}	
				}
				else{
						$_SESSION["eleitorSelecionado"] = null;
						$this->erros[] = "Preencha o campo de pesquisa ";
				}
			}
		}
		return $this->url;
	}

	/**
	 * Confirma os dados do eleitor e encaminha para a tela de votacao estadual
	 */
	public function confirmarEleitor(){
		$this->url = 'confirmar-eleitor';
		if(isset($_POST["btn"])){
			if($_POST["btn"]=="confirmar"){
				$this->url = "votar-estadual";
			}
			else{
				$this->url = "iniciar-voto";
			}
		}
	}

	/**
	 * Confirma o voto estadual e caso o eleitor esteja em transito direciona para o voto no conselho fiscal, se não direciona para o regional
	 */
	public function votarEstadual(){
		$this->url = 'votar-estadual';
		if(count($_POST)){
			$nr1 = $_POST["nr1"];
			$c = new Chapa();
			$chapas = $c->find('estadual',$nr1);
			if(count($chapas)>0){
				$chapa = $chapas[0]->id;
				$_SESSION["chapaEstadual"] = $chapa;
				if($_SESSION["eleitorSelecionado"]->flag_transito){
					$this->url = 'votar-fiscal';
				}
				else{
					$this->url = 'votar-regional';
				}
			}
		}
		return $this->url;
	}

	/**
	 * Confirma o voto regional e encaminha para a tela do conselho fiscal
	 */
	public function votarRegional(){
		$this->url = 'votar-regional';
		if(count($_POST)){
			$nr1 = $_POST["nr1"];
			$nr2 = $_POST["nr2"];
			$nr = $nr1.$nr2;
			$c = new Chapa();
			$chapas = $c->find('regional',$nr);
			if(count($chapas)>0){
				$chapa = $chapas[0]->id;
				$_SESSION["chapaRegional"] = $chapa;
				$this->url = 'votar-fiscal';
			}
		}
		return $this->url;
	}

	/**
	 * Confirma o voto do conselho fiscal e caso o eleitor esteja em transido encaminha para a tela de finalizacao do voto, se nao verifica se o eleitor esta na mesma cidade em que ele pode votar e encaminha para a tela de voto municipal
	 */
	public function votarFiscal(){
		$this->url = 'votar-fiscal';
		if(count($_POST)){
			$nr1 = $_POST["nr1"];
			$nr = $nr1;
			$c = new Chapa();
			$chapas = $c->find('fiscal',$nr);
			if(count($chapas)>0){
				$chapa = $chapas[0]->id;
				$_SESSION["chapaFiscal"] = $chapa;
				if($_SESSION["eleitorSelecionado"]->flag_transito){
					$this->finalizaVotacao();
				}
				else{
					/**
					 * eleitor só pode votar municipal se ele estiver votando na mesma cidade do seu cadastro
					 */
					if($_SESSION['eleitorSelecionado']->id_cidade==$this->urna->id_cidade){
						$this->url = 'votar-municipal';
					}
					else{
						$this->finalizaVotacao();
					}
				}
			}
		}
		return $this->url;
	}

	/**
	 * Confirma o voto municipal e envia para a finalizacao do voto
	 */
	public function votarMunicipal(){
		$this->url = 'votar-municipal';
		if(count($_POST)){
			$nr1 = $_POST["nr1"];
			$nr2 = $_POST["nr2"];
			$nr3 = $_POST["nr3"];
			$nr = $nr1.$nr2.$nr3;
			$c = new Chapa();
			$chapas = $c->find('municipal',$nr);
			if(count($chapas)>0){
				$chapa = $chapas[0]->id;
				$_SESSION["chapaMunicipal"] = $chapa;
				$this->finalizaVotacao();
			}
		}
		return $this->url;
	}

	/**
	 * Finaliza a urna e redireciona para a tela de urna finalizada
	 */
	public function finalizarUrna(){
		$this->url = 'finalizar-urna';
		if(count($_POST)){
			$s1 = $_POST["senha1"];
			$s2 = $_POST["senha2"];
			$senhas = [$this->urna->senha_mesario_abertura,$this->urna->senha_diretoria_abertura];
			$urna = ["urna"=>$this->urna->id];
			if(in_array(sha1($s1),$senhas) && in_array(sha1($s2),$senhas)){
				$this->url = 'urna-finalizada';
				$this->crypt->writeUrnaFim(json_encode($urna));
				$this->crypt->writeStatus("end");
			}
			else{
				$this->erros[] = "Senha 1 e Senha 2 não conferem com a base de dados";
			}
		}
		return $this->url;
	}

	/**
	 * Abre a tela de urna finalizada
	 */
	public function urnaFinalizada(){
		$this->url = 'urna-finalizada';
	}

	/**
	 * Chama o metodo conforme a url e inicia o arquivo da view
	 */
	public function show(){
		$this->{$this->method}();
		$view = $this;
		$url = "view/".$this->url.".php";
		include("view/layout.php");
	}

	/**
	 * Abre a tela de voto finaizado, mostra na tela o codigo do eleitor, chama os metodos para as insercoes nos arquivos
	 */
	private function finalizaVotacao(){
		$this->url = 'votar-fim';
		$eleitor = json_encode(array($_SESSION["eleitorSelecionado"]->id=>array($_SESSION["eleitorSelecionado"]->finish_code,$_SESSION["eleitorSelecionado"]->flag_transito)));
		if($this->crypt->writeVoter($eleitor)){
			if($_SESSION["eleitorSelecionado"]->flag_transito){
				$vote = array(time()=>array($_SESSION["chapaEstadual"],$_SESSION["chapaFiscal"]));
			}
			else{
				$vote = array(time()=>array($_SESSION["chapaEstadual"],$_SESSION["chapaRegional"],$_SESSION["chapaFiscal"],$_SESSION["chapaMunicipal"]));
			}
			$this->crypt->writeVote(json_encode($vote));
		}
		else{
			$this->erros[] = "Ocorreu um erro ao gravar o voto, por favor chame o mesário!";
			$this->url = "iniciar-voto";
		}
	}
}