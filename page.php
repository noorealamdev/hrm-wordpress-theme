<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Hrm
 */

get_header();
?>

<div class="container">
    <!--Page breadcrumb-->
    <div class="d-sm-flex text-center justify-content-between align-items-center mb-4">
        <h3 class="mb-sm-0 mb-1 fs-4"><?php echo the_title(); ?></h3>
    </div>
    <!--End Page breadcrumb-->

    <!--Page content start-->
    <div class="page-content-wrapper">
        <div class="row justify-content-center">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', 'page' );
			endwhile; // End of the loop.
			?>
        </div>
    </div><!--Page content end-->
</div>
<?php
get_footer();
