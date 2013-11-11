<?php
class Regiao_model extends CI_Model{

	# Tabela cidades.
	private $tabela_cidade = 'cidades';
	# Tabela bairros.
	private $tabela_bairro = 'bairros';

	public function __construct(){
		parent::__construct();
	}

	/**
	 * Recupera as cidades.
	 *
	 * @return array
	 */
	public function get_cidades(){
		$q = $this->db->get($this->tabela_cidade);
		return $q->result();
	}

	/**
	 * Recupera os bairros de uma determinada cidade.
	 *
	 * @param int $id_cidade recebe o ID da cidade.
	 * @return array
	 */
	public function get_bairros($id_cidade){
		$q = $this->db->get_where($this->tabela_bairro, array('cid_id' => $id_cidade));
		return $q->result();
	}
}