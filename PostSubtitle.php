<?php

namespace wpscholar\WordPress;

/**
 * Class PostSubtitle
 *
 * @package wpscholar\WordPress
 */
class PostSubtitle {

	/**
	 * @var string Name of the WordPress post type support.
	 */
	const FEATURE = 'subtitle';

	/**
	 * @var string Name of the post subtitle field.
	 */
	const FIELD = 'post_subtitle';

	/**
	 * Setup WordPress hooks.
	 */
	public static function initialize() {
		add_action( 'init', [ __CLASS__, 'registerMeta' ] );
		add_action( 'edit_form_after_title', [ __CLASS__, 'render' ] );
		add_action( 'save_post', [ __CLASS__, 'save' ], 10, 2 );
	}

	/**
	 * Register meta field.
	 */
	public static function registerMeta() {
		register_meta( 'post', '_' . self::FIELD, [
			'type'              => 'string',
			'description'       => 'The subtitle for the post.',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => [ __CLASS__, 'canEdit' ],
			'show_in_rest'      => true,
		] );
	}

	/**
	 *
	 *
	 * @param bool $allowed
	 * @param string $meta_key
	 * @param int $post_id
	 *
	 * @return bool
	 */
	public static function canEdit( $allowed, $meta_key, $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			$allowed = false;
		}

		return $allowed;
	}

	/**
	 * Render the post subtitle field.
	 *
	 * @param \WP_Post $post
	 */
	public static function render( \WP_Post $post ) {
		if ( post_type_supports( $post->post_type, self::FEATURE ) ) {
			do_action( 'edit_form_before_subtitle', $post );
			wp_nonce_field( self::FIELD, self::FIELD . '_nonce' );
			?>
            <div id="post-subtitle-wrap">
                <label for="<?php echo esc_attr( self::FIELD ); ?>"
                       style="font-size: 14px; font-weight: 600; line-height: 1.4;">
					<?php esc_html_e( 'Subtitle', 'wpscholar-post-subtitle' ); ?>
                </label>
                <input type="text"
                       class="widefat"
                       id="<?php echo esc_attr( self::FIELD ); ?>"
                       name="<?php echo esc_attr( self::FIELD ); ?>"
                       placeholder="<?php esc_attr_e( 'Enter subtitle here', 'wpscholar-post-subtitle' ); ?>"
                       style="font-size: 1.5em; height: 1.5em; line-height: 1; padding: 3px 8px;"
                       value="<?php echo esc_attr( self::getSubtitle( $post->ID ) ); ?>" />
            </div>
			<?php
			do_action( 'edit_form_after_subtitle', $post );
		}
	}

	/**
	 * Save the post subtitle (during normal post save)
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 */
	public static function save( $post_id, \WP_Post $post ) {
		if ( post_type_supports( $post->post_type, self::FEATURE ) ) {
			if ( wp_verify_nonce( filter_input( INPUT_POST, self::FIELD . '_nonce' ), self::FIELD ) ) {
				self::setSubtitle( $post_id, filter_input( INPUT_POST, self::FIELD ) );
			}
		}
	}

	/**
	 * Set the post subtitle
	 *
	 * @param int $post_id
	 * @param string $value
	 *
	 * @return bool|int
	 */
	public static function setSubtitle( $post_id, $value ) {
		return update_post_meta( $post_id, '_' . self::FIELD, $value );
	}

	/**
	 * Get the post subtitle
	 *
	 * @param int $post_id
	 *
	 * @return string
	 */
	public static function getSubtitle( $post_id ) {
		return (string) get_post_meta( $post_id, '_' . self::FIELD, true );
	}

}