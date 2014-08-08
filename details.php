<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Goffice extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Goffice',
				
				),
			'description' => array(
				'en' => 'Goffice',
				
				),
			'frontend'	=> TRUE,
			'backend'	=> TRUE,
			'skip_xss'	=> FALSE,
			'menu'		=> 'Goffice',
			'roles' => array(
				'moso_rendeles','bufe_rendeles'
				)
			);
	}

	public function install()
	{
		return TRUE;
	}

	public function uninstall()
	{
		//it's a core module, lets keep it around
		return TRUE;
	}

	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		/**
		 * Either return a string containing help info
		 * return "Some help info";
		 *
		 * Or add a language/help_lang.php file and
		 * return TRUE;
		 *
		 * help_lang.php contents
		 * $lang['help_body'] = "Some help info";
		*/
		return TRUE;
	}
}

/* End of file details.php */
