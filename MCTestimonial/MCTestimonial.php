<?php
namespace App\Inc;

use Rakit\Validation\Validator;

/**
 * MC Testimonial.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class MCTestimonial {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		/**
		 * Adding action to execute register_post_type for
		 * adding a testimonial post type.
		 */
		add_action( 'init', [ $this, 'registerTestimonialPost' ] );

		/**
		 * Adding action to add meta box in testimonial post.
		 */
		add_action( 'add_meta_boxes', [ $this, 'registerDetailMetaBox' ] );

		/**
		 * Adding action during saving post to save the all the
		 * data in the detail meta box.
		 */
		add_action( 'save_post', [ $this, 'saveDetailMetaBoxData' ] );

		/**
		 * Adding action to set or reset the post columns.
		 */
		add_action( 'manage_testimonial_posts_columns', array( $this, 'setCustomColumns' ) );

		/**
		 * Adding action to set the data of the new column "rating", "is_featured".
		 */
		add_action( 'manage_testimonial_posts_custom_column', array( $this, 'setCustomColumnsData' ), 10, 2 );

		/**
		 * Adding sort event to column "rating" and "is_featured".
		 */
		add_filter( 'manage_edit-testimonial_sortable_columns', array( $this, 'addSortEventToColumns' ) );

		/**
		 * Register mc-testimonial-form shortcode.
		 */
		add_shortcode( 'mc-testimonial-form', [ $this, 'renderTestimonialForm' ] );

		/**
		 * Register mc-testimonial-ratings shortcode.
		 */
		add_shortcode( 'mc-testimonial-ratings', [ $this, 'renderTestimonialRatings' ] );

		/**
		 * Ajax handdling for saving testimonial.
		 */
		add_action( 'wp_ajax_mc_save_testimonial', [ $this, 'saveTestimonial' ] );
		add_action( 'wp_ajax_nopriv_mc_save_testimonial', [ $this, 'saveTestimonial' ] );
	}


	/**
	 * Register the testimonial post.
	 *
	 * @since 1.0.0
	 */
	public function registerTestimonialPost() {
		$labels = array(
			'name'                  => 'Testimonials',
			'singular_name'         => 'Testimonial',
			'menu_name'             => 'Testimonials',
			'name_admin_bar'        => 'Testimonials',
			'archives'              => 'Testimonial Archives',
			'attributes'            => 'Testimonial Attributes',
			'parent_item_colon'     => 'Parent Testimonial:',
			'all_items'             => 'All Testimonials',
			'add_new_item'          => 'Add New Testimonial',
			'add_new'               => 'Add New',
			'new_item'              => 'New Testimonial',
			'edit_item'             => 'Edit Testimonial',
			'update_item'           => 'Update Testimonial',
			'view_item'             => 'View Testimonial',
			'view_items'            => 'View Testimonials',
			'search_items'          => 'Search Testimonial',
			'not_found'             => 'Not found',
			'not_found_in_trash'    => 'Not found in Trash',
			'featured_image'        => 'Featured Image',
			'set_featured_image'    => 'Set featured image',
			'remove_featured_image' => 'Remove featured image',
			'use_featured_image'    => 'Use as featured image',
			'insert_into_item'      => 'Insert into Testimonial',
			'uploaded_to_this_item' => 'Uploaded to this Testimonial',
			'items_list'            => 'Testimonials list',
			'items_list_navigation' => 'Testimonials list navigation',
			'filter_items_list'     => 'Filter Testimonials list',
		);
		$args = array(
			'label'                 => 'Testimonial',
			'description'           => 'Holds all reviews and testimonial',
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-format-quote',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'capability_type'       => 'page',
		);
		register_post_type( 'testimonial', $args );
	}


	/**
	 * Register a new meta box detail.
	 *
	 * @since 1.0.0
	 */
	public function registerDetailMetaBox() {
		add_meta_box( 'details', 'Details', array( $this, 'renderDetailMetaBox' ), 'testimonial', 'side', 'default' );
	}


	/**
	 * Render the metabox detail in testimonial dashboard.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $post  The post data of current post editing.
	 */
	public function renderDetailMetaBox( $post ) {
		wp_nonce_field( 'mc_action_testimonial_details', 'mc_nonce_testimonial_details' );
		$rating      = get_post_meta( $post->ID, '_mc_testimonial_rating', true );
		$position    = get_post_meta( $post->ID, '_mc_testimonial_position', true );
		$is_featured = get_post_meta( $post->ID, '_mc_testimonial_is_featured', true );
		?>
			<style type="text/css">
				.mc-testimonial-form {
					margin-bottom: 10px;
				}
				.mc-testimonial-form label {
					display: block;
					font-weight: bold;
				}
				.mc-testimonial-form input[type=text],
				.mc-testimonial-form select {
					width: 100%;
				}
				.mc-testimonial-form label.inline {
					display: inline-block;
					margin-right: 10px;
				}
			</style>
			<div class="mc-testimonial-form">
				<label for="mc_testimonial_position">Position</label>
				<input type="text" id="mc_testimonial_position" name="mc_testimonial_position" value="<?php echo esc_attr( $position ); ?>">
			</div>

			<div class="mc-testimonial-form">
				<label for="mc_testimonial_rating">Ratings</label>
				<select id="mc_testimonial_rating" name="mc_testimonial_rating">
					<option value="0" <?php selected( $rating, 0 ) ?>>0</option>
					<option value="1" <?php selected( $rating, 1 ) ?>>1</option>
					<option value="2" <?php selected( $rating, 2 ) ?>>2</option>
					<option value="3" <?php selected( $rating, 3 ) ?>>3</option>
					<option value="4" <?php selected( $rating, 4 ) ?>>4</option>
					<option value="5" <?php selected( $rating, 5 ) ?>>5</option>
				</select>
			</div>

			<div class="mc-testimonial-form">
				<label for="mc_testimonial_is_featured" class="inline">Featured</label>
				<input type="checkbox" name="mc_testimonial_is_featured" value="1" <?php checked( $is_featured, 1 ); ?>>
			</div>
		<?php
	}


	/**
	 * Saving all the data in detail meta box.
	 *
	 * @since 1.0.0
	 * 
	 * @param  int  $post_id  The id of the current post editing.
	 */
	public function saveDetailMetaBoxData( $post_id ) {
		if ( ! isset( $_POST['mc_nonce_testimonial_details'] ) ) {
			return $post_id;
		}

		if ( ! wp_verify_nonce( $_POST['mc_nonce_testimonial_details'], 'mc_action_testimonial_details' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		} 

		$position = sanitize_text_field( $_POST['mc_testimonial_position'] );
		update_post_meta( $post_id, '_mc_testimonial_position', $position );

		$rating = sanitize_text_field( $_POST['mc_testimonial_rating'] );
		if ( $rating < 0 || $rating > 5 ) {
			$rating = 0;
		}
		update_post_meta( $post_id, '_mc_testimonial_rating', $rating );

		$is_featured = $_POST['mc_testimonial_is_featured'] ? 1 : 0;
		update_post_meta( $post_id, '_mc_testimonial_is_featured', $is_featured );
	}


	/**
	 * Organize the column of testimonial post.
	 *
	 * @since 1.0.0
	 * 
	 * @param array  $columns  The current column order.
	 */
	public function setCustomColumns( $columns ) {
		// get the old default columns
		$title = $columns['title'];
		$date  = $columns['date'];	

		// unsetting the array columns for new order
		unset( $columns['title'], $columns['date'] );

		// setting to new order
		$columns['title']	= $title;
		$columns['rating']  = 'Rating';
		$columns['is_featured'] = 'Is Featured';
		$columns['date']	= $date;
		return $columns;
	}


	/**
	 * Print the data of each column. Note inside of this
	 * method will automatically in foreach so its better
	 * to set a condition in which column will be affected.
	 *
	 * @since 1.0.0
	 * 
	 * @param array  $column   The list of the columns available.
	 * @param int    $post_id  The post id of the post.
	 */
	public function setCustomColumnsData( $column, $post_id ) {
		// Get meta value for rating and is_featured.
		$rating 	 = get_post_meta( $post_id, '_mc_testimonial_rating', true );
		$is_featured = get_post_meta( $post_id, '_mc_testimonial_is_featured', true );

		// Checking values.
		$rating 	 = ( isset( $rating ) ? $rating : 0 );
		$is_featured = ( isset( $is_featured ) && $is_featured == 1 ? 'YES' : 'NO' );

		// Printing each data.
		switch ( $column ) {
			case 'rating':
				echo '<strong>'. $rating .'</strong>';
				break;
			case 'is_featured':
				echo '<strong>'. $is_featured .'</strong>';
				break;
		}
	}


	/**
	 * Adding sort event in each column.
	 *
	 * @since 1.0.0
	 * 
	 * @param array  $columns  The list of the columns available.
	 */
	public function addSortEventToColumns( $columns ) {
		// This will equal as : sort by rating value order by ASC or DESC
		$columns['rating'] = 'rating'; 
		// This will equal as : sort by is_featured value order by ASC or DESC
		$columns['is_featured'] = 'is_featured'; 
		return $columns;
	}


	/**
	 * Render the shortcode testimonial form.
	 *
	 * @since 1.0.0
	 */
	public function renderTestimonialForm() {
		ob_start();
		require_once get_template_directory() .'/app/Inc/MCTestimonial/partials/form.php';
		return ob_get_clean();
	}


	/**
	 * Ajax evet for saving testimonial.
	 *
	 * @since 1.0.0
	 */
	public function saveTestimonial() {
		// Security check.
		if ( ! DOING_AJAX ) {
			wp_send_json_error([
				'errors' => [ 
					'Error security issue, please try again.' 
				]
			]);
		}

		if( ! isset( $_POST['mc_save_testimonial_nonce'] ) || ! wp_verify_nonce( $_POST['mc_save_testimonial_nonce'], 'mc_save_testimonial' ) ) {
			wp_send_json_error([
				'errors' => [ 
					'Error security issue, please try again.' 
				]
			]);
		}

		// Validation.
		$validator = new Validator;
		$validation = $validator->make( $_POST + $_FILES, [
			'fullname'	 => 'required|min:2|max:50',
			'position'	 => 'required|min:5|max:50',
			'message'	 => 'required|min:5|max:250',
			'rating'	 => 'required|in:1,2,3,4,5',
			'image_file' => 'uploaded_file|max:50KB|mimes:jpeg,png'
		]);
		$validation->validate();
		if( $validation->fails() ) {
			$errors = $validation->errors();
			wp_send_json_error([
				'errors' => $errors->all()
			]);
		}

		// Sanitizing.
		$fullname = sanitize_text_field( $_POST['fullname'] );
		$position = sanitize_text_field( $_POST['position'] );
		$rate 	  = sanitize_text_field( $_POST['rating'] );
		$message  = sanitize_textarea_field( $_POST['message'] );

		// Set post value.
		$post_value = array(
			'post_title'	=> $fullname,
			'post_content'	=> $message,
			'post_author'	=> 1,
			'post_status'	=> 'pending',
			'post_type'		=> 'testimonial',
			'meta_input'	=> array(
				'_mc_testimonial_position'		=> $position,
				'_mc_testimonial_rating'		=> $rate,
				'_mc_testimonial_is_featured'	=> 0
			)
		);

		// Inserting to the database.
		$latest_saved_post_id = wp_insert_post( $post_value );
		if ( $latest_saved_post_id === 0 ) {
			wp_send_json_error([
				'errors' => [
					'Failed to submit your testimonial.'
				]
			]);
		}

		if ( isset( $_FILES['image_file'] ) ) {

			/**
			 * Check if wp_generate_attachment metadata function exists.
			 * if not then require them once.
			 */
			if( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
			}

			// Check if has error in uploading.
			if( $_FILES['image_file']['error'] != 0 ) {
				wp_send_json_success([
					'message' => 'Your testimonial has been successfully submitted except for you profile picture.'
				]);
			} else {
				// Rename image filename to fullname-post_id.extension.
				$_FILES['image_file']['name'] = $this->renameImage( $_FILES['image_file']['name'], $fullname, $latest_saved_post_id );

				// Upload image to attachement_media and get the lastest attachement_id.
				$latest_saved_attachmenet_id = media_handle_upload( 'image_file', 0 );

				if( $latest_saved_attachmenet_id > 0 ) {
					update_post_meta( $latest_saved_post_id, '_thumbnail_id', $latest_saved_attachmenet_id );
					wp_send_json_success([
						'message' => 'Your testimonial has been successfully submitted.'
					]);
				}else{
					wp_send_json_success([
						'message' => 'Your testimonial has been successfully submitted except for you profile picture.'
					]);
				}
			}

		} else {
			wp_send_json_success([
				'message' => 'Your testimonial has been successfully submitted.'
			]);
		}
	}


	/**
	 * Rename the image in this format fullname-post_id
	 * in order to avoid a collision.
	 *
	 * @since 1.0.0
	 * 
	 * @param  string  $filename  The filename of the image
	 * @param  string  $fullname  The fullname of the user.
	 * @param  int 	   $post_id   The id of the current post submited or testimonial
	 * @return string
	 */
	private function renameImage( $filename, $fullname, $post_id ) {
		$output = 0;
		if ( ! empty( $filename ) ) {
			$extension = explode( '.', $filename )[1];
			$temp_fullname = strtolower( str_replace( ' ', '-',  $fullname ) );
			$output = $temp_fullname .'-'. $post_id .'.'. $extension;
		}
		return $output;
	}


	/**
	 * Render the shortcode testimonial ratings.
	 *
	 * @since 1.0.0
	 */
	public function renderTestimonialRatings() {
		ob_start();
		$average 		= number_format( $this->getAverageRating()[0]->average, 2 );
		$total_count 	= $this->getTotalRatingCount()[0]->count;
		$rating_details = $this->getRatingListDetail();
		require_once get_template_directory() .'/app/Inc/MCTestimonial/partials/ratings.php';
		return ob_get_clean();
	}


	/**
	 * Returns the total average of the rating
	 *
	 * @since 1.0.0
	 * 
	 * @return object
	 */
	private function getAverageRating() {
		global $wpdb;
		$sql = "
			SELECT AVG( $wpdb->postmeta.meta_value ) AS average
			FROM   $wpdb->posts, $wpdb->postmeta
			WHERE  $wpdb->posts.ID = $wpdb->postmeta.post_id
			AND    $wpdb->postmeta.meta_key = '_mc_testimonial_rating' 
    		AND    $wpdb->posts.post_status = 'publish' 
    		AND    $wpdb->posts.post_type = 'testimonial'";
		return $wpdb->get_results( $sql, 'object' );
	}


	/**
	 * Returns the rating count and link each
	 * rating value.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	private function getEachRatingCount( $meta_value ) {
		global $wpdb;
		$sql = "
			SELECT COUNT( $wpdb->postmeta.meta_value ) AS count
			FROM   $wpdb->posts, $wpdb->postmeta
			WHERE  $wpdb->posts.ID = $wpdb->postmeta.post_id
			AND    $wpdb->postmeta.meta_key = '_mc_testimonial_rating'
			AND    $wpdb->postmeta.meta_value = $meta_value
    		AND    $wpdb->posts.post_status = 'publish' 
    		AND    $wpdb->posts.post_type = 'testimonial'
		";
		return $wpdb->get_results( $sql, 'object' );
	}


	/**
	 * Returns the total count of rating.
	 *
	 * @since 1.0.0
	 * 
	 * @return object
	 */
	private function getTotalRatingCount() {
		global $wpdb;
		$sql = "
			SELECT COUNT( $wpdb->postmeta.meta_value ) AS count
			FROM   $wpdb->posts, $wpdb->postmeta
			WHERE  $wpdb->posts.ID = $wpdb->postmeta.post_id
			AND    $wpdb->postmeta.meta_key = '_mc_testimonial_rating'
    		AND    $wpdb->posts.post_status = 'publish' 
    		AND    $wpdb->posts.post_type = 'testimonial'
		";
		return $wpdb->get_results( $sql, 'object' );
	}


	/**
	 * Returns the rating detail with count and link.
	 *
	 * @since 1.0.0
	 * 
	 * @return object
	 */
	private function getRatingListDetail() {
		$output = [];
		for ( $i=5; $i >= 1; $i-- ) { 
			$count = $this->getEachRatingCount( $i )[0]->count;
			$total_count = $this->getTotalRatingCount()[0]->count;
			$new_item = [
				'rating'  => $i,
				'count'   => $count,
				'link'	  => get_post_type_archive_link( 'testimonial' ) .'?rating='. $i,
				'percent' => number_format( ( $count / $total_count ) * 100 ) .'%'
			];
			array_push( $output, $new_item );
		}
		return (object) $output;
	}
}

new MCTestimonial();