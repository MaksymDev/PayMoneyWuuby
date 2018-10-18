<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

class US_Layout {

	/**
	 * @var US_Layout
	 */
	protected static $instance;

	/**
	 * Singleton pattern: US_Layout::instance()->do_something()
	 *
	 * @return US_Layout
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * @var string Columns type: right, left, none
	 */
	public $sidebar_pos;

	/**
	 * @var string Canvas type: wide / boxed
	 */
	public $canvas_type;

	/**
	 * @var string Default-state header orientation: 'hor' / 'ver'
	 */
	public $header_orientation;

	/**
	 * @var string Default-state header position: 'static' / 'fixed'
	 */
	public $header_pos;

	/**
	 * @var string Default-state header background: 'solid' / 'transparent'
	 */
	public $header_bg;

	/**
	 * @var string Default-state header show: 'always' / 'never'
	 */
	public $header_show;

	protected function __construct() {

		do_action( 'us_layout_before_init', $this );

		if ( WP_DEBUG AND ! ( isset( $GLOBALS['post'] ) OR is_404() OR is_search() OR is_archive() OR ( is_home() AND ! have_posts() ) ) ) {
			wp_die( 'US_Layout can be inited only after the current post is obtained' );
		}

		/**
		 * Define Sidebar appearance from Theme Options
		 */
		// TODO: use "us_get_page_area_value" function instead
		$this->sidebar_pos = 'none';
		$public_cpt = array_keys( us_get_public_cpt() );
		// Defaults
		if ( us_get_option( 'sidebar_id' ) != '' ) {
			$this->sidebar_pos = us_get_option( 'sidebar_pos' );
		}
		// Portfolio Pages
		if ( is_singular( array( 'us_portfolio' ) ) ) {
			if ( us_get_option( 'sidebar_portfolio_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_portfolio_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_portfolio_pos' );
			}
			// Posts and attachments
		} elseif ( is_singular( array( 'post', 'attachment' ) ) ) {
			if ( us_get_option( 'sidebar_post_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_post_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_post_pos' );
			}
			// Search Results Page
		} elseif ( is_search() ) {
			if ( us_get_option( 'sidebar_search_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_search_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_search_pos' );
			}
			// Blog Home Page
		} elseif ( is_home() ) {
			if ( us_get_option( 'sidebar_blog_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_blog_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_blog_pos' );
			}
			// Shop & Products
		} elseif ( function_exists( 'is_woocommerce' ) AND is_woocommerce() ) {
			if ( is_singular() ) {
				if ( us_get_option( 'sidebar_product_id' ) == '' ) {
					$this->sidebar_pos = 'none';
				} elseif ( us_get_option( 'sidebar_product_id' ) != '__defaults__' ) {
					$this->sidebar_pos = us_get_option( 'sidebar_product_pos' );
				}
			} else {
				if ( us_get_option( 'sidebar_shop_id' ) == '' ) {
					$this->sidebar_pos = 'none';
				} elseif ( us_get_option( 'sidebar_shop_id' ) != '__defaults__' ) {
					$this->sidebar_pos = us_get_option( 'sidebar_shop_pos' );
				}
				if ( ! is_search() AND ! is_tax() ) {
					if ( usof_meta( 'us_sidebar', array(), wc_get_page_id( 'shop' ) ) == 'hide' ) {
						$this->sidebar_pos = 'none';
					} elseif ( usof_meta( 'us_sidebar', array(), wc_get_page_id( 'shop' ) ) == 'custom' ) {
						$this->sidebar_pos = usof_meta( 'us_sidebar_pos', array(), wc_get_page_id( 'shop' ) );
					}
				}
			}
			// Archive Pages
		} elseif ( is_archive() ) {
			if ( us_get_option( 'sidebar_archive_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_archive_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_archive_pos' );
			}
			// Supported custom post types
		} elseif ( is_array( $public_cpt ) AND count( $public_cpt ) > 0 AND is_singular( $public_cpt ) ) {
			$post_type = get_post_type();
			if ( us_get_option( 'sidebar_' . $post_type . '_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_' . $post_type . '_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_' . $post_type . '_pos' );
			}
		}
		// Forums archive page
		if ( is_post_type_archive( 'forum' ) ) {
			if ( us_get_option( 'sidebar_forum_id' ) == '' ) {
				$this->sidebar_pos = 'none';
			} elseif ( us_get_option( 'sidebar_forum_id' ) != '__defaults__' ) {
				$this->sidebar_pos = us_get_option( 'sidebar_forum_pos' );
			}
		}

		$this->canvas_type = us_get_option( 'canvas_layout', 'wide' );
		$this->header_orientation = us_get_header_option( 'orientation', 'default', 'hor' );
		$this->header_pos = us_get_header_option( 'sticky', 'default', FALSE ) ? 'fixed' : 'static';
		$this->header_initial_pos = 'top';
		$this->header_bg = us_get_header_option( 'transparent', 'default', FALSE ) ? 'transparent' : 'solid';
		$this->header_shadow = us_get_header_option( 'shadow', 'default', 'thin' );
		$this->header_show = 'always';

		// Some of the options may be overloaded by post's meta settings
		$postID = NULL;
		if ( is_singular() ) {
			$postID = get_the_ID();
		}
		if ( is_404() AND $page_404 = get_post( us_get_option( 'page_404' ) ) ) {
			$postID = $page_404->ID;
		}
		if ( is_singular(
				array_merge(
					array(
						'post',
						'page',
						'us_portfolio',
						'product',
					), $public_cpt
				)
			) OR ( is_404() AND $postID != NULL ) ) {
			if ( usof_meta( 'us_sidebar', array(), $postID ) == 'hide' ) {
				$this->sidebar_pos = 'none';
			} elseif ( usof_meta( 'us_sidebar', array(), $postID ) == 'custom' ) {
				$this->sidebar_pos = usof_meta( 'us_sidebar_pos', array(), $postID );
			}

			global $us_iframe;
			if ( ( isset( $us_iframe ) AND $us_iframe ) OR usof_meta( 'us_header', array(), $postID ) == 'hide' ) {
				$this->header_show = 'never';
				$this->header_orientation = 'none';
			} elseif ( usof_meta( 'us_header', array(), $postID ) == 'custom' AND usof_meta( 'us_header_sticky_pos', array(), $postID ) != '' AND $this->header_orientation == 'hor' AND $this->sidebar_pos == 'none' ) {
				$this->header_initial_pos = usof_meta( 'us_header_sticky_pos', array(), $postID );
			}
		}

		$this->post_id = $postID;

		// Some wrong value may came from various theme options, so filtering it
		if ( ! in_array( $this->sidebar_pos, array( 'right', 'left', 'none' ) ) ) {
			$this->sidebar_pos = 'none';
		}

		if ( $this->header_orientation == 'ver' ) {
			$this->header_pos = 'fixed';
			$this->header_bg = 'solid';
		}

		do_action( 'us_layout_after_init', $this );
	}

