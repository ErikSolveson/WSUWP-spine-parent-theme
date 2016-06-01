<?php
/**
 * Class Spine_Theme_Navigation
 *
 * Manages the various customizations to navigation used by the Spine theme. This
 * includes adjustments based on other plugins in addition to WordPress core.
 */
class Spine_Theme_Navigation {
	/**
	 * Parents of dogeared items that should be also be marked
	 * as dogeared items.
	 *
	 * @since 0.26.8
	 *
	 * @var array
	 */
	var $parent_dogeared = array();

	public function __construct() {
		add_action( 'init', array( $this, 'theme_menus' ) );

		// Filters for navigation handled by WordPress core.
		add_filter( 'nav_menu_css_class', array( $this, 'abbridged_menu_classes' ), 10, 3 );

		// Filters for navigation handled by BU Navigation.
		add_filter( 'bu_navigation_filter_pages', array( $this, 'bu_filter_page_urls' ), 11 );
		add_filter( 'bu_navigation_filter_anchor_attrs', array( $this, 'bu_filter_anchor_attrs' ), 10, 1 );
		add_filter( 'bu_navigation_filter_item_attrs', array( $this, 'bu_navigation_filter_item_attrs' ), 10, 2 );
	}

	/**
	 * Setup the default navigation menus used in the Spine.
	 */
	public function theme_menus() {
		register_nav_menus(
			array(
				'site'    => 'Site',
				'offsite' => 'Offsite',
			)
		);
	}

	/**
	 * Condense verbose menu classes provided by WordPress when processing the Spine
	 * navigation. Removes the default current-menu-item and current_page_parent classes
	 * if they are found on this page view and replaces them with 'current'.
	 *
	 * Adds the `current` class to a current page's immediate parent if the page itself
	 * is not in the Spine navigation menu.
	 *
	 * If this is not a menu in the Spine navigation, the `current` classes is appended to
	 * the array, but other classes are left alone.
	 *
	 * @param array    $classes Current list of nav menu classes.
	 * @param WP_Post  $item    Post object representing the menu item.
	 * @param stdClass $args    Arguments used to create the menu.
	 *
	 * @return array Modified list of nav menu classes.
	 */
	public function abbridged_menu_classes( $classes, $item, $args ) {
		$post = get_post();
		$current_or_parent_page = array_intersect( array( 'current-menu-item', 'current_page_parent' ), $classes );
		$current_page_parent = ( $item->object_id == $post->post_parent );
		$current_page_not_in_menu = ! in_array( 'current_page_parent', $classes, true );

		if ( in_array( $args->menu, array( 'site', 'offsite' ) ) ) {
			if ( $current_or_parent_page || ( $current_page_parent && $current_page_not_in_menu ) ) {
				$classes = array( 'current' );
			} else {
				$classes = array();
			}
		} elseif ( $current_or_parent_page ) {
			$classes[] = 'current';
		}

		return $classes;
	}

	/**
	 * Look for pages that are intended to be section labels rather than
	 * places where content exists. Filter the URL attached to these pages
	 * to be only '#' so that an overview page is not generated within the
	 * Spine navigation framework.
	 *
	 * @param array $pages A list of pages used with BU Navigation.
	 *
	 * @return array
	 */
	public function bu_filter_page_urls( $pages ) {
		global $wpdb;

		$filtered = array();

		if ( is_array( $pages ) && count( $pages ) > 0 ) {

			$ids = array_map( 'absint', array_keys( $pages ) );
			$query = $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '%s' AND post_id IN (" .  implode( ',', $ids ) . ") and meta_value = '%s'", '_wp_page_template', 'templates/section-label.php' );
			$labels = $wpdb->get_results( $query, OBJECT_K );

			if ( is_array( $labels ) && count( $labels ) > 0 ) {
				foreach ( $pages as $page ) {
					if ( array_key_exists( $page->ID, $labels ) ) {
						$page->url = '#';
					}
					$filtered[ $page->ID ] = $page;
				}
			} else {
				$filtered = $pages;
			}
		}

		return $filtered;
	}

	/**
	 * Filter anchor attributes when generating the BU Navigation menu to remove the
	 * title attribute. This allows the Spine default "Overview" behavior to continue.
	 *
	 * @param array $attrs List of attributes to output as part of the anchor.
	 *
	 * @return array
	 */
	public function bu_filter_anchor_attrs( $attrs ) {
		$attrs['title'] = '';

		return $attrs;
	}

	/**
	 * Filter the list item classes to manually add current and dogeared when necessary.
	 *
	 * @param array   $item_classes List of classes assigned to the list item.
	 * @param WP_Post $page         Post object for the current page.
	 *
	 * @return array
	 */
	public function bu_navigation_filter_item_attrs( $item_classes, $page ) {
		if ( in_array( 'current_page_item', $item_classes ) || in_array( 'current_page_parent', $item_classes ) ) {
			$item_classes[] = 'current';
		}

		if ( is_singular() && get_the_ID() == $page->ID ) {
			$item_classes[] = 'dogeared';
		}

		if ( is_singular( 'post' ) && $page->ID === get_option( 'page_for_posts' ) ) {
			$item_classes[] = 'dogeared';

			if ( 0 != $page->post_parent ) {
				$this->parent_dogeared[] = $page->post_parent;
			}
		}

		if ( 'page' === $page->post_type && in_array( $page->ID, $this->parent_dogeared ) ) {
				$item_classes[] = 'current';
				$item_classes[] = 'parent';
		}

		return $item_classes;
	}
}
new Spine_Theme_Navigation();
