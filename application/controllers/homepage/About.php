<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends CI_Controller {

	public function index() {
		$content 	= '_homepageLayouts/tentang/index';
		$data 		= array('title'      	=> 'Tentang',
							'content'    	=> $content,);

		$this->load->view('_homepageLayouts/wrapper', $data);
	}
}
