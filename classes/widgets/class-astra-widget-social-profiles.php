<?php
/**
 * Widget List Icons
 *
 * @package Astra Addon
 * @since 1.6.0
 */

if ( ! class_exists( 'Astra_Widget_Social_Profiles' ) ) :

	/**
	 * Astra_Widget_Social_Profiles
	 *
	 * @since 1.6.0
	 */
	class Astra_Widget_Social_Profiles extends WP_Widget {

		/**
		 * Instance
		 *
		 * @since 1.6.0
		 *
		 * @access private
		 * @var object Class object.
		 */
		private static $instance;

		/**
		 * Widget Base
		 *
		 * @since 1.6.0
		 *
		 * @access public
		 * @var string Widget ID base.
		 */
		public $id_base = 'astra-widget-social-profiles';

		/**
		 * Stored data
		 *
		 * @since 1.6.0
		 *
		 * @access private
		 * @var array Widget stored data.
		 */
		private $stored_data = array();

		/**
		 * Initiator
		 *
		 * @since 1.6.0
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
		 * @since 1.6.0
		 */
		function __construct() {
			parent::__construct(
				$this->id_base,
				__( 'Astra: Social Profiles', 'astra-addon' ),
				array(
					'classname'   => $this->id_base,
					'description' => __( 'Display social profiles.', 'astra-addon' ),
				),
				array(
					'id_base' => $this->id_base,
				)
			);

			add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		}


		/**
		 * Register admin scripts
		 *
		 * @return void
		 */
		function register_admin_scripts() {
			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$js_uri  = ASTRA_WIDGETS_URI . 'assets/js/' . $dir_name . '/';
			$css_uri = ASTRA_WIDGETS_URI . 'assets/css/' . $dir_name . '/';

			wp_enqueue_script( 'astra-widgets-' . $this->id_base, $js_uri . 'astra-widget-social-profiles' . $file_prefix . '.js', array(), ASTRA_WIDGETS_VER );
			wp_register_style( 'astra-widget-social-profiles-admin', $css_uri . 'astra-widget-social-profiles-admin' . $file_prefix . '.css' );
		}

		/**
		 * Register scripts
		 *
		 * @return void
		 */
		function register_scripts() {
			/* Directory and Extension */
			$file_prefix = ( SCRIPT_DEBUG ) ? '' : '.min';
			$dir_name    = ( SCRIPT_DEBUG ) ? 'unminified' : 'minified';

			$js_uri  = ASTRA_WIDGETS_URI . 'assets/js/' . $dir_name . '/';
			$css_uri = ASTRA_WIDGETS_URI . 'assets/css/' . $dir_name . '/';

			wp_register_style( 'astra-widgets-' . $this->id_base, $css_uri . 'astra-widget-social-profiles' . '.css' );
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
		 * Dynamic CSS
		 *
		 * @return string              Dynamic CSS.
		 */
		function get_dynamic_css() {

			$dynamic_css = '';

			$instances = get_option( 'widget_' . $this->id_base );

			if ( array_key_exists( $this->number, $instances ) ) {
				$instance = $instances[ $this->number ];

				$icon_color       = isset( $instance['icon-color'] ) ? $instance['icon-color'] : '';
				$bg_color         = isset( $instance['bg-color'] ) ? $instance['bg-color'] : '';
				$icon_hover_color = isset( $instance['icon-hover-color'] ) ? $instance['icon-hover-color'] : '';
				$bg_hover_color   = isset( $instance['bg-hover-color'] ) ? $instance['bg-hover-color'] : '';
				$icon_width       = isset( $instance['width'] ) ? $instance['width'] : '';
				$color_type       = isset( $instance['color-type'] ) ? $instance['color-type'] : '';
				$list             = isset( $instance['list'] ) ? $instance['list'] : '';
				// $icons_color      = Astra_Widgets_Helper::get_icons();
				$icons_color      = array();

				if ( 'official-color' === $color_type ) {

					$new_color_output = '';
					if ( ! empty( $list ) ) {
						foreach ( $list as $key => $value ) {
							$str                 = $value['icon'];
							// $color_array         = $icons_color[ $str ];
							$icon_color_official = "#333";
							$trimmed = str_replace( 'astra-icon-', '', $str );

							$color_output = array(
								'.astra-widget-social-profiles-inner.icon-official-color.simple li .' . $value['icon'] . '.ast-widget-icon' => array(
									'color'            => esc_attr( $icon_color_official ),
									'background-color' => 'transparent',
								),
								'.astra-widget-social-profiles-inner.icon-official-color li .' . $value['icon'] . '.ast-widget-icon' => array(
									'color'            => '#fff',
									'background-color' => esc_attr( $icon_color_official ),
								),
								'.astra-widget-social-profiles-inner.icon-official-color.square-outline li .' . $value['icon'] . '.ast-widget-icon, .astra-widget-social-profiles-inner.icon-official-color.circle-outline li .' . $value['icon'] . '.ast-widget-icon' => array(
									'color'            => esc_attr( $icon_color_official ),
									'background-color' => 'transparent',
								),
							);
							$color_output = astra_widgets_parse_css( $color_output );

							$new_color_output .= $color_output;
						}
					}

					$dynamic_css .= $dynamic_css . $new_color_output;
				}
				$css_output  = array(
					'.astra-widget-social-profiles-inner li .ast-widget-icon' => array(
						'color' => esc_attr( $icon_color ),
					),
					'.astra-widget-social-profiles-inner li .ast-widget-icon:hover' => array(
						'color' => esc_attr( $icon_hover_color ),
					),
					'.astra-widget-social-profiles-inner.square-outline li .ast-widget-icon, .astra-widget-social-profiles-inner.circle-outline li .ast-widget-icon' => array(
						'background' => 'transparent',
						'color'      => esc_attr( $icon_color ),
						'border'     => esc_attr( $bg_color ),
						'color'      => esc_attr( $icon_hover_color ),
					),
					'.astra-widget-social-profiles-inner.square .ast-widget-icon, .astra-widget-social-profiles-inner.circle .ast-widget-icon' => array(
						'background' => esc_attr( $bg_color ),
						'border'     => esc_attr( $bg_color ),
						'color'      => esc_attr( $icon_color ),
					),
					'.astra-widget-social-profiles-inner.square-outline li .ast-widget-icon:hover, .astra-widget-social-profiles-inner.circle-outline li .ast-widget-icon:hover' => array(
						'background' => 'transparent',
						'color'      => esc_attr( $icon_hover_color ),
						'border'     => esc_attr( $bg_hover_color ),
					),
					'.astra-widget-social-profiles-inner.square .ast-widget-icon:hover, .astra-widget-social-profiles-inner.circle .ast-widget-icon:hover' => array(
						'background' => esc_attr( $bg_hover_color ),
						'border'     => esc_attr( $bg_hover_color ),
						'color'      => esc_attr( $icon_hover_color ),
					),
					'.astra-widget-social-profiles-inner .ast-widget-icon' => array(
						'font-size' => astra_widget_get_css_value( $icon_width, 'px' ),
					),
					'.astra-widget-social-profiles-inner.circle li .ast-widget-icon, .astra-widget-social-profiles-inner.circle-outline li .ast-widget-icon' => array(
						'font-size' => astra_widget_get_css_value( $icon_width, 'px' ),
					),
				);
				$css_output  = astra_widgets_parse_css( $css_output );
				$dynamic_css = $dynamic_css . $css_output;
			}

			return $dynamic_css;
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
			wp_enqueue_style( 'astra-widgets-font-style' );

			$list             = $this->get_fields( 'list', array() );
			$align            = $this->get_fields( 'align' );
			$icon_color       = $this->get_fields( 'icon-color' );
			$bg_color         = $this->get_fields( 'bg-color' );
			$icon_hover_color = $this->get_fields( 'icon-hover-color' );
			$bg_hover_color   = $this->get_fields( 'bg-hover-color' );
			$icon_style       = $this->get_fields( 'icon-style' );
			$display_title    = $this->get_fields( 'display-title', false );
			$color_type       = $this->get_fields( 'color-type', false );
			$icon             = $this->get_fields( 'icon', false );
			$title            = apply_filters( 'widget_title', $this->get_fields( 'title' ) );

			// Before Widget.
			echo $args['before_widget'];
			if ( $title ) {
				echo $args['before_title'] . $title . $args['after_title'];
			} ?>

			<div class="astra-widget-social-profiles-inner clearfix <?php echo esc_attr( $align ); ?> <?php echo esc_attr( $icon_style ); ?> <?php echo 'icon-' . esc_attr( $color_type ); ?>">
				<?php if ( ! empty( $list ) ) { ?>
					<ul>
						<?php
						foreach ( $list as $index => $list ) {
							$target = ( 'same-page' === $list['link-target'] ) ? '_self' : '_blank';
							$rel    = ( 'enable' === $list['nofollow'] ) ? 'noopener nofollow' : '';

							$list_data = json_decode( $list['icon'] );

							$trimmed = str_replace( 'astra-icon-', '', $list['icon'] );
							?>
							<li>
								<a target="_blank" href="<?php echo esc_url( $list['link'] ); ?>" target="<?php echo esc_attr( $target ); ?>" rel="<?php echo esc_attr( $rel ); ?>">
									<!-- <div class="icon <?php // echo esc_html( $trimmed ); ?>"> -->
										<!-- <span class="<?php // echo esc_html( $list['icon'] ); ?>"></span> -->
										<span class="ast-widget-icon <?php echo ( is_object( $list_data ) ) ? esc_html( $list_data->name ) : ''; ?>">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="<?php echo ( isset( $list_data->viewbox ) ) ? $list_data->viewbox : ''; ?>"><path d="<?php echo ( isset( $list_data->path ) ) ? $list_data->path : ''; ?>"></path></svg>
										</span>
									<!-- </div> -->
									<?php if ( $display_title ) { ?>
										<span class="link"><?php echo esc_html( $list['title'] ); ?></span>
									<?php } ?>
								</a>
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

			$instance = wp_parse_args( $new_instance, $old_instance );

			/**
			 * Checkbox field support!
			 *
			 * @todo The checkbox field we need to set the boolean value `true/false`
			 *       For now we have not able to detect the `checkbox` field in `generate` of class `Astra_Widgets_Helper`.
			 */
			$instance['display-title'] = isset( $new_instance['display-title'] ) ? (bool) $new_instance['display-title'] : false;

			return $instance;
		}

		/**
		 * Widget Form
		 *
		 * @param  array $instance Widget instance.
		 * @return void
		 */
		function form( $instance ) {

			wp_enqueue_script( 'astra-widgets-' . $this->id_base );
			wp_enqueue_style( 'astra-widget-social-profiles-admin' );
			wp_enqueue_style( 'astra-widgets-font-style' );

			$fields = array(
				array(
					'type'    => 'text',
					'id'      => 'title',
					'name'    => __( 'Title', 'astra-addon' ),
					'default' => ( isset( $instance['title'] ) && ! empty( $instance['title'] ) ) ? $instance['title'] : '',
				),
				array(
					'type' => 'separator',
				),
				array(
					'type' => 'heading',
					'name' => __( 'Social Profiles', 'astra-addon' ),
				),
				array(
					'id'      => 'list',
					'type'    => 'repeater',
					'title'   => __( 'Add Profile', 'astra-addon' ),
					'options' => array(
						array(
							'type'    => 'text',
							'id'      => 'title',
							'name'    => __( 'Title', 'astra-addon' ),
							'default' => '',
						),
						array(
							'type'    => 'text',
							'id'      => 'link',
							'name'    => __( 'Link', 'astra-addon' ),
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
							'type'      => 'icon',
							'id'        => 'icon',
							'name'      => __( 'Icon', 'astra-addon' ),
							'default'   => '',
							'show_icon' => 'yes',
						),
					),
				),
				array(
					'type' => 'separator',
				),
				array(
					'type' => 'heading',
					'name' => __( 'Styling', 'astra-addon' ),
				),
				array(
					'type'    => 'checkbox',
					'id'      => 'display-title',
					'name'    => __( 'Display profile title?', 'astra-addon' ),
					'default' => ( isset( $instance['display-title'] ) && ! empty( $instance['display-title'] ) ) ? $instance['display-title'] : false,
				),
				array(
					'type'    => 'select',
					'id'      => 'align',
					'name'    => __( 'Alignment', 'astra-addon' ),
					'default' => isset( $instance['align'] ) ? $instance['align'] : '',
					'options' => array(
						'inline' => __( 'Inline', 'astra-addon' ),
						'stack'  => __( 'Stack', 'astra-addon' ),
					),
				),
				array(
					'type'    => 'select',
					'id'      => 'icon-style',
					'name'    => __( 'Icon Style', 'astra-addon' ),
					'default' => isset( $instance['icon-style'] ) ? $instance['icon-style'] : '',
					'options' => array(
						'simple'         => __( 'Simple', 'astra-addon' ),
						'circle'         => __( 'Circle', 'astra-addon' ),
						'square'         => __( 'Square', 'astra-addon' ),
						'circle-outline' => __( 'Circle Outline', 'astra-addon' ),
						'square-outline' => __( 'Square Outline', 'astra-addon' ),
					),
				),
				array(
					'type'    => 'select',
					'id'      => 'color-type',
					'name'    => __( 'Alignment', 'astra-addon' ),
					'default' => isset( $instance['color-type'] ) ? $instance['color-type'] : '',
					'options' => array(
						'official-color' => __( 'Official Color', 'astra-addon' ),
						'custom-color'   => __( 'Custom', 'astra-addon' ),
					),
				),
				array(
					'type'    => 'color',
					'id'      => 'icon-color',
					'name'    => __( 'Icon Color', 'astra-addon' ),
					'default' => ( isset( $instance['icon-color'] ) && ! empty( $instance['icon-color'] ) ) ? $instance['icon-color'] : '',
				),
				array(
					'type'    => 'color',
					'id'      => 'bg-color',
					'name'    => __( 'Background Color', 'astra-addon' ),
					'default' => ( isset( $instance['bg-color'] ) && ! empty( $instance['bg-color'] ) ) ? $instance['bg-color'] : '',
				),
				array(
					'type'    => 'color',
					'id'      => 'icon-hover-color',
					'name'    => __( 'Hover Icon Color', 'astra-addon' ),
					'default' => ( isset( $instance['icon-hover-color'] ) && ! empty( $instance['icon-hover-color'] ) ) ? $instance['icon-hover-color'] : '',
				),
				array(
					'type'    => 'color',
					'id'      => 'bg-hover-color',
					'name'    => __( 'Hover Background Color', 'astra-addon' ),
					'default' => ( isset( $instance['bg-hover-color'] ) && ! empty( $instance['bg-hover-color'] ) ) ? $instance['bg-hover-color'] : '',
				),
				array(
					'type'    => 'number',
					'id'      => 'width',
					'name'    => __( 'Icon Width:', 'astra-addon' ),
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

	}

endif;
