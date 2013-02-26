<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Booking_Controller extends MY_Controller {

  public function __construct() {
    parent::__construct();

    $userRoleID = $userRoleID = $this->session->userdata('userrole_id');
    if ($userRoleID == null) {
      $this->session->set_flashdata(array('msg' => array(array(
        'type' => 'error',
        'head' => '',
        'msg' => 'Cannot access to the configuration by permission.'
      ))));

      redirect(site_url());
    }

    $this->addView('booking/aside');
  }
}
