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
		}

		/**
		 * Widget
		 *
		 * @param  array $args Widget arguments.
		 * @param  array $instance Widget instance.
		 * @return void
		 */
		function widget( $args, $instance ) {

			wp_enqueue_style( 'astra-widgets-font-style' );

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

			<div class="address clearfix">
				<address class="widget-address widget-address-<?php echo esc_attr( $style ); ?> widget-address-icons-<?php echo esc_attr( $social_icons ); ?>">

					<?php if ( ! empty( $address ) ) { ?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
								<span class="astra-icon-globe address-icons"></span>
							<?php } ?>
							<span class="address-meta"><?php echo nl2br( $address ); ?></span>
						</div>
					<?php } ?>
					<?php if ( ! empty( $phone ) ) { ?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
								<span class="astra-icon-phone address-icons"></span>
							<?php } ?>
							<span class="address-meta">
								<a href="tel:+<?php echo preg_replace( '/\D/', '', esc_attr( $phone ) ); ?>" ><?php echo esc_attr( $phone ); ?></a>
							</span>
						</div>
					<?php } ?>
					<?php if ( ! empty( $fax ) ) { ?>
						<div class="widget-address-field">
							<?php if ( $social_icons ) { ?>
								<span class="astra-icon-file address-icons"></span>
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
								<span class="astra-icon-envelope address-icons"></span>
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
