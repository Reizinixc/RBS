<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
  private $views = array();

  public function __construct() {
    parent::__construct();
  }

  public function addView($view) {
    $this->views[] = $view;
  }

  public function loadView($data = array(), $return = false) {
    $content = '';
    foreach ($this->views as $view) {
      $content .= $this->load->view($view, $data, true);
    }
    $data['content'] = $content;

    // Load the master view
    $this->load->view('layout/application', $data, $return);
  }
}
