<?php

namespace Drupal\zz100\Controller;

use Drupal\zz100\Biz\Zz100Biz;
use Symfony\Component\HttpFoundation\JsonResponse;

class Zz100ConfigController extends Zz100BaseController
{
  public function getDefaultConfig()
  {
      $zz100Biz = new Zz100Biz();
      $data = $zz100Biz->getDefault();
      return new JsonResponse($data);
  }
}
