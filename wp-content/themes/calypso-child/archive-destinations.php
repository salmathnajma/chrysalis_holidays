<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package calypso
 */

get_header();
?>
<section class="destination_header"> <!-- removed class="ca-welcome" -->
<div class="container"> <!-- removed class="position-relative" -->
   <div class="row w-100 destinations_select_box">  <!-- removed class="select_box" -->
		 <div class="col-12 col-lg-5 col-md-12 col-sm-12 ">
			 <h4 class="text-light font-weight-bold">6 Destinations to choose from</h4>
			 <select id="destinations" class="filters-select" value-group="destinations">
				 <option value="*">Choose Your Heavenly Destination</option>
				   <?php 
				   $args = array(
					 'type' => 'destination',
					 'orderby' => 'name',
					 'order' => 'ASC',
					 'taxonomy' => 'destination-category'
				   );
				   $categories = get_categories($args);
				   foreach($categories as $category) { ?>
				   <option value=".<?php echo $category->slug ?>"><?php echo $category->name ?></option>
				   <?php   } ?>
			 </select>
		 </div>
		 <div class="text-center mt-3 col-12 col-lg-2 col-md-12 col-sm-12 ">
			 <h1 style="font-size: 2.5rem;" class="text-light font-weight-bold">Or</h1>
		 </div>
		 <div class="col-12 col-lg-5 col-md-12 col-sm-12">
			 <h4 class="text-light font-weight-bold">Choose Holiday Type</h4>
			 <select id="tags" class="filters-select" value-group="tags">
			 <option value="*">Choose Your Holiday Type</option>
			 <?php 
				   $args = array(
					 'type' => 'destination',
					 'orderby' => 'name',
					 'order' => 'ASC',
					 'taxonomy' => 'destination-tag'
				   );
				   $tags = get_tags($args);
				   foreach($tags as $tag) { ?>
				   <option value=".<?php echo $tag->slug ?>"><?php echo $tag->name  ?></option>
				   <?php   } ?>
			 </select>
		 </div>
	 </div>
 </div>
</section>
<?php
do_action( 'ca_before_page_wrapper' );
?>

<div class=" <?php echo ca_layout(); ?>">
	<main id="main" class="site-main content-area">
		<div class="ca-blog">
			<div class="container ">
			<?php do_action( 'ca_before_single_post_content' ); ?>
				<div class="row grid" id="blog-list">
					<?php //do_action( 'ca_render_blogs'); ?>
					<?php dest_archive();?>
				</div>
			</div>
		</div><!-- .ca-blogs -->
	</main><!-- #main -->
</div><!-- .main -->

<?php get_footer();?>
