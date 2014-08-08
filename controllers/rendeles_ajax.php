<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Rendeles_ajax extends Public_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	/*
	*
	*Rendelés ajax funkciók
	*
	*/

	function rendszam_lista() 
	{
		$term=$this->input->get('term');
		$results=$this->db->like('rendszam',$term)
		->get('goffice_autok',10)->result();
		
		$autok=array();
		foreach($results as $res)
		{
			$autok[]=$res->rendszam;
		}

		echo json_encode($autok);

	}

	function rendszam_ellenorzes($rendszam)
	{	
		$keres=$this->db->get_where('goffice_autok',array('rendszam'=>$rendszam));
		$db=$keres->num_rows();
		if($db === 0)
		{
			$statusz="uj";
			$auto_id=FALSE;
		}
		else
		{
			$statusz="regisztralt";
			$auto_id=$keres->row()->id;
		}
		echo json_encode(array('statusz'=>$statusz,'auto_id'=>$auto_id));
	}

	function auto_reg()
	{
		$this->load->driver('Streams');
		$auto=$this->input->post();


		$auto=$this->streams->entries->insert_entry($auto,'autok','streams');
		echo json_encode($auto);
	}

	function auto_adatok($ugyfel_id)
	{

		//$this->output->enable_profiler(TRUE);
		$meret_res=$this->db->select('goffice_auto_meretek.id, goffice_auto_meretek.nev')
		->join('goffice_ugyfelek_auto_meretek', 'goffice_ugyfelek_auto_meretek.auto_meret = goffice_auto_meretek.id')
		->where('goffice_ugyfelek_auto_meretek.ugyfel',$ugyfel_id)
		->get('goffice_auto_meretek')->result();

		$meretek=array();
		$meretek['']='-----';
		foreach($meret_res as $meret)
		{
			$meretek[$meret->id]=$meret->nev;
		}

		$data['ugyfel']=$ugyfel_id;
		$data['meretek']=$meretek;
		//$this->load->view('rendeles/auto_adatok',$data);
		$this->template
		->title('Rendeles')
		->set_layout('ures.html')
		->build('rendeles/auto_adatok',$data);

	}

	function moso_tabla($auto_id)
	{
		$auto=$this->db->get_where('goffice_autok',array('id'=>$auto_id))->row();
		$kategoriak=$this->db->get('goffice_moso_tetel_kategoriak')->result();

		$tetelek=array();
		foreach($kategoriak as $kategoria)
		{
			$tetel_kategoria=$this->db
			->select('*,goffice_moso_tetel_kombinaciok.id as komb_id,goffice_moso_tetelek.id as tetel_id')
			->join('goffice_moso_tetelek', 'goffice_moso_tetelek.id = goffice_moso_tetel_kombinaciok.moso_tetel')
			->where('goffice_moso_tetelek.mosas_kategoria',$kategoria->id)
			->where('goffice_moso_tetel_kombinaciok.auto_meret',$auto->auto_meret)
			->where('goffice_moso_tetel_kombinaciok.ugyfel',$auto->ugyfel)
			->order_by('goffice_moso_tetelek.ordering_count','asc')
			->get('goffice_moso_tetel_kombinaciok')->result();
			$tetelek[$kategoria->id]=$tetel_kategoria;
		}

		//var_dump($tetelek);
		$data['kategoriak']=$kategoriak;
		$data['tetelek']=$tetelek;
		$data['auto_id']=$auto_id;
		$this->template
		->title('Rendeles')
		->set_layout('ures.html')
		->build('rendeles/moso_tabla',$data);
	}

	function mosas_felvesz()
	{
		$this->load->model('goffice_m');
		$this->load->driver('Streams');
		$rendeles['felvette']=$this->current_user->id;
		$rendeles['datum_kezdes']=date('Y-m-d H:i:s');
		$rendeles_id=$this->streams->entries->insert_entry($rendeles,'rendelesek','streams');

		$autok_rendelesek['auto']=$this->input->post('auto_id');
		$autok_rendelesek['rendeles']=$rendeles_id;
		$this->streams->entries->insert_entry($autok_rendelesek,'autok_rendelesek','streams');


		$ar=0;
		$tetelek=$this->input->post('m');


		//Végigjárjuk a tételeket kivéve az akciókat azokat külön kezeljük
		foreach($tetelek as $key => $val)
		{	
			unset($rendelesek_moso_tetel_kombinaciok);
			$tetel=$this->goffice_m->get_moso_tetel_kombinacio($key);
			$rendelesek_moso_tetel_kombinaciok['rendeles']=$rendeles_id;
			$rendelesek_moso_tetel_kombinaciok['moso_tetel_kombinacio']=$tetel->komb_id;
			if($tetel->ar_tipus === "fix")
			{
				$rendelesek_moso_tetel_kombinaciok['ar']=$tetel->ar;
				$ar+=$tetel->ar;
			}
			else if($tetel->ar_tipus === "valt")
			{
				$rendelesek_moso_tetel_kombinaciok['ar']=$val;
				$ar+=$val;
			}
			if($tetel->tetel_id == 3)
			{
				$rendelesek_moso_tetel_kombinaciok['egyeb_nev']=$this->input->post('egyeb_text');			
			}



			$this->streams->entries->insert_entry($rendelesek_moso_tetel_kombinaciok,'rendelesek_moso_tetel_kombinaciok','streams');

			//kiszedjük az egyéb nevet hogy ne legyen ott minden rekordban
		}

		if($akcio=$this->input->post('akcio'))
		{
			$tetel=$this->goffice_m->get_moso_tetel_kombinacio($akcio);
			$rendelesek_moso_tetel_kombinaciok['rendeles']=$rendeles_id;
			$rendelesek_moso_tetel_kombinaciok['moso_tetel_kombinacio']=$tetel->komb_id;
			if($tetel->ar_tipus === "fix")
			{
				$rendelesek_moso_tetel_kombinaciok['ar']=$tetel->ar;
				$ar+=$tetel->ar;
			}
			elseif($tetel->ar_tipus === "mod")
			{
				$rendelesek_moso_tetel_kombinaciok['ar']=$tetel->ar;
				if($tetel->ar<1)
				{
					$ar*=$tetel->ar;
				}
				else
				{
					$ar+=$tetel->ar;
				}
			}
			$this->streams->entries->insert_entry($rendelesek_moso_tetel_kombinaciok,'rendelesek_moso_tetel_kombinaciok','streams');
		}
		$this->goffice_m->rendeles_update_ar(array('key'=>'ar_moso','val'=>$ar),$rendeles_id);
		//$this->db->update('goffice_rendelesek',array('ar_mosas'=>$ar,'ar_osszesen'=>$ar),array('id'=>$rendeles_id));




	}
	
}

