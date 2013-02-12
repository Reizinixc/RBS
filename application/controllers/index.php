<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller {

  public function __construct() {
    parent::__construct();
  }

	/**
	 * Index Page for this controller.
	 */
	public function index() {
		$this->addView('index');
    $this->loadView(array('title' => 'Room Finding'));
	}
}

/* End of file index.php */
/* Location: ./application/controllers/welcome.php */