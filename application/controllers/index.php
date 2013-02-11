<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->load->view('index');
	}
}

/* End of file index.php */
/* Location: ./application/controllers/welcome.php */