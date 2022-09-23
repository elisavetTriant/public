<?php

/**
 * The template for displaying single events.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty Child
 * @since Twenty Twenty 1.0
 */

get_header();
?>

<main id="site-content">

	<?php

	if (have_posts()) {

		while (have_posts()) {
			the_post();

	?>
			<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

				<?php

				get_template_part('template-parts/entry-header');

				if (!is_search()) {
					get_template_part('template-parts/featured-image');
				}

				?>

				<div class="post-inner <?php echo is_page_template('templates/template-full-width.php') ? '' : 'thin'; ?> ">

					<div class="entry-content">

						<?php
						if (is_search() || !is_singular() && 'summary' === get_theme_mod('blog_content', 'full')) {
							the_excerpt();
						} else {
							the_content(__('Continue reading', 'twentytwenty'));
						}
						?>

					</div><!-- .entry-content -->

				</div><!-- .post-inner -->

				<?php if (get_field('location_latitude') && get_field('location_longitude')) : ?>
					<div class="section-inner">
						<h3>Location of <?php the_title(); ?> Event</h3>
						<!-- example usage on custom post-type / single point of interest marker -->
						<div class="acf-map leaflet-map" data-id=<?php the_ID(); ?> data-template=<?php echo get_post_type() ?>></div>
					</div>
				<?php endif; ?>

				<div class="section-inner">
					<?php
					wp_link_pages(
						array(
							'before'      => '<nav class="post-nav-links bg-light-background" aria-label="' . esc_attr__('Page', 'twentytwenty') . '"><span class="label">' . __('Pages:', 'twentytwenty') . '</span>',
							'after'       => '</nav>',
							'link_before' => '<span class="page-number">',
							'link_after'  => '</span>',
						)
					);

					edit_post_link();

					// Single bottom post meta.
					twentytwenty_the_post_meta(get_the_ID(), 'single-bottom');

					if (post_type_supports(get_post_type(get_the_ID()), 'author') && is_single()) {

						get_template_part('template-parts/entry-author-bio');
					}
					?>

				</div><!-- .section-inner -->

				<?php

				if (is_single()) {

					get_template_part('template-parts/navigation');
				}

				/*
 * Output comments wrapper if it's a post, or if comments are open,
 * or if there's a comment number – and check for password.
 */
				if ((is_single() || is_page()) && (comments_open() || get_comments_number()) && !post_password_required()) {
				?>

					<div class="comments-wrapper section-inner">

						<?php comments_template(); ?>

					</div><!-- .comments-wrapper -->

				<?php
				}
				?>

			</article><!-- .post -->
	<?php




		}
	}

	?>

</main><!-- #site-content -->

<?php get_template_part('template-parts/footer-menus-widgets'); ?>

<?php get_footer(); ?>