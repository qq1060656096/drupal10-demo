<?php

/**
 * @file
 * Contains \Drupal\otp_verification\Form\MiniorangeSettings.
 */

namespace Drupal\otp_verification\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;
use Drupal\otp_verification\MiniorangeOTPVerificationCustomer;
use Drupal\user\Entity\User;
use Drupal\otp_verification\MiniorangeOtpUtilities;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\otp_verification\MiniorangeOTPVerificationConstants;
use Drupal\Component\Utility\Unicode;


class MiniorangeValidateUser extends FormBase
{
  public function getFormId()
  {
      return 'miniorange_otp_verification_validate_user';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

      global $base_url, $user, $form, $form_state;

      // DO NOT DELETE
      MiniorangeOtpUtilities::isSessionStarted();
      MiniorangeOtpUtilities::set_otp_cookie('trans_id', $_GET['tx_id']);
	//Dont delete this
    //check if url is hit directly without registration
    /* if (!isset($_SESSION['otp_status']) || !isset($_SESSION['arr2'])) {
       $response = new RedirectResponse($base_url . '/user/register?q=Access Denied.');
       $response->send();
     }*/

    \Drupal::service('page_cache_kill_switch')->trigger();
    $form = self::miniorange_otp_login_build_form($form, $form_state);
    return $form;
  }

  /**
   * Custom form build.
   */
  function miniorange_otp_login_build_form($form, &$form_state, $success_form = TRUE)
  {
      $submit_attributes = array();
      $form = self::miniorange_otp_login_build_form_content($form, $form_state, $success_form);
      $form['loader']['#markup'] = '</div><div class="mo_otp-modal-mo_otp_container mo_otp-modal-footer">';

      $form['actions']['submit'] = array(
          '#type' => 'submit',
          '#value' => 'Validate',
          '#submit' => array('::miniorange_otp_validation_user_validate'),
      );

      $form['actions']['resend'] = array(
          '#type' => 'submit',
          '#value' => t('Resend OTP'),
          '#attributes' => $submit_attributes,
          '#limit_validation_errors' => array(),
          '#submit' => array('::miniorange_otp_resend_otp'),
      );

      $form['actions']['back'] = array(
          '#type' => 'submit',
          '#value' => t('Back'),
          '#attributes' => $submit_attributes,
          '#limit_validation_errors' => array(),
          '#submit' => array('::miniorange_otp_back'),
      );

      return $form;
  }

  function miniorange_otp_login_build_form_content($form, $form_state, $success_form = TRUE)
  {
      return self::miniorange_otp_login_build_otp_validation_form($form, $form_state, $success_form);
  }

  function miniorange_otp_back($form, $form_state)
  {
      global $base_url;
      $response = new RedirectResponse($base_url . '/user/register');
      $response->send();
      exit;
  }

  /**
   * Custom form build with error message.
   */
  function miniorange_otp_build_form_with_error_message(&$form_state)
  {
      $form = array();
      $form = self::miniorange_otp_build_form($form, $form_state, FALSE);
      $form_state['complete form']['header']['#markup'] = $form['header']['#markup'];
      return $form;
  }

  /**
   * Custom validation form build.
   */
  public function miniorange_otp_login_build_otp_validation_form($form, &$form_state, $success_message = TRUE)
  {
      $config = \Drupal::config('otp_verification.settings');
      $otp_options = $config->get('miniorange_otp_options');
      MiniorangeOtpUtilities::isSessionStarted();

       $otp_sent = MiniorangeOtpUtilities::get_otp_cookie('otp_status');
      if (isset($_GET['otpto']) && $otp_sent == 1)
      {
          $code = $_GET['otpto'];
          $msg = self::miniorange_email_phone_success_msg($otp_options, $code);
          \Drupal::messenger()->addMessage(t($msg), 'status');
          MiniorangeOtpUtilities::set_otp_cookie('otp_status', 0);
      }

      if ($otp_options == 0)
      {
          $form['otp'] = array(
              '#name' => 'otp',
              '#type' => 'textfield',
              '#attributes' => array('autofocus' => 'autofocus',),
              '#title' => t('OTP'),
              '#default_value' => '',
              '#size' => 60,
              '#description' => t('Please enter the OTP that has been sent to the registered Email ID.'),
              '#maxlength' => 15,
              '#required' => true,
          );
          return $form;
      } elseif ($otp_options == 1)
      {
          $form['otp'] = array(
              '#name' => 'otp',
              '#type' => 'textfield',
              '#attributes' => array('autofocus' => 'autofocus',),
              '#title' => t('OTP'),
              '#default_value' => '',
              '#size' => 60,
              '#description' => t('Please enter the OTP that has been sent to the registered Phone.'),
              '#maxlength' => 15,
              '#required' => true,
          );
          return $form;
      }
  }

