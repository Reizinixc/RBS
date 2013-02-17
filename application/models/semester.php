<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Semester extends MY_Model {
  /**
   * @var datetime
   */
  var $year;
  /**
   * @var int
   */
  var $semesterPeriod_id;
  /**
   * @var datetime
   */
  var $startDateTime;
  /**
   * @var datetime
   */
  var $endDateTime;

  public function __construct() {
    parent::__construct('semesters');
  }

  /**
   * @param int $limit
   * @param int $offset
   * @return CI_DB_mysql_result
   */
  public function findAll($limit = null, $offset = null) {
    return $this->db->get($this->tablename, $limit, $offset);
  }

  public function find($year, $semesterPeriod) {
    $query = $this->db->get_where($this->tablename, array('year' => $year, 'semesterPeriod_id' => $semesterPeriod), 1);

    return $query->num_rows() ? $query->result()[0] : false;
  }

  public function valid() {
    $this->errors = array();

    if (strlen($this->year) != 4 and !is_int($this->year))
      $this->errors[] = "Human year must have 4 digits only";

    if (!is_int($this->semesterPeriod_id) and $this->semesterPeriod_id < 0)
      $this->errors[] = "Semester Period must be integer";

    if (!preg_match("/^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$/", $this->startDateTime))
      $this->errors[] = "Start date and time is invalid";

    if (!preg_match("/^([0-9]{4}-[0-9]{1,2}-[0-9]{1,2})$/", $this->endDateTime))
      $this->errors[] = "End date and time is invalid";

    if (strtotime($this->startDateTime) > strtotime($this->endDateTime)) {
      $this->errors[] = "Start date must start before end date";
    }

    return empty($this->errors);
  }

  public function getPKAttr() {
    return array('year' => $this->year, 'semesterPeriod_id' => $this->semesterPeriod_id);
  }

  public function getValidationRule() {
    return array(
      array(
        'field' => 'year',
        'label' => 'Year',
        'rules' => 'required|min_length[4]|max_length[4]|is_natural_no_zero|xss_clean'
      ),
      array(
        'field' => 'semesterPeriod',
        'label' => 'Semester Period',
        'rules' => 'required|is_natural|xss_clean'
      ),
      array(
        'field' => 'startDate',
        'label' => 'Start Date',
        'rules' => 'required|xss_clean'
      ),
      array(
        'field' => 'endDate',
        'label' => 'End Date',
        'rules' => 'required|xss_clean'
      )
    );
  }
}
