<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

include 'application/core/my_booking_controller.php';

class Bookings extends MY_Booking_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('booking');
  }

  public function index($room_id = null) {
    if (!$room_id) {
      // Show all current booking
      $data['emptyMsg'] = "No booking. Would you like to create one?";
      $data['canEdit'] = true;
      $data['title'] = $this->session->userdata('name')."'s Booking List";
      $data['bookings'] = $this->booking->findAllByUser($this->session->userdata('user_id'));


      $this->addView('booking/index');
    } else {
      // Show booking details
      throw new Exception("Unimplemented");
    }
    $this->loadView($data);
  }

  /**
   * Show pending request booking
   *
   * Permission: Staff
   */
  public function pending() {
    $data['bookings'] = $this->booking->findPending();
    $bookingsCount = count($data['bookings']);
    $data['title'] = ($bookingsCount ? "($bookingsCount) " : "")."Pending List";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['accept'])) {
        if ($this->booking->approve($this->input->get_post('accept'), 1)) {
          $this->session->set_flashdata(array('msg' => array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Successfully accepted pending request'
            )
          )));
        } else {
          $this->session->set_flashdata(array('msg' => array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Cannot accept pending request'
            )
          )));
        }
      } else if (isset($_POST['reject'])) {
        if ($this->booking->approve($this->input->get_post('reject'), 2)) {
          $this->session->set_flashdata(array('msg' => array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Successfully rejected pending request'
            )
          )));
        } else
          $this->session->set_flashdata(array('msg' => array(
            array(
              'type' => 'success',
              'head' => '',
              'msg' => 'Cannot reject pending request'
            )
          )));
      } else {
        $this->session->set_flashdata(array('msg' => array(
          array(
            'type' => 'success',
            'head' => '',
            'msg' => 'Cannot reject pending request'
          )
        )));
      }
      redirect('bookings/pending');
    }
    $this->addView('booking/pending');
    $this->loadView($data);
  }

  /**
   * Showing booking request history
   *
   * Permission: Logged in user
   */
  public function history() {
    $data['canEdit'] = false;
    $data['title'] = "History Booking Request";
    $data['emptyMsg'] = "No history booking data";
    $data['bookings'] = $this->booking->findHistory($this->session->userdata('user_id'));

    $this->addView('booking/history');
    $this->loadView($data);
  }

  public function create() {
    $this->_includeForm();
    $data = $this->_initDataArray();
    $data['title'] = "Create a Booking Request";
    $data['bookingRooms'] = array(0);
    // Initialize data
    $data['data'] = new Booking();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $this->form_validation->set_rules('startDate', 'Start', 'trim|xss_clean|required|callback__sqlDate|callback__conflict');
      $this->form_validation->set_rules('endDate', '', 'trim|xss_clean|required|callback__sqlDate');
      $this->form_validation->set_rules('startTime', 'End', 'trim|xss_clean|required|callback__sqlTime');
      $this->form_validation->set_rules('endTime', '', 'trim|xss_clean|required|callback__sqlTime');
      $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
      foreach ($days as $day) {
        $dayAttr = "isEvery".$day;
        $_POST[$dayAttr] = isset($_POST[$dayAttr]) ? $_POST[$dayAttr] : 0;
        $this->form_validation->set_rules($dayAttr, $day, 'trim|xss_clean|is_natural');
      }
      $this->form_validation->set_rules('rooms', 'Rooms', 'trim|xss_clean|is_natural');
      $this->form_validation->set_rules('bookingObjective', 'Objective', 'trim|xss_clean|required|is_natural');
      $this->form_validation->set_rules('course_code', 'Course Code', 'trim|xss_clean|numeric|min_length[6]|max_length[8]');
      $this->form_validation->set_rules('additionObjective', 'Activity Name', 'trim|xss_clean|required|max_length[64]');

      $this->booking->id = 0;

      if ($this->form_validation->run() == false) {
        $this->addView('booking/create');
        $this->loadView($data);
      } else {
        $this->booking->user_id = $this->session->userdata('user_id');
        $this->booking->bookDate = null;
        $this->booking->approveStatus_id = 0;
        foreach ($days as $day) {
          $dayAttr = "isEvery".$day;
          $this->booking->$dayAttr = $this->input->get_post($dayAttr);
        }
        $this->booking->startDate = $this->input->get_post('startDate');
        $this->booking->startTime = $this->input->get_post('startTime');
        $this->booking->endDate = $this->input->get_post('endDate');
        $this->booking->endTime = $this->input->get_post('endTime');
        $this->booking->bookingObjective_id = $this->input->get_post('bookingObjective');
        $this->booking->course_code = $this->input->get_post('course_code');
        $this->booking->additionObjective = $this->input->get_post('additionObjective');

        if ($this->booking->_insert(array($this->input->get_post('rooms')))) {
          $this->session->set_flashdata(array('msg' => array(array(
            'type' => 'success',
            'head' => '',
            'msg' => 'Successfully create booking request'
          ))));
        } else {
          $this->session->set_flashdata(array('msg' => array(array(
            'type' => 'error',
            'head' => '',
            'msg' => 'Failed to create booking request'
          ))));
        }
        redirect('bookings');
      }

    } else { // GET
      $this->addView('booking/create');
      $this->loadView($data);
    }
  }

  public function edit($id) {
    $this->_includeForm();

    $data = $this->_initDataArray();
    $data['title'] = "Editing Booking Request";
    $bookingRoomsQuery = $this->db->select('room_id')->distinct()->get_where('timeslots', "booking_id = $id");
    $data['bookingRooms'] = $bookingRoomsQuery->num_rows() ? $bookingRoomsQuery->result()[0]->room_id : array(0);
    $data['data'] = $this->booking->find($id);
    if ($data['data'] === false) {
      $this->session->set_flashdata(array('msg' => array(array(
        'type' => 'error',
        'head' => '',
        'msg' => 'Cannot find booking request. Maybe deleted or invalid booking request ID'
      ))));
      redirect('bookings');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') { // POST
      $this->form_validation->set_rules('startDate', 'Start', 'trim|xss_clean|required|callback__sqlDate|callback__conflict');
      $this->form_validation->set_rules('endDate', '', 'trim|xss_clean|required|callback__sqlDate');
      $this->form_validation->set_rules('startTime', 'End', 'trim|xss_clean|required|callback__sqlTime');
      $this->form_validation->set_rules('endTime', '', 'trim|xss_clean|required|callback__sqlTime');
      $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
      foreach ($days as $day) {
        $dayAttr = "isEvery".$day;
        $_POST[$dayAttr] = isset($_POST[$dayAttr]) ? $_POST[$dayAttr] : 0;
        $this->form_validation->set_rules($dayAttr, $day, 'trim|xss_clean|is_natural');
      }
      $this->form_validation->set_rules('rooms', 'Rooms', 'trim|xss_clean|is_natural');
      $this->form_validation->set_rules('bookingObjective', 'Objective', 'trim|xss_clean|required|is_natural');
      $this->form_validation->set_rules('course_code', 'Course Code', 'trim|xss_clean|numeric|min_length[6]|max_length[8]');
      $this->form_validation->set_rules('additionObjective', 'Activity Name', 'trim|xss_clean|required|max_length[64]');

      $this->booking->id = $id;

      if ($this->form_validation->run() == false) {
        $this->addView('booking/edit');
        $this->loadView($data);
      } else {
        $this->booking->user_id = $data['data']->user_id;
        $this->booking->bookDate = $data['data']->bookDate;
        $this->booking->approveStatus_id = 0;
        foreach ($days as $day) {
          $dayAttr = "isEvery".$day;
          $this->booking->$dayAttr = $this->input->get_post($dayAttr);
        }
        $this->booking->startDate = $this->input->get_post('startDate');
        $this->booking->startTime = $this->input->get_post('startTime');
        $this->booking->endDate = $this->input->get_post('endDate');
        $this->booking->endTime = $this->input->get_post('endTime');
        $this->booking->bookingObjective_id = $this->input->get_post('bookingObjective');
        $this->booking->course_code = $this->input->get_post('course_code');
        $this->booking->additionObjective = $this->input->get_post('additionObjective');

        if ($this->booking->_update(array($this->input->get_post('rooms')))) {
          $this->session->set_flashdata(array('msg' => array(array(
            'type' => 'success',
            'head' => '',
            'msg' => 'Successfully edited booking request'
          ))));
        } else {
          $this->session->set_flashdata(array('msg' => array(array(
            'type' => 'error',
            'head' => '',
            'msg' => 'Failed to edit booking request'
          ))));
        }
        redirect('bookings');
      }
    } else { // GET
      $this->addView('booking/edit');
      $this->loadView($data);
    }
  }

  public function delete($id) {
    $this->booking->id = $id;
    if ($this->booking->delete()) {
      $this->session->set_flashdata(array('msg' => array(array(
        'type' => 'success',
        'head' => '',
        'msg' => 'Successfully deleted booking request.'
      ))));
    } else {
      $this->session->set_flashdata(array('msg' => array(array(
        'type' => 'error',
        'head' => '',
        'msg' => 'Failed to deleted booking request.'
      ))));
    }
    redirect('bookings');
  }

  public function _conflict() {
    $room_id = $this->input->get_post('rooms');

    date_default_timezone_set('Asia/Bangkok');
    $startDate = new DateTime($this->input->get_post('startDate'));
    $startTime = explode(':', $this->input->get_post('startTime'));
    $startTime = new DateInterval("PT$startTime[0]H$startTime[1]M");
    $endDate = new DateTime($this->input->get_post('endDate').' '.$this->input->get_post('endTime'));
    $endTime = explode(':', $this->input->get_post('endTime'));
    $endTime = new DateInterval("PT$endTime[0]H$endTime[1]M");

    $eventOccurred = 0;
    for ($currentDate = clone $startDate; $currentDate <= $endDate; $currentDate->add(new DateInterval('P1D'))) {
      if (!$this->input->get_post("isEvery".$currentDate->format('D')))
        continue;

      ++$eventOccurred;
      $currentStartTime = clone $currentDate;
      $currentStartTime->add($startTime);
      $currentEndTime = clone $currentDate;
      $currentEndTime->add($endTime);

      $bookingResult = $this->booking->isConflict($room_id, $currentStartTime->format('Y-m-d H:i'), $currentEndTime->format('Y-m-d H:i'));
      if ($bookingResult !== false) {
        $bookings = $this->db->get_where('bookings', "id = $bookingResult")->result()[0];
        $this->form_validation->set_message('_conflict', "This booking is conflict with $bookings->additionObjective at ".$currentStartTime->format('d M y H:i')." <a href='".site_url("bookings/$bookings->id")."'>See Conflict Booking Request</a>");
        return false;
      }
    }

    if (!$eventOccurred) {
      $this->form_validation->set_message('_conflict', 'Booking range cannot make any request');
      return false;
    } else {
      return true;
    }
  }

  public function _sqlDate($str) {
    if (!preg_match("/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}$/", $str)) {
      $this->form_validation->set_message('_sqlDate', 'Invalid date format');
      return false;
    } else {
      return true;
    }
  }

  public function _sqlTime($str) {
    if (!preg_match("/^[0-9]{1,2}:[0-9]{1,2}(:[0-9]{1,2})?$/", $str)) {
      $this->form_validation->set_message('_sqlTime', 'Invalid time format');
      return false;
    } else {
      return true;
    }
  }

  private function _initDataArray() {
    $data['stylesheets'] = array('asset/css/datepicker.css', 'asset/css/timepicker.min.css');
    $data['jsscripts'] = array(site_url('asset/js/layout/bootstrap-datepicker.js'), site_url('asset/js/layout/bootstrap-timepicker.min.js'));
    $data['bookingObjectives'] = $this->db->get('bookingObjectives')->result();
    $data['jsonRooms'] = json_encode($this->db->select('id, name')->get('rooms')->result());
    $data['jsonCourses'] = json_encode($this->db->get('courses')->result());
    return $data;
  }

  private function _includeForm() {
    $this->load->library('form_validation');
    $this->load->helper('form');
    $this->load->helper('flashmsg');
  }
}
