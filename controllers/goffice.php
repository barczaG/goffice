<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Goffice extends Public_Controller {


    public function __construct() 
    {
        parent::__construct();
        $this->load->database();
        $this->template->enable_parser(true);
    }



    function rendeles() 
    {   
        role_or_die('goffice','moso_rendeles','users/login');
        $this->load->helper('form');
        $this->load->model('goffice_m');

        $cegek=$this->goffice_m->get_ceglista();
        
        $this->template
        ->title('Rendeles')
        ->set('cegek',$cegek)
        ->build('rendeles/rendeles');
    }

    function aktiv() 
    {   
        //role_or_die('goffice','moso_rendeles','users/login');
        //$this->output->enable_profiler(TRUE);
        $this->load->model('goffice_m');
        $rendelesek=$this->goffice_m->get_aktiv_rendelesek();
        foreach($rendelesek as &$rendeles)
        {
            $tipus=$this->goffice_m->get_rendeles_tipus($rendeles->id);
            $rendeles->rendeles_tips=$tipus;
            //var_dump($tipus);

            if($tipus === "moso" OR $tipus === "vegyes")
            {
                $rendeles->moso_van=TRUE;
                $rendeles->moso_tetelek=$this->goffice_m->get_rendeles_moso_tetelek($rendeles->id);
                $rendeles->auto=$this->goffice_m->get_rendeles_auto($rendeles->id);
                //var_dump($rendeles->auto);

            }
            if($tipus === "bufe" OR $tipus === "vegyes")
            {
                $rendeles->bufe_van=TRUE;
                $rendeles->bufe_tetelek=$this->goffice_m->get_rendeles_bufe_tetelek($rendeles->id);
            }

        }
        //var_dump($rendelesek);
        $cegobj=$this->goffice_m->get_markalista_object();

        $this->template
        ->title('Rendeles')
        ->set('rendelesek',$rendelesek)
        ->set('cegobj',$cegobj)
        ->build('aktiv/aktiv');
    }

    function bufe_rendeles($rendeles_id=0,$szemelyzet_id=0)
    {

        $this->template
        ->set('rendeles_id',$rendeles_id)
        ->set('szemelyzet_id',$szemelyzet_id)
        ->title('Rendeles')
        ->build('bufe/bufe');
    }

    function zaras()
    {
        $this->load->model('goffice_m');
        $mutatok=$this->goffice_m->get_zaras_mutatok();
        $mosasok=$this->goffice_m->get_zaras_moso();
        $bufek=$this->goffice_m->get_zaras_bufe();
        //$this->output->enable_profiler(TRUE);


        $this->template
        ->title('Rendeles')
        ->set('kp',$mutatok['kp'])
        ->set('mosasok',$mosasok)
        ->set('bufek',$bufek)
        ->build('zaras/zaras');
    }

    function kiadasok()
    {
        $this->template
        ->title('Rendeles')
        ->build('kiadasok/kiadasok');
    }


    function fotozas($i=0)
    {


        $this->load->driver('cache', array('adapter' => 'file'));
        
        if ( ! $tomb = $this->cache->get('tomb'))
        {
            $tomb=range(1,999);
            $headers=array();

            foreach($tomb as $key=>$val)
            {
                $hossz=strlen($val);
                $nullak=3-$hossz;
                $tomb[$key]=$val=str_repeat("0", $nullak).$val;


                $url = "http://szalagavato.tv/cor/img/$val.jpg";
                //echo $url;
                $headers[$key] = get_headers($url, 1);
                //var_dump($headers[$key]);
                if ($headers[$key][0] === 'HTTP/1.1 200 OK') 
                {
                //ez jó
                }
                else
                {
                    unset($tomb[$key]);
                }

            }
            $this->cache->save('tomb', $tomb, 60*60*24);
        }
        
        $this->load->library('pagination');

        $config['base_url'] = base_url() . "goffice/fotozas/";
        $config['uri_segment'] = 3;
        $config['total_rows'] = count($tomb);
        //$config['total_rows'] = 50;
        $config['per_page'] = '12';
        //$config['anchor_class'] = 'rel="ajax" scroll="300" ';
        $config['full_tag_open'] = '<div class="pagination pagination-centered pagination-top"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_link'] = 'Első';
        $config['last_link'] = 'Utolsó';
        $config['prev_link'] = '«';
        $config['next_link'] = '»';
        $config['cur_tag_open'] = ' <li><span class="active">';
        $config['cur_tag_close'] = '</span></li>';


        $this->pagination->initialize($config);

        
        $kepek = new ArrayIterator($tomb);
        $data=array(
            'iterator'=>new LimitIterator($kepek, $i, 12),
            'pager'=>$this->pagination->create_links()     
            );
        //var_dump($data);
        if($this->input->is_ajax_request())
        {
            //$this->load->view('fotozas/fotozas_ajax',$data);
            $this->template
            ->title('Rendeles')
            ->build('fotozas/fotozas',$data); 
        }
        else
        {
         $this->template
         ->title('Rendeles')
         ->build('fotozas/fotozas',$data);    
     }

 }




}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */