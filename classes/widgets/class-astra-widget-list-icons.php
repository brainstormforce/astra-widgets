<?php
/**
 * Widget List Icons
 *
 * @package Astra Addon
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Widget_List_Icons' ) ) :

	/**
	 * Astra_Widget_List_Icons
	 *
	 * @since 1.0.0
	 */
	class Astra_Widget_List_Icons extends WP_Widget {

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
		 * @var string Widget ID base.
		 */
		public $id_base = 'astra-widget-list-icons';

		/**
		 * Stored data
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 * @var array Widget stored data.
		 */
		private $stored_data = array();

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
				$this->id_base, __( 'Astra: List Icons', 'astra-addon' ),
				array(
					'classname'   => $this->id_base,
					'description' => __( 'Display list icons.', 'astra-addon' ),
				),
				array(
					'id_base' => $this->id_base,
				)
			);

			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
			add_filter( 'astra_dynamic_css', array( $this, 'get_dynamic_css' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		}

		/**
		 * Register admin scripts
		 *
		 * @return void
		 */
		function register_admin_scripts() {
			wp_register_script( 'astra-widgets-' . $this->id_base, ASTRA_WIDGETS_URI . 'assets/js/unminified/astra-widget-list-icons.js' );
		}

		/**
		 * Register scripts
		 *
		 * @return void
		 */
		function register_scripts() {
			wp_register_style( 'astra-widgets-' . $this->id_base, ASTRA_WIDGETS_URI . 'assets/css/unminified/astra-widget-list-icons.css' );
		}

		/**
		 * Get fields
		 *
		 * @param  string $field Widget field.
		 * @param  mixed  $default Widget field default value.
		 * @return mixed stored/default widget field value.
		 */
		function get_fields( $field = '', $default = '' ) {

			// Emtpy stored values.
			if ( empty( $this->stored_data ) ) {
				return $default;
			}

			// Emtpy field.
			if ( empty( $field ) ) {
				return $default;
			}

			if ( ! array_key_exists( $field, $this->stored_data ) ) {
				return $default;
			}

			return $this->stored_data[ $field ];
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

			// Enqueue dynamic Scripts.
			wp_add_inline_style( 'astra-widgets-' . $this->id_base, $this->get_dynamic_css() );
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

			$width = $instance['width'] ? $instance['width'] : '';

			if ( ! empty( $width ) ) {
				$width = 'style= max-width:' . esc_attr( $width ) . 'px';
			}

			$list  = $this->get_fields( 'list', array() );
			$title = apply_filters( 'widget_title', $this->get_fields( 'title' ) );

			// Before Widget.
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>

			<div class="astra-widget-list-icons clearfix">
				<?php if ( ! empty( $list ) ) { ?>
					<ul>
						<?php
						foreach ( $list as $index => $list ) {
							$target = ( 'same-page' === $list['link-target'] ) ? '_self' : '_blank';
							$rel    = ( 'enable' === $list['nofollow'] ) ? 'noopener nofollow' : '';
							?>
							<li>
								<div class="link">
									<a href="<?php echo esc_url( $list['link'] ); ?>" target="<?php echo esc_attr( $target ); ?>" rel="<?php echo esc_attr( $rel ); ?>">
									<?php if ( 'icon' === $list['imageoricon'] ) { ?>
										<div class="icon">
											<span class="<?php echo esc_html( $list['icon'] ); ?>"></span>
										</div>
									<?php } ?>
									<?php if ( 'image' === $list['imageoricon'] ) { ?>		
										<div class="image" <?php echo esc_attr( $width ); ?>>
											<?php echo wp_get_attachment_image( $list['image'] ); ?>
										</div>
									<?php } ?>

									<span class="link-text"><?php echo esc_html( $list['title'] ); ?></span>

									</a>
								</div>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
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
			return wp_parse_args( $new_instance, $old_instance );
		}

		/**
		 * Widget Form
		 *
		 * @param  array $instance Widget instance.
		 * @return void
		 */
		function form( $instance ) {

			$custom_css = " .body{ background: '#000'; }";
			wp_enqueue_script( 'astra-widgets-' . $this->id_base );
			wp_add_inline_style( 'astra-font-style-style', $custom_css );

			$fields = array(
				array(
					'type'    => 'text',
					'id'      => 'title',
					'name'    => __( 'Title:', 'astra-addon' ),
					'default' => ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) ? $instance['title'] : '',
				),
				array(
					'id'      => 'list',
					'type'    => 'repeater',
					'title'   => __( 'Add Item:', 'astra-addon' ),
					'options' => array(
						array(
							'type'    => 'text',
							'id'      => 'title',
							'name'    => __( 'Title:', 'astra-addon' ),
							'default' => '',
						),
						array(
							'type'    => 'text',
							'id'      => 'link',
							'name'    => __( 'Link:', 'astra-addon' ),
							'default' => '',
						),
						array(
							'type'    => 'select',
							'name'    => 'Target',
							'id'      => 'link-target',
							'default' => ( isset( $instance['link-target'] ) && ! empty( $instance['link-target'] ) ) ? $instance['link-target'] : 'same-page',
							'options' => array(
								'same-page' => __( 'Same Page', 'astra-addon' ),
								'new-page'  => __( 'New Page', 'astra-addon' ),
							),
						),
						array(
							'type'    => 'select',
							'id'      => 'nofollow',
							'name'    => __( 'No Follow', 'astra-addon' ),
							'default' => ( isset( $instance['nofollow'] ) && ! empty( $instance['nofollow'] ) ) ? $instance['nofollow'] : 'enable',
							'options' => array(
								'enable'  => __( 'Enable', 'astra-addon' ),
								'disable' => __( 'Disable', 'astra-addon' ),
							),
						),
						array(
							'type'    => 'select',
							'id'      => 'imageoricon',
							'name'    => __( 'Image / Icon', 'astra-addon' ),
							'default' => ( isset( $instance['imageoricon'] ) && ! empty( $instance['imageoricon'] ) ) ? $instance['imageoricon'] : 'icon',
							'options' => array(
								'image' => __( 'Image', 'astra-addon' ),
								'icon'  => __( 'Icon', 'astra-addon' ),
							),
						),
						array(
							'type'    => 'image',
							'id'      => 'image',
							'name'    => __( 'Image:', 'astra-addon' ),
							'default' => '',
						),
						array(
							'type'    => 'icon',
							'id'      => 'icon',
							'name'    => __( 'Icon', 'astra-addon' ),
							'default' => '',
						),
					),
				),
				array(
					'type' => 'separator',
				),
				array(
					'type'    => 'number',
					'id'      => 'width',
					'name'    => __( 'Image / Icon Width:', 'astra-addon' ),
					'default' => ( isset( $instance['width'] ) && ! empty( $instance['width'] ) ) ? $instance['width'] : '',
				),
			);
			?>

			<div class="<?php echo esc_attr( $this->id_base ); ?>-fields">
				<?php
					// Generate fields.
					astra_generate_widget_fields( $this, $fields );
				?>
			</div>
			<?php
		}


		/**
		 * Dynamic CSS
		 *
		 * @return string
		 */
		function get_dynamic_css() {

			$dynamic_css = '';

			$instances = get_option( 'widget_' . $this->id_base );

			if ( array_key_exists( $this->number, $instances ) ) {
				$instance = $instances[ $this->number ];

				$width = isset( $instance['width'] ) ? $instance['width'] : '';

				$css_output = '';

				if ( isset( $width ) && ! empty( $width ) ) {
					$css_output = array(
						'.astra-widget-list-icons .image img' => array(
							'min-width' => esc_attr( $width ) . 'px',
						),
						'.astra-widget-list-icons .icon span' => array(
							'font-size' => esc_attr( $width ) . 'px',
						),
					);

					$css_output = astra_parse_css( $css_output );
				}

				return $dynamic_css . $css_output;
			}

			return $dynamic_css;

		}

	}

endif;
