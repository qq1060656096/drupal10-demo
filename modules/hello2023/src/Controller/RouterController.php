<?php

namespace Drupal\hello2023\Controller;

use Drupal\Core\Controller\ControllerBase;
class RouterController extends ControllerBase
{
  public function hello($name)
  {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello @name!', ['@name' => $name]),
    ];
  }

  public function hello2($name)
  {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello @name!', ['@name' => $name]),
    ];
  }
}
