<?php

namespace Drupal\shop\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Shop entity entities.
 *
 * @ingroup shop
 */
interface ShopEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Shop entity name.
   *
   * @return string
   *   Name of the Shop entity.
   */
  public function getName();

  /**
   * Sets the Shop entity name.
   *
   * @param string $name
   *   The Shop entity name.
   *
   * @return \Drupal\shop\Entity\ShopEntityInterface
   *   The called Shop entity entity.
   */
  public function setName($name);

  /**
   * Gets the Shop entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Shop entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Shop entity creation timestamp.
   *
   * @param int $timestamp
   *   The Shop entity creation timestamp.
   *
   * @return \Drupal\shop\Entity\ShopEntityInterface
   *   The called Shop entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Shop entity published status indicator.
   *
   * Unpublished Shop entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Shop entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Shop entity.
   *
   * @param bool $published
   *   TRUE to set this Shop entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\shop\Entity\ShopEntityInterface
   *   The called Shop entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Shop entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Shop entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\shop\Entity\ShopEntityInterface
   *   The called Shop entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Shop entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Shop entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\shop\Entity\ShopEntityInterface
   *   The called Shop entity entity.
   */
  public function setRevisionUserId($uid);

}
