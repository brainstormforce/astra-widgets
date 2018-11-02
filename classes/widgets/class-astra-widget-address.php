<?php
/**
 * Address Widget
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Widget_Address' ) ) :

	/**
	 * Astra_Widget_Address
	 *
	 * @since 1.0.0
	 */
	class Astra_Widget_Address extends WP_Widget {

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Widget Base
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 * @var object Class object.
		 */
		public $id_base = 'astra-widget-address';

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 *
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		function __construct() {
			parent::__construct(
				$this->id_base,
				__( 'Astra: Address', 'astra' ),
				array(
					'classname'   => $this->id_base,
					'description' => __( 'Display Address.', 'astra' ),
				),
				array(
					'id_base' => $this->id_base,
				)
			);
			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		}

		/**
		 * Register scripts
		 *
		 * @return void
		 */
		function register_scripts() {
			wp_register_style( 'astra-widgets-' . $this->id_base, ASTRA_WIDGETS_URI . 'assets/css/unminified/astra-widget-address.css' );
		}

		/**
		 * Frontend setup
		 *
		 * @param  array $args Widget arguments.
		 * @param  array $instance Widget instance.
		 * @return void
		 */
		function _front_setup( $args, $instance ) {

			// Set stored data.
			$this->stored_data = $instance;

			// Enqueue Scripts.
			wp_enqueue_style( 'astra-widgets-' . $this->id_base );
		}

		/**
		 * Widget
		 *
		 * @param  array $args Widget arguments.
		 * @param  array $instance Widget instance.
		 * @return void
		 */
		function widget( $args, $instance ) {

			$this->_front_setup( $args, $instance );

			$title        = apply_filters( 'widget_title', $instance['title'] );
			$style        = isset( $instance['style'] ) ? $instance['style'] : 'stack';
			$social_icons = isset( $instance['display-icons'] ) ? $instance['display-icons'] : false;
			$address      = isset( $instance['address'] ) ? $instance['address'] : '';
			$phone        = isset( $instance['phone'] ) ? $instance['phone'] : '';
			$fax          = isset( $instance['fax'] ) ? $instance['fax'] : '';
			$email        = isset( $instance['email'] ) ? $instance['email'] : '';

			// Before Widget.
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>
			<?php
			$widget_content_font_size = '15';
			if ( function_exists( 'astra_get_option' ) ) {
				$widget_content_font_size = astra_get_option( 'font-size-widget-content' );
				$widget_content_font_size = $widget_content_font_size['desktop'];
				if ( '' === $widget_content_font_size ) {
					$widget_content_font_size = '15';
				}
			}
			?>

			<div class="address clearfix">
				<address class="widget-address widget-address-<?php echo esc_attr( $style ); ?> widget-address-icons-<?php echo esc_attr( $social_icons ); ?>">

					<?php if ( ! empty( $address ) ) { ?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
									<?php // Font Awesome 5 SVG ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="address-icons" width="<?php echo $widget_content_font_size . 'px'; ?>" height="<?php echo $widget_content_font_size . 'px'; ?>" viewBox="0 0 496 512"><path d="M336.5 160C322 70.7 287.8 8 248 8s-74 62.7-88.5 152h177zM152 256c0 22.2 1.2 43.5 3.3 64h185.3c2.1-20.5 3.3-41.8 3.3-64s-1.2-43.5-3.3-64H155.3c-2.1 20.5-3.3 41.8-3.3 64zm324.7-96c-28.6-67.9-86.5-120.4-158-141.6 24.4 33.8 41.2 84.7 50 141.6h108zM177.2 18.4C105.8 39.6 47.8 92.1 19.3 160h108c8.7-56.9 25.5-107.8 49.9-141.6zM487.4 192H372.7c2.1 21 3.3 42.5 3.3 64s-1.2 43-3.3 64h114.6c5.5-20.5 8.6-41.8 8.6-64s-3.1-43.5-8.5-64zM120 256c0-21.5 1.2-43 3.3-64H8.6C3.2 212.5 0 233.8 0 256s3.2 43.5 8.6 64h114.6c-2-21-3.2-42.5-3.2-64zm39.5 96c14.5 89.3 48.7 152 88.5 152s74-62.7 88.5-152h-177zm159.3 141.6c71.4-21.2 129.4-73.7 158-141.6h-108c-8.8 56.9-25.6 107.8-50 141.6zM19.3 352c28.6 67.9 86.5 120.4 158 141.6-24.4-33.8-41.2-84.7-50-141.6h-108z"></path>
									</svg>
								</span>
							<?php } ?>
							<span class="address-meta"><?php echo nl2br( $address ); ?></span>
						</div>
					<?php } ?>
					<?php if ( ! empty( $phone ) ) { ?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
									<?php // Font Awesome 5 SVG ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="address-icons" width="<?php echo $widget_content_font_size . 'px'; ?>" height="<?php echo $widget_content_font_size . 'px'; ?>" viewBox="0 0 512 512"><path d="M493.4 24.6l-104-24c-11.3-2.6-22.9 3.3-27.5 13.9l-48 112c-4.2 9.8-1.4 21.3 6.9 28l60.6 49.6c-36 76.7-98.9 140.5-177.2 177.2l-49.6-60.6c-6.8-8.3-18.2-11.1-28-6.9l-112 48C3.9 366.5-2 378.1.6 389.4l24 104C27.1 504.2 36.7 512 48 512c256.1 0 464-207.5 464-464 0-11.2-7.7-20.9-18.6-23.4z"></path>
									</svg>
								</span>
							<?php } ?>
							<span class="address-meta">
								<a href="tel:+<?php echo preg_replace( '/\D/', '', esc_attr( $phone ) ); ?>" ><?php echo esc_attr( $phone ); ?></a>
							</span>
						</div>
					<?php } ?>
					<?php if ( ! empty( $fax ) ) { ?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
									<?php // Font Awesome 5 SVG ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="address-icons" width="<?php echo $widget_content_font_size . 'px'; ?>" height="<?php echo $widget_content_font_size . 'px'; ?>" viewBox="0 0 384 512"><path d="M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm160-14.1v6.1H256V0h6.1c6.4 0 12.5 2.5 17 7l97.9 98c4.5 4.5 7 10.6 7 16.9z"></path>
									</svg>
								</span>
							<?php } ?>
							<span class="address-meta"><?php echo esc_attr( $fax ); ?></span>
						</div>
					<?php } ?>
					<?php
					if ( ! empty( $email ) ) {
						$email = sanitize_email( $email );
						?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
									<?php // Font Awesome 5 SVG ?>
									<svg xmlns="http://www.w3.org/2000/svg" class="address-icons" width="<?php echo $widget_content_font_size . 'px'; ?>" height="<?php echo $widget_content_font_size . 'px'; ?>" viewBox="0 0 512 512"><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"></path>
									</svg>
								</span>
							<?php } ?>
							<span class="address-meta">
								<a href="mailto:<?php echo antispambot( $email ); ?>" ><?php echo antispambot( $email ); ?></a>
							</span>
						</div>
					<?php } ?>
				</address>
			</div>

			<?php

			// After Widget.
			echo $args['after_widget'];
		}

		/**
		 * Update
		 *
		 * @param  array $new_instance Widget new instance.
		 * @param  array $old_instance Widget old instance.
		 * @return array                Merged updated instance.
		 */
		function update( $new_instance, $old_instance ) {

			$instance = wp_parse_args( $new_instance, $old_instance );

			/**
			 * Checkbox field support!
			 *
			 * @todo The checkbox field we need to set the boolean value `true/false`
			 *       For now we have not able to detect the `checkbox` field in `generate` of class `Astra_Widgets_Helper`.
			 */
			$instance['display-icons'] = isset( $new_instance['display-icons'] ) ? (bool) $new_instance['display-icons'] : false;

			return $instance;
		}

		/**
		 * Widget Form
		 *
		 * @param  array $instance Widget instance.
		 * @return void
		 */
		function form( $instance ) {

			$fields = array(
				array(
					'type'    => 'text',
					'id'      => 'title',
					'name'    => __( 'Title:', 'astra' ),
					'default' => ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) ? $instance['title'] : '',
				),
				array(
					'name'    => 'Style',
					'id'      => 'style',
					'type'    => 'select',
					'default' => ( isset( $instance['style'] ) && ! empty( $instance['style'] ) ) ? $instance['style'] : 'stack',
					'options' => array(
						'inline' => __( 'Inline', 'astra-addon' ),
						'stack'  => __( 'Stack', 'astra-addon' ),
					),
				),
				array(
					'type'    => 'checkbox',
					'id'      => 'display-icons',
					'name'    => __( 'Display Icons?', 'astra-addon' ),
					'default' => ( isset( $instance['display-icons'] ) && ! empty( $instance['display-icons'] ) ) ? $instance['display-icons'] : false,
				),
				array(
					'type'    => 'textarea',
					'id'      => 'address',
					'name'    => __( 'Address:', 'astra' ),
					'default' => ( isset( $instance['address'] ) && ! empty( $instance['address'] ) ) ? $instance['address'] : '',
				),
				array(
					'type'    => 'text',
					'id'      => 'phone',
					'name'    => __( 'Phone:', 'astra' ),
					'default' => ( isset( $instance['phone'] ) && ! empty( $instance['phone'] ) ) ? $instance['phone'] : '',
				),
				array(
					'type'    => 'text',
					'id'      => 'fax',
					'name'    => __( 'FAX:', 'astra' ),
					'default' => ( isset( $instance['fax'] ) && ! empty( $instance['fax'] ) ) ? $instance['fax'] : '',
				),
				array(
					'type'    => 'text',
					'id'      => 'email',
					'name'    => __( 'Email:', 'astra' ),
					'default' => ( isset( $instance['email'] ) && ! empty( $instance['email'] ) ) ? $instance['email'] : '',
				),
			);

			// Generate fields.
			astra_generate_widget_fields( $this, $fields );
		}

	}

endif;
