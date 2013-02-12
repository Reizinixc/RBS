<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Auth or Authentication controller เป็น controller ที่มีหน้าที่ handle การยืนยันตัวตนของผู้ใช้
 */
class Auth extends MY_Controller {

  function __construct() {
    parent::__construct();
  }

  /**
   * Login method
   */
  public function login() {
    if ($this->session->userdata('islogined') === '1')
      redirect(site_url());

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->load->library('form_validation');

      $this->form_validation->set_rules('username', 'username', 'trim|required|max_length[16]|xss_clean|alpha_numeric');
      $this->form_validation->set_rules('password', 'password', 'required|max_length[64]|xss_clean');

      if ($this->form_validation->run() == true) {
        $username = $this->input->get_post('username', true);
        $password = $this->input->get_post('password', true);

        $query = $this->db->get_where('users', array(
          'username' => $username,
          'password' => md5($username.'\'s is '.$password.'.')
        ));

        if ($query->num_rows() == 1) {
          $result = $query->result()[0];
          $this->session->set_userdata(array(
            'logined' => '1',
            'name' => $result->name,
            'email' => $result->email,
            'user_id' => $result->id,
            'userrole_id' => $result->userRole_id
          ));

          $this->session->set_flashdata('msg', array(array(
            'type' => 'success',
            'head' => '',
            'msg' => 'Login Successfully'
          )));

          redirect(site_url()); // Login Successfully

        } else {
          $this->session->set_flashdata('msg', array(array(
            'type' => 'error',
            'head' => '',
            'msg' => 'Wrong username and password combination.'
          )));
          redirect(site_url('login'));
        }
      } else {
        $this->load->helper('form');
        $this->session->set_flashdata('msg', array(array(
          'type' => 'error',
          'head' => '',
          'msg' => form_error('username').'<br />'.form_error('password')
        )));
        redirect(site_url('login'));
      }
    }

    $this->addView('auth/login');
    $this->loadView(array('title' => 'Login Required'));
  }

  /**
   * Logout method
   */
  public function logout() {
    $this->session->sess_destroy();
    $this->session->sess_create();
    $this->session->set_flashdata('msg', array(array(
      'type' => 'success',
      'head' => '',
      'msg' => 'Logout successfully'
    )));

    redirect(site_url(''));
  }
}
