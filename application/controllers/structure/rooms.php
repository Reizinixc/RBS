<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

include ('/application/core/my_structure_controller.php');

class Rooms extends MY_Structure_Controller {
  public function __construct() {
    parent::__construct();
    $this->load->model('room');
  }

  public function index($page = null) {
    $list = 50;

    $data['title'] = 'Room List';
    $query = $this->db->join('buildings', 'buildings.id = rooms.building_id', 'inner')->join('roomtypes', 'roomtypes.id = rooms.roomtype_id', 'inner')->select('rooms.name AS name, roomtypes.name AS roomtype, buildings.name AS building, capacity, imageURI, rooms.id AS id')->get('rooms', $list, $page * $list);
    $data['rooms'] = $query->result();
    $data['roomsPage'] = floor($query->num_rows() / $list);
    $data['page'] = $page ? $page : 0 ;

    $this->addView('structure/room');
    $this->loadView($data);
  }

  public function create() {
    $this->_formInclude();
    $this->load->model('building');
    $this->load->model('roomtype');

    $data['buildings'] = $this->building->findAll();
    $data['roomtypes'] = $this->roomtype->findAll();
    $data['title'] = 'Adding a room';

    if ($_SERVER['REQUEST_METHOD'] != 'POST') { // GET
      $this->addView('structure/room_create');
      $this->loadView($data);
    } else { // POST
      if ($this->_submit(0)) {
        $this->session->set_flashdata('msg',
          array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Successfully created new semester'
            )
          )
        );
        redirect('structure/rooms');
      } else {
        $this->addView('structure/room_create');
        $this->loadView($data);
      }
    }
  }

  public function edit($id) {
    $this->_formInclude();
    $this->load->model('building');
    $this->load->model('roomtype');

    $data['data'] = $this->room->find($id);
    $data['buildings'] = $this->building->findAll();
    $data['roomtypes'] = $this->roomtype->findAll();

    $data['title'] = 'Room Editing';

    if ($_SERVER['REQUEST_METHOD'] != 'POST') { // GET
      $this->addView('structure/room_edit');
      $this->loadView($data);
    } else { // POST
      $_POST['id'] = $this->room->id;

      if ($this->_submit($id)) {
        $this->session->set_flashdata('msg',
          array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Successfully edited.'
            )
          )
        );
        redirect('structure/rooms');
      } else {
        echo print_r($this->semester->getValidationErrors());
        $this->addView('structure/room_edit');
        $this->loadView($data);
      }
    }
  }

  private function _formInclude() {
    $this->load->helper('form');
    $this->load->library('form_validation');
    $this->load->helper('flashmsg');
  }

  private function _submit($id) {
    $this->form_validation->set_rules($this->room->getValidationRule());

    if ($this->form_validation->run() == false)
      return false;

    $this->room->id = $id;
    $this->room->name = $this->input->get_post('name');
    $this->room->building_id = $this->input->get_post('building');
    $this->room->roomType_id = $this->input->get_post('roomType');
    $this->room->capacity = $this->input->get_post('capacity');
    $this->room->imageURI = $this->input->get_post('imageURI');

    if (!$this->room->valid()) {
      echo print_r($this->room->getValidationErrors());
      echo print_r($this->room);
      return false;
    }

    return $this->room->save();
  }
}
