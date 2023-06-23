<?php

namespace Drupal\hello2023\Controller;

use Drupal\Core\Controller\ControllerBase;

class AdminController extends ControllerBase
{
  public function index()
  {
    $header = [
      'Name',
      'Desc',
      'Link',
    ];
    $rows = [];
    $rows[] = [
      'Hello2023',
      'Hello2023 Desc',
      \Drupal::l('Hello2023 Link', Url::fromRoute('hello2023.admin')),
    ];

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
