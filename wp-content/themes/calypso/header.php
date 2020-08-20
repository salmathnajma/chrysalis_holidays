<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package calypso
 */
?>

<?php
$wrapper_div_classes = 'wrapper ';
if ( is_single() ) {
	$wrapper_div_classes .= join( ' ', get_post_class() );
}
$layout  = get_theme_mod( 'ca_header_layout', 'default' ) ;
$disabled_frontpage   = get_theme_mod( 'disable_frontpage_sections', false );
$wrapper_div_classes .=
	(
		( is_front_page() && ! is_page_template() && ! is_home() && false === (bool) $disabled_frontpage ) ||
		( class_exists( 'WooCommerce' ) && ( is_product() || is_product_category() ) ) ||
		( is_archive() && ( class_exists( 'WooCommerce' ) && ! is_shop() ) )
	) ? '' : ' ' . $layout . ' ';

$header_class = ' site-header header ';
/*
$hide_top_bar = get_theme_mod( 'ca_top_bar_hide', true );
if ( (bool) $hide_top_bar === false ) {
	$header_class .= ' ca-with-topbar ';
}
*/


?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="mainpage" class="<?php echo esc_attr( $wrapper_div_classes ); ?>">
	<header id="mainhead" class="<?php echo esc_attr( $header_class ); ?>">
		<?php do_action( 'ca_do_header' ); ?>
	</header><!-- #masthead -->
