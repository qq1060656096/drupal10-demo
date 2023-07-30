<?php

/**
 * @file
 * Contains \Drupal\otp_verification\Form\MiniorangeOTPVerificationCustomerSetup.
 */

namespace Drupal\otp_verification\Form;

use Drupal\Core\Url;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\otp_verification\MiniorangeOtpUtilities;
use Drupal\otp_verification\MiniorangeOTPVerificationCustomer;
use Drupal\otp_verification\MiniorangeOTPVerificationConstants;


class MiniorangeOTPVerificationCustomerSetup extends FormBase {
  public function getFormId()
  {
    return 'miniorange_otp_verification_customer_setup';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    global $base_url;
    $config = \Drupal::config('otp_verification.settings');

    $form['markup_library'] = array(
      '#attached' => array(
        'library' => array(
          "otp_verification/otp_verification.admin",
          'otp_verification/otp_verification.phone',
        )
      ),
    );

    $current_status = $config->get('miniorange_otp_verification_status');

    if ($current_status == 'VALIDATE_OTP') {

      $form['markup_top_otp'] = array(
        '#markup' => '<div class="mo_otp_verification_table_layout_1"><div class="mo_otp_verification_table_layout mo_otp_container">'
      );

      $form['miniorange_otp_verification_customer_otp_token'] = array(
        '#type' => 'textfield',
        '#title' => t('OTP<span style="color: red">*</span>'),
      );

      $form['miniorange_otp_verification_customer_validate_otp_button'] = array(
        '#type' => 'submit',
        '#value' => t('Validate OTP'),
        '#submit' => array('::miniorange_otp_verification_validate_otp_submit'),
      );

      $form['miniorange_otp_verification_customer_setup_resendotp'] = array(
        '#type' => 'submit',
        '#value' => t('Resend OTP'),
        '#submit' => array('::miniorange_otp_verification_resend_otp'),
      );

      $form['miniorange_otp_verification_customer_setup_back'] = array(
        '#type' => 'submit',
        '#value' => t('Back'),
        '#submit' => array('::miniorange_otp_verification_back'),
          '#suffix' => '</div><div>'
      );

      MiniorangeOtpUtilities::Two_FA_Advertisement($form, $form_state);

      return $form;
    } elseif ($current_status == 'PLUGIN_CONFIGURATION') {

      $form['markup_top_message'] = array(
        '#markup' => '<div class="mo_otp_verification_table_layout_1"><div class="mo_otp_verification_table_layout mo_otp_container">'
      );

      $form['markupboit_message'] = array(
        '#markup' => '<div class="mo_otp_verification_welcome_message">Thank you for registering with miniOrange</div></br><h4>Your Profile: </h4>'
      );

      $header = array(
        ['data' => t('ATTRIBUTE')],
        ['data' => t('VALUE')],
      );

      $options = array(
        ['Customer Email', $config->get('miniorange_otp_verification_customer_admin_email')],
        ['Customer ID', $config->get('miniorange_otp_verification_customer_id')],
        //['Token Key', $config->get('miniorange_otp_verification_customer_admin_token')],
        //['API Key', $config->get('miniorange_otp_verification_customer_api_key')],
        ['Drupal Version', \Drupal::VERSION],
        ['PHP Version', phpversion()],
      );

      $form['fieldset']['customerinfo'] = array(
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $options,
      );

      $form['markup_1'] = array(
        '#markup' => t('<br><h6>Track your remaining transactions:</h6>
          1. Click on the button below.<br>
          2. Login using the credentials you used to register for this module.<br>
          3. You will be presented with <b><i>View Transactions</i></b> page.<br>
          4. From this page you can track your remaining transactions<br><br>'),
      );

      $user_email = $config->get('miniorange_otp_verification_customer_admin_email');
      $transactionURL = MiniorangeOTPVerificationConstants::BASE_URL . '/moas/login?username=' . $user_email . '&redirectUrl='. MiniorangeOTPVerificationConstants::BASE_URL .'/moas/viewtransactions';

        $form['overview_view_transaction_button'] = [
            '#type' => 'link',
            '#title' => t('View Transactions'),
            '#url' => Url::fromUri($transactionURL),
            '#attributes' => ['class' => ['button', 'button--primary'], 'target' => '_blank'],
        ];

      $form['markup_2']['miniorage_remove_account'] = array(
        '#type' => 'link',
        '#title' => $this->t('Remove Account'),
        '#url' => Url::fromRoute('otp_verification.modal_form'),
        '#attributes' => [
          'class' => [
            'use-ajax',
            'button',
          ],
        ],
        '#suffix' => '<br/><br/></div>',
      );

      MiniorangeOtpUtilities::Two_FA_Advertisement($form, $form_state);

      return $form;
    }

    $tab = isset($_GET['tab']) && $_GET['tab'] == 'login' ? $_GET['tab'] : 'register';

    $url = $base_url . '/admin/config/people/otp_verification/customer_setup';

    $form['header_top_style_1'] = array('#markup' => '<div class="mo_otp_verification_table_layout_1">',);

    $form['markup_top'] = array(
      '#markup' => '<div class="mo_otp_verification_table_layout mo_otp_container">'
    );

    if ($tab == 'register') {

      $form['markup_14'] = array('#markup' => '<h3>Register with mini<span class="mo_orange"><b>O</b></span>range</h3><hr><br>');

      $form['markup_15'] = array(
        '#markup' => '<div class="mo_otp_verification_highlight_background_notes">Just complete the short registration below to configure' . ' the OTP Verification module. Please enter a valid email id that you have' . ' access to. You will be able to move forward after verifying an OTP' . ' that we will send to this email.</div>'
      );

      $form['markup_register_face'] = array(
        '#markup' => ' <p style="color: green">If you face any issues during registraion then you can <a href="https://www.miniorange.com/businessfreetrial" target="_blank"><b>click here</b></a> to quick register your account with miniOrange
                            and use the same credentials to login into the module.</br></p><br>'
      );

      $form['miniorange_otp_verification_customer_setup_username'] = array(
        '#type' => 'textfield',
        '#title' => t('Email<span style="color: red">*</span>'),
      );

      $form['miniorange_otp_verification_customer_setup_phone'] = array(
        '#type' => 'textfield',
        '#title' => t('Phone'),
        '#id' => 'query_phone',
        '#attributes' => array('class' => array('query_phone'),),
        '#description' => '<b>Note:</b> We will only call if you need support.',
      );

      $form['miniorange_otp_verification_customer_setup_password'] = array(
        '#type' => 'password_confirm',
      );

      $form['markup_break'] = array('#markup' => '<br>');

      $form['miniorange_otp_verification_customer_setup_button'] = array(
        '#type' => 'submit',
        '#button_type' => 'primary',
        '#value' => t('Register'),
      );

      $form['already_account_link'] = array(
        '#markup' => '<a href="' . $url . '/?tab=login" class="button">Already have an account ?</a>',
        '#prefix' => '<div class="otp_value">',
        '#suffix' => '</div>'
      );

      $form['markup_breaks'] = array('#markup' => '<br><br></div>');

    }else{
      $form['markup_15'] = array(
        '#markup' => '<h2>Login with mini<span class="mo_orange"><b>O</b></span>range</h2><hr>',
      );

      $form['markup_16'] = array(
        '#markup' => '<br><div class="mo_saml_highlight_background_note_2" style="width:35% !important;">Please login with your miniorange account.</b></div><br>'
      );

      $form['Mo_auth_customer_login_username'] = array(
        '#type' => 'email',
        '#title' => t('Email <span style="color: red">*</span>'),
        '#attributes' => array('style' => 'width:50%'),
      );

      $form['Mo_auth_customer_login_password'] = array(
        '#type' => 'password',
        '#title' => t('Password <span style="color: red">*</span>'),
        '#attributes' => array('style' => 'width:50%'),
      );

      $form['Mo_auth_customer_login_button'] = array(
        '#type' => 'submit',
        '#value' => t('Login'),
        '#button_type' => 'primary',
        '#limit_validation_errors' => array(),
        '#prefix' => '<br><div class="otp_row"><div class="otp_name">',
        '#suffix' => '</div>'
      );

      $form['register_link'] = array(
        '#markup' => '<a href="'.$url.'" class="button">Create an account?</a>',
        '#prefix' => '<div class="otp_value">',
        '#suffix' => '</div></div></div>'
      );
    }

    MiniorangeOtpUtilities::Two_FA_Advertisement($form, $form_state);

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');

    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'register';
    $phone = '';

    if ($tab == 'register') {
      $username = trim($form['miniorange_otp_verification_customer_setup_username']['#value']);
      $phone    = trim($form['miniorange_otp_verification_customer_setup_phone']['#value']);
      $password = trim($form['miniorange_otp_verification_customer_setup_password']['#value']['pass1']);
    }else{
      $username = trim($form['Mo_auth_customer_login_username']['#value']);
      $password = trim($form['Mo_auth_customer_login_password']['#value']);
    }

    if (empty($username) || empty($password)) {
      \Drupal::messenger()->addError(t('The <b><u>Email </u></b> and <b><u>Password</u></b> fields are mandatory.'));
      return;
    }

    if (!\Drupal::service('email.validator')->isValid($username)) {
      \Drupal::messenger()->addError(t('The email address <i>' . $username . '</i> is not valid.'));
      return;
    }

    $customer_config = new MiniorangeOTPVerificationCustomer($username, $phone, $password, NULL);
    $check_customer_response = json_decode($customer_config->checkCustomer());

    if ($check_customer_response->status == 'CUSTOMER_NOT_FOUND') {

      if ($tab == 'login') {
        \Drupal::messenger()->addError(t('The account with username <i>'.$username.'</i> does not exist.'));
        return;
      }

      $db_var->set('miniorange_otp_verification_customer_admin_email', $username)
        ->set('miniorange_otp_verification_customer_admin_phone', $phone)
        ->set('miniorange_otp_verification_customer_admin_password', $password)
        ->save();

      $send_otp_response = json_decode($customer_config->sendOtp());

      if ($send_otp_response->status == 'SUCCESS') {
        $current_status = 'VALIDATE_OTP';
        $db_var->set('miniorange_otp_verification_tx_id', $send_otp_response->txId)
          ->set('miniorange_otp_verification_status', $current_status)
          ->save();
        \Drupal::messenger()->addStatus(t('Verify email address by entering the passcode sent to @username', ['@username' => $username]));
      }
    } elseif ($check_customer_response->status == 'TRANSACTION_LIMIT_EXCEEDED') {
      \Drupal::messenger()->addError(t('An error has been occured while processing your request. Please try after some time.'));
    } elseif ($check_customer_response->status == 'CURL_ERROR') {
      \Drupal::messenger()->addError(t('cURL is not enabled. Please enable cURL.'));
    } else {
      $customer_keys_response = json_decode($customer_config->getCustomerKeys());

      if (json_last_error() == JSON_ERROR_NONE) {

        $current_status = 'PLUGIN_CONFIGURATION';

        $db_var->set('miniorange_otp_verification_customer_id', $customer_keys_response->id)
          ->set('miniorange_otp_verification_customer_admin_token', $customer_keys_response->token)
          ->set('miniorange_otp_verification_customer_admin_email', $username)
          ->set('miniorange_otp_verification_customer_admin_phone', $phone)
          ->set('miniorange_otp_verification_customer_api_key', $customer_keys_response->apiKey)
          ->set('miniorange_otp_verification_status', $current_status)
          ->save();

        \Drupal::messenger()->addStatus(t('Successfully retrieved your account.'));
      } else {
        \Drupal::messenger()->addError(t('Invalid credentials.'));
      }
    }
  }

  public function miniorange_otp_verification_back(&$form, $form_state) {
    $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');
    $current_status = 'CUSTOMER_SETUP';

    $db_var->set('miniorange_otp_verification_status', $current_status)->save();

    $db_var->clear('miniorange_miniorange_otp_verification_customer_admin_email')
      ->clear('miniorange_otp_verification_customer_admin_phone')
      ->clear('miniorange_otp_verification_tx_id')
      ->save();

    \Drupal::messenger()->addStatus(t('Register/Login with your miniOrange Account.'));
  }

  public function miniorange_otp_verification_resend_otp(&$form, $form_state)
  {

    $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');
    $config = \Drupal::config('otp_verification.settings');

    $username = $config->get('miniorange_otp_verification_customer_admin_email');
    $phone = $config->get('miniorange_otp_verification_customer_admin_phone');
    $customer_config = new MiniorangeOTPVerificationCustomer($username, $phone, NULL, NULL);
    $send_otp_response = json_decode($customer_config->sendOtp());
    if ($send_otp_response->status == 'SUCCESS') {
      // Store txID.
      $db_var->clear('miniorange_otp_verification_tx_id')->save();
      $current_status = 'VALIDATE_OTP';
      $db_var->set('miniorange_otp_verification_tx_id', $send_otp_response->txId)
        ->set('miniorange_otp_verification_status', $current_status)
        ->save();

      \Drupal::messenger()->addStatus(t('An OTP has been resent to @username', array('@username' => $username)));
    } else {
      \Drupal::messenger()->addError(t('An error occured while sending OTP. Please try again.'));
    }
  }

  public function miniorange_otp_verification_validate_otp_submit(&$form, $form_state) {
    $config = \Drupal::config('otp_verification.settings');
    $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');

    $otp_token = trim($form['miniorange_otp_verification_customer_otp_token']['#value']);
    if (empty($otp_token)){
      \Drupal::messenger()->addError(t('The <b>OTP</b> field is mandatory.'));
      return;
    }

    $otp_token = trim($otp_token);
    $username = $config->get('miniorange_otp_verification_customer_admin_email');
    $phone = $config->get('miniorange_otp_verification_customer_admin_phone');
    $tx_id = $config->get('miniorange_otp_verification_tx_id');
    $customer_config = new MiniorangeOTPVerificationCustomer($username, $phone, NULL, $otp_token);
    $validate_otp_response = json_decode($customer_config->validateOtp($tx_id));

    if ($validate_otp_response->status == 'SUCCESS') {
      $db_var->clear('miniorange_otp_verification_tx_id')->save();
      $password = $config->get('miniorange_otp_verification_customer_admin_password');
      $customer_config = new MiniorangeOTPVerificationCustomer($username, $phone, $password, NULL);
      $create_customer_response = json_decode($customer_config->createCustomer());

      if ($create_customer_response->status == 'SUCCESS') {
        $current_status = 'PLUGIN_CONFIGURATION';
        $db_var->set('miniorange_otp_verification_status', $current_status)
          ->set('miniorange_otp_verification_customer_admin_email', $username)
          ->set('miniorange_otp_verification_customer_admin_phone', $phone)
          ->set('miniorange_otp_verification_customer_admin_token', $create_customer_response->token)
          ->set('miniorange_otp_verification_customer_id', $create_customer_response->id)
          ->set('miniorange_otp_verification_customer_api_key', $create_customer_response->apiKey)
          ->save();

        \Drupal::messenger()->addStatus(t('You account has been created successfully. You can now proceed to configure the module.'));
      } else {
        \Drupal::messenger()->addError(t('An error has been occured while creating account. Please try again.'));
      }
    } else {
      \Drupal::messenger()->addError(t('Invalid OTP.'));
    }
  }
}
