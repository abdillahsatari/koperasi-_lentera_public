<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regulations extends CI_Controller {

	public function index() {
		$content 	= '_homepageLayouts/adArt/index';
		$data 		= array('title'      	=> 'AD / ART ',
							'content'    	=> $content,);

		$this->load->view('_homepageLayouts/wrapper', $data);
	}
}
