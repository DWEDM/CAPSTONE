<?php

class Model extends Database
{

  public function __construct()
  {
    if (!property_exists($this, 'table')) {

      $this->table = strtolower(get_class($this)) . 's';
    }
  }

  public function findAll()
  {
    $query = "select * from  $this->table";
    $result = $this->query($query);
    if ($result) {
      return $result;
    }
    return false;
  }

  public function where($data, $data_not = [])
  {
    $keys = array_keys($data);
    $keys_not = array_keys($data_not);

    $query = "select * from  $this->table where ";

    foreach ($keys as $key) {
      $query .= $key . " = :" . $key . " && ";
    }

    foreach ($keys_not as $key) {
      $query .= $key . " != :" . $key . " && ";
    }

    $query = trim($query, ' && ');

    $data = array_merge($data, $data_not);
    $result = $this->query($query, $data);

    if ($result) {
      return $result;
    }
    return false;
  }

  public function first($data, $data_not = [])
  {
    $keys = array_keys($data);
    $keys_not = array_keys($data_not);

    $query = "select * from  $this->table where ";

    foreach ($keys as $key) {
      $query .= $key . " = :" . $key . " && ";
    }

    foreach ($keys_not as $key) {
      $query .= $key . " != :" . $key . " && ";
    }

    $query = trim($query, ' && ');

    $data = array_merge($data, $data_not);
    $result = $this->query($query, $data);

    if ($result) {
      return $result[0];
    }
    return false;
  }

  public function insert($data)
  {
    $columns = implode(', ', array_keys($data));
    $values = implode(', :', array_keys($data));
    $query = "insert into $this->table ($columns) values (:$values)";

    $this->query($query, $data);

    return false;
  }

  public function update($id, $data, $column = 'id')
  {
    $keys = array_keys($data);
    $query = "update $this->table set ";

    foreach ($keys as $key) {
      $query .= $key . " = :" . $key . ", ";
    }

    $query = trim($query, ", ");

    $query .= " where $column = :$column";

    $data[$column] = $id;
    $this->query($query, $data);

    return false;
  }

  public function delete($id, $column = 'id')
  {
      $data[$column] = $id;
      $query = "DELETE FROM $this->table WHERE $column = :$column";
  
      // Execute the query and check if it affected any rows
      $stmt = $this->query($query, $data);
  
      // Return true if the query was successful (affected rows > 0)
      if ($stmt && $stmt->rowCount() > 0) {
          return true;
      }
  
      return false;
  }
  
  public function findUserByUsername()
  {
      // Check if session exists
      if (!isset($_SESSION['username'])) {
          return false; // If no session, return false
      }

      // Get the logged-in user's username from session
      $username = $_SESSION['username'];

      // Query the database to find the user by the username
      $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
      $result = $this->query($query, ['username' => $username]);

      // Return the user data if found
      if ($result) {
          return $result[0]; // Return the first matching user
      }

      return false; // Return false if no user is found
  }
  public function isInUse($id, $relatedTable, $foreignKey)
  {
      $query = "SELECT COUNT(*) as count FROM $relatedTable WHERE $foreignKey = :id";
      $result = $this->query($query, ['id' => $id]);
  
      if ($result && $result[0]->count > 0) {
          return true; // Row is in use
      }
      
      return false; // Row is not in use
  }
}