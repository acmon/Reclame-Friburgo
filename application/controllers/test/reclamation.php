<?php
class Reclamation extends CI_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('reclamation_model', 'reclamation');
	}

	private function header_post($arr){
		$_POST = $arr;
	}

	public function index(){
		$r = $this->reclamation->get_reclamations();
		print_r($r);
	}

	public function view($id){
		$u = $this->reclamation->select($id);
		print_r($u);
	}

	public function new_reclamation(){
		$this->header_post(array(
			'usu_id'		=> 6,
			'bai_id'		=> 6,
			'rec_titulo'	=> 'Título da reclamação',
			'rec_descricao'	=> 'Essa aqui é a descrição da reclamação.',
			'rec_local'		=> 'Por aqui ou ali.',
			'rec_situacao'	=> 'Caótica!',
			));

		try{
			$this->reclamation->insert();
		} catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function edit($id = 0){
		$this->header_post(array(
			'usu_id'		=> 6,
			'bai_id'		=> 6,
			'rec_titulo'	=> 'MOD. Título da reclamação',
			'rec_descricao'	=> 'MOD. Essa aqui é a descrição da reclamação.',
			'rec_local'		=> 'MOD. Por aqui ou ali.',
			'rec_situacao'	=> 'MOD. Caótica!',
			));

		try{
			$this->reclamation->update($id);
		} catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function delete($id){
		try{
			$this->reclamation->delete($id);
		} catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function upload(){
		$this->load->view('upload');
	}

	public function test_upload(){
		$this->reclamation->upload();
	}


	public function vis(){
		print_r($_SERVER);
	}


}