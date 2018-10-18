<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying Author page
 */

$us_layout = US_Layout::instance();

get_header();

// Output Title Bar
us_load_template( 'templates/titlebar' );

// Get grid params from Theme Options
$template_vars = array(
	'items_layout' => us_get_option( 'archive_layout', 'blog_classic' ),
	'type' => us_get_option( 'archive_type', 'grid' ),
	'columns' => us_get_option( 'archive_cols', 1 ),
	'img_size' => us_get_option( 'archive_img_size', 'default' ),
	'items_gap' => us_get_option( 'archive_items_gap', 5 ) . 'rem',
	'pagination' => us_get_option( 'archive_pagination', 'regular' ),
);

// Get user params
$curauth = ( get_query_var( 'author_name' ) ) ? get_user_by( 'slug', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );
$author_avatar = get_avatar( $curauth->ID );
global $wpdb;
$author_comments_count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM " . $wpdb->comments . " WHERE comment_approved = 1 AND user_id = %s", $curauth->ID ) );

?>
<div class="l-main">
	<div class="l-main-h i-cf">

		<main class="l-content"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
			<section class="l-section<?php echo ( us_get_option( 'row_height' ) == 'small' ) ? ' height_small' : ''; ?>">
				<div class="l-section-h i-cf">

					<div class="w-author"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemscope itemtype="https://schema.org/Person" itemprop="author"' : ''; ?>>
						<div class="w-author-img">
							<?php echo $author_avatar ?>
						</div>
						<div class="w-author-meta">
							<?php echo sprintf( _n( '%s post', '%s posts', count_user_posts( $curauth->ID ), 'us' ), count_user_posts( $curauth->ID ) ) . ', ' . sprintf( us_translate_n( '%s <span class="screen-reader-text">Comment</span>', '%s <span class="screen-reader-text">Comments</span>', $author_comments_count ), $author_comments_count ) ?>
						</div>
						<div class="w-author-url"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="url"' : ''; ?>>
							<?php if ( get_the_author_meta( 'url' ) ) { ?>
								<a href="<?php echo esc_url( get_the_author_meta( 'url' ) ); ?>" target="_blank" rel="nofollow"><?php echo esc_url( get_the_author_meta( 'url' ) ); ?></a>
							<?php } ?>
						</div>
						<div class="w-author-desc"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="description"' : ''; ?>>
							<?php the_author_meta( 'description' ) ?>
						</div>
					</div>

					<?php do_action( 'us_before_archive' ) ?>

					<?php us_load_template( 'templates/us_grid/listing', $template_vars ) ?>

					<?php do_action( 'us_after_archive' ) ?>

				</div>
			</section>
		</main>

		<?php us_load_template( 'templates/sidebar' ) ?>

	</div>
</div>

<?php get_footer() ?>
