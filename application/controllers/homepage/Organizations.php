<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organizations extends CI_Controller {

	public function index() {
		$content 	= '_homepageLayouts/strukturOrganisasi/index';
		$data 		= array('title'      	=> 'Struktur Organisasi ',
							'content'    	=> $content,);

		$this->load->view('_homepageLayouts/wrapper', $data);
	}
}
