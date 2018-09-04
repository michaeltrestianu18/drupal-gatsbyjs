<?php

namespace Drupal\shop;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\shop\Entity\ShopEntityInterface;

/**
 * Defines the storage handler class for Shop entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Shop entity entities.
 *
 * @ingroup shop
 */
class ShopEntityStorage extends SqlContentEntityStorage implements ShopEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ShopEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {shop_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {shop_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ShopEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {shop_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('shop_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
