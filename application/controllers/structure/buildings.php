<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

  include ('/application/core/my_structure_controller.php');

class Buildings extends MY_Structure_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      $query = $this->db->get('buildings');

      $data['buildings'] = $query->result();
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
        $this->db->insert('buildings', array('name' => $this->input->get_post('buildingname', true)));
        $this->session->set_flashdata('msg', array(array('type' => 'success', 'head' => '', 'msg' => "Successfully added building {$this->input->get_post('buildingname')}")));
      }
      redirect(site_url('structure/buildings'));
    }
  }

  public function edit($id) {
    $data = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Update Data
      $this->load->library('form_validation');
      $this->load->helper('form');
      $this->load->helper('flashmsg');

      $this->form_validation->set_rules('buildingname', 'Building Name', 'trim|required|max_length[128]|xss_clean');
      if ($this->form_validation->run() == false) {
        echo flashmsg('error', '', form_error('buildingname'));
        redirect(current_url());
      } else {
        $this->db->update('buildings', array('name' => $this->input->get_post('buildingname', true)), array('id' => $id));
        $this->session->set_flashdata('msg', array(array('type' => 'success', 'head' => '', 'msg' => "Successfully edited building to {$this->input->get_post('buildingname')}")));
        redirect(site_url('structure/buildings'));
      }
    } else {
      // Render form
      $building = $this->_getBuilding($id);
      if ($building !== false) {
        $data['buildingname'] = $building[0]->name;
        $data['title'] = 'Building Editing';
      } else {
        $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => "Building not found. Maybe deleted by other user.")));
        redirect(site_url('structure/buildings'));
      }

      $this->addView('structure/building_edit');
      $this->loadView($data);
    }
  }

  public function delete($id) {
    $buildings = $this->_getBuilding($id);

    if ($buildings === false) {
      $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => "Building not found. Maybe deleted by other user.")));
    } else {
      $buildingname = $buildings[0]->name;

      if ($this->db->delete('buildings', array('id' => $id))) {
        $this->session->set_flashdata('msg', array(array('type' => 'success', 'head' => '', 'msg' => "Successfully deleted building $buildingname")));
      } else {
        $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => "Cannot delete $buildingname. May be this building was booked from other user.")));
      }

      redirect(site_url('structure/buildings'));
    }
  }

  /**
   * Get the building object from building id
   * @param number $id
   * @return boolean false if cannot find
   * @return array list of id
   */
  private function _getBuilding($id) {
    $query = $this->db->get_where('buildings', array('id' => $id));

    return $query->num_rows() ? $query->result() : false;
  }
}
