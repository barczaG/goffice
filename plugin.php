<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Blog Plugin
 *
 * Create lists of posts
 *
 * @package		PyroCMS
 * @author		PyroCMS Dev Team
 * @copyright	Copyright (c) 2008 - 2011, PyroCMS
 *
 */
class Plugin_Fotokeszito extends Plugin {

    public function menu() {
        $this->load->model('fotokeszito_m');
        $sigreq=$this->fotokeszito_m->facebook->getSignedRequest();

        
        $data['reg']=FALSE;
        if (isset($sigreq['user_id'])) {
            if($this->user= $this->fotokeszito_m->get_user($sigreq['user_id'])){
                $data['reg']=TRUE;
            }
        }
        return $this->load->view('fotokeszito/menu',$data,TRUE); 

    }

}

/* End of file plugin.php */