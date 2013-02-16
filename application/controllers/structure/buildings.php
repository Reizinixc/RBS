<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

  include ('/application/core/my_structure_controller.php');

class Buildings extends MY_Structure_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('building');
  }

  public function index() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      $data['buildings'] = $this->building->findAll();
      $data['title'] = 'Building List';

      $this->addView('structure/building');
      $this->loadView($data);
    } else {
      $this->load->library('form_validation');
      $this->load->helper('form');
      $this->load->helper('flashmsg');

      $this->form_validation->set_rules('buildingname', 'Building Name', 'trim|required|max_length[128]|xss_clean');
      if ($this->form_validation->run() == false) {
        echo flashmsg('error', '', form_error('buildingname'));
      } else {
        $this->building->name = $this->input->get_post('buildingname', true);
        if ($this->building->save())
          $this->session->set_flashdata('msg', array(array('type' => 'success', 'head' => '', 'msg' => "Successfully added building {$this->input->get_post('buildingname')}")));
      }
      redirect(site_url('structure/buildings'));
    }
  }

  public function edit($id) {
    $this->load->library('form_validation');
    $this->load->helper('form');
    $this->load->helper('flashmsg');

    $data = array();
    $data['form_errors'] = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Updating Data
      $this->form_validation->set_rules($this->building->getValidationRule());
      if ($this->form_validation->run() == false) {
        $this->addView('structure/building_edit');
        $this->loadView();
      } else {
        $this->building->id = $id;
        $this->building->name = $this->input->get_post('buildingname', true);

        if ($this->building->save()) {
          $this->session->set_flashdata('msg', array(array('type' => 'success', 'head' => '', 'msg' => "Successfully edited building to {$this->input->get_post('buildingname')}")));
          redirect(site_url('structure/buildings'));
        }
      }
      // END UPDATING DATA
    } else {
      // RENDER FORM
      $building = $this->building->find($id);
      if ($building !== false) {
        $data['buildingname'] = $building->name;
        $data['title'] = 'Building Editing';
      } else {
        $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => "Building not found. Maybe deleted by other user.")));
        redirect(site_url('structure/buildings'));
      }

      $this->addView('structure/building_edit');
      $this->loadView($data);
      // END RENDERFORM
    }
  }

  public function delete($id) {
    $building = $this->building->find($id);

    if ($building === false) {
      $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => "Building not found. Maybe deleted by other user.")));
    } else {
      $this->building->id = $id;
      $buildingname = $building->name;

      if ($this->building->delete()) {
        $this->session->set_flashdata('msg', array(array('type' => 'success', 'head' => '', 'msg' => "Successfully deleted building $buildingname")));
      } else {
        $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => "Cannot delete $buildingname. May be this building was booked from other user.")));
      }

      redirect(site_url('structure/buildings'));
    }
  }
}