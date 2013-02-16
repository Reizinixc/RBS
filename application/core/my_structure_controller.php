<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Structure_Controller extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->addView('structure/aside.php');
  }
}