  public function miniorange_email_phone_success_msg($otp_options, $code)
  {
      $config = \Drupal::config('otp_verification.settings');
      if ($otp_options == 0) {
          $success_msg = $config->get('miniorange_success_email_otp_message', '');

          if (!empty($success_msg))
              $success_msg = str_replace('##email##', $code, $success_msg);
          else
              $success_msg = 'A One Time Passcode has been sent to ' . $code . '. Please enter the OTP below to verify your email address. If you cannot see the email in your inbox, make sure to check your SPAM folder.';
      }

      if ($otp_options == 1) {
          $success_msg = $config->get('miniorange_success_phone_otp_message', '');
          if (!empty($success_msg))
              $success_msg = str_replace('##phone##', $code, $success_msg);
          else
              $success_msg = 'A One Time Passcode has been sent to ' . $code . '. Please enter the OTP below to verify your phone number.';
      }

      return $success_msg;
  }


  /**
   * Handling and validating user.
   */
  public function miniorange_otp_validation_user_validate($form, &$form_state)
  {
      $config = \Drupal::config('otp_verification.settings');
      $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');

      MiniorangeOtpUtilities::isSessionStarted();

      $otp_options = $config->get('miniorange_otp_options');
      $mail = MiniorangeOtpUtilities::get_otp_cookie('arr2')['mail'];

      $otp_token = $form_state->getValue('otp');
      $otp_token = trim($otp_token);

      $trans_id = MiniorangeOtpUtilities::get_otp_cookie('trans_id');
      $trans_id = base64_decode($trans_id);

      \Drupal::messenger()->deleteAll();

      if (!MiniorangeOtpUtilities::isCurlInstalled())
      {
          return json_encode(array(
              "status" => 'CURL_ERROR',
              "statusMessage" => '<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.',
          ));
      }

      $url = MiniorangeOTPVerificationConstants::BASE_URL . '/moas/api/auth/validate';

      global $base_url;
      $customer_key = $config->get('miniorange_otp_verification_customer_id');
      $api_key = $config->get('miniorange_otp_verification_customer_api_key');

      if ($otp_options == 1)
      {
          $fields = array(
              'txId' => $trans_id,
              'token' => $otp_token,
              'customerKey' => $customer_key,
              'username' => $mail,
              'authType' => 'PHONE',
          );
      } else {
          $fields = array(
              'txId' => $trans_id,
              'token' => $otp_token,
              'customerKey' => $customer_key,
              'username' => $mail,
              'authType' => 'EMAIL',
          );
      }

      $mo_otp_verification_customer = new MiniorangeOTPVerificationCustomer(null,null,null,null,$customer_key,$api_key);
      $content = $mo_otp_verification_customer->callService($url, $fields,true);

      $db_var->set('miniorange_otp_token', $otp_token)->save();
      $otp_status = json_decode($content)->status;

      \Drupal::messenger()->deleteAll();

      if ($otp_status == "SUCCESS") {
          $arr2 = MiniorangeOtpUtilities::get_otp_cookie('arr2');
          $default_role = "authenticated user";
          $custom_fields = $config->get('miniorange_custom_fields_registration', '');
          $custom_fields_register = isset($custom_fields) ? explode(';', $custom_fields) : '';

          $new_user = array(
              'name' => $arr2['name'],
              'mail' => $arr2['mail'],
              'pass' => MiniorangeOtpUtilities::getNestedValue($arr2['pass']),
              'status' => isset( $arr2['status'] ) ? $arr2['status'] : 1,
              'preferred_langcode' => isset($arr2['preferred_langcode']) ? $arr2['preferred_langcode'] : 'en',
          );

        if ($otp_options == 1){
          $machine_name_of_phone_field = $config->get('machine_name_of_phone_field');
          $new_user[$machine_name_of_phone_field] = MiniorangeOtpUtilities::getNestedValue($arr2[$machine_name_of_phone_field]);
        }

        //Handling custom fields
        if ($custom_fields_register[0] != '') {
            foreach ($custom_fields_register as $custom) {
              $new_user[trim($custom)] = MiniorangeOtpUtilities::getNestedValue( $arr2[$custom] );
            }
        }

        $is_it_updation= MiniorangeOtpUtilities::get_otp_cookie('is_it_updation');
        if(isset($is_it_updation) && $is_it_updation){

            $current_phone_number = MiniorangeOtpUtilities::get_otp_cookie('current_phone_number');
            $account=\Drupal::currentUser();
            $current_user = \Drupal::currentUser();
            $uid = $current_user->id();
            $account = User::load($account->id());
            $account->field_phone_number = $current_phone_number;
            $account->save();
            \Drupal::messenger()->addMessage(t('The changes have been saved.'), 'status');
            $response = new RedirectResponse($base_url . '/user/'.$uid.'/edit');
            $response->send();
            exit;
        }

        $account = User::create($new_user);
        $account->save();

        //setting timezone
        if(isset($arr2['timezone'])){
          $account->set('timezone',$arr2['timezone']);
          $account->save();
        }

        //Setting profile picture
        if( $arr2['user_picture'][0]['fids'] !== '' ) {
          $account->set('user_picture', $arr2['user_picture'][0]['fids']);
          $account->save();
        }

        //Handling roles
        if(isset($arr2['roles'])) {
          foreach ($arr2['roles'] as $role) {
            $account->addRole($role);
            $account->save();
          }
        }

          !empty(\Drupal::config('otp_verification.settings')->get('otp_login_url')) ? $edit['redirect'] = \Drupal::config('otp_verification.settings')->get('otp_login_url') : $edit['redirect'] = $base_url;
          user_login_finalize($account);

          // No administrator approval required.
          _user_mail_notify('register_no_approval_required', $account);

          \Drupal::messenger()->addMessage(t('Registration successful. Now you are logged in.'), 'status');
          $response = new RedirectResponse($edit['redirect']);
          $response->send();
          exit;
      }
      elseif ($otp_status == "FAILED")
      {
          $invalid_otp_msg = $config->get('miniorange_invalid_otp_message', '');
          if (!empty($invalid_otp_msg))
              \Drupal::messenger()->addMessage(t($invalid_otp_msg), 'error');
          else
              \Drupal::messenger()->addMessage(t('OTP entered is incorrect. Please enter valid OTP.'), 'error');
      }
  }


