<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Szemelyzet extends Public_Controller {


	public function __construct() 
	{
		parent::__construct();
		$this->load->database();
		$this->template->enable_parser(true);
	}


	function index()
	{
		$this->load->model('goffice_m');
		$szemelyzet=$this->goffice_m->get_szemelyzet_index();
		$this->template
		->title('Rendeles')
		->set('szemelyzet',$szemelyzet)
		->build('szemelyzet/index');
	}

	function muszak_uj($szemelyzet_id)
	{

		$this->template
		->title('Rendeles')
		->set('szemelyzet',$szemelyzet_id)
		->build('szemelyzet/muszak_uj');
	}

	function muszak_lezar($muszak_id)
	{
		$this->template
		->title('Rendeles')
		->set('muszak',$muszak_id)
		->build('szemelyzet/muszak_lezar');
	}

	function szemely_info($szemelyzet_id)
	{
		$this->load->model('goffice_m');
		$muszakok=$this->goffice_m->get_aktiv_muszakok($szemelyzet_id);
		$bufe_rendelesek=$this->goffice_m->get_aktiv_szemelyzet_bufe($szemelyzet_id);
		$levonasok=$this->goffice_m->get_aktiv_levonasok($szemelyzet_id);
		//$this->output->enable_profiler(TRUE);

		$this->template
		->title('Rendeles')
		->set('muszakok',$muszakok)
		->set('bufe_rendelesek',$bufe_rendelesek)
		->set('levonasok',$levonasok)
		->build('szemelyzet/szemely_info');
	}
	function kifizetes($szemelyzet_id)
	{
		$this->template
		->title('Rendeles')
		->build('szemelyzet/kifizetes');
	}

}