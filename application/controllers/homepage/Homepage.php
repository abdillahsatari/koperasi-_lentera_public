<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends CI_Controller {

    public function index() {
		$content 	= '_homepageLayouts/homepage/index';
		$data 		= array('title'      	=> 'Homepage ',
							'content'    	=> $content,);

        $this->load->view('_homepageLayouts/wrapper', $data);
    }
}
