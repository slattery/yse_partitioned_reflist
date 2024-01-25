<?php

namespace Drupal\yse_partitioned_reflist\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\entity_reference_revisions\Plugin\Field\FieldFormatter\EntityReferenceRevisionsEntityFormatter;

/**
 * Plugin implementation of the 'entity reference rendered entity' formatter.
 *
 * @FieldFormatter(
 *   id = "yse_partitioned_reflist_above",
 *   label = @Translation("Rendered above list partition"),
 *   description = @Translation("Filter and display the referenced entities above the partition."),
 *   field_types = {
 *     "entity_reference_revisions"
 *   }
 * )
 */
class YsePartitionedReflistAboveFormatter extends EntityReferenceRevisionsEntityFormatter {

	public function getEntitiesToView(EntityReferenceFieldItemListInterface $items, $langcode) {
		$entities = parent::getEntitiesToView($items, $langcode);
		$length = null;

		foreach ($items->referencedEntities() as $idx => $para) {
			if ($para->getType() == 'partition') {
				$length = $idx;
				break;
			}
		}

		//slice the above-the-fold items from the list to view
		//using idx works because array_slice uses start-from-one
		//and the partition is a list element but it is non-content
		//it takes a position without being renderable
		//if the 'break' position in a list is content, then we would use idx+1 to render.

		//we recognize a valid index of zero as 'empty' since there is nothing above it.
		return empty($length) ? [] : array_slice($entities, 0, $length);
	}
}
