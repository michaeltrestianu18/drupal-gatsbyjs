<?php

namespace Drupal\shop;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface ShopEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Shop entity revision IDs for a specific Shop entity.
   *
   * @param \Drupal\shop\Entity\ShopEntityInterface $entity
   *   The Shop entity entity.
   *
   * @return int[]
   *   Shop entity revision IDs (in ascending order).
   */
  public function revisionIds(ShopEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Shop entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Shop entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\shop\Entity\ShopEntityInterface $entity
   *   The Shop entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ShopEntityInterface $entity);

  /**
   * Unsets the language for all Shop entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
