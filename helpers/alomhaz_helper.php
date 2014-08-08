<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function egyedi_link(){
	return base_url()."alomhaz/ajanlo/".ci()->current_user->username;
}

/* End of file users/helpers/user_helper.php */