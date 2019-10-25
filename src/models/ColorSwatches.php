<?php

namespace fieldwork\colorswatches\models;

class ColorSwatches
{
  /**
   * @var string
   */
  public $values = [];

  public function __construct($value)
  {
    if (!empty($value)) {
      if (!is_array($value)) {
        $value = json_decode($value);
      }

      foreach ($value as $val) {
        $this->values[] = new ColorSwatch($val);
      }
    }
  }

  public function __toString()
  {
    return serialize( $this->labels() );
  }

  public function colors()
  {
    return array_map(function($val) {
      return $val->color;
    }, $this->values);
  }

  public function labels()
  {
    return array_map(function($val) {
      return $val->label;
    }, $this->values);
  }
}

class ColorSwatch
{
  /**
   * @var string
   */
  public $label = '';

  /**
   * @var string
   */
  public $color = '';

  public function __construct($value)
  {
    if (!empty($value)) {
      if (is_array($value)) {
        $this->label = $value['label'];
        $this->color = $value['color'];
      }
      else {
        $this->label = $value->label;
        $this->color = $value->color;
      }
    }
  }

  public function __toString()
  {
    return $this->label;
  }
}
