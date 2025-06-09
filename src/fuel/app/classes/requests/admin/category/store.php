<?php

use Fuel\Core\Validation;

class Requests_Admin_Category_Store extends Requests_Common_Base
{
  public static function setValidator(Validation $val): Validation
  {
    $val->add('name', 'name')
      ->add_rule('required')
      ->add_rule('max_length', 255);

    return $val;
  }
}
