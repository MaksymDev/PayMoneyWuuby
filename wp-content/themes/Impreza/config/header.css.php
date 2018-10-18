<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Generates and outputs header generated stylesheets
 *
 * @action Before the template: us_before_template:config/header.css
 * @action After the template: us_after_template:config/header.css
 */
global $us_template_directory;

$tablets_breakpoint = us_get_header_option( 'breakpoint', 'tablets' ) ? intval( us_get_header_option( 'breakpoint', 'tablets' ) ) : 900;
$mobiles_breakpoint = us_get_header_option( 'breakpoint', 'mobiles' ) ? intval( us_get_header_option( 'breakpoint', 'mobiles' ) ) : 600;

/* Header styles as variables */
$header_hor_styles = file_get_contents( $us_template_directory . '/css/base/header-hor.css' );
$header_ver_styles = file_get_contents( $us_template_directory . '/css/base/header-ver.css' );
?>

/* =============================================== */
/* ================ Default state ================ */
/* =============================================== */

@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {

	.hidden_for_default { display: none !important; }

<?php if ( ! us_get_header_option( 'top_show' ) ) { ?>
	.l-subheader.at_top { display: none; }
<?php }
if ( ! us_get_header_option( 'bottom_show' ) ) { ?>
	.l-subheader.at_bottom { display: none; }
<?php }
if ( us_get_header_option( 'bg_img' ) AND $bg_image = usof_get_image_src( us_get_header_option( 'bg_img' ) ) ) { ?>
	.l-subheader.at_middle {
		background-image: url(<?php echo $bg_image[0] ?>);
		background-attachment: <?php echo ( us_get_header_option( 'bg_img_attachment' ) ) ? 'scroll' : 'fixed'; ?>;
		background-position: <?php echo us_get_header_option( 'bg_img_position' ) ?>;
		background-repeat: <?php echo us_get_header_option( 'bg_img_repeat' ) ?>;
		background-size: <?php echo us_get_header_option( 'bg_img_size' ) ?>;
	}
<?php }

// Horizontal header
if ( us_get_header_option( 'orientation' ) == 'hor' ) {
	echo $header_hor_styles;
?>
	.l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_height' ) ?>px;
		height: <?php echo us_get_header_option( 'top_height' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_sticky_height' ) ?>px;
		height: <?php echo us_get_header_option( 'top_sticky_height' ) ?>px;
	<?php if ( us_get_header_option( 'top_sticky_height' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_height' ) ?>px;
		height: <?php echo us_get_header_option( 'middle_height' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_sticky_height' ) ?>px;
		height: <?php echo us_get_header_option( 'middle_sticky_height' ) ?>px;
	<?php if ( us_get_header_option( 'middle_sticky_height' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_height' ) ?>px;
		height: <?php echo us_get_header_option( 'bottom_height' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_sticky_height' ) ?>px;
		height: <?php echo us_get_header_option( 'bottom_sticky_height' ) ?>px;
	<?php if ( us_get_header_option( 'bottom_sticky_height' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	/* Calculate top padding for content overlapped by sticky header */
	.l-header.pos_fixed ~ .l-section,
	.sidebar_left .l-header.pos_fixed + .l-main,
	.sidebar_right .l-header.pos_fixed + .l-main,
	.sidebar_none .l-header.pos_fixed + .l-main .l-section:first-child,
	.sidebar_none .l-header.pos_fixed + .l-main .l-section-gap:nth-child(2),
	.header_inpos_below .l-header.pos_fixed ~ .l-main .l-section:nth-child(2),
	.l-header.pos_static.bg_transparent ~ .l-section,
	.sidebar_left .l-header.pos_static.bg_transparent + .l-main,
	.sidebar_right .l-header.pos_static.bg_transparent + .l-main,
	.sidebar_none .l-header.pos_static.bg_transparent + .l-main .l-section:first-child {
	<?php
	$header_height = us_get_header_option( 'top_show' ) ? intval( us_get_header_option( 'top_height' ) ) : 0;
	$header_height += intval( us_get_header_option( 'middle_height' ) );
	$header_height += us_get_header_option( 'bottom_show' ) ? intval( us_get_header_option( 'bottom_height' ) ) : 0;
	?>
		padding-top: <?php echo $header_height ?>px;
	}
	.l-header.pos_static.bg_solid + .l-main .l-section.preview_trendy .w-blogpost-preview {
		top: -<?php echo $header_height ?>px;
	}
	.header_inpos_bottom .l-header.pos_fixed ~ .l-main .l-section:first-child {
		padding-bottom: <?php echo $header_height ?>px;
	}

	/* Fix vertical centering of first section when header is transparent */
	.l-header.bg_transparent ~ .l-main .l-section.valign_center:first-child > .l-section-h {
		top: -<?php echo $header_height/2 ?>px;
	}
	.header_inpos_bottom .l-header.pos_fixed.bg_transparent ~ .l-main .l-section.valign_center:first-child > .l-section-h {
		top: <?php echo $header_height/2 ?>px;
	}

	/* Calculate height of "Full Screen" rows */
	.l-header.pos_fixed ~ .l-main .l-section.height_full:not(:first-child) {
	<?php
	$header_sticky_height = us_get_header_option( 'top_show' ) ? intval( us_get_header_option( 'top_sticky_height' ) ) : 0;
	$header_sticky_height += intval( us_get_header_option( 'middle_sticky_height' ) );
	$header_sticky_height += us_get_header_option( 'bottom_show' ) ? intval( us_get_header_option( 'bottom_sticky_height' ) ) : 0;
	?>
		min-height: calc(100vh - <?php echo $header_sticky_height ?>px);
	}
	.admin-bar .l-header.pos_fixed ~ .l-main .l-section.height_full:not(:first-child) {
		min-height: calc(100vh - <?php echo $header_sticky_height + 32 ?>px);
	}
	.l-header.pos_static.bg_solid ~ .l-main .l-section.height_full:first-child {
		min-height: calc(100vh - <?php echo $header_height ?>px);
	}

	/* Calculate position of "Sticky" rows */
	.l-header.pos_fixed ~ .l-main .l-section.sticky {
		top: <?php echo $header_sticky_height ?>px;
	}
	.admin-bar .l-header.pos_fixed ~ .l-main .l-section.sticky {
		top: <?php echo $header_sticky_height + 32 ?>px;
	}
	.l-header.pos_fixed.sticky + .l-section.type_sticky,
	.sidebar_none .l-header.pos_fixed.sticky + .l-main .l-section.type_sticky:first-child {
		padding-top: <?php echo $header_sticky_height ?>px;
	}
	
	/* Initial header position BOTTOM & BELOW */
	.header_inpos_below .l-header.pos_fixed:not(.sticky) {
		position: absolute;
		top: 100%;
	}
	.header_inpos_bottom .l-header.pos_fixed:not(.sticky) {
		position: absolute;
		bottom: 0;
	}
	.header_inpos_below .l-header.pos_fixed ~ .l-main .l-section:first-child,
	.header_inpos_bottom .l-header.pos_fixed ~ .l-main .l-section:first-child {
		padding-top: 0 !important;
	}
	.header_inpos_below .l-header.pos_fixed ~ .l-main .l-section.height_full:nth-child(2) {
		min-height: 100vh;
	}
	.admin-bar.header_inpos_below .l-header.pos_fixed ~ .l-main .l-section.height_full:nth-child(2) {
		min-height: calc(100vh - 32px); /* WP admin bar height */
	}
	.header_inpos_bottom .l-header.pos_fixed:not(.sticky) .w-cart-dropdown,
	.header_inpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .w-nav-list.level_2 {
		bottom: 100%;
		transform-origin: 0 100%;
	}
	.header_inpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_mobile.m_layout_dropdown .w-nav-list.level_1 {
		top: auto;
		bottom: 100%;
		box-shadow: 0 -3px 3px rgba(0,0,0,0.1);
	}	
	.header_inpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .w-nav-list.level_3,
	.header_inpos_bottom .l-header.pos_fixed:not(.sticky) .w-nav.type_desktop .w-nav-list.level_4 {
		top: auto;
		bottom: 0;
		transform-origin: 0 100%;
	}
<?php } else {
// Vertical header
	echo $header_ver_styles;
?>
	.l-body {
		padding-left: <?php echo us_get_header_option( 'width' ) ?>px;
		position: relative;
	}
	.l-body.rtl {
		padding-left: 0;
		padding-right: <?php echo us_get_header_option( 'width' ) ?>px;
	}
	.l-header,
	.l-header .w-cart-notification,
	.w-nav.type_mobile.m_layout_panel .w-nav-list.level_1 {
		width: <?php echo us_get_header_option( 'width' ) ?>px;
	}
	.l-body.rtl .l-header {
		left: auto;
		right: 0;
	}
	.l-body:not(.rtl) .l-navigation.inv_true .to_next,
	.l-body:not(.rtl) .l-navigation.inv_false .to_prev {
		left: calc(<?php echo us_get_header_option( 'width' ) ?>px - 13.5rem);
	}
	.l-body:not(.rtl) .w-toplink.pos_left,
	.l-body:not(.rtl) .l-section.sticky,
	.no-touch .l-body:not(.rtl) .l-navigation.inv_true .to_next:hover,
	.no-touch .l-body:not(.rtl) .l-navigation.inv_false .to_prev:hover {
		left: <?php echo us_get_header_option( 'width' ) ?>px;
	}
	.l-body.rtl .l-navigation.inv_true .to_prev,
	.l-body.rtl .l-navigation.inv_false .to_next {
		right: calc(<?php echo us_get_header_option( 'width' ) ?>px - 13.5rem);
	}
	.l-body.rtl .w-toplink.pos_right,
	.l-body.rtl .l-section.sticky,
	.no-touch .l-body.rtl .l-navigation.inv_true .to_prev:hover,
	.no-touch .l-body.rtl .l-navigation.inv_false .to_next:hover {
		right: <?php echo us_get_header_option( 'width' ) ?>px;
	}
	.w-nav.type_desktop [class*="columns"] .w-nav-list.level_2 {
		width: calc(100vw - <?php echo us_get_header_option( 'width' ) ?>px);
		max-width: 980px;
	}
	.rtl .w-nav.type_desktop .w-nav-list.level_2 {
		left: auto;
		right: 100%;
		}
<?php
if ( us_get_header_option( 'elm_align' ) == 'left' ) { ?>
	.l-subheader-cell {
		text-align: left;
		align-items: flex-start;
	}
<?php }
if ( us_get_header_option( 'elm_align' ) == 'right' ) { ?>
	.l-subheader-cell {
		text-align: right;
		align-items: flex-end;
	}
<?php }
if ( us_get_header_option( 'elm_valign' ) == 'middle' ) { ?>
	.l-subheader.at_middle {
		display: flex;
		align-items: center;
	}
<?php }
if ( us_get_header_option( 'elm_valign' ) == 'bottom' ) { ?>
	.l-subheader.at_middle {
		display: flex;
		align-items: flex-end;
	}
<?php }
}
?>
}



/* =============================================== */
/* ================ Tablets state ================ */
/* =============================================== */

@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {

	.hidden_for_tablets { display: none !important; }

<?php if ( ! us_get_header_option( 'top_show', 'tablets' ) ) { ?>
	.l-subheader.at_top { display: none; }
<?php }
if ( ! us_get_header_option( 'bottom_show', 'tablets' ) ) { ?>
	.l-subheader.at_bottom { display: none; }
<?php }
if ( us_get_header_option( 'bg_img', 'tablets' ) AND $bg_image = usof_get_image_src( us_get_header_option( 'bg_img', 'tablets' ) ) ) { ?>
	.l-subheader.at_middle {
		background-image: url(<?php echo $bg_image[0] ?>);
		background-attachment: <?php echo ( us_get_header_option( 'bg_img_attachment', 'tablets' ) ) ? 'scroll' : 'fixed'; ?>;
		background-position: <?php echo us_get_header_option( 'bg_img_position', 'tablets' ) ?>;
		background-repeat: <?php echo us_get_header_option( 'bg_img_repeat', 'tablets' ) ?>;
		background-size: <?php echo us_get_header_option( 'bg_img_size', 'tablets' ) ?>;
	}
<?php }

// Horizontal header on Tablets
if ( us_get_header_option( 'orientation', 'tablets' ) == 'hor' ) {
	echo $header_hor_styles;
?>
	.l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_height', 'tablets' ) ?>px;
		height: <?php echo us_get_header_option( 'top_height', 'tablets' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_sticky_height', 'tablets' ) ?>px;
		height: <?php echo us_get_header_option( 'top_sticky_height', 'tablets' ) ?>px;
	<?php if ( us_get_header_option( 'top_sticky_height', 'tablets' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_height', 'tablets' ) ?>px;
		height: <?php echo us_get_header_option( 'middle_height', 'tablets' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_sticky_height', 'tablets' ) ?>px;
		height: <?php echo us_get_header_option( 'middle_sticky_height', 'tablets' ) ?>px;
	<?php if ( us_get_header_option( 'middle_sticky_height', 'tablets' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_height', 'tablets' ) ?>px;
		height: <?php echo us_get_header_option( 'bottom_height', 'tablets' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_sticky_height', 'tablets' ) ?>px;
		height: <?php echo us_get_header_option( 'bottom_sticky_height', 'tablets' ) ?>px;
	<?php if ( us_get_header_option( 'bottom_sticky_height', 'tablets' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	/* Calculate top padding for content overlapped by sticky header */
	.l-header.pos_fixed ~ .l-section,
	.sidebar_left .l-header.pos_fixed + .l-main,
	.sidebar_right .l-header.pos_fixed + .l-main,
	.sidebar_none .l-header.pos_fixed + .l-main .l-section:first-child,
	.sidebar_none .l-header.pos_fixed + .l-main .l-section-gap:nth-child(2),
	.l-header.pos_static.bg_transparent ~ .l-section,
	.sidebar_left .l-header.pos_static.bg_transparent + .l-main,
	.sidebar_right .l-header.pos_static.bg_transparent + .l-main,
	.sidebar_none .l-header.pos_static.bg_transparent + .l-main .l-section:first-child {
	<?php
	$header_height = us_get_header_option( 'top_show', 'tablets' ) ? intval( us_get_header_option( 'top_height', 'tablets' ) ) : 0;
	$header_height += intval( us_get_header_option( 'middle_height', 'tablets' ) );
	$header_height += us_get_header_option( 'bottom_show', 'tablets' ) ? intval( us_get_header_option( 'bottom_height', 'tablets' ) ) : 0;
	?>
		padding-top: <?php echo $header_height ?>px;
	}
	.l-header.pos_static.bg_solid + .l-main .l-section.preview_trendy .w-blogpost-preview {
		top: -<?php echo $header_height ?>px;
	}

	/* Calculate position of "Sticky" rows */
	.l-header.pos_fixed ~ .l-main .l-section.sticky {
	<?php
	$header_sticky_height = us_get_header_option( 'top_show', 'tablets' ) ? intval( us_get_header_option( 'top_sticky_height', 'tablets' ) ) : 0;
	$header_sticky_height += intval( us_get_header_option( 'middle_sticky_height', 'tablets' ) );
	$header_sticky_height += us_get_header_option( 'bottom_show', 'tablets' ) ? intval( us_get_header_option( 'bottom_sticky_height', 'tablets' ) ) : 0;
	?>
		top: <?php echo $header_sticky_height ?>px;
	}
	.l-header.pos_fixed.sticky + .l-section.type_sticky,
	.sidebar_none .l-header.pos_fixed.sticky + .l-main .l-section.type_sticky:first-child {
		padding-top: <?php echo $header_sticky_height ?>px;
	}
<?php } else {
// Vertical header on Mobiles
	echo $header_ver_styles;
?>
	.l-header,
	.l-header .w-cart-notification,
	.w-nav.type_mobile.m_layout_panel .w-nav-list.level_1 {
		width: <?php echo us_get_header_option( 'width', 'tablets' ) ?>px;
	}
	.w-search.layout_simple,
	.w-search.layout_modern.active {
		width: <?php echo us_get_header_option( 'width', 'tablets' ) - 40 ?>px;
	}

	/* Slided vertical header */
	.w-header-show,
	.w-header-overlay {
		display: block;
	}
	.l-header {
		bottom: 0;
		overflow-y: auto;
		-webkit-overflow-scrolling: touch;
		box-shadow: none;
		transition: transform 0.3s;
		transform: translate3d(-100%,0,0);
	}
	.header-show .l-header {
		transform: translate3d(0,0,0);
	}
<?php if ( us_get_header_option( 'elm_align', 'tablets' ) == 'left' ) { ?>
	.l-subheader-cell {
		text-align: left;
		align-items: flex-start;
	}
<?php }
if ( us_get_header_option( 'elm_align', 'tablets' ) == 'right' ) { ?>
	.l-subheader-cell {
		text-align: right;
		align-items: flex-end;
	}
<?php }
}
?>
}



/* =============================================== */
/* ================ Mobiles state ================ */
/* =============================================== */

@media (max-width: <?php echo $mobiles_breakpoint ?>px) {

	.hidden_for_mobiles { display: none !important; }

<?php if ( ! us_get_header_option( 'top_show', 'mobiles' ) ) { ?>
	.l-subheader.at_top { display: none; }
<?php }
if ( ! us_get_header_option( 'bottom_show', 'mobiles' ) ) { ?>
	.l-subheader.at_bottom { display: none; }
<?php }
if ( us_get_header_option( 'bg_img', 'mobiles' ) AND $bg_image = usof_get_image_src( us_get_header_option( 'bg_img', 'mobiles' ) ) ) { ?>
	.l-subheader.at_middle {
		background-image: url(<?php echo $bg_image[0] ?>);
		background-attachment: <?php echo ( us_get_header_option( 'bg_img_attachment', 'mobiles' ) ) ? 'scroll' : 'fixed'; ?>;
		background-position: <?php echo us_get_header_option( 'bg_img_position', 'mobiles' ) ?>;
		background-repeat: <?php echo us_get_header_option( 'bg_img_repeat', 'mobiles' ) ?>;
		background-size: <?php echo us_get_header_option( 'bg_img_size', 'mobiles' ) ?>;
	}
<?php }

// Horizontal header on Mobiles
if ( us_get_header_option( 'orientation', 'mobiles' ) == 'hor' ) {
	echo $header_hor_styles;
?>
	.l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_height', 'mobiles' ) ?>px;
		height: <?php echo us_get_header_option( 'top_height', 'mobiles' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_top {
		line-height: <?php echo us_get_header_option( 'top_sticky_height', 'mobiles' ) ?>px;
		height: <?php echo us_get_header_option( 'top_sticky_height', 'mobiles' ) ?>px;
	<?php if ( us_get_header_option( 'top_sticky_height', 'mobiles' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_height', 'mobiles' ) ?>px;
		height: <?php echo us_get_header_option( 'middle_height', 'mobiles' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_middle {
		line-height: <?php echo us_get_header_option( 'middle_sticky_height', 'mobiles' ) ?>px;
		height: <?php echo us_get_header_option( 'middle_sticky_height', 'mobiles' ) ?>px;
	<?php if ( us_get_header_option( 'middle_sticky_height', 'mobiles' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	.l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_height', 'mobiles' ) ?>px;
		height: <?php echo us_get_header_option( 'bottom_height', 'mobiles' ) ?>px;
	}
	.l-header.sticky .l-subheader.at_bottom {
		line-height: <?php echo us_get_header_option( 'bottom_sticky_height', 'mobiles' ) ?>px;
		height: <?php echo us_get_header_option( 'bottom_sticky_height', 'mobiles' ) ?>px;
	<?php if ( us_get_header_option( 'bottom_sticky_height', 'mobiles' ) == 0 ): ?>
		overflow: hidden;
	<?php endif; ?>
	}

	/* Calculate top padding for content overlapped by sticky header */
	.l-header.pos_fixed ~ .l-section,
	.sidebar_left .l-header.pos_fixed + .l-main,
	.sidebar_right .l-header.pos_fixed + .l-main,
	.sidebar_none .l-header.pos_fixed + .l-main .l-section:first-child,
	.sidebar_none .l-header.pos_fixed + .l-main .l-section-gap:nth-child(2),
	.l-header.pos_static.bg_transparent ~ .l-section,
	.sidebar_left .l-header.pos_static.bg_transparent + .l-main,
	.sidebar_right .l-header.pos_static.bg_transparent + .l-main,
	.sidebar_none .l-header.pos_static.bg_transparent + .l-main .l-section:first-child {
	<?php
	$header_height = us_get_header_option( 'top_show', 'mobiles' ) ? intval( us_get_header_option( 'top_height', 'mobiles' ) ) : 0;
	$header_height += intval( us_get_header_option( 'middle_height', 'mobiles' ) );
	$header_height += us_get_header_option( 'bottom_show', 'mobiles' ) ? intval( us_get_header_option( 'bottom_height', 'mobiles' ) ) : 0;
	?>
		padding-top: <?php echo $header_height ?>px;
	}
	.l-header.pos_static.bg_solid + .l-main .l-section.preview_trendy .w-blogpost-preview {
		top: -<?php echo $header_height ?>px;
	}

	/* Calculate position of "Sticky" rows */
	.l-header.pos_fixed ~ .l-main .l-section.sticky {
	<?php
	$header_sticky_height = us_get_header_option( 'top_show', 'mobiles' ) ? intval( us_get_header_option( 'top_sticky_height', 'mobiles' ) ) : 0;
	$header_sticky_height += intval( us_get_header_option( 'middle_sticky_height', 'mobiles' ) );
	$header_sticky_height += us_get_header_option( 'bottom_show', 'mobiles' ) ? intval( us_get_header_option( 'bottom_sticky_height', 'mobiles' ) ) : 0;
	?>
		top: <?php echo $header_sticky_height ?>px;
	}
	.l-header.pos_fixed.sticky + .l-section.type_sticky,
	.sidebar_none .l-header.pos_fixed.sticky + .l-main .l-section.type_sticky:first-child {
		padding-top: <?php echo $header_sticky_height ?>px;
	}
<?php } else {
// Vertical header on Mobiles
	echo $header_ver_styles;
?>
	.l-header,
	.l-header .w-cart-notification,
	.w-nav.type_mobile.m_layout_panel .w-nav-list.level_1 {
		width: <?php echo us_get_header_option( 'width', 'mobiles' ) ?>px;
	}
	.w-search.layout_simple,
	.w-search.layout_modern.active {
		width: <?php echo us_get_header_option( 'width', 'mobiles' ) - 40 ?>px;
	}

	/* Slided vertical header */
	.w-header-show,
	.w-header-overlay {
		display: block;
	}
	.l-header {
		bottom: 0;
		overflow-y: auto;
		-webkit-overflow-scrolling: touch;
		box-shadow: none;
		transition: transform 0.3s;
		transform: translate3d(-100%,0,0);
	}
	.header-show .l-header {
		transform: translate3d(0,0,0);
	}
<?php if ( us_get_header_option( 'elm_align', 'mobiles' ) == 'left' ) { ?>
	.l-subheader-cell {
		text-align: left;
		align-items: flex-start;
	}
<?php }
if ( us_get_header_option( 'elm_align', 'mobiles' ) == 'right' ) { ?>
	.l-subheader-cell {
		text-align: right;
		align-items: flex-end;
	}
<?php }
}
?>
}



/* Image */

<?php foreach ( us_get_header_elms_of_a_type( 'image' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> { height: <?php echo $param['height'] ?>px; }
	.l-header.sticky .<?php echo $class ?> { height: <?php echo $param['height_sticky'] ?>px; }
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> { height: <?php echo $param['height_tablets'] ?>px; }
	.l-header.sticky .<?php echo $class ?> { height: <?php echo $param['height_sticky_tablets'] ?>px; }
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> { height: <?php echo $param['height_mobiles'] ?>px; }
	.l-header.sticky .<?php echo $class ?> { height: <?php echo $param['height_sticky_mobiles'] ?>px; }
}
<?php endforeach; ?>



/* Text */

<?php foreach ( us_get_header_elms_of_a_type( 'text' ) as $class => $param ): ?>

<?php if ( $param['font'] != 'body' ) : ?>
.<?php echo $class ?> { <?php echo us_get_font_css( $param['font'] ); ?> }
<?php endif;
if ( is_array( $param['text_style'] ) AND in_array( 'bold', $param['text_style'] ) ): ?>
.<?php echo $class ?> { font-weight: bold; }
<?php endif;
if ( is_array( $param['text_style'] ) AND in_array( 'italic', $param['text_style'] ) ): ?>
.<?php echo $class ?> { font-style: italic; }
<?php endif;
if ( is_array( $param['text_style'] ) AND in_array( 'uppercase', $param['text_style'] ) ): ?>
.<?php echo $class ?> { text-transform: uppercase; }
<?php endif; ?>
	
<?php if ( ! empty( $param['color'] ) ) : ?>
.<?php echo $class ?> .w-text-value { color: <?php echo $param['color'] ?>; }
<?php endif; ?>

<?php if ( ! $param['wrap'] ): ?>
.<?php echo $class ?> { white-space: nowrap; }
<?php endif; ?>

@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> { font-size: <?php echo $param['size'] ?>px; }
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> { font-size: <?php echo $param['size_tablets'] ?>px; }
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> { font-size: <?php echo $param['size_mobiles'] ?>px; }
}
<?php endforeach; ?>



/* Button */

<?php foreach ( us_get_header_elms_of_a_type( 'btn' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.w-btn.<?php echo $class ?> { font-size: <?php echo $param['size'] ?>px; }
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.w-btn.<?php echo $class ?> { font-size: <?php echo $param['size_tablets'] ?>px; }
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.w-btn.<?php echo $class ?> { font-size: <?php echo $param['size_mobiles'] ?>px; }
}
.w-btn.<?php echo $class ?>,
.no-touch .btn_hov_none .w-btn.<?php echo $class ?>:hover {
	border-color: <?php echo $param['color_bg'] ?>;
	color: <?php echo $param['color_text'] ?>;
}
.w-btn.<?php echo $class ?>.style_solid {
	background-color: <?php echo $param['color_bg'] ?>;
}
.no-touch .btn_hov_fade .w-btn.<?php echo $class ?>:before,
.no-touch .btn_hov_slide .w-btn.<?php echo $class ?>:before,
.no-touch .btn_hov_reverse .w-btn.<?php echo $class ?>.style_outlined:hover {
	background-color: <?php echo $param['color_hover_bg'] ?>;
}
.no-touch body:not(.btn_hov_none) .w-btn.<?php echo $class ?>:hover {
	border-color: <?php echo $param['color_hover_bg'] ?>;
	color: <?php echo $param['color_hover_text'] ?>;
}
<?php endforeach; ?>



/* Menu */

<?php foreach ( us_get_header_elms_of_a_type( 'menu' ) as $class => $param ): ?>
.header_hor .<?php echo $class ?>.type_desktop .w-nav-list.level_1 > .menu-item > a {
	padding: 0 <?php echo $param['indents']/2 ?>px;
}
.header_ver .<?php echo $class ?>.type_desktop {
	line-height: <?php echo $param['indents'] ?>px;
}
<?php if ( $param['font'] != 'body' ) : ?>
.<?php echo $class ?> { <?php echo us_get_font_css( $param['font'] ); ?> }
<?php endif;
if ( is_array( $param['text_style'] ) AND in_array( 'bold', $param['text_style'] ) ): ?>
.<?php echo $class ?> { font-weight: bold; }
<?php endif;
if ( is_array( $param['text_style'] ) AND in_array( 'italic', $param['text_style'] ) ): ?>
.<?php echo $class ?> { font-style: italic; }
<?php endif;
if ( is_array( $param['text_style'] ) AND in_array( 'uppercase', $param['text_style'] ) ): ?>
.<?php echo $class ?> { text-transform: uppercase; }
<?php endif; ?>

.<?php echo $class ?>.type_desktop .btn.menu-item.level_1 > .w-nav-anchor {
	margin: <?php echo $param['indents']/4 ?>px;
}
<?php if ( $param['dropdown_arrow'] ): ?>
.<?php echo $class ?>.type_desktop .menu-item-has-children .w-nav-anchor.level_1 > .w-nav-arrow {
	display: inline-block;
}
<?php endif; ?>
.<?php echo $class ?>.type_desktop .w-nav-list > .menu-item.level_1 {
	font-size: <?php echo $param['font_size'] ?>px;
}
.<?php echo $class ?>.type_desktop .w-nav-list > .menu-item:not(.level_1) {
	font-size: <?php echo $param['dropdown_font_size'] ?>px;
}
<?php if ( $param['dropdown_width'] ): ?>
.<?php echo $class ?>.type_desktop {
	position: relative;
}
<?php endif; ?>
.<?php echo $class ?>.type_mobile .w-nav-anchor.level_1 {
	font-size: <?php echo $param['mobile_font_size'] ?>px;
}
.<?php echo $class ?>.type_mobile .w-nav-anchor:not(.level_1) {
	font-size: <?php echo $param['mobile_dropdown_font_size'] ?>px;
}
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> .w-nav-icon {
		font-size: <?php echo $param['mobile_icon_size'] ?>px;
	}
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> .w-nav-icon {
		font-size: <?php echo $param['mobile_icon_size_tablets'] ?>px;
	}
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> .w-nav-icon {
		font-size: <?php echo $param['mobile_icon_size_mobiles'] ?>px;
	}
}
/* Show mobile menu instead of desktop */
@media screen and (max-width: <?php echo $param['mobile_width'] - 1 ?>px) {
	.<?php echo $class ?> .w-nav-list {
		display: none;
	}
	.<?php echo $class ?> .w-nav-control {
		display: block;
	}
}
<?php endforeach; ?>



/* Links Menu */

<?php foreach ( us_get_header_elms_of_a_type( 'additional_menu' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size'] ?>px;
	}
	.header_hor .<?php echo $class ?> .w-menu-list {
		margin: 0 -<?php echo $param['indents']/2 ?>px;
	}
	.header_hor .<?php echo $class ?> .w-menu-item {
		padding: 0 <?php echo $param['indents']/2 ?>px;
	}
	.header_ver .<?php echo $class ?> .w-menu-list {
		line-height: <?php echo $param['indents'] ?>px;
	}
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_tablets'] ?>px;
	}
	.header_hor .<?php echo $class ?> .w-menu-list {
		margin: 0 -<?php echo $param['indents_tablets']/2 ?>px;
	}
	.header_hor .<?php echo $class ?> .w-menu-item {
		padding: 0 <?php echo $param['indents_tablets']/2 ?>px;
	}
	.header_ver .<?php echo $class ?> .w-menu-list {
		line-height: <?php echo $param['indents_tablets'] ?>px;
	}
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_mobiles'] ?>px;
	}
	.header_hor .<?php echo $class ?> .w-menu-list {
		margin: 0 -<?php echo $param['indents_mobiles']/2 ?>px;
	}
	.header_hor .<?php echo $class ?> .w-menu-item {
		padding: 0 <?php echo $param['indents_mobiles']/2 ?>px;
	}
	.header_ver .<?php echo $class ?> .w-menu-list {
		line-height: <?php echo $param['indents_mobiles'] ?>px;
	}
}
<?php endforeach; ?>



/* Search */

<?php foreach ( us_get_header_elms_of_a_type( 'search' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?>.layout_simple {
		max-width: <?php echo $param['width'] ?>px;
	}
	.<?php echo $class ?>.layout_modern.active {
		width: <?php echo $param['width'] ?>px;
	}
	.<?php echo $class ?> .w-search-open,
	.<?php echo $class ?> .w-search-close,
	.<?php echo $class ?> .w-search-form-btn {
		font-size: <?php echo $param['icon_size'] ?>px;
	}
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?>.layout_simple {
		max-width: <?php echo $param['width_tablets'] ?>px;
	}
	.<?php echo $class ?>.layout_modern.active {
		width: <?php echo $param['width_tablets'] ?>px;
	}
	.<?php echo $class ?> .w-search-open,
	.<?php echo $class ?> .w-search-close,
	.<?php echo $class ?> .w-search-form-btn {
		font-size: <?php echo $param['icon_size_tablets'] ?>px;
	}
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> .w-search-open,
	.<?php echo $class ?> .w-search-close,
	.<?php echo $class ?> .w-search-form-btn {
		font-size: <?php echo $param['icon_size_mobiles'] ?>px;
	}
}
<?php endforeach; ?>



/* Socials */

<?php foreach ( us_get_header_elms_of_a_type( 'socials' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size'] ?>px;
	}
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_tablets'] ?>px;
	}
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> {
		font-size: <?php echo $param['size_mobiles'] ?>px;
	}
}
<?php endforeach; ?>



/* Dropdown */

<?php foreach ( us_get_header_elms_of_a_type( 'dropdown' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> .w-dropdown-h {
		font-size: <?php echo $param['size'] ?>px;
	}
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> .w-dropdown-h {
		font-size: <?php echo $param['size_tablets'] ?>px;
	}
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> .w-dropdown-h {
		font-size: <?php echo $param['size_mobiles'] ?>px;
	}
}
<?php endforeach; ?>



/* Cart */

<?php foreach ( us_get_header_elms_of_a_type( 'cart' ) as $class => $param ): ?>
@media (min-width: <?php echo $tablets_breakpoint + 1 ?>px) {
	.<?php echo $class ?> .w-cart-link {
		font-size: <?php echo $param['size'] ?>px;
	}
}
@media (min-width: <?php echo $mobiles_breakpoint + 1 ?>px) and (max-width: <?php echo $tablets_breakpoint ?>px) {
	.<?php echo $class ?> .w-cart-link {
		font-size: <?php echo $param['size_tablets'] ?>px;
	}
}
@media (max-width: <?php echo $mobiles_breakpoint ?>px) {
	.<?php echo $class ?> .w-cart-link {
		font-size: <?php echo $param['size_mobiles'] ?>px;
	}
}
<?php endforeach; ?>



/* Design Options */

<?php echo us_get_header_design_options_css() ?>
