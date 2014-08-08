<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Bufe_ajax extends Public_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	function tetel_lista()
	{
		$term=$this->input->get('term');
		$results=$this->db->like('nev',$term)
		->get('goffice_bufe_tetelek',10)->result();
		
		$tetelek=array();
		foreach($results as $res)
		{
			$tetelek[]=$res->nev;
		}

		echo json_encode($results);
	}

	function rendeles_felvesz($tipus,$szemelyzet_id=0)
	{
		$this->load->model('goffice_m');
		$this->load->driver('Streams');
		if(is_numeric($tipus))
		{
			$rendeles_id=$tipus;
			$this->db->update('goffice_rendelesek',array('bufe_azon'=>$this->input->post('bufe_azon')),array('id'=>$rendeles_id));
		}
		else{

			$rendeles['felvette']=$this->current_user->id;
			$rendeles['datum_kezdes']=date('Y-m-d H:i:s');
			$rendeles['bufe_azon']=$this->input->post('bufe_azon');
			if($tipus ==="kp" OR $tipus === "kartya" )
			{
				$rendeles['datum_lezaras']=date('Y-m-d H:i:s');
				$rendeles['fizetesi_mod']=$tipus;
			}
			else if($tipus=="szemelyzeti")
			{
				$rendeles['fizetesi_mod']=$tipus;
			} 
			$rendeles_id=$this->streams->entries->insert_entry($rendeles,'rendelesek','streams');

			if($tipus=="szemelyzeti")
			{
				$szemelyzet_rendelesek['szemelyzet']=$szemelyzet_id;
				$szemelyzet_rendelesek['rendeles']=$rendeles_id;
				$this->streams->entries->insert_entry($szemelyzet_rendelesek,'szemelyzet_rendelesek','streams');
			}
		}

		$ar=0;
		$tetelek=$this->input->post('m');
		foreach($tetelek as $form_tetel)
		{	
			//Ha nincs kitöltve nem foglalkozunk vele
			if(empty($form_tetel['nev'])) break;

			unset($rendelesek_bufe_tetelek);
			$tetel=$this->goffice_m->get_bufe_tetel($form_tetel['tetel_id']);
			$rendelesek_bufe_tetelek['rendeles']=$rendeles_id;
			$rendelesek_bufe_tetelek['bufe_tetel']=$form_tetel['tetel_id'];
			$rendelesek_bufe_tetelek['mennyiseg']=$form_tetel['db'];
			if($tipus=="szemelyzeti"):
				$rendelesek_bufe_tetelek['ar']=$tetel->szemelyzeti_ar;
			else:
				$rendelesek_bufe_tetelek['ar']=$tetel->ar;
			endif;
			$ar+=$form_tetel['db']*$tetel->ar;



			$this->streams->entries->insert_entry($rendelesek_bufe_tetelek,'rendelesek_bufe_tetelek','streams');

			//kiszedjük az egyéb nevet hogy ne legyen ott minden rekordban
		}
		$this->goffice_m->rendeles_update_ar(array('key'=>'ar_bufe','val'=>$ar),$rendeles_id);
	}
	
}

