<?php

namespace Drupal\hello2023\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class MyForm extends FormBase {

  public function getFormId() {
    return 'bizdemo_my_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
    ];
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $name = $form_state->getValue('name');
    $age = $form_state->getValue('age');
    $email = $form_state->getValue('email');
    $this->messenger()->addMessage($this->t('Name: @name', ['@name' => $name]));
    $this->messenger()->addMessage($this->t('Age: @age', ['@age' => $age]));
    $this->messenger()->addMessage($this->t('Email: @email', [
      '@email' => $email,
    ]));
  }

}
