<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Zaras_ajax extends Public_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function zaras_1()
	{
		$this->load->model('goffice_m');
		$muszakok=$this->goffice_m->get_mai_muszakok();
		if(!$muszakok)
		{
			echo "nincs_muszak";
			return;
		}
		$dolgozok=array();
		foreach($muszakok as $muszak)
		{
			$dolgozok[$muszak->id]=$muszak->nev;
		}

		$kartyas=$this->goffice_m->get_zaras_kartyas_fizetes();
		$this->template
		->title('Rendeles')
		->set('dolgozok',$dolgozok)
		->set('kartyas',$kartyas)
		->set_layout('ures.html')
		->build('zaras/zaras_1');
	}

	function zaras_2()
	{
		$this->load->model('goffice_m');
		$statok=$this->goffice_m->update_zaras_borravalo($this->input->post('kulso_borravalo'));
		$muszakok=$this->goffice_m->get_mai_muszakok();


		$this->template
		->title('Rendeles')
		->set_layout('ures.html')
		->build('zaras/zaras_2');
	}
	
}

