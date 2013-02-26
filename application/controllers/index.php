<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Index extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->helper('form');
    $this->load->model('room');
  }

  public function index() {
    $data['title'] = "Room Finding";
    $data['jsonRooms'] = json_encode($this->db->join('buildings', 'buildings.id = rooms.building_id', 'left')->select("rooms.id AS id, rooms.name AS name, buildings.name AS buildingName, imageURI")->get('rooms')->result());

    $this->addView('index');
    $this->loadView($data);
  }

  public function loadRoom() {
    $room_id = $this->input->get_post('room_id');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data['rooms'] = $this->db->join('buildings', 'rooms.building_id = buildings.id', 'left')->join('roomTypes', 'roomtypes.id = rooms.roomType_id', 'left')->select('rooms.id AS id, rooms.name AS name, buildings.name AS building, roomtypes.name AS roomType, capacity, imageURI')->get_where('rooms', "rooms.id = $room_id")->result()[0];
      $data['bookingDetails'] = $this->db->join('bookingcarddetails', 'bookingcarddetails.id = timeslots.booking_id', 'left')->order_by('startDateTime')->select('booking_id, room_id, startDateTime, endDateTime, name, approveStatus, bookingObjective, course_code, additionObjective, isEverySun, isEveryMon, isEveryTue, isEveryWed, isEveryThu, isEveryFri, isEverySat')->get_where("timeslots", "room_id = $room_id")->result();
      echo $this->load->view('room', $data, true);
    }
  }
}

/* End of file index.php */
/* Location: ./application/controllers/welcome.php */