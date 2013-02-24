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
}
