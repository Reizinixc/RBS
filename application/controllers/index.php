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
    $data['jsonRooms'] = json_encode($this->room->findAll());

    $this->addView('index');
    $this->loadView($data);
  }

  public function post() {
    echo "get ok";
    echo $this->input->post("id");
  }

  public function getajax() {
    $query = $this->db->query("SELECT * FROM bookings right outer join bookedroom on bookedroom.booking_id = bookings.id");

    foreach ($query->result_array() as $row) {
      echo $row['booking_id'];
      echo $row['room_id'];
    }
  }

  public function getjson($val = null) {
    $query = $this->db->query("SELECT * FROM rooms");
    $rooms = array();
    foreach ($query->result() as $row):
      $rooms[$row->id] = $row->name;
    endforeach;


    echo form_dropdown('rooms', $rooms, '', 'class="rooms"');
  }

  public function getjson2($x = null) {
    $query = $this->db->query("SELECT * FROM rooms WHERE id=$x");
    foreach ($query->result() as $row):
      echo $row->name;
    endforeach;
    echo "<br />";
    $query = $this->db->query("SELECT * FROM bookedroom right outer join bookings on bookedroom.room_id = bookings.id  right outer join users on bookings.user_id = users.id right outer join courses on bookings.course_code= courses.code WHERE room_id=$x");

    foreach ($query->result() as $row):
      echo '<button class="btn btn-large btn-primary" type="button">';

      echo "การเรียนการสอนประจำวิชา";
      echo $row->abbrName;
      echo "<br>";
      echo "วันที่จอง";
      echo $row->bookDate;
      echo "&nbsp";
      echo "เวลา";
      echo $row->startTime;
      echo " : ";
      echo $row->endTime;
      echo "<br>";
      echo "ชื่อผู้จอง";
      echo $row->name;
      echo "<br>";
    endforeach;


    echo '</button>';


    //$query = $this->db->query("SELECT * FROM bookedroom right outer join bookings on bookedroom.booking_id = bookings.id");
    //$query = $this->db->query("SELECT * FROM rooms");
    //    foreach ($query->result() as $row):
    //echo $row->booking_id;
    //echo $row->room_id;
    //      echo $x;
    //endforeach;

  }

}


/* End of file index.php */
/* Location: ./application/controllers/welcome.php */