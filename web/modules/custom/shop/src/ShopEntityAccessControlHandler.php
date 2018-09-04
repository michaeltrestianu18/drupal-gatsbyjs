<?php

namespace Drupal\shop;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Shop entity entity.
 *
 * @see \Drupal\shop\Entity\ShopEntity.
 */
class ShopEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\shop\Entity\ShopEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished shop entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published shop entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit shop entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete shop entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add shop entity entities');
  }

}
