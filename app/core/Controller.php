<?php

class Controller
{
  protected function model($model)
  {
    require_once "../app/models/{$model}.php"; // Adjust the path if necessary
    return new $model();
  }
  public function view($name, $data = [])
  {
    if (!empty($data)) {
      extract($data);
    }

    if (file_exists('../app/views/' . $name . '.php')) {

      require '../app/views/' . $name . '.php';
    } else {

      require '../app/views/404.php';
    }
  }
}