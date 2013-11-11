<?php
# Testes Usuário.

class User extends CI_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('user_model', 'user');
	}

	private function header_post($arr){
		$_POST = $arr;
	}

	public function index(){
		$u = $this->user->get_users();
		print_r($u);
	}

	public function view($id){
		$u = $this->user->select($id);
		print_r($u);
	}

	public function new_user(){
		$this->header_post(array(
			'usu_nome'		=> 'Pessoa de testes',
			'usu_email'		=> 'teste@reclamefriburgo.org',
			'usu_senha'		=> '123',
			'usu_avatar'	=> 'w/tr'
			));

		try{
			$this->user->insert();
		} catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function edit($id = 0){
		$this->header_post(array(
			'usu_nome'		=> 'Nova Pessoa de testes',
			'usu_email'		=> 'teste@reclamefriburgo.org',
			'usu_senha'		=> '123456',
			'usu_avatar'	=> '0w/tr'
			)
		);

		try{
			$this->user->update($id);
		} catch(Exception $e){
			exit($e->getMessage());
		}
	}

	public function delete($id){
		try{
			$this->user->delete($id);
		} catch(Exception $e){
			exit($e->getMessage());
		}
	}


}