	/**
	 * Obtain theme-defined CSS classes for <html> element
	 *
	 * @return string
	 */
	public function html_classes() {
		$classes = '';

		if ( ! us_get_option( 'responsive_layout', TRUE ) ) {
			$classes .= 'no-responsive';
		}

		return $classes;
	}

	/**
	 * Obtain theme-defined CSS classes for <body> element
	 *
	 * @return string
	 */
	public function body_classes() {
		$classes = US_THEMENAME . '_' . US_THEMEVERSION;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'us-header-builder/us-header-builder.php' ) ) {
			$classes .= ' HB';
			if ( defined( 'US_HB_VERSION' ) ) {
				$classes .= '_' . US_HB_VERSION;
			} else {
				$hb_data = @get_plugin_data( ABSPATH . 'wp-content/plugins/us-header-builder/us-header-builder.php' );
				$classes .= '_' . $hb_data['Version'];
			}
		}
		$classes .= ' header_' . $this->header_orientation;
		$classes .= ' header_inpos_' . $this->header_initial_pos;
		$classes .= ' btn_hov_' . us_get_option( 'button_hover' );
		if ( us_get_option( 'links_underline' ) == TRUE ) {
			$classes .= ' links_underline';
		}
		if ( us_get_option( 'rounded_corners' ) !== NULL AND us_get_option( 'rounded_corners' ) == FALSE ) {
			$classes .= ' rounded_none';
		}
		$classes .= ' state_default';

		global $us_iframe;
		if ( ( isset( $us_iframe ) AND $us_iframe ) ) {
			$classes .= ' us_iframe';
		}

		return $classes;
	}

	/**
	 * Obtain CSS classes for .l-canvas
	 *
	 * @return string
	 */
	public function canvas_classes() {

		$classes = 'sidebar_' . $this->sidebar_pos . ' type_' . $this->canvas_type;

		// Language modificator
		if ( defined( 'ICL_LANGUAGE_CODE' ) AND ICL_LANGUAGE_CODE ) {
			$classes .= ' wpml_lang_' . ICL_LANGUAGE_CODE;
		}

		return $classes;
	}

	/**
	 * Obtain CSS classes for .l-header
	 *
	 * @return string
	 */
	public function header_classes() {

		$classes = 'pos_' . $this->header_pos;
		$classes .= ' bg_' . $this->header_bg;
		$classes .= ' shadow_' . $this->header_shadow;
		if ( us_get_option( 'header_invert_logo_pos', FALSE ) ) {
			$classes .= ' logopos_right';
		}

		return $classes;
	}

}
