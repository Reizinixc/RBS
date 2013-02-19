<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Room extends MY_Model {
  /**
   * Room ID
   * @var int
   */
  var $id;
  /**
   * Room name
   * @var string
   */
  var $name;
  /**
   * Building ID
   * @var int
   */
  var $building_id;
  /**
   * Room type ID
   * @var int
   */
  var $roomType_id;
  /**
   * Capacity of room
   * @var int
   */
  var $capacity;
  /**
   * Image URI
   * @var string
   */
  var $imageURI;

  public function __construct() {
    parent::__construct('rooms');
  }

  public function find($id) {
    $query = $this->db->get_where($this->tablename, array('id' => $id));
    return $query->num_rows() ? $query->result()[0] : false;
  }

  public function valid() {
    $this->errors = array();

    if (!((is_numeric($this->id) and $this->id > 0) or !is_null($this->id)))
      $this->errors['id'][] = "ID must be a integer";

    if (strlen($this->name) > 64)
      $this->errors['name'][] = "Name must be less than 64 characters";

    if (!is_numeric($this->building_id) or $this->building_id <= 0)
      $this->errors['building_id'][] = "Building ID must be a integer";

    if (!is_numeric($this->capacity) or $this->capacity <= 0)
      $this->errors['capacity'][] = "Capacity must be a integer";

    if (strlen($this->imageURI) > 128)
      $this->errors['imageURI'][] = "Image URL must be less than 128 characters";

    return empty($this->errors);
  }

  public function getImageURI($id = null) {
    $target = md5($id ? $id : $this->id);
    return site_url("assert/img/room/$target");
  }

  public function getPKAttr() {
    return array(
      'id' => $this->id
    );
  }

  public function getValidationRule() {
    return
      array(
        array(
          "field" => "name",
          "label" => "Name",
          "rules" => "trim|required|max_length[64]|xss_clean"
        ),
        array(
          "field" => "building",
          "label" => "Building",
          "rules" => "trim|required|is_natural_no_zero|xss_clean"
        ),
        array(
          "field" => "roomType",
          "label" => "Room Type",
          "rules" => "trim|required|is_natural_no_zero|xss_clean"
        ),
        array(
          "field" => "imageURI",
          "label" => "Image",
          "rules" => "trim|xss_clean"
        )
      );
  }
}
