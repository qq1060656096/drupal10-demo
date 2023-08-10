<?php

namespace Drupal\zz100\Controller;

class Zz100HookController extends Zz100BaseController
{
  public function runHook()
  {
    $result = \Drupal::service('module_handler')->invokeAll('hello_say');
    var_dump($result);
    exit;
  }
}
