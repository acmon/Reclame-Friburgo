<?php
# Testes Região

class Regiao extends CI_Controller{

	public function __construct(){
		parent::__construct();

		$this->load->model('regiao_model', 'regiao');
	}

	public function cidades(){
		$b = $this->regiao->get_cidades();

		print_r($b);
	}

	public function bairros(){
		$b = $this->regiao->get_bairros(1);

		print_r($b);
	}
}