# YSE Partitioned Reflist

Support for spliting reflist to render in different places on the same page render.  V1 only does simple single split, and uses a dedicated 'partition' paragraph as the signal and breakpoint.  It should only be available for entity_reference_revisions.

This provides a non-content partition paragraph type for editors to add to any ref list, and field formatters to assign to a twig_tweak drupal_field() call.

## Use

paragraphs.paragraphs_type.partition.yml can be found in config/optional
The partition paragraphs type would need to be added to the entity options config for use in an entity reference field.
In the twig for an above or below the fold display, use drupal_field() with args for the parent node id and the entity reference field name, and the above or below formatter id.

We use two formatters 'yse_partitioned_reflist_above' and 'yse_partitioned_reflist_below'.   Configuration may be used in the future.

ex: {{ drupal_field('field_reorderable_feature_bundle', 'node', 1, {label: 'hidden', type: 'yse_partitioned_reflist_above'}) }}

## Reliance on twig tweak

For now, we use the 'drupal_field()' call from twig_tweak.  This allows us to call separate FieldFormatters to process EntityReference[Revisions]FieldItemList fields.

## Redundant processing

Processing a reflist is frustating, the parent of the entities within is the Node, the _referringItem is the field itself, but it is not possible to or access attributes to the field from the list.  Lack of easy access to node and field render arrays at the list level, and lack of straightforward functions to add attributes into a reflist that can be accessed down the twig chain, makes it hard to use a single preprocess hook unless it is a the root level.  Any values there which are not site settings would be 'noise' to every object in the system.

We may decide later to control this better with a new FieldType, or other means, but for now we accept that the discovery loop will happen twice.
