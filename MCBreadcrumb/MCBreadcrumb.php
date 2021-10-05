<?php
namespace App;

/**
 * MC Breadcrumb.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class MCBreadcrumb {

	/**
	 * The separator of the breadcrumb.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $separator = '|';

	/**
	 * Home title placeholder.
	 *
	 * @since 1.0.0
	 * 
	 * @var string
	 */
	private $home_title = 'Home';


	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 * 
	 * @param array  $args  Containing the necessary arguments.
	 * $args = [
	 * 	'separator'  => (string) Holds the separator.
	 * 	'home_title' => (string) Holds the home title placeholder.
	 * ]
	 */
	public function __construct( $args = [] ) {
		if ( isset( $args['separator'] ) ) {
			$this->separator = $args['separator'];
		}

		if ( isset( $args['home_title'] ) ) {
			$this->home_title = $args['home_title'];
		}
	}


	/**
	 * Use the breadcrumb note need to be called in chain
	 * after initializing.
	 *
	 * @since 1.0.0
	 * 
	 * @return HTMLElement
	 */
	public function getBreadcrumb() {
		if ( is_front_page() ) {
			return;
		}

		$output  = '';
		$output .= '<ul class="mc-breadcrumb">';

		// Home.
		$output .= $this->getHome() . $this->getSeparator();

		// Category and tag for post type: post.
		if ( is_category() || is_tag() ) {
			$output .= $this->getPostLink( get_option('page_for_posts') ).
					   $this->getSeparator().
					   $this->getCurrentLink( single_cat_title( 'Archives For ', false ) );

		}

		// Archive post type: post.
		if ( is_archive() && get_post_type() == 'post' ) {
			$date = [
			    'd' => get_the_date( 'd' ),
			    'm' => get_the_date( 'm' ),
			    'y' => get_the_date( 'Y' )
			];

			if ( is_date() ) {
				if ( is_day() ) {
					$output .= $this->getPostLink( get_option('page_for_posts') ).
							   $this->getSeparator().
							   $this->getDateLink( 'year', $date ).
							   $this->getSeparator().
							   $this->getDateLink( 'month', $date ).
							   $this->getSeparator().
							   $this->getCurrentLink( 'Archives For Day ' .' '. $date['d'] );
				} elseif ( is_month() ) {
					$output .= $this->getPostLink( get_option('page_for_posts') ).
						   	   $this->getSeparator().
					   		   $this->getDateLink( 'year', $date ).
							   $this->getSeparator().
							   $this->getCurrentLink( 'Archives For Month ' .' '. $date['m'] );
				} elseif ( is_year() ) {
					$output .= $this->getPostLink( get_option('page_for_posts') ).
						   	   $this->getSeparator().
							   $this->getCurrentLink( ' Archives For Year ' .' '. $date['y'] );
				}
			} elseif ( is_author() ) {
				$output .= $this->getPostLink( get_option('page_for_posts') ).
						   $this->getSeparator().
						   $this->getCurrentLink( get_the_author_meta('display_name') );
			}
		}


		// Page.
		if ( is_page() ) {
			$page_id = get_the_ID();
			// Parent.
			if ( wp_get_post_parent_id( $page_id ) > 0 ) {
				$parent_id = wp_get_post_parent_id( $page_id );
				// Grand parent.
				if ( wp_get_post_parent_id( $parent_id ) > 0 ) {
					$grand_parent_id = wp_get_post_parent_id( $parent_id );
					$output .= $this->getPostLink( $grand_parent_id ).
							   $this->getSeparator().
							   $this->getPostLink( $parent_id ).
							   $this->getSeparator().
							   $this->getCurrentLink( get_the_title() );
				} else {
					$output .= $this->getPostLink( $parent_id ).
							   $this->getSeparator().
							   $this->getCurrentLink( get_the_title() );
				}
			} else {
				$output .= $this->getCurrentLink( get_the_title() );
			}
		}


		// Static Blog.
		if ( is_home() ) {
			$post_id = get_option( 'page_for_posts' );
			if ( $post_id ) {
				$output .= $this->getCurrentLink( get_the_title( $post_id ) );
			}
		}


		// Singular: post.
		if ( is_singular( 'post' ) ) {
			$output .= $this->getPostLink( get_option('page_for_posts') ).
					   $this->getSeparator().
					   $this->getCurrentLink( get_the_title() );
		}

		// Search Page.
		if ( is_search() ) {
			$search_title = $this->searchPrefix .' "'. get_search_query() .'"';
			$output .= $this->getCurrentLink( $search_title );
		}


			// 404 page
			if( is_404() ) {
				$output .= $this->getCurrentLink('Error 404');
			}

		$output .= '</ul>';
		return $output;
	}


	/**
	 * Wraps the element in li tag.
	 *
	 * @since 1.0.0
	 * 
	 * @param  HTMLElement  $data  The element or data to be wrap.
	 * @return HTMLElement
	 */
	private function getWrapped( $data ) {
		$output = '';
		if ( ! empty( $data ) ) {
			$output = '<li>'. $data .'</li>';
		}
		return $output;
	}


	/**
	 * Returns the homepage link.
	 *
	 * @since 1.0.0
	 * 
	 * @return HTMLElement
	 */
	private function getHome() {
		$link = '<a class="mc-breadcrumb__link" href="'. home_url() .'">'. $this->home_title .'</a>';
		return $this->getWrapped( $link );
	}


	/**
	 * Returns the separator.
	 *
	 * @since 1.0.0
	 * 
	 * @return HTMLElement
	 */
	private function getSeparator() {
		$separator = '<span class="mc-breadcrumb__separator">'. $this->separator .'</span>';
		return $this->getWrapped( $separator );
	}


	/**
	 * Returns the current link.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string  $title  The title of the link.
	 * @return HTMLElement
	 */
	private function getCurrentLink( $title ) {
		$current_link = '';
		if ( ! empty( $title ) ) {
			$current_link = '<span class="mc-breadcrumb__current">'. esc_html( $title ) .'</span>';
		}
		return $this->getWrapped( $current_link );
	}


	/**
	 * Returns the link.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string  $title  The title of the link.
	 * @param  string  $url    The url of the link.
	 * @return HTMLElement
	 */
	private function getLink( $title, $url ) {
		$link = '';
		if ( ! empty( $title ) && ! empty( $url ) ) {
			$link = '<a class="mc-breadcrumb__link" href="'. esc_url( $url ) .'">'. esc_html( $title ) .'</a>';
		}
		return $this->getWrapped( $link );
	}


	/**
	 * Returns the link of a post based on given post id.
	 *
	 * @since 1.0.0
	 * 
	 * @param  int  $post_id  The post id.
	 * @return HTMLElement
	 */
	private function getPostLink( $post_id ) {
		$post_link = '';
		if ( ! empty( $post_id ) ) {
			if ( ! empty( get_the_title( $post_id ) ) ) {
				$post_link = $this->getLink( get_the_title( $post_id ), get_the_permalink( $post_id ) );
			}
		}
		return $post_link;
	}


	/**
	 * Returns the date link of the post.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string  $format  The format date |day|month|year|.
	 * @param  date    $date    The date of the post published.
	 * @return HTMLElement
	 */
	private function getDateLink( $format, $date ) {
		$date_link = '';
		if ( ! empty( $format ) && ! empty( $date ) ) {
			if ( $format == 'day' ) {
				$date_link = $this->getLink( $date['d'], get_day_link( $date['y'], $date['m'], $date['d'] ) );
			} elseif ( $format == 'month' ) {
				$date_link = $this->getLink( $date['m'], get_month_link( $date['y'], $date['m'] ) );
			} elseif ( $format == 'year' ) {
				$date_link = $this->getLink( $date['y'], get_year_link( $date['y'] ) );
			}
		}
		return $date_link;
	}
}


function get_breadcrumb() {
	$breadcrumb = new MCBreadcrumb();
	return $breadcrumb->getBreadcrumb();
}