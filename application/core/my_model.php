<?php

class MY_Model extends CI_Model {
  /**
   * Table name for manipulate data.
   * @var string
   */
  private $tablename;

  /**
   * Validation error array.
   * @var array
   */
  protected $errors;

  public function __construct($tablename = null) {
    parent::__construct();
    $this->tablename = $tablename;
  }

  /**
   * Get all record in table
   *
   * @return mixed
   */
  public function findAll() {
    return $this->db->get($this->tablename)->result();
  }

  /**
   * Is object stored in database?
   *
   * @return bool
   */
  public function isStored() {
    return !!$this->db->get_where($this->tablename, $this->getPKAttr(), 1)->num_rows();
  }

  /**
   * Save the current attribute to database
   * If primary is
   * @return bool|CI_DB_active_record
   */
  public function save() {
    return $this->isStored() === false ? !!$this->insert() : $this->update();
  }

  private function insert() {
    return $this->valid() ? !!$this->db->insert($this->tablename, $this) : false;
  }

  private function update() {
    return $this->valid() ? !!$this->db->update($this->tablename, $this, $this->getPKAttr()) : false;
  }

  /**
   * Delete this object from database
   * @return bool
   */
  public function delete() {
    return !!$this->db->delete($this->tablename, $this->getPKAttr(), 1);
  }

  /**
   * Validate the object's attribute.
   *
   * @return bool
   */
  public function valid() {
    throw new Exception('Unimplemented');
  }

  /**
   * Get the array of validation error.
   *
   * @return array
   */
  public function getValidationErrors() {
    return $this->errors;
  }

  /**
   * Get primary key associative array.
   *
   * @return array Associative array of primary key
   */
  protected function getPKAttr() {
    throw new Exception('Unimplemented');
  }

  public function getValidationRule() {
    throw new Exception('Unimplemented');
  }
}