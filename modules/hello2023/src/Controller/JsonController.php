<?php

namespace Drupal\hello2023\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonController extends ControllerBase
{
  public function hello()
  {
    $data = [
      'data' => $this->t('Say Hello!'),
    ];
    // 创建 JSON 响应
    $response = new JsonResponse();
    $response->setData($data);
    return $response;
  }

  public function dbTable()
  {
    // 查询表数据
    $database = \Drupal::database();
    $query = $database->select('biz_demo', 't');
    $query->fields('t', ['id', 'name', 'age']);
    // 调用 PagerInterface 对象实现分页
    $pager = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(5);
    // 执行查询
    $results = $pager->execute()->fetchAll();
    $rows = [];

    foreach ($results as $result) {
      $rows[] = [
        $result->id,
        $result->name,
        $result->age,
      ];
    }
    $data = [
      'data' => $rows,
    ];
    $response  = new JsonResponse();
    $response->setData($data);
    return $response;
  }

}
