<?php

namespace Drupal\hello2023\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

class AdminController extends ControllerBase
{
  public function index()
  {
    $header = [
      'Name',
      'Desc',
      'Link',
    ];
    $list = [
      [
        'name' => 'page hello',
        'desc' => 'page hello Desc',
        'route_name' => 'hello2023.page.hello',
      ],
      [
        'name' => 'page html',
        'desc' => 'page html Desc',
        'route_name' => 'hello2023.page.html',
      ],
      [
        'name' => 'page form',
        'desc' => 'page form Desc',
        'route_name' => 'hello2023.page.form',
      ],
      [
        'name' => 'page table',
        'desc' => 'page table Desc',
        'route_name' => 'hello2023.page.table',
      ],
      [
        'name' => 'page dbTable',
        'desc' => 'page dbTable Desc',
        'route_name' => 'hello2023.page.dbTable',
      ],
      [
        'name' => 'json hello',
        'desc' => 'json hello Desc',
        'route_name' => 'hello2023.json.hello',
      ],
      [
        'name' => 'json dbTable',
        'desc' => 'json dbTable Desc',
        'route_name' => 'hello2023.json.dbTable',
      ],
      [
        'name' => 'router hello',
        'desc' => 'router hello Desc',
        'route_name' => 'hello2023.router.hello',
        'router_param' => ['name'=>'张三'],
      ],
      [
        'name' => 'router hello2',
        'desc' => 'router hello2 Desc',
        'route_name' => 'hello2023.router.hello2',
      ],

      [
        'name' => 'module sms',
        'desc' => 'module sms Desc',
        'route_name' => 'hello2023.module.sms.send',
      ],
    ];
    $rows = [];
    foreach ($list as $row) {
      $parameter = isset($row['router_param']) ? $row['router_param'] : [];
      $url = Url::fromRoute($row['route_name'], $parameter);
      $link = Link::fromTextAndUrl('view', $url)->toString();
      $rows[] = [
        $row['name'],
        $row['desc'],
        $link,
      ];
    }

    $build = [];
    // Build the table
    $build['table'] = [
      '#prefix' => '<h1>Admin Links</h1>',
      '#theme' => 'table',
      '#attributes' => [
        'data-striping' => 0
      ],
      '#header' => $header,
      '#rows' => $rows,
    ];

    return $build;
  }
}
