<?php

namespace Drupal\zz_login_register\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * ZZ Login Register login form.
 */
class ZZLoginLoginForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'zz_login_register_login';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => '用户名',
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => '密码',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => '登录',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // 获取提交的值
    $username = $form_state->getValue('username');
    $password = $form_state->getValue('password');

    // 使用Drupal核心的用户登录函数进行验证
    $uid = user_authenticate($username, $password);

    if ($uid) {
      // 登录成功
      user_login_finalize($uid);
      drupal_set_message('登录成功！');
    }
    else {
      // 登录失败
      \Drupal::logger('zz_login_register')->error('登录失败，用户名或密码错误。');
      drupal_set_message('登录失败，用户名或密码错误。', 'error');
    }
  }
}
