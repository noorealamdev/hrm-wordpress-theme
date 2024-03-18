<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Hrm
 */

?>

</div> <!--content-area-->

<?php if ( true == get_theme_mod( 'enable_footer', 'on' ) ) : ?>
<!-- Footer Section Start -->
<footer class="footer-section text-center fix">
    <div class="container">
        <p class="copyright"><?php echo get_theme_mod( 'copyright_text' ); ?></p>
    </div>
</footer>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
