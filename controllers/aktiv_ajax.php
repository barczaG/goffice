<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Aktiv_ajax extends Public_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function auto_modosit()
	{
		$this->load->model('goffice_m');
		$this->goffice_m->auto_modosit($this->input->post());
	}

	function fizet($rendeles_id,$fizetesi_mod,$osszeg)
	{
		$rendeles_adatok=$this->db->get_where('goffice_rendelesek',array('id'=>$rendeles_id))->row();
		

		$rendeles['lezarta']=$this->current_user->id;
		$rendeles['datum_lezaras']=date('Y-m-d H:i:s');
		$rendeles['borravalo']=$osszeg-$rendeles_adatok->ar_osszesen;
		$rendeles['fizetesi_mod']=$fizetesi_mod;

		$this->db->update('goffice_rendelesek',$rendeles,array('id'=>$rendeles_id));

		echo json_encode(array('status'=>'ok'));	
	}
	
}

