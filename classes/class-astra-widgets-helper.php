<?php
/**
 * Astra Widgets
 *
 * @package Astra Widgets
 * @since 1.0.0
 */

if ( ! class_exists( 'Astra_Widgets_Helper' ) ) :

	/**
	 * Astra_Widgets_Helper
	 *
	 * @since 1.0.0
	 */
	class Astra_Widgets_Helper {

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
		public function __construct() {
		}

		/**
		 * Load Font Awesome Icons
		 *
		 * @since 1.0.0
		 */
		public static function backend_load_font_awesome_icons() {

			if ( ! file_exists( ASTRA_WIDGETS_DIR . 'assets/fonts/icons.json' ) ) {
				return array();
			}

			$str  = file_get_contents( ASTRA_WIDGETS_DIR . 'assets/fonts/icons.json' );
			$json = json_decode( $str, true ); // decode the JSON into an associative array.

			return $json;
		}

		/**
		 * Check exiting fields have any repeater field?
		 *
		 * If found then return `true`. Default `false`.
		 *
		 * @param  array $fields Fields array.
		 * @return boolean        Repeater field exist.
		 */
		function have_repeator_field( $fields = array() ) {
			foreach ( $fields as $key => $field ) {
				if ( 'repeater' === $field['type'] ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Generate fields.
		 *
		 * @param  object $self        Widget object.
		 * @param  array  $fields      Fields array.
		 * @param  string $repeater_id Repeater ID.
		 * @return void
		 */
		function generate( $self, $fields = array(), $repeater_id = '' ) {

			$defaults = array(
				'type'    => '',
				'id'      => '',
				'name'    => '',
				'default' => '',
				'desc'    => '',
			);

			if ( ! empty( $fields ) && is_array( $fields ) ) {
				foreach ( $fields as $key => $value ) {
					$value              = wp_parse_args( $value, $defaults );
					$font_awesome_icons = self::backend_load_font_awesome_icons();

					$class = isset( $value['class'] ) ? $value['class'] : '';

					switch ( $value['type'] ) {
						case 'icon':
							$field_id   = '';
							$field_name = '';

							$decoded_icon_data = json_decode( $value['default'] );
							$encode_icon_data  = $value['default'];

							if ( empty( $repeater_id ) || $this->have_repeator_field( $fields ) ) {
								$field_id   = $self->get_field_id( $value['id'] );
								$field_name = $self->get_field_name( $value['id'] );
							}
							?>
									<div class="astra-widget-icon-selector">
										<?php if( isset( $value['name'] ) && '' !== $value['name'] ) { ?>
										<label for="<?php echo esc_attr( $field_id ); ?>">
											<?php echo esc_html( $value['name'] ); ?>
										</label>
										<?php } ?>

										<div class="astra-widget-icon-selector-actions">
											<div class="astra-select-icon button">
												<div class="astra-selected-icon"> 
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="<?php echo ( isset( $decoded_icon_data->viewbox ) ) ? esc_attr( $decoded_icon_data->viewbox ) : ''; ?>"><path d="<?php echo ( isset( $decoded_icon_data->path ) ) ? esc_attr( $decoded_icon_data->path ) : ''; ?>"></path></svg>
												</div>
												<?php esc_html_e( 'Choose icon..', 'astra-addon' ); ?>
											</div>
										</div>


										<div class="astra-icons-list-wrap">
											<input type="search" placeholder="Search icon..." class="search-icon">
											<ul class="astra-widget-icons-list">
												<?php
													// Get icons array.
													// $icons = self::get_icons(); .
												foreach ( $font_awesome_icons as $index => $field ) {

													$viewbox_array = '';
													$viewbox_array = ( isset( $field['svg']['brands']['viewBox']['0'] ) ) ? $field['svg']['brands']['viewBox'] : $field['svg']['solid']['viewBox'];
													$viewbox       = implode( ' ', $viewbox_array );
													array_push( $field['search']['terms'], $index );
													array_push( $field['search']['terms'], $field['styles']['0'] );

													?>

														<li class="astra-widget-icon <?php echo $index; ?>" data-search-terms="<?php echo implode( ' ', $field['search']['terms'] ); ?>" data-font="<?php echo $index; ?>" data-viewbox="<?php echo $viewbox; ?>" data-path="<?php echo ( isset( $field['svg']['brands']['path'] ) ) ? $field['svg']['brands']['path'] : $field['svg']['solid']['path']; ?>"> 
															<?php
															if ( isset( $field['svg']['brands']['raw'] ) ) {
																echo $field['svg']['brands']['raw'];
															} elseif ( isset( $field['svg']['solid']['raw'] ) ) {
																echo $field['svg']['solid']['raw'];
															}
															?>
														</li>
													<?php
												}
												?>
											</ul>
										</div>

										<input class="widefat selected-icon" type="hidden"
											id="<?php echo esc_attr( $field_id ); ?>"
											name="<?php echo esc_attr( $field_name ); ?>"
											value="<?php echo esc_attr( $encode_icon_data ); ?>"
											data-field-id="<?php echo esc_attr( $value['id'] ); ?>"
											data-icon-visible="<?php echo esc_attr( ( isset( $value['show_icon'] ) ) ? $value['show_icon'] : 'no' ); ?>"
										/>
										<span><?php echo $value['desc']; ?></span>
									</div>
								<?php
							break;

						/**
						 * Note: Add below code in `update()` function of individual widget.
						 *
						 * $instance['{FIELD_NAME}'] = isset( $new_instance['{FIELD_NAME}'] ) ? (bool) $new_instance['{FIELD_NAME}'] : false;
						 *
						 * @todo We'll do this in this function instead of the individual widget update function.
						 */
						case 'checkbox':
							?>
									<div class="astra-widget-field astra-widget-field-checkbox">
										<input class="checkbox" type="checkbox"
											<?php checked( $value['default'] ); ?>
											id="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"
											name="<?php echo esc_attr( $self->get_field_name( $value['id'] ) ); ?>" />
										<label for="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"><?php echo $value['name']; ?></label>
									</div>
									<?php
							break;
						case 'repeater':
							?>
								<div class="astra-repeater">
									<div class="astra-repeater-container">
										<div class="astra-repeater-sortable">
											<?php
											$this->generate_repeater_fields( $self, $fields, $value );
											?>
										</div>
									</div>
									<div class="add-new">
										<button class="add-new-btn button"><?php _e( 'Add item', 'astra-addon' ); ?></button>
									</div>

									<?php
									$repeater_id = 'widget-' . $self->id_base . '[' . $self->number . '][' . $value['id'] . ']';
									?>

									<div class="astra-repeater-fields" title="<?php echo $value['title']; ?>" data-id="<?php echo esc_attr( $repeater_id ); ?>" style="display: none;">
										<?php $this->generate( $self, $value['options'], $value['id'] ); ?>
									</div>
								</div>
							<?php
							break;

						case 'text':
							$field_id   = '';
							$field_name = '';
							if ( empty( $repeater_id ) || $this->have_repeator_field( $fields ) ) {
								$field_id   = $self->get_field_id( $value['id'] );
								$field_name = $self->get_field_name( $value['id'] );
							}
							?>
										<div class="astra-widget-field astra-widget-field-text">
											<label for="<?php echo esc_attr( $field_id ); ?>">
												<?php echo $value['name']; ?>
											</label>
											<input class="widefat" type="text"
												id="<?php echo esc_attr( $field_id ); ?>"
												name="<?php echo esc_attr( $field_name ); ?>"
												value="<?php echo esc_attr( $value['default'] ); ?>"
												data-field-id="<?php echo esc_attr( $value['id'] ); ?>"
											/>
											<span><?php echo $value['desc']; ?></span>
										</div>
									<?php
							break;
						case 'image':
							$img_url = '';
							if ( ! empty( $value['default'] ) ) {
								if ( strstr( $value['default'], 'http://' ) ) {
									$img_url = $value['default'];
								} else {
									$img_url     = wp_get_attachment_image_src( $value['default'], 'medium' );
										$img_url = $img_url[0];
								}
							}

							$field_id   = '';
							$field_name = '';
							if ( empty( $repeater_id ) || $this->have_repeator_field( $fields ) ) {
								$field_id   = $self->get_field_id( $value['id'] );
								$field_name = $self->get_field_name( $value['id'] );
							}
							?>
									<p>
										<div class="astra-field-image-wrapper">
											<div class="astra-field-image-title" for="<?php echo esc_attr( $field_id ); ?>">
													<?php echo $value['name']; ?>
											</div>
											<div class="astra-field-image">
												<div class="astra-field-image-preview">
													<?php if ( ! empty( $img_url ) ) { ?>
														<span class="astra-remove-image button">Remove</span><img src="<?php echo $img_url; ?>" />
													<?php } ?>
												</div>
												<input
													class="astra-field-image-preview-id"
													id="<?php echo esc_attr( $field_id ); ?>"
													name="<?php echo esc_attr( $field_name ); ?>"
													type="hidden"
													value="<?php echo $value['default']; ?>"
													data-field-id="<?php echo esc_attr( $value['id'] ); ?>">
												<div class="astra-select-image">Choose Image</div>
											</div>
										</div>
									</p>
									<?php
							break;
						case 'radio':
							?>
									<p>
										<label for="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"><?php echo $value['name']; ?></label>
										<?php foreach ( $value['options'] as $option ) { ?>

											<?php
											$c = '';
											if ( $option == $value['default'] ) {
												$c = ' checked="checked" ';
											}
											?>
											<input <?php echo $value['default']; ?> class="widefat" type="radio" <?php echo $c; ?> name="<?php echo $self->get_field_name( $value['id'] ); ?>" value="<?php echo esc_attr( $option ); ?>" />
										<?php } ?>
									</p>
									<?php
							break;
						case 'select':
								$field_id   = '';
								$field_name = '';
							if ( empty( $repeater_id ) || $this->have_repeator_field( $fields ) ) {
								$field_id   = $self->get_field_id( $value['id'] );
								$field_name = $self->get_field_name( $value['id'] );
							}
							?>
								<div class="astra-widget-field astra-widget-field-select">
									<div class="astra-widget-field-<?php echo esc_attr( $value['id'] ); ?>">
									<label for="<?php echo esc_attr( $field_id ); ?>"><?php echo $value['name']; ?></label>
										<select class="widefat" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_name ); ?>"
											data-field-id="<?php echo esc_attr( $value['id'] ); ?>">
											<?php
											foreach ( $value['options'] as $op_val => $op_name ) {
												?>
												<option value="<?php echo $op_val; ?>" <?php selected( $value['default'], $op_val ); ?>><?php echo $op_name; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<?php
							break;
						case 'hidden':
							?>
										<input class="<?php echo $class; ?> widefat" type="hidden" id="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>" name="<?php echo esc_attr( $self->get_field_name( $value['id'] ) ); ?>" value="<?php echo esc_attr( $value['default'] ); ?>"/>
									<?php
							break;
						case 'color':
							?>

									<div class="astra-widget-field astra-widget-field-color">
										<div class="astra-widget-field-<?php echo esc_attr( $value['id'] ); ?>">
											<label for="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"><?php echo $value['name']; ?></label>
											<input class="<?php echo $class; ?> widefat" type="text" id="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>" name="<?php echo esc_attr( $self->get_field_name( $value['id'] ) ); ?>" value="<?php echo esc_attr( $value['default'] ); ?>"/>
										</div>
									</div>

									<?php
							break;
						case 'separator':
							?>
										<hr/>
									<?php
							break;
						case 'heading':
							?>
										<div class="astra-widget-field astra-widget-field-heading">
											<label><?php echo esc_html( $value['name'] ); ?></label>
										</div>
									<?php
							break;
						case 'email':
							?>
										<p>
											<label for="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"><?php echo $value['name']; ?></label>
											<input class="widefat" type="email" id="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>" name="<?php echo esc_attr( $self->get_field_name( $value['id'] ) ); ?>" value="<?php echo esc_attr( $value['default'] ); ?>"/>
										</p>
									<?php
							break;

						case 'textarea':
							?>
										<p>
											<label for="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"><?php echo $value['name']; ?></label>
											<textarea class="widefat" id="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>" name="<?php echo esc_attr( $self->get_field_name( $value['id'] ) ); ?>" rows="5"><?php echo esc_attr( $value['default'] ); ?></textarea>
										</p>
									<?php
							break;

						case 'number':
							?>
										<p class="<?php echo $class; ?> <?php echo isset( $value['unit'] ) ? 'astra-widgets-number-unit' : ''; ?>">
											<label for="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>"><?php echo $value['name']; ?></label>
											<input class="widefat" type="number" id="<?php echo esc_attr( $self->get_field_id( $value['id'] ) ); ?>" name="<?php echo esc_attr( $self->get_field_name( $value['id'] ) ); ?>" value="<?php echo esc_attr( $value['default'] ); ?>"/><span class="astra-widgets-unit"> <?php echo ( isset( $value['unit'] ) ) ? $value['unit'] : ''; ?> </span>
										</p>
									<?php
							break;

						case 'separator':
							?>
								<hr />
							<?php
							break;
					}
				}
			}
		}

		/**
		 * Generate repeatable fields.
		 *
		 * @param  object $self   Widget object.
		 * @param  array  $fields  Fields array.
		 * @param  array  $value   Default value.
		 * @return void
		 */
		function generate_repeater_fields( $self, $fields, $value ) {
			$instances = $self->get_settings();

			if ( array_key_exists( $self->number, $instances ) ) {
				$instance = $instances[ $self->number ];

				if ( array_key_exists( $value['id'], $instance ) ) {
					$stored           = $instance[ $value['id'] ];
					$repeater_options = $value['options'];
					$repeater_fields  = array();
					foreach ( $repeater_options as $index => $field ) {
						foreach ( $stored as $stored_index => $stored_field ) {
							foreach ( $stored_field as $stored_field_id => $stored_field_value ) {
								if ( $stored_field_id === $field['id'] ) {
									$field['default']                   = $stored_field_value;
									$repeater_fields[ $stored_index ][] = $field;
								}
							}
						}
					}

					// Generate field.
					foreach ( $repeater_fields as $index => $fields ) {
						?>
						<div class="astra-repeater-field">
							<div class="actions">
								<span class="index"><?php echo $index; ?></span>
								<span class="dashicons dashicons-move"></span>
								<span class="title"></span>
								<span class="dashicons dashicons-admin-page clone"></span>
								<span class="dashicons dashicons-trash remove"></span>
								<span class="dashicons toggle-arrow"></span>
							</div>
							<div class="markukp">
								<?php $this->generate( $self, $fields, $value['id'] ); ?>
							</div>
						</div>
						<?php
					}
				}
			}
		}

	}

	/**
	 * Initialize class object with 'get_instance()' method
	 */
	Astra_Widgets_Helper::get_instance();

endif;

/**
 * Generate Widget Fields
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'astra_generate_widget_fields' ) ) :

	/**
	 * Wrapper function of `generate()`
	 *
	 * @param  object $self        Widget object.
	 * @param  array  $fields      Fields array.
	 * @param  string $repeater_id Repeater ID.
	 * @return void
	 */
	function astra_generate_widget_fields( $self, $fields = array(), $repeater_id = '' ) {
		Astra_Widgets_Helper::get_instance()->generate( $self, $fields, $repeater_id );
	}
endif;


/**
 * Parse CSS
 */
if ( ! function_exists( 'astra_widgets_parse_css' ) ) {

	/**
	 * Parse CSS
	 *
	 * @param  array  $css_output Array of CSS.
	 * @param  string $min_media  Min Media breakpoint.
	 * @param  string $max_media  Max Media breakpoint.
	 * @return string             Generated CSS.
	 */
	function astra_widgets_parse_css( $css_output = array(), $min_media = '', $max_media = '' ) {

		$parse_css = '';
		if ( is_array( $css_output ) && count( $css_output ) > 0 ) {

			foreach ( $css_output as $selector => $properties ) {

				if ( ! count( $properties ) ) {
					continue; }

				$temp_parse_css   = $selector . '{';
				$properties_added = 0;

				foreach ( $properties as $property => $value ) {

					if ( '' === $value ) {
						continue; }

					$properties_added++;
					$temp_parse_css .= $property . ':' . $value . ';';
				}

				$temp_parse_css .= '}';

				if ( $properties_added > 0 ) {
					$parse_css .= $temp_parse_css;
				}
			}

			if ( '' != $parse_css && ( '' !== $min_media || '' !== $max_media ) ) {

				$media_css       = '@media ';
				$min_media_css   = '';
				$max_media_css   = '';
				$media_separator = '';

				if ( '' !== $min_media ) {
					$min_media_css = '(min-width:' . $min_media . 'px)';
				}
				if ( '' !== $max_media ) {
					$max_media_css = '(max-width:' . $max_media . 'px)';
				}
				if ( '' !== $min_media && '' !== $max_media ) {
					$media_separator = ' and ';
				}

				$media_css .= $min_media_css . $media_separator . $max_media_css . '{' . $parse_css . '}';

				return $media_css;
			}
		}

		return $parse_css;
	}
}


/**
 * Get CSS value
 */
if ( ! function_exists( 'astra_widget_get_css_value' ) ) {

	/**
	 * Get CSS value
	 *
	 * Syntax:
	 *
	 *  astra_widget_get_css_value( VALUE, UNIT );
	 *
	 * E.g.
	 *
	 *  astra_widget_get_css_value( VALUE, 'url' );
	 *  astra_widget_get_css_value( VALUE, 'px' );
	 *  astra_widget_get_css_value( VALUE, 'em' );
	 *
	 * @param  string $value        CSS value.
	 * @param  string $unit         CSS unit.
	 * @param  string $default      CSS default font.
	 * @return mixed               CSS value depends on $unit
	 */
	function astra_widget_get_css_value( $value = '', $unit = 'px', $default = '' ) {

		if ( '' == $value && '' == $default ) {
			return $value;
		}

		$css_val = '';

		switch ( $unit ) {

			case 'px':
			case '%':
						$value   = ( '' != $value ) ? $value : $default;
						$css_val = esc_attr( $value ) . $unit;
				break;

			case 'url':
						$css_val = $unit . '(' . esc_url( $value ) . ')';
				break;

			default:
				$value = ( '' != $value ) ? $value : $default;
				if ( '' != $value ) {
					$css_val = esc_attr( $value ) . $unit;
				}
		}

		return $css_val;
	}
}
