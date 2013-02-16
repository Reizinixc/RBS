<?php if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Building extends MY_Model {
  /**
   * The id for primary key in Buildings table.
   * @var int
   */
  var $id = 0;
  /**
   * Building name (not greater than 128 characters.
   * @var string
   */
  var $name = null;

  public function __construct() {
    parent::__construct('buildings');
  }

  public function find($id) {
    $query = $this->db->get_where($this->tablename, array('id' => $id), 1);

    return $query->num_rows() ? $query->result()[0] : false;
  }

  public function valid() {
    $this->errors = array();

    $this->id = (integer)$this->id;

    if (strlen($this->name) > 128)
      $this->errors[] = 'The building name is too long.';

    return empty($this->errors);
  }

  public function getPKAttr() {
    return array('id' => $this->id);
  }

  public function getValidationRule() {
    return
      array(
        array(
          "field" => "buildingname",
          "label" => "Building Name",
          "rules" => "trim|required|max_length[128]|xss_clean"
        )
      );
  }
}