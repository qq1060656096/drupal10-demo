<?php

namespace Drupal\zz_login_register\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * ZZ Login Register registration form.
 */
class ZZLoginRegisterForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'zz_login_register_register';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => '名字',
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => '姓氏',
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => '邮箱',
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => '密码',
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => '注册',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // 获取提交的值
    $first_name = $form_state->getValue('first_name');
    $last_name = $form_state->getValue('last_name');
    $email = $form_state->getValue('email');
    $password = $form_state->getValue('password');

    // 创建用户账号
    $user = \Drupal::entityTypeManager()
      ->getStorage('user')
      ->create([
        'name' => $email,
        'mail' => $email,
        'status' => 1,
        'pass' => $password,
        'field_first_name' => $first_name,
        'field_last_name' => $last_name,
      ]);
    $user->enforceIsNew();
    $user->save();

    // 发送注册成功的消息
    drupal_set_message('注册成功！');

    // 跳转到登录页面
    $form_state->setRedirect('zz_login_register.login');
  }
}
