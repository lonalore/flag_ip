<?php

namespace Drupal\flag_ip;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\flag\FlagService;

/**
 * Flag service.
 */
class FlagIpFlagService extends FlagService {

  /**
   * The current user injected into the service.
   *
   * @var AccountInterface
   */
  private $currentUser;

  /**
   * @var EntityTypeManagerInterface
   *
   */
  private $entityTypeManager;

  /**
   * The session manager.
   *
   * @var \Drupal\Core\Session\SessionManagerInterface
   */
  private $sessionManager;

  /**
   * Constructor.
   *
   * @param AccountInterface $current_user
   *   The current user.
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity manager.
   * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
   *   The session manager.
   */
  public function __construct(AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager, SessionManagerInterface $session_manager) {
    parent::__construct($current_user, $entity_type_manager, $session_manager);
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->sessionManager = $session_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function populateFlaggerDefaults(AccountInterface &$account = NULL, &$session_id = NULL) {
    // Note that the $account parameter must be explicitly set to be passed by
    // reference for the case when the variable is NULL rather than an object;
    // also, it must be optional to allow a variable that is NULL to pass the
    // type-hint check.

    // Get the current user if the account is NULL.
    if ($account == NULL) {
      $account = $this->currentUser;

      // If the user is anonymous, get the session ID.
      if ($account->isAnonymous()) {
        // Ensure something is in $_SESSION, otherwise the session ID will
        // not persist.
        // TODO: Replace this with something cleaner once core provides it.
        // See https://www.drupal.org/node/2865991.
        $_SESSION['flag'] = TRUE;

        $this->sessionManager->start();

        // Intentionally clobber $session_id; it makes no sense to specify that
        // but not $account.
        // $session_id = $this->sessionManager->getId();

        // TODO
        // Override methods and use OR condition: $session_id or $session_ip
        $session_id = \Drupal::request()->getClientIp();
      }
    }
    elseif ($account->isAnonymous() && is_null($session_id)) {
      throw new \LogicException('Anonymous users must be identified by session_id');
    }
  }

}
