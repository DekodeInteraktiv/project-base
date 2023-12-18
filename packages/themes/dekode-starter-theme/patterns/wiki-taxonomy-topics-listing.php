<?php
/**
 * Title: Wiki Topics Taxonomy Listing
 * Slug: dekode-starter-theme/wiki-topics-taxonomy-listing
 * Categories: text
 * Inserter: false
 * Description: Adding the blocks group for showing the taxonomy terms list for the parent term that we are visiting.
 * Keywords: wiki, topics, list
 *
 * @package DekodeStarterTheme
 */

?>

<!-- wp:t2/section {"layout":"wide"} -->
	<!-- wp:heading {"placeholder":"Enter section heading"} -->
	<h2 class="wp-block-heading" id="knowledge-base-topics">
		<?php echo esc_html__( 'Knowledge base topics', 'dekode-starter-theme' ); ?>
	</h2>
	<!-- /wp:heading -->

	<!-- wp:t2/wiki-topics {"articlesPerTopic":0,"maxNumberOfTopics":50, "hideArticlesCounter": true, "showSubtopicsOnly": true} /-->

<!-- /wp:t2/section -->
