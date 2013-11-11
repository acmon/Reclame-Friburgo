<?php
class User_model extends CI_Model{

	# Tabela usuários.
	private $table_user = 'usuarios';

	private $data_user = array();

	public function __construct(){
		parent::__construct();

		$this->load->helper('populate_record');
	}

	/**
	 * Mapeia e atribui os dados.
	 */
	private function set_data_user(){
		$this->data_user = array(
			'usu_nome'		=> NULL,
			'usu_email'		=> NULL,
			'usu_senha'		=> NULL,
			'usu_avatar'	=> NULL,
			'data_cadastro'	=> date('Y-m-d H:i:s')
			);

		try{
			populate_record($this->data_user, $this->input->post());
		} catch(Exception $e){
			exit('Erro: '.$e->getMessage());
		}

		$this->data_user['usu_senha'] = md5($this->data_user['usu_senha']);
	}

	/**
	 * Insere um usuário.
	 */
	public function insert(){
		$this->set_data_user();

		$ret = $this->db->insert($this->table_user, $this->data_user);

		if($ret === false)
			throw new Exception('Falha ao inserir o usuário.');
	}

	/**
	 * Altera o usuário.
	 *
	 * @param int $id recebe o ID do usuário.
	 */
	public function update($id){
		if(is_numeric($id) && $id > 0){
			$this->set_data_user();
			unset($this->data_user['data_cadastro']);

			$this->db->where('usu_id', $id);
			$ret = $this->db->update($this->table_user, $this->data_user);

			if($ret === false)
				throw new Exception('Falha ao alterar o usuário.');
		} else
			throw new Exception('Nenhum ID foi informado.');
	}

	/**
	 * Exclui o usuário.
	 *
	 * @param int $id recebe o ID do usuário.
	 */
	public function delete($id){
		if(is_numeric($id) && $id > 0){
			$this->db->where('usu_id', $id);
			$ret = $this->db->delete($this->table_user);

			if($ret === false)
				throw new Exception('Falha ao excluir o usuário.');
		} else
			throw new Exception('Nenhum ID foi informado.');
	}

	/**
	 * Seleciona o usuário.
	 *
	 * @param int $id recebe o ID do usuário.
	 */
	public function select($id){
		$q = $this->db->get_where($this->table_user, array('usu_id' => $id));
		return $q->result();
	}

	/**
	 * Lista todos os usuários.
	 */
	public function get_users(){
		$q = $this->db->get($this->table_user);
		return $q->result();
	}
}