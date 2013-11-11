<?php
class Reclamation_model extends CI_Model{

	private $table_reclamation = 'reclamacoes';
	private $table_user = 'usuarios';
	private $table_history = 'historico_visitas';
	private $tabela_cidade = 'cidades';
	private $tabela_bairro = 'bairros';

	private $data_reclamation = array();

	public function __construct(){
		parent::__construct();

		$this->load->helper('populate_record');
	}

	/**
	 * Mapeia e atribui os dados.
	 */
	private function set_data_reclamation(){
		$this->data_reclamation = array(
			'usu_id'		=> NULL,
			'bai_id'		=> NULL,
			'rec_titulo'	=> NULL,
			'rec_descricao'	=> NULL,
			'rec_local'		=> NULL,
			'rec_situacao'	=> NULL,
			'rec_data_hora'	=> date('Y-m-d H:i:s')
			);

		try{
			populate_record($this->data_reclamation, $this->input->post());
		} catch(Exception $e){
			exit('Erro: '.$e->getMessage());
		}
	}

	/**
	 * Insere uma reclamação.
	 */
	public function insert(){
		$this->set_data_reclamation();

		$ret = $this->db->insert($this->table_reclamation, $this->data_reclamation);

		if($ret === false)
			throw new Exception('Falha ao inserir a reclamação.');
	}

	/**
	 * Altera a reclamação.
	 *
	 * @param int $id recebe o ID da reclamação.
	 */
	public function update($id){
		if(is_numeric($id) && $id > 0){
			$this->set_data_reclamation();
			unset($this->data_reclamation['rec_data_hora']);

			$this->db->where('rec_id', $id);
			$ret = $this->db->update($this->table_reclamation, $this->data_reclamation);

			if($ret === false)
				throw new Exception('Falha ao alterar a reclamação.');
		} else
			throw new Exception('Nenhum ID foi informado.');
	}

	/**
	 * Exclui a reclamação.
	 *
	 * @param int $id recebe o ID da reclamação.
	 */
	public function delete($id){
		if(is_numeric($id) && $id > 0){
			$this->db->where('rec_id', $id);
			$ret = $this->db->delete($this->table_reclamation);

			if($ret === false)
				throw new Exception('Falha ao excluir a reclamação.');
		} else
			throw new Exception('Nenhum ID foi informado.');
	}

	/**
	 * Seleciona a reclamação.
	 *
	 * @param int $id recebe o ID da reclamação.
	 */
	public function select($id){
		$this->db->from($this->table_reclamation.' AS rec');
		$this->db->join($this->table_user.' AS u', 'u.usu_id = rec.usu_id', 'LEFT');
		$this->db->join($this->tabela_bairro.' AS b', 'b.bai_id = rec.bai_id', 'LEFT');
		$this->db->join($this->tabela_cidade.' AS c', 'c.cid_id = b.cid_id', 'LEFT');
		$this->db->where('rec.rec_id', $id);
		$q = $this->db->get();

		$this->register_view($id);

		return $q->result();
	}

	/**
	 * Lista todos as reclamações.
	 */
	public function get_reclamations(){
		$this->db->from($this->table_reclamation.' AS rec');
		$this->db->join($this->table_user.' AS u', 'u.usu_id = rec.usu_id', 'LEFT');
		$this->db->join($this->tabela_bairro.' AS b', 'b.bai_id = rec.bai_id', 'LEFT');
		$this->db->join($this->tabela_cidade.' AS c', 'c.cid_id = b.cid_id', 'LEFT');
		$q = $this->db->get();

		return $q->result();
	}

	/**
	 * Registra as visitas.
	 *
	 * @param int $id recebe o ID da reclamação.
	 */
	private function register_view($id){
		$registers = array(
			'REMOTE_ADDR'		=> $_SERVER['REMOTE_ADDR'],
			'REQUEST_URI'		=> $_SERVER['REQUEST_URI'],
			'HTTP_USER_AGENT'	=> $_SERVER['HTTP_USER_AGENT'],
			'QUERY_STRING'		=> $_SERVER['QUERY_STRING']
			);

		$registers = json_encode($registers);
		$hash = sha1($registers);

		$q = $this->db->get_where($this->table_history, array('hash_visitante' => $hash));

		if($q->num_rows() === 0){
			$data = array(
				'rec_id'			=> $id,
				'dados_visita'		=> $registers,
				'hash_visitante'	=> $hash
				);
			$this->db->insert($this->table_history, $data);
		}
	}

	/**
	 * Realiza o upload.
	 *
	 * @return array
	 */
	public function upload(){
		$config['upload_path'] = './images/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);

		if(!$this->upload->do_upload()){
			$error = array('error' => $this->upload->display_errors());
			return $error;
		} else{
			$data = array('upload_data' => $this->upload->data());
			return $data;
		}
	}
}