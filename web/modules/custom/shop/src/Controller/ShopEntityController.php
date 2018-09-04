<?php

namespace Drupal\shop\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\shop\Entity\ShopEntityInterface;

/**
 * Class ShopEntityController.
 *
 *  Returns responses for Shop entity routes.
 */
class ShopEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Shop entity  revision.
   *
   * @param int $shop_entity_revision
   *   The Shop entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($shop_entity_revision) {
    $shop_entity = $this->entityManager()->getStorage('shop_entity')->loadRevision($shop_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('shop_entity');

    return $view_builder->view($shop_entity);
  }

  /**
   * Page title callback for a Shop entity  revision.
   *
   * @param int $shop_entity_revision
   *   The Shop entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($shop_entity_revision) {
    $shop_entity = $this->entityManager()->getStorage('shop_entity')->loadRevision($shop_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $shop_entity->label(), '%date' => format_date($shop_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Shop entity .
   *
   * @param \Drupal\shop\Entity\ShopEntityInterface $shop_entity
   *   A Shop entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ShopEntityInterface $shop_entity) {
    $account = $this->currentUser();
    $langcode = $shop_entity->language()->getId();
    $langname = $shop_entity->language()->getName();
    $languages = $shop_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $shop_entity_storage = $this->entityManager()->getStorage('shop_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $shop_entity->label()]) : $this->t('Revisions for %title', ['%title' => $shop_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all shop entity revisions") || $account->hasPermission('administer shop entity entities')));
    $delete_permission = (($account->hasPermission("delete all shop entity revisions") || $account->hasPermission('administer shop entity entities')));

    $rows = [];

    $vids = $shop_entity_storage->revisionIds($shop_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\shop\ShopEntityInterface $revision */
      $revision = $shop_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $shop_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.shop_entity.revision', ['shop_entity' => $shop_entity->id(), 'shop_entity_revision' => $vid]));
        }
        else {
          $link = $shop_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.shop_entity.translation_revert', ['shop_entity' => $shop_entity->id(), 'shop_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.shop_entity.revision_revert', ['shop_entity' => $shop_entity->id(), 'shop_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.shop_entity.revision_delete', ['shop_entity' => $shop_entity->id(), 'shop_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['shop_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
