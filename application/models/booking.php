<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Booking extends MY_Model {
  /**
   * @var int|null
   */
  var $id;
  /**
   * @var int
   */
  var $user_id;
  /**
   * @var datetime
   */
  var $bookDate;
  /**
   * @var int
   */
  var $approveStatus_id;
  /**
   * @var bool
   */
  var $isEverySun;
  /**
   * @var bool
   */
  var $isEveryMon;
  /**
   * @var bool
   */
  var $isEveryTue;
  /**
   * @var bool
   */
  var $isEveryWed;
  /**
   * @var bool
   */
  var $isEveryThu;
  /**
   * @var bool
   */
  var $isEveryFri;
  /**
   * @var bool
   */
  var $isEverySat;
  /**
   * @var datetime
   */
  var $startDate;
  /**
   * @var datetime
   */
  var $endDate;
  /**
   * @var datetime
   */
  var $startTime;
  /**
   * @var datetime
   */
  var $endTime;
  /**
   * @var int
   */
  var $bookingObjective_id;
  /**
   * @var string
   */
  var $course_code;
  /**
   * @var string
   */
  var $additionObjective;


  public function __construct() {
    parent::__construct('bookings');
  }

  public function findAllByUser($user_id) {
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    return $this->db->where("user_id = $user_id AND ((endDate >= '$currentDate') OR (endDate = '$currentDate' AND endTime >= '$currentTime'))")->order_by('startDate, startTime', 'asc')->get('bookingcarddetails')->result();
  }

  public function findPending() {
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    return $this->db->where("approveStatus = 0 AND ((endDate >= '$currentDate') OR (endDate = '$currentDate' AND endTime >= '$currentTime'))")->get('bookingcarddetails')->result();
  }

  public function findHistory($user_id) {
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i');
    return $this->db->where("user_id = $user_id AND ((endDate < '$currentDate') OR (endDate = '$currentDate' AND endTime < '$currentTime'))")->order_by('startDate, startTime', 'desc')->get('bookingcarddetails')->result();
  }

  public function isBooked($room_id, $startDateTime, $endDateTime) {
    $query = $this->db->get_where("timeslots", "room_id = $room_id AND startDateTime = '$startDateTime' AND endDateTime = '$endDateTime'");
    return $query->num_rows() ? $query->result()[0]->booking_id : false;
  }

  public function isConflict($room_id, $startDateTime, $endDateTime) {
    return $this->isBooked($room_id, $startDateTime, $endDateTime) !== false;
  }

  public function approve($booking_id, $status) {
    if ($status == 2) {
      // Deallocate time slots
      if (!$this->db->delete('timeslots', "booking_id = $booking_id"))
        return false;
    }

    return $this->db->update('bookings', array('approveStatus_id' => $status), "id = $booking_id");
  }
}
