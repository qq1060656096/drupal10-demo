<?php

namespace Drupal\hello2023\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Pager\Pager;
use Drupal\Core\Pager\PagerManager;
use Drupal\Core\Url;

class PageController extends ControllerBase
{
  public function hello()
  {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Say Hello!'),
    ];
  }

  public function html()
  {
    return [
      '#type' => 'markup',
      '#markup' => '<h2 style="color:red">' . $this->t('Say Hello!') . '</h2>',
    ];
  }


  /**
   * 表单
   * @return array
   */
  public function form()
  {
    return \Drupal::formBuilder()->getForm('\Drupal\bizdemo\Form\MyForm');
  }


  /**
   * 静态页面分页（分页从0开始）
   * @return array
   */
  public function table()
  {
    $count = 100;
    $limit = 10;
    /* @var PagerManager $pagerManager */
    $pagerManager = \Drupal::getContainer()->get('pager.manager');
    $pager = $pagerManager->createPager($count, $limit);
    $currentPage = $pager->getCurrentPage();

    $header = [
      'ID',
      'Name',
      'Age',
    ];
    $rows = [];
    for ($i = 1; $i <= $count; $i++) {
      $id = $i;
      $age = rand(1, 100);
      $rows[] = [
        $id,
        'test '.$i,
        $age,
      ];
    }
    $rows = array_slice($rows, $currentPage * $limit, $limit);
    $build = [];
    // Build the table
    $build['table'] = [
      '#prefix' => '<h1>Static Table Pagination</h1>',
      '#theme' => 'table',
      '#attributes' => [
        'data-striping' => 0
      ],
      '#header' => $header,
      '#rows' => $rows,
    ];

    $build['#cache'] = [
      'max-age' => 0,
      'no-cache' => TRUE,
    ];

    // Create pager
    $build['pager'] = [
      '#type' => 'pager',
      '#quantity' => $limit,
      '#total' => $count,
    ];
    return $build;
  }

  /**
   * 数据库分页
   *
   * @return array[]
   */
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
    $header = [
      'ID',
      'Name',
      'Age',
    ];
    foreach ($results as $result) {
      $rows[] = [
        $result->id,
        $result->name,
        $result->age,
      ];
    }
    $build = [];
    // Build the table
    $build['table'] = [
      '#prefix' => '<h1>Db Display Table Pagination</h1>',
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    $build['#cache'] = [
      'max-age' => 0,
      'no-cache' => TRUE,
    ];

    // Create pager
    $build['pager'] = [
      '#type' => 'pager',
    ];
    return $build;
  }
}
