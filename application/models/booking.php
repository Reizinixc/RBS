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

  public function isBooked($room_id, string $startDateTime, string $endDateTime) {
    $query = $this->db->get_where("timeslots", "room_id = $room_id AND startDateTime = '$startDateTime' AND endDateTime = '$endDateTime'");
    return $query->num_rows() ? $query->result()[0]->booking_id : false;
  }

  public function isConflict($room_id, $startDateTime, $endDateTime) {
    $query = $this->db->get_where("timeslots", "room_id = $room_id AND startDateTime = '$startDateTime' AND endDateTime = '$endDateTime' AND booking_id != $this->id");
    return $query->num_rows() ? $query->result()[0]->booking_id : false;
  }

  public function approve($booking_id, $status) {
    if ($status == 2) {
      // Deallocate time slots
      if (!$this->db->delete('timeslots', "booking_id = $booking_id"))
        return false;
    }

    return $this->db->update('bookings', array('approveStatus_id' => $status), "id = $booking_id");
  }

  public function valid() {
    $this->errors = array();

    return empty($this->errors);
  }

  public function _insert($rooms) {
    if ($this->db->insert($this->tablename, $this)) {
      $this->id = $this->db->order_by('id', 'desc')->select('id')->get('bookings', 1)->result()[0]->id;
      var_dump($this);
      return $this->insertTimeSlot($rooms);
    } else {
      return false;
    }
  }

  public function _update($rooms) {
    $result = true;

    $this->db->trans_begin();
    $result = ($result and $this->deleteTimeSlot());
    $result = ($result and $this->insertTimeSlot($rooms));
    if ($result == true) {
      $this->db->trans_commit();
    } else {
      $this->db->trans_rollback();
    }
    $this->db->update('bookings', $this, $this->getPKAttr());

    return $result;
  }

  public function delete() {
    if ($this->deleteTimeSlot()) {
      return parent::delete();
    } else {
      return false;
    }
  }

  public function find($id) {
    $query = $this->db->where("id = $id")->get($this->tablename);
    return $query->num_rows() ? $query->result()[0] : false;
  }

  private function insertTimeSlot($rooms) {
    $batchSlots = array();

    $endDate = new DateTime($this->endDate);
    $startTime = explode(':', $this->startTime);
    $startTime = new DateInterval("PT$startTime[0]H$startTime[1]M");
    $endTime = explode(':', $this->endTime);
    $endTime = new DateInterval("PT$endTime[0]H$endTime[1]M");

    for ($currentDate = new DateTime($this->startDate); $currentDate <= $endDate; $currentDate->add(new DateInterval('P1D'))) {
      $dayAttr = "isEvery".$currentDate->format('D');
      if (!$this->$dayAttr)
        continue;
      $startDateTime = clone $currentDate;
      $startDateTime->add($startTime);
      $endDateTime = clone $currentDate;
      $endDateTime->add($endTime);

      foreach ($rooms as $room) {
        $batchSlots[] = array('booking_id' => $this->id, 'room_id' => $room, 'startDateTime' => $startDateTime->format('Y-m-d H:i'), 'endDateTime' => $endDateTime->format('Y-m-d H:i'));
      }
    }

    return $this->db->insert_batch('timeslots', $batchSlots);
  }

  private function deleteTimeSlot() {
    return $this->db->delete('timeslots', "booking_id = $this->id");
  }

  public function getPKAttr() {
    return array('id' => $this->id);
  }
}