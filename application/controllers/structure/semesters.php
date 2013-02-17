<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include ('/application/core/my_structure_controller.php');

class Semesters extends MY_Structure_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('semester');
  }

  public function index() {
    $data['semesters'] = $this->db->join('semesterperiods', 'semesterperiods.id = semesters.semesterperiod_id')->get('semesters')->result();
    $data['title'] = 'Semester List';

    $this->addView('structure/semester');
    $this->loadView($data);
  }

  public function edit($year, $semesterPeriod_id) {
    $this->_formInclude();

    $data['data'] = $this->semester->find($year, $semesterPeriod_id);
    $data['stylesheets'] = array(site_url('asset/css/datepicker.css'));
    $data['jsscripts'] = array(site_url('asset/js/layout/bootstrap-datepicker.js'));
    $data['title'] = 'Semester Editing';
    $data['method'] = 'edit';
    $data['semesterPeriods'] = $this->db->get('semesterperiods')->result();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') { // GET
      if ($data['data'] === false) {
        $this->session->set_flashdata('msg', array(array('type' => 'error', 'head' => '', 'msg' => 'Cannot find semester. May be other user deleted it.')));
        redirect('structure/semesters');
      } else {
        $this->addView('structure/semester_edit');
        $this->loadView($data);
      }

    } else { // POST
      $_POST['year'] = $year;
      $_POST['semesterPeriod'] = $semesterPeriod_id;

      if ($this->_submit($year, $semesterPeriod_id)) {
        $this->session->set_flashdata(msg,
          array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Successfully edited.'
            )
          )
        );
        redirect('structure/semesters');
      } else {
        echo print_r($this->semester->getValidationErrors());
        $this->addView('structure/semester_edit');
        $this->loadView($data);
      }
    }
  }

  public function create() {
    $this->_formInclude();

    $data['stylesheets'] = array(site_url('asset/css/datepicker.css'));
    $data['jsscripts'] = array(site_url('asset/js/layout/bootstrap-datepicker.js'));

    $data['method'] = 'create';
    $data['title'] = 'Adding a semester';
    $data['semesterPeriods'] = $this->db->get('semesterperiods')->result();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') { // GET
      $this->addView('structure/semester_create');
      $this->loadView($data);
    } else { // POST
      if ($this->_submit($this->input->get_post('year'), $this->input->get_post('semesterPeriod'))) {
        $this->session->set_flashdata('msg',
          array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Successfully created new semester'
            )
          )
        );
        redirect('structure/semesters');
      } else {
        $this->addView('structure/semester_create');
        $this->loadView($data);
      }
    }
  }

  public function delete($year, $semesterPeriod_id) {
    $this->semester->year = $year;
    $this->semester->semesterPeriod_id = $semesterPeriod_id;
    $result = $this->semester->delete();

    $this->session->set_flashdata('msg',
      array(
        array(
          'type' => $result ? 'success' : 'error',
          'head' => '',
          'msg' => $result ? 'Successfully deleted semester' : 'Cannot delete the semester. May be someone booked the room during semester'
        )
      ));

    redirect('structure/semesters');
  }

  private function _formInclude() {
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('flashmsg');
  }

  private function _submit($year, $semesterPeriod_id) {
    $this->form_validation->set_rules($this->semester->getValidationRule());

    if ($this->form_validation->run() == false)
      return false;

    $this->semester->year = $year;
    $this->semester->semesterPeriod_id = $semesterPeriod_id;
    $this->semester->startDateTime = $this->input->get_post('startDate');
    $this->semester->endDateTime = $this->input->get_post('endDate');

    if (!$this->semester->valid())
      return false;

    return $this->semester->save();
  }
}