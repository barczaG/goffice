<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Goffice_m extends CI_Model {



    function __construct() {

    }

    function get_ceglista()
    {
        $ceglista=$this->db->get('goffice_ugyfelek')->result();

        $cegek=array();
        $cegek[]="Kérlek válassz";
        foreach($ceglista as $ceg)
        {
            $cegek[$ceg->id]=$ceg->nev;
        }
        return $cegek;
    }

    function get_markalista_object()
    {
        $ceglista=$this->db->get('goffice_auto_markak')->result();

        $cegek=array();
        foreach($ceglista as $ceg)
        {
            $cegobj = new stdClass();
            $cegobj->value=$ceg->id;
            $cegobj->text=$ceg->nev;
            $cegek[]=$cegobj;
        }
        return $cegek;
    }

    function get_moso_tetel_kombinacio($komb_id)
    {
        return $this->db
        ->select('*,goffice_moso_tetel_kombinaciok.id as komb_id,goffice_moso_tetelek.id as tetel_id')
        ->join('goffice_moso_tetelek', 'goffice_moso_tetelek.id = goffice_moso_tetel_kombinaciok.moso_tetel')
        ->where('goffice_moso_tetel_kombinaciok.id',$komb_id)
        ->get('goffice_moso_tetel_kombinaciok')->row();
    }



    function get_aktiv_rendelesek()
    {
        return $this->db->where('datum_lezaras', null)
        ->where('fizetesi_mod !=','szemelyzeti')
        ->get('goffice_rendelesek')->result();
    }



    function get_rendeles_tipus($rendeles_id)
    {
        $mosas=$this->db->get_where('goffice_rendelesek_moso_tetel_kombinaciok',array('rendeles'=> $rendeles_id))->num_rows();
        $bufe=$this->db->get_where('goffice_rendelesek_bufe_tetelek',array('rendeles'=> $rendeles_id))->num_rows();
        if($mosas > 0 AND $bufe > 0)
        {
            return "vegyes";
        }
        else if($mosas > 0 )
        {
            return "moso";
        }
        else if($bufe > 0)
        {
            return "bufe";
        }
        else
        {
            return "hiba";
        }
    }


    function get_rendeles_moso_tetelek($rendeles_id)
    {

        return $this->db->select('*,goffice_rendelesek_moso_tetel_kombinaciok.ar as tetel_ar')
        ->join('goffice_moso_tetel_kombinaciok', 'goffice_rendelesek_moso_tetel_kombinaciok.moso_tetel_kombinacio = goffice_moso_tetel_kombinaciok.id')
        ->join('goffice_moso_tetelek', 'goffice_moso_tetelek.id = goffice_moso_tetel_kombinaciok.moso_tetel')
        ->where('goffice_rendelesek_moso_tetel_kombinaciok.rendeles',$rendeles_id)
        ->get('goffice_rendelesek_moso_tetel_kombinaciok')->result();


    }

    function get_rendeles_bufe_tetelek($rendeles_id)
    {

        return $this->db->select('*,goffice_rendelesek_bufe_tetelek.ar as tetel_ar')
        ->join('goffice_bufe_tetelek', 'goffice_rendelesek_bufe_tetelek.bufe_tetel = goffice_bufe_tetelek.id')
        ->where('goffice_rendelesek_bufe_tetelek.rendeles',$rendeles_id)
        ->get('goffice_rendelesek_bufe_tetelek')->result();


    }

    function get_rendeles_auto($rendeles_id)
    {

        return $this->db->select('*, goffice_auto_meretek.nev as auto_meret_nev, goffice_auto_markak.nev as auto_marka_nev,goffice_autok.id as auto_id')
        ->join('goffice_autok', 'goffice_autok_rendelesek.auto = goffice_autok.id')
        ->join('goffice_auto_meretek','goffice_auto_meretek.id = goffice_autok.auto_meret')
        ->join('goffice_auto_markak','goffice_auto_markak.id = goffice_autok.auto_marka','left')
        ->where('goffice_autok_rendelesek.rendeles',$rendeles_id)
        ->get('goffice_autok_rendelesek')->row();
    }

    function auto_modosit($post)
    {
        $this->db->update('goffice_autok',array($post['name']=>$post['value']),array('id'=>$post['pk']));
    }

    function get_bufe_tetel($tetel_id)
    {
        return $this->db
        ->get_where('goffice_bufe_tetelek',array('id'=>$tetel_id))->row();
    }

    function rendeles_update_ar($set,$rendeles_id)
    {
       $this->db->set($set['key'],$set['val'])
       ->where('id',$rendeles_id)
       ->update('goffice_rendelesek');

       $this->db->set('ar_osszesen','ar_moso+ar_bufe+ar_berlet',FALSE)
       ->where('id',$rendeles_id)
       ->update('goffice_rendelesek');
    }

   function get_szemelyzet_index()
    {
        $szemelyzet=$this->db->get_where('goffice_szemelyzet',array('statusz'=>'aktiv'))->result();
        foreach($szemelyzet as &$szemely)
        {
            //Aktív műszak megkeresése
            $muszak_leker=$this->db->get_where('goffice_muszakok',array('datum_lezaras'=>null,'szemelyzet'=>$szemely->id));
            if($muszak_leker->num_rows() >0)
            {
                $szemely->muszak=$muszak_leker->row()->id;
            }

            $szemely->bufe_egyenleg=$this->get_aktiv_szemelyzet_bufe_egyenleg($szemely->id);
            $szemely->fizetes=$this->get_aktiv_fizetes($szemely->id);
            $szemely->levonasok=$this->get_aktiv_levonasok_egyenleg($szemely->id);
            $szemely->egyenleg=$szemely->fizetes-$szemely->bufe_egyenleg-$szemely->levonasok;
        }
        return $szemelyzet;
    }

    function get_aktiv_aktiv_fizetes_datum($szemelyzet_id)
    {
        $fizetes_keres=$this->db->get_where('goffice_fizetesek',array('szemelyzet'=>$szemelyzet_id,'datum_lezaras'=>NULL));
        if($fizetes_keres->num_rows() > 0)
        {
            return $fizetes_keres->row()->datum_lezaras;
        }
        else
        {
            return '0000-00-00 00:00:00';
        }
    }

    function get_aktiv_muszakok($szemelyzet_id,$idoszak_vege=0)
    {
        if(!$idoszak_vege) $idoszak_vege=date('Y-m-d').' 23:59:59';
        $idoszak_kezdete=$this->get_aktiv_aktiv_fizetes_datum($szemelyzet_id);
        $muszakok=$this->db
        ->order_by('erkezett','asc')
        ->get_where('goffice_muszakok',array('szemelyzet'=>$szemelyzet_id,'vegzett >'=>$idoszak_kezdete,'vegzett <='=>$idoszak_vege))->result();
        $oraber=$this->db->get_where('goffice_szemelyzet',array('id'=>$szemelyzet_id))->row()->oraber;
        foreach($muszakok as &$muszak)
        {
            $keses=(strtotime($muszak->erkezett)-strtotime($muszak->erkeznie_kellett))/60;
            $muszak->keses=$keses;

            if($keses > 10 AND $keses<=30)
            {
                $muszak->levonas=1000;
            }
            else if($keses > 30)
            {
                $muszak->levonas=2500;
            }
            else
            {
                $muszak->levonas=0;
            }

            $oraszam=(strtotime($muszak->vegzett)-strtotime($muszak->erkeznie_kellett))/60/60;
            $muszak->oraszam=$oraszam;
            $muszak->oraber=$oraber;
            $muszak->fizetes=$oraszam*$oraber;

            $muszak->osszesen=$muszak->fizetes+$muszak->borravalo-$muszak->levonas;


        }
        return $muszakok;
    }

    function get_aktiv_fizetes($szemelyzet_id)
    {
        $muszakok=$this->get_aktiv_muszakok($szemelyzet_id);
        $egyenleg=0;
        foreach($muszakok as $muszak)
        {
            $egyenleg+=$muszak->osszesen;
        }
        return $egyenleg;
    }


    function get_aktiv_szemelyzet_bufe($szemelyzet_id)
    {
        $rendelesek=$this->db->select('*')
        ->join('goffice_rendelesek', 'goffice_rendelesek.id = goffice_szemelyzet_rendelesek.rendeles')
        ->where('goffice_szemelyzet_rendelesek.szemelyzet',$szemelyzet_id)
        ->where('goffice_rendelesek.datum_lezaras',null)
        ->get('goffice_szemelyzet_rendelesek')->result();

        foreach ($rendelesek as &$rendeles) 
        {
            $rendeles->bufe_tetelek=$this->get_rendeles_bufe_tetelek($rendeles->rendeles);
        }
        return $rendelesek;

    }

    function get_aktiv_szemelyzet_bufe_egyenleg($szemelyzet_id)
    {
        $rendelesek=$this->get_aktiv_szemelyzet_bufe($szemelyzet_id);
        $egyenleg=0;
        foreach($rendelesek as $rendeles)
        {
            $egyenleg+=$rendeles->ar_bufe;
        }
        return $egyenleg;
    }

    function get_aktiv_levonasok($szemelyzet_id,$idoszak_vege=0)
    {
        if(!$idoszak_vege) $idoszak_vege=date('Y-m-d H:i:s');
        $idoszak_kezdete=$this->get_aktiv_aktiv_fizetes_datum($szemelyzet_id);
        return $this->db
        ->order_by('created','asc')
        ->get_where('goffice_kifizetesek',array('szemelyzet'=>$szemelyzet_id,'created >'=>$idoszak_kezdete,'created <='=>$idoszak_vege))->result();
    }

    function get_aktiv_levonasok_egyenleg($szemelyzet_id)
    {
        $levonasok=$this->get_aktiv_levonasok($szemelyzet_id);
        $egyenleg=0;
        foreach($levonasok as $levonas)
        {
            $egyenleg+=$levonas->osszeg;
        }
        return $egyenleg;
    }

    function get_zaras_mutatok()
    {
        $aktiv_zaras=$this->db->get_where('goffice_zarasok',array('datum_lezaras'=>null))->row();
        $mutatok = new stdClass();
        $kp=$this->db->select('SUM(ar_osszesen) as osszesen,SUM(ar_moso) as moso,SUM(ar_bufe) as bufe,SUM(ar_berlet) as berlet,SUM(ar_berlet) as berlet,SUM(borravalo) as borravalo,',FALSE)
        ->where('fizetesi_mod','kp')
        ->where('datum_lezaras >',$aktiv_zaras->datum_kezdes)
        ->get('goffice_rendelesek')->row();
        //var_dump($kp_lekerdezes);
        //$this->output->enable_profiler(TRUE);
        $kiadas=$this->db->select('SUM(osszeg) as osszesen',FALSE)
        ->where('created >',$aktiv_zaras->datum_kezdes)
        ->get('goffice_kiadasok')->row();
        $kp->kiadas=$kiadas->osszesen;
        $kp->kassza=$kp->osszesen-$kp->kiadas;

        $data['kp']=$kp;
        $data['kiadas']=$kiadas;
        return $data;
    }

    function get_zaras_moso()
    {
        $aktiv_zaras=$this->db->get_where('goffice_zarasok',array('datum_lezaras'=>null))->row();
        
        $rendelesek=$this->db->select('*,goffice_rendelesek.id as rendeles_id,goffice_auto_markak.nev as marka_nev,goffice_auto_meretek.nev as meret_nev')
        ->join('goffice_autok_rendelesek', 'goffice_autok_rendelesek.rendeles = goffice_rendelesek.id')
        ->join('goffice_autok', 'goffice_autok_rendelesek.auto = goffice_autok.id')
        ->join('goffice_auto_meretek','goffice_auto_meretek.id = goffice_autok.auto_meret')
        ->join('goffice_auto_markak','goffice_auto_markak.id = goffice_autok.auto_marka','left')
        ->where('ar_moso >',0)
        ->where('datum_lezaras >',$aktiv_zaras->datum_kezdes)
        ->get('goffice_rendelesek')->result();

        foreach($rendelesek as &$rendeles)
        {
            $rendeles->moso_tetelek=$this->get_rendeles_moso_tetelek($rendeles->rendeles_id);
        }
        return $rendelesek;
    }

    function get_zaras_bufe()
    {
        $aktiv_zaras=$this->db->get_where('goffice_zarasok',array('datum_lezaras'=>null))->row();
        $rendelesek=$this->db
        ->where('ar_bufe >',0)
        ->where('datum_lezaras >',$aktiv_zaras->datum_kezdes)
        ->where('fizetesi_mod !=','szemelyzeti')
        ->get('goffice_rendelesek')->result();

        foreach($rendelesek as &$rendeles)
        {
            $rendeles->bufe_tetelek=$this->get_rendeles_bufe_tetelek($rendeles->id);
        }
        return $rendelesek;
    }



    function get_mai_muszakok()
    {
         
        $muszakok=$this->db->select('*')
        ->join('goffice_szemelyzet', 'goffice_szemelyzet.id = goffice_muszakok.szemelyzet')
        ->where('DATE(erkezett)=DATE(now())',NULL,FALSE)
        ->get('goffice_muszakok')->result();

        foreach($muszakok as &$muszak)
        {
            $oraszam=(strtotime($muszak->vegzett)-strtotime($muszak->erkeznie_kellett))/60/60;
            $muszak->oraszam=$oraszam;

            if($muszak->pultos) $oraszam*=2;
            $muszak->suly=$oraszam;

        }
        return $muszakok;


    }

    function update_zaras_borravalo($kulso_borravalo)
    {
        $mutatok=$this->get_zaras_mutatok();
        $muszakok=$this->get_mai_muszakok();
        $ossz_suly=0;
        foreach($muszakok as $muszak)
        {
            $ossz_suly+=$muszak->suly;
        }

        $statok=new stdClass();
        $statok->ossz_suly=$ossz_suly;
        $statok->beszorzando=($mutatok['kp']->borravalo+$kulso_borravalo)/$ossz_suly;

        foreach($muszakok as $muszak)
        {
            $this->db->update('goffice_muszakok',array('borravalo'=>$muszak->suly*$statok->beszorzando),array('id'=>$muszak->id));
        }

        return $statok;

    }


    function get_zaras_kartyas_fizetes()
    {
        $aktiv_zaras=$this->db->get_where('goffice_zarasok',array('datum_lezaras'=>null))->row();
        $mutatok = new stdClass();
        $ossz=$this->db->select('SUM(ar_osszesen) as osszesen,SUM(ar_moso) as moso,SUM(ar_bufe) as bufe,SUM(ar_berlet) as berlet,SUM(ar_berlet) as berlet,SUM(borravalo) as borravalo,',FALSE)
        ->where('fizetesi_mod','kartya')
        ->where('datum_lezaras >',$aktiv_zaras->datum_kezdes)
        ->get('goffice_rendelesek')->row();   

        $tetelek=$this->db->select('*')
        ->where('fizetesi_mod','kartya')
        ->where('datum_lezaras >',$aktiv_zaras->datum_kezdes)
        ->get('goffice_rendelesek')->result(); 

        $data['ossz']=$ossz->osszesen;
        $data['tetelek']=$tetelek;
        return $data;
    }




}

/* End of file search_m.php */