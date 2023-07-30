<?php
 /**
 * @file
 * Contains \Drupal\otp_verification\Controller\DefaultController.
 */

namespace Drupal\otp_verification\Controller;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Controller\ControllerBase;
use Drupal\otp_verification\MiniorangeOtpUtilities;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\otp_verification\MiniorangeOtpVerificationConstants;
use Symfony\Component\HttpFoundation\Response;
use \Drupal\otp_verification\MiniorangeOTPVerificationCustomer;
use Drupal\Core\Form\formBuilder;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;



class otp_verificationController extends ControllerBase
{
  protected $formBuilder;
  public function __construct(FormBuilder $formBuilder) {
      $this->formBuilder = $formBuilder;
  }

  public static function create(ContainerInterface $container) {
      return new static(
          $container->get("form_builder")
      );
  }

  public function openModalForm() {
    $response = new AjaxResponse();
    $modal_form = $this->formBuilder->getForm('\Drupal\otp_verification\Form\MiniorangeOTPRemoveAccount');
    $response->addCommand(new OpenModalDialogCommand('Remove Account', $modal_form, ['width' => '800'] ) );
    return $response;
}

  public function miniorange_otp_feedback_func()
  {
    global $base_url;
    $config = \Drupal::config('otp_verification.settings');
    if (isset($_GET['miniorange_feedback_submit'])) {
      $modules_info = \Drupal::service('extension.list.module')->getExtensionInfo('otp_verification');
      $modules_version = $modules_info['version'];
      $_SESSION['mo_other'] = "False";
      $reason = $_GET['deactivate_plugin'];
      $q_feedback = $_GET['query_feedback'];
      $message = 'Reason: ' . $reason . '<br>Feedback: ' . $q_feedback;
      $url = MiniorangeOtpVerificationConstants::BASE_URL . '/moas/api/notify/send';
      $email = $config->get('miniorange_otp_verification_customer_admin_email');
      if (empty($email))
        $email = $_GET['miniorange_feedback_email'];
      $phone = $config->get('miniorange_otp_verification_customer_admin_phone');
      $customerKey = $config->get('miniorange_otp_verification_customer_id');
      if ($customerKey == '') {
        $customerKey = "16555";
      }

      $fromEmail = $email;
      $subject = 'Drupal ' . \DRUPAL::VERSION . ' OTP Verification Module Feedback | ' . $modules_version;
      $query = '[Drupal ' . MiniorangeOtpUtilities::mo_get_drupal_core_version() . ' OTP Verification | ' . $modules_version . ']: ' . $message;
      $content = '<div >Hello, <br><br>Company :<a href="' . $_SERVER['SERVER_NAME'] . '" target="_blank" >' . $_SERVER['SERVER_NAME'] . '</a><br><br>Phone Number :' . $phone . '<br><br>Email :<a href="mailto:' . $fromEmail . '" target="_blank">' . $fromEmail . '</a><br><br>Query :' . $query . '</div>';
      $fields = array(
        'customerKey' => $customerKey,
        'sendEmail' => true,
        'email' => array(
          'customerKey' => $customerKey,
          'fromEmail' => $fromEmail,
          'fromName' => 'miniOrange',
          'toEmail' => 'drupalsupport@xecurify.com',
          'toName' => 'drupalsupport@xecurify.com',
          'subject' => $subject,
          'content' => $content
        ),
      );
      $mo_otp_verification_customer = new MiniorangeOTPVerificationCustomer($email, $phone, null, null);
      $content = $mo_otp_verification_customer->callService($url, $fields, true);
    }
    \Drupal::configFactory()->getEditable('otp_verification.settings')->clear('miniorange_otp_verification_uninstall_status')->save();
    \Drupal::service('module_installer')->uninstall(['otp_verification']);
    $uninstall_redirect = $base_url . '/admin/modules';
    \Drupal::messenger()->addMessage('The module has been successfully uninstalled.');
    return new RedirectResponse($uninstall_redirect);
  }

  function otp_user_logout()
  {
    global $base_url;

    $relayState = $base_url . "/user/login";
    \Drupal::service('session_manager')->destroy();
    $request = \Drupal::request();
    $request->getSession()->clear();

    if (!empty(\Drupal::config('otp_verification.settings')->get('otp_logout_url'))) {
      $logout_url = \Drupal::config('otp_verification.settings')->get('otp_logout_url');
      $response = new RedirectResponse($logout_url);
      $response->send();
    }
    $response = new RedirectResponse($relayState);
    $response->send();
    return new Response();
  }


  public function submitForm(array &$form, FormStateInterface $form_state)
  {

  }
}
