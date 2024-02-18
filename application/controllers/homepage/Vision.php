<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vision extends CI_Controller {

	public function index() {
		$content 	= '_homepageLayouts/visiMisi/index';
		$data 		= array('title'      	=> 'Visi Misi ',
							'content'    	=> $content,);

		$this->load->view('_homepageLayouts/wrapper', $data);
	}
}