  /**
   * Resend OTP
   */
  function miniorange_otp_resend_otp(&$form, $form_state)
  {
      global $_miniorange_otp_x;

      MiniorangeOtpUtilities::isSessionStarted();

      $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');
      $config = \Drupal::config('otp_verification.settings');
      $user_values = MiniorangeOtpUtilities::get_otp_cookie('arr2');
      $umail = $user_values['mail'];

      $otp_options = $config->get('miniorange_otp_options');
      \Drupal::messenger()->deleteAll();

      if ($otp_options == 0) {
          $emailcount = mb_strlen($umail);
          $emailc1 = mb_substr($umail, 0, 3);
          $emailc2 = mb_substr($umail, $emailcount - 4, $emailcount);

          for ($i = 4; $i <= $emailcount - 4; $i++)
          {
              $_miniorange_otp_x = "X" . $_miniorange_otp_x;
          }

          $email_code = $emailc1 . $_miniorange_otp_x . $emailc2;
          if (!MiniorangeOtpUtilities::isCurlInstalled())
          {
              return json_encode(array(
                  "status" => 'CURL_ERROR',
                  "statusMessage" => '<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.',
              ));
          }
          $url = MiniorangeOTPVerificationConstants::BASE_URL . '/moas/api/auth/challenge';
          $customer_key = $config->get('miniorange_otp_verification_customer_id');
          $api_key = $config->get('miniorange_otp_verification_customer_api_key');

          $fields = array(
              'customerKey' => $customer_key,
              'email' => $umail,
              'authType' => 'EMAIL',
              'transactionName' => 'Drupal OTP Verification',
          );

          $mo_otp_verification_customer = new MiniorangeOTPVerificationCustomer(null,null,null,null,$customer_key, $api_key);
          $content = $mo_otp_verification_customer->callService($url, $fields,true);

          $trans_status = json_decode($content)->status;

          if ($trans_status == "SUCCESS") {
              $tx_id = json_decode($content)->txId;
              MiniorangeOtpUtilities::set_otp_cookie('trans_id', $tx_id);
              \Drupal::messenger()->addMessage(t('An OTP has been resent to @otp_resend', array('@otp_resend' => $email_code)), 'status');
          } else {
              $error_phone_msg = $config->get('miniorange_error_email_otp_message', '');
              if (!empty($error_phone_msg))
                  \Drupal::messenger()->addMessage(t($error_phone_msg), 'error');
              else
                  \Drupal::messenger()->addMessage(t('There was an error in sending the OTP.'), 'error');
          }
      }
      else
      {
          $ph = MiniorangeOtpUtilities::get_otp_cookie('phone_during_register');
          $phno = mb_strlen($ph);
          $phbr1 = mb_substr($ph, 0, 4);
          $phbr2 = mb_substr($ph, $phno - 2, $phno);

          for ($i = 5; $i <= $phno - 2; $i++)
          {
              $_miniorange_otp_x = "X" . $_miniorange_otp_x;
          }

          $ph_code = $phbr1 . $_miniorange_otp_x . $phbr2;

          if (!MiniorangeOtpUtilities::isCurlInstalled()) {
              return json_encode(array(
                  "status" => 'CURL_ERROR',
                  "statusMessage" => '<a href="http://php.net/manual/en/curl.installation.php">PHP cURL extension</a> is not installed or disabled.',
              ));
          }

          $url = MiniorangeOTPVerificationConstants::BASE_URL . '/moas/api/auth/challenge';
          $customer_key = $config->get('miniorange_otp_verification_customer_id');
          $api_key = $config->get('miniorange_otp_verification_customer_api_key');

          $fields = array(
              'customerKey' => $customer_key,
              'phone' => $ph,
              'authType' => 'SMS',
              'transactionName' => 'Drupal OTP Verification',
          );

          $mo_otp_verification_customer = new MiniorangeOTPVerificationCustomer(null,null,null,null, $customer_key, $api_key);
          $content = $mo_otp_verification_customer->callService($url, $fields,true);

          $trans_status = json_decode($content)->status;

          if ($trans_status == "SUCCESS") {
              $tx_id = json_decode($content)->txId;
              MiniorangeOtpUtilities::set_otp_cookie('trans_id', $tx_id);
              \Drupal::messenger()->addMessage(t('OTP has been resent to @otp_resend_phone', array('@otp_resend_phone' => $ph_code)), 'status');
          }
          else
          {
              $error_phone_msg = $config->get('miniorange_error_phone_otp_message', '');
              if (!empty($error_phone_msg))
                  \Drupal::messenger()->addMessage(t($error_phone_msg), 'error');
              else
                  \Drupal::messenger()->addMessage(t('There was an error in sending the OTP.'), 'error');
          }
      }
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }
}
