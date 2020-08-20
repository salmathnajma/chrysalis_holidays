<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package calypso
 */
?>

</div><!-- #content -->

<footer id="" class="footer  footer-big site-footer ">
    <div class="footer-content footer-background">
        <?php do_action( 'ca_do_footer' ); ?>

        <div class="site-info mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 text-left  copy-right">
                        <span class="">@2020chrysalisholidays.All Rights Reserved</span>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 copy-right text-right">
                        <a href="<?php echo esc_url( __( 'https://www.conditionsapply.net/', 'calypso' ) ); ?>">
                            <?php
									/* translators: %s: CMS name, i.e. WordPress. */
									printf( esc_html__( 'Powered by %s', 'calypso' ), '*conditionsapply' );
									?>
                        </a>
                    </div>
                </div><!-- .site-info -->
            </div>
        </div>
    </div>
</footer><!-- #colophon -->
<!-- #page -->

<?php wp_footer(); ?>

</body>

</html>