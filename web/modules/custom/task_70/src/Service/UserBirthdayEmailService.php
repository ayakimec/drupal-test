<?php

namespace Drupal\task_70\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Mail\Plugin\Mail\PhpMail;
use Psr\Log\LoggerInterface;

/**
 * Class UserBirthdayEmailService.
 *
 * @package Drupal\task_70.
 */
class UserBirthdayEmailService {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new MyService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, LoggerInterface $logger) {
    $this->entityTypeManager = $entity_type_manager;
    $this->logger = $logger->get('userinfo');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.factory')
    );
  }

  /**
   * Generate User Email.
   */
  public function userBirthdayEmails() {
    $send_mail = new PhpMail();
    $users = $this->getUsers();

    if (count($users) > 0) {
      foreach ($users as $user) {
        $email = $user->get('mail')->value;
        $name = $user->get('name')->value;

        $message_email = $this->emailTemplate($email, $name);

        $send_mail->mail($message_email);
        $this->logger->get('action')->notice('Birthday mail sent to ' . $name);
      }
    }
  }

  /**
   * Get Users.
   */
  public function getUsers() {
    $current_date = new DrupalDateTime('now');
    $month_day = $current_date->format('m-d');

    $storage = $this->entityTypeManager->getStorage('user');

    $query = $storage->getQuery()
      ->condition('status', 1)
      ->condition('field_user_birthday', '%' . $month_day . '%', 'LIKE');
    $uids = $query->execute();

    $users = $this->entityTypeManager->getStorage('user')->loadMultiple($uids);

    return $users;
  }

  /**
   * Generate Template Email.
   */
  public function emailTemplate($user_mail, $user_name) {
    $from = 'sitename@example.com';
    $to = $user_mail;
    $message['headers'] = [
        'content-type' => 'text/html',
        'MIME-Version' => '1.0',
        'reply-to' => $from,
        'from' => 'Site Admin <' . $from . '>'
    ];

    $message['to'] = $to;
    $message['subject'] = "Happy Birthday " . $user_name . "!!!";

    $message['body'] = 'Dear ' . $user_name . ',
    <br><br>
    We value your special day just as much as we value you. On your birthday, we send you our warmest and most heartfelt wishes..
    <br><br>
    Regards,<br>
    Site Admin';

    return $message;
  }

}
