<?php

/**
 * @file
 * Contains \Drupal\otp_verification\Form\MiniorangeSettings.
 */

namespace Drupal\otp_verification\Form;

use Drupal\user\Entity\User;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\otp_verification\MiniorangeOtpUtilities;

class MiniorangeSettings extends FormBase
{
  public function getFormId()
  {
    return 'miniorange_otp_verification_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {
    global $base_url;
    $config = \Drupal::config('otp_verification.settings');
    $conf_url = $base_url . '/admin/config/people/otp_verification/configuration';
    $config_url = $base_url . '/admin/config/people/accounts';
    $isCustomerRegisterd = !MiniorangeOtpUtilities::isCustomerRegistered();

    $form['markup_library'] = array(
      '#attached' => array(
        'library' => array(
          "otp_verification/otp_verification.admin",
        )
      ),
    );

    $form['header_top_style_2'] = array('#markup' => '<div class="mo_otp_verification_table_layout_1"><div class="mo_otp_verification_table_layout mo_otp_container">');

    $form['miniorange_otp_customer_validation'] = array(
      '#method' => 'post',
      '#type' => 'hidden',
      '#id' => 'mo_otp_verification_settings',
      '#value' => 'mo_customer_validation_settings',
    );

    $form['markup_1'] = array(
      '#markup' => '<h3>OTP VERIFICATION SETTINGS</h3><hr><br>',
    );

    if ($isCustomerRegisterd) {
      $register_url = $base_url . '/admin/config/people/otp_verification/customer_setup';
      $form['header'] = array(
        '#markup' => '<div class="mo_saml_configure_message">You need to <a href="' . $register_url . '" >register/login</a> with miniOrange before using this module.</div><br>',
      );
    }

    $form['markup_2'] = array(
      '#markup' => 'By following these easy steps you can verify your users email or phone number instantly:<br><br>1. Select the Verification method.<br>'
        . '2. To configure your SMS/Email messages/gateway check under <a href="' . $conf_url . '">Configuration Tab</a>.<br>'
        . '3. Save your settings.<br>'
        . '4. Deselect the option: <b> "Require e-mail verification when a visitor creates an account" </b> under <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>"Registration and cancellation"</b> section from'
        . '<a href = "' . $config_url . '" target="_blank"> here</a>. This will allow new user to set their own <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;passwords while registration.<br>'
        . '5. Log out and go to your registration or landing page for testing.<br><br>',
    );

    $form['miniorange_otp_during_registration_time'] = array(
      '#type' => 'checkbox',
      '#title' => t('<b>Drupal Default Registration Form</b>'),
      '#default_value' => $config->get('miniorange_otp_during_registration'),
      '#disabled' => $isCustomerRegisterd,
    );

    $form['set_of_radiobuttons'] = array(
      '#type' => 'fieldset',
      '#states' => array(
        // Only show this field when the checkbox is enabled.
        'visible' => array(
          ':input[name="miniorange_otp_during_registration_time"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['set_of_radiobuttons']['miniorange_otp_options'] = array(
      '#type' => 'radios',
      '#tree' => TRUE,
      '#default_value' => is_null($config->get('miniorange_otp_options')) ? 0 : $config->get('miniorange_otp_options'),
      '#options' => array(0 => t('Enable Email Verification'), 1 => t('Enable Phone Verification')),
      '#disabled' => $isCustomerRegisterd,
      //'#suffix' => '<br>',
    );

    $form['Instructions_field'] = array(
      '#type' => 'details',
      '#id' => 'phone_field_id',
      '#title' => t('Create a Phone Field by following the steps below:'),
      '#open' => True,
      '#states' => array(
        // Only show this field when the checkbox is enabled.
        'visible' => array(
          ':input[name="mo_phone_field_name"]' => array('visible' => TRUE),
        ),
      ),
    );

    $form['Instructions_field']['miniorange_otp_phone_instructions'] = array(
      '#markup' => "
               <div id ='miniorange_otp_phone_instr'>
                   <ol>
                        <li>Click on the link <a href='" . $base_url . "/admin/config/people/accounts/fields' target='_blank'>here</a> to go to <b>manage fields</b> page. </li>
                        <li>If phone field already exists. Copy the machine name of the phone field and paste it into the textbox below otherwise follow step 3 to create one.</li>
                        <li>Click on <b><i>Add Field</i></b> under add a new field dropdown select <b>Text(plain)</b>, enter <b><i>Phone Number</i></b> in Label textbox.  Now, click on the <b><i>Save and Continue</i></b> button to continue.</b></li>
                        <li>Keep the maximum length 15 and   click on <b><i>Save Field Settings</i></b> button. </li>
                        <li>Finally, click on the <b>Save Settings</b> button to save all your settings. Your phone Number field is now ready</li>
                        <li>Copy the machine name of phone field in textbox below</li>
                    </ol>
                </div><br>",
      '#tree' => TRUE,
    );

    $form['mo_phone_field_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Machine name of phone field<span style="color:red;">*</span>'),
      '#default_value' => $config->get('machine_name_of_phone_field'),
      '#size' => 50,
      '#states' => array(
        'visible' => array('or',
          array(':input[name="miniorange_otp_options"]' => array('value' => 1), ':input[name="miniorange_otp_during_registration_time"]' => array('checked' => True),),),
      ),
    );

    $form['Country_Code_field'] = array(
      '#type' => 'fieldset',
      '#states' => array(
        // Only show this field when the checkbox is enabled.
        'visible' => array(
          ':input[name="miniorange_otp_options"]' => array('value' => 1),
        ),
      ),
    );

    $form['Country_Code_field']['markup_10'] = array(
      '#markup' => '<h2>Country Code Restriction</h2><hr>',
    );

    $form['Country_Code_field']['country_code_restriction_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check this option if you want  <b>Country Code Restriction</b>'),
      '#default_value' => $config->get('miniorange_enable_country_code_restriction'),
      '#disabled' => $isCustomerRegisterd,
    );

    $form['Country_Code_field']['miniorange_set_of_radiobuttons_country'] = array(
      '#type' => 'fieldset',
      '#states' => array(
        // Only show this field when the checkbox is enabled.
        'visible' => array(
          ':input[name="country_code_restriction_checkbox"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['Country_Code_field']['miniorange_set_of_radiobuttons_country']['miniorange_allow_or_block_country_code'] = array(
      '#type' => 'radios',
      '#maxlength' => 5,
      '#options' => array('allow' => 'I want to allow only some of the country codes', 'block' => 'I want to block some of the country codes'),
      '#default_value' => is_null($config->get('miniorange_allow_or_block_country_code')) ? 'allow' : $config->get('miniorange_allow_or_block_country_code'),
      '#disabled' => $isCustomerRegisterd,
    );


    $form['Country_Code_field']['miniorange_set_of_radiobuttons_country']['miniorange_country_codes'] = array(
      '#type' => 'textarea',
      '#title' => t('Enter list of country codes'),
      '#tree' => TRUE,
      '#attributes' => array(
        'style' => 'width:700px;height:70px;',
        'placeholder' => t('Eg. +xx;+xxx;'),
      ),
      '#description' => t('Enter semicolon(;) separated country codes with (+) sign (Eg. +xx; +xxx)'),
      '#default_value' => is_null($config->get('miniorange_country_codes')) ? '' : $config->get('miniorange_country_codes'),
      '#suffix' => '<br>',
    );

    $form['mo_otp_custom_fields'] = array(
      '#type' => 'textarea',
      '#title' => t('Add custom fields on Registration form'),
      '#tree' => TRUE,
      '#attributes' => array(
        'style' => 'width:88%;height:22%;',
        'placeholder' => t('Eg. field_first_name; field_last_name;'),
      ),
      '#description' => t('<b>Note: </b> Enter semicolon(;) separated machine name of your custom fields (Eg. field_first_name; field_last_name), whose values you want to map to user\'s profile.'),
      '#default_value' => is_null($config->get('miniorange_custom_fields_registration')) ? '' : $config->get('miniorange_custom_fields_registration'),
      '#suffix' => '<br>',
      '#disabled' => $isCustomerRegisterd,
    );

    $form['markup_9'] = array(
      '#markup' => '<br><h2>Domain Restriction</h2><hr>',
    );

    $form['domain_restriction_checkbox'] = array(
      '#type' => 'checkbox',
      '#title' => t('Check this option if you want  <b>Domain Restriction</b>'),
      '#default_value' => $config->get('miniorange_block_domain_value'),
      '#disabled' => $isCustomerRegisterd,
    );

    $form['miniorange_set_of_radiobuttons'] = array(
      '#type' => 'fieldset',
      '#states' => array(
        // Only show this field when the checkbox is enabled.
        'visible' => array(
          ':input[name="domain_restriction_checkbox"]' => array('checked' => TRUE),
        ),
      ),
    );

    $form['miniorange_set_of_radiobuttons']['miniorange_allow_or_block_domains'] = array(
      '#type' => 'radios',
      '#maxlength' => 5,
      '#options' => array('white' => 'I want to allow only some of the domains', 'black' => 'I want to block some of the domains'),
      '#default_value' => is_null($config->get('miniorange_domains_are_white_or_black')) ? 'white' : $config->get('miniorange_domains_are_white_or_black'),
      '#disabled' => $isCustomerRegisterd,
    );


    $form['miniorange_set_of_radiobuttons']['miniorange_domains'] = array(
      '#type' => 'textarea',
      '#title' => t('Enter list of domains'),
      '#tree' => TRUE,
      '#attributes' => array(
        'style' => 'width:700px;height:70px;',
        'placeholder' => t('Eg. xxxx.com;xxxx.com;'),
      ),
      '#description' => t('Enter semicolon(;) separated domains (Eg. xxxx.com; xxxx.com)'),
      '#default_value' => is_null($config->get('miniorange_block_domains')) ? '' : $config->get('miniorange_block_domains'),
      '#suffix' => '<br>',
    );

    $form['miniorange_otp_settings_save_button'] = array(
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#prefix' => '<br><br>',
      '#value' => t('Save'),
      '#submit' => array('::miniorange_otp_settings_save'),
      '#disabled' => $isCustomerRegisterd,
    );

    $form['miniorange_otp_settings_delete'] = array(
      '#type' => 'submit',
      '#button_type' => 'danger',
      '#value' => t('Delete'),
      '#submit' => array('::miniorange_otp_settings_delete'),
      '#disabled' => $isCustomerRegisterd,
      '#suffix' => '<br><br><br></div>',
    );

    MiniorangeOtpUtilities::Two_FA_Advertisement($form, $form_state);

    return $form;
  }

  /**
   * Handling Save Settings tab.
   */
  function miniorange_otp_settings_save($form, &$form_state)
  {

    global $base_url;
    $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');

    $user = User::load(\Drupal::currentUser()->id());
    $user_enabled = null;
    $domains = null;
    $block_email_domains = $form['domain_restriction_checkbox']['#value'];

    if ($block_email_domains == 1) {
      $block_email_domains = TRUE;
      $domains = trim($form['miniorange_set_of_radiobuttons']['miniorange_domains']['#value']);
      if (empty($domains)) {
        \Drupal::messenger()->addMessage(t('Domain field is required.'), 'error');
        return;
      }
    } else {
      $block_email_domains = FALSE;
    }

    $white_or_black = $form['miniorange_set_of_radiobuttons']['miniorange_allow_or_block_domains']['#value'];
    $otp_during_registration = $form['miniorange_otp_during_registration_time']['#value'];
    $custom_fields = str_replace(' ', '', $form['mo_otp_custom_fields']['#value']);


    if ($otp_during_registration == 1) {
      $otp_during_registration = TRUE;
      $user_enabled = $form['set_of_radiobuttons']['miniorange_otp_options']['#value'];
    } else {
      $otp_during_registration = FALSE;
    }

    $logout_url = $base_url . '/user/logout';
    $phone_field = $form['mo_phone_field_name']['#value'];

    if ($user_enabled == 1) {

      try {
        $user->get($form['mo_phone_field_name']['#value'])->value;
      } catch (\Exception $e) {
        \Drupal::messenger()->addMessage(t('The  field ( ' . $form['mo_phone_field_name']['#value'] . ' ) does not exist. Please enter correct machine name.'), 'error');
        return;
      }

    }

    if (($user_enabled == 1) && empty($phone_field)) {

      $message = "Machine Name of the Phone field is required.";
      \Drupal::messenger()->addMessage(t($message), 'error');
      return;

    }

    $enable_country_code_restriction = $form_state->getValue('country_code_restriction_checkbox');
    $country_codes = trim($form_state->getValue('miniorange_country_codes'));
    $allow_or_block_country_code = $form_state->getValue('miniorange_allow_or_block_country_code');

    if ($enable_country_code_restriction == 1 && empty($country_codes)) {
      \Drupal::messenger()->addMessage(t('Country Codes field is required.'), 'error');
      return;
    }

    $db_var->set('machine_name_of_phone_field', $phone_field)
      ->set('miniorange_otp_options', $user_enabled)
      ->set('miniorange_otp_during_registration', $otp_during_registration)
      ->set('miniorange_block_domain_value', $block_email_domains)
      ->set('miniorange_block_domains', $domains)
      ->set('miniorange_domains_are_white_or_black', $white_or_black)
      ->set('miniorange_enable_country_code_restriction', $enable_country_code_restriction)
      ->set('miniorange_allow_or_block_country_code', $allow_or_block_country_code)
      ->set('miniorange_country_codes', $country_codes)
      ->set('miniorange_custom_fields_registration', $custom_fields)
      ->save();

    $message = 'Settings saved successfully. You can go to your registration form page to test the plugin. <a href="' . $logout_url . '">Click here</a> to logout.';

    drupal_flush_all_caches();
    \Drupal::messenger()->addMessage(t($message), 'status');
  }

  function miniorange_otp_settings_delete($form, &$form_state)
  {

    $db_var = \Drupal::configFactory()->getEditable('otp_verification.settings');

    $db_var->clear('miniorange_otp_options')
      ->clear('miniorange_otp_during_registration')
      ->clear('machine_name_of_phone_field')
      ->clear('miniorange_block_domain_value')
      ->clear('miniorange_block_domains')
      ->save();

    drupal_flush_all_caches();
    \Drupal::messenger()->addMessage(t('Settings deleted successfully.'), 'status');
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }
}
