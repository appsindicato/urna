<?php
/**
* Classe que faz a criptografia, le e escreve nos arquivos
* @copyright GPL Version 3 © 2017 
* @author APP - Sindicato <contato@app.com.br>
* @license GPL Version 3
* 
*/
class Crypt{
	private $urlKey = "pub.key";
	private $backupAdress = "/media/urna/Mdv_Root6/";
	private $urlDestResult = "files/result.vt";
	private $urlDestVotes = "files/votes.vt";
	private $statusFile = "files/status.conf";
	private $publicKey;
	
	/**
	 * Ao iniciar a classe, tenta criar a pasta de backup. Também instancia a chave pública.
	 */
	public function __construct(){
		@mkdir($this->backupAdress."files");
		if ( !($pubfile = fopen($this->urlKey,'r')) )
			return false;
		$this->publicKey = fread($pubfile,4096);
		fclose($pubfile);
	}
	
	/**
	 * Escreve no inicio do arquivo de votos a urna
	 */
	public function writeUrnaInicio($urna){
		$string = base64_encode($urna);
		@file_put_contents($this->backupAdress.$this->urlDestVotes, $string."\n", FILE_APPEND);
		return file_put_contents($this->urlDestVotes, $string."\n", FILE_APPEND);
	}

	/**
	 * Escreve a urna no começo do arquivo de votantes
	 */
	public function writeUrnaFim($urna){
		$string = base64_encode($urna);
	    $lines = $this->getAllVoters();
	    array_unshift($lines,$string);
	    @file_put_contents($this->backupAdress.$this->urlDestResult, implode("\n",$lines));
		return file_put_contents($this->urlDestResult, implode("\n",$lines));
	}

	/**
	 * Encripta o voto e escreve no arquivo de votos
	 */
	public function writeVote($line){
		openssl_public_encrypt($line, $encryptedData, $this->publicKey);
		$string = base64_encode($encryptedData);
		@file_put_contents($this->backupAdress.$this->urlDestVotes, $string."\n", FILE_APPEND);
		return file_put_contents($this->urlDestVotes, $string."\n", FILE_APPEND);
	}

	/**
	 * Transforma o votante em base64 e retorna o valor
	 */
	public function encryptVoter($voter){
		$encryptedData = base64_encode($voter);
		return $encryptedData;
	}

	/**
	 * Escreve o votante no arquivo e embaralha as linhas
	 */
	public function writeVoter($line){
		$string = $this->encryptVoter($line);
	    $lines = $this->getAllVoters();
	    $lines[] = $string;
	    shuffle($lines);
	    @file_put_contents($this->backupAdress.$this->urlDestResult, implode("\n",$lines));
		return file_put_contents($this->urlDestResult, implode("\n",$lines));
	}

	/**
	 * Le do arquivo de votantes e retorna um array
	 */
	public function getAllVoters(){
		if(file_exists($this->urlDestResult)){
			return file($this->urlDestResult, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}
		else{
			return array();
		}
	}

	/**
	 * Troca o status do arquivo de controle
	 */
	public function writeStatus($status){
		if(!file_put_contents($this->statusFile,$status)){
			echo "Permissoes invalidas"; die();
		}		
	}

	/**
	 * Retorna o status do arquivo de controle
	 */
	public function getStatus(){
		if(file_exists($this->statusFile)){
			return file_get_contents($this->statusFile);
		}
		return false;
	}
}