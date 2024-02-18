<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Programs extends CI_Controller {

	public function index() {
		$content 	= '_homepageLayouts/programKami/index';
		$data 		= array('title'      	=> 'Program Koperasi',
							'content'    	=> $content,);

		$this->load->view('_homepageLayouts/wrapper', $data);
	}
}
