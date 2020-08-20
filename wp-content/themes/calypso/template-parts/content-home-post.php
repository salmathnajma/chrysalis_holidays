<?php
/**
 * Template part for displaying post content
 *
 * @package calypso
 */

global $posts_count, $columns_class, $class, $allowed_html;


$blog_layout = new Ca_Blog_Layout();

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( "ca-post-item $columns_class $class" ); ?>>
    <div class="card card-plain card-blog">
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="card-image">
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <?php the_post_thumbnail( 'ca-post-thumb' ); ?>
            </a>
        </div>
    <?php endif; ?>
        <div class="content">
            <?php echo $blog_layout->render_post_body(); ?>
        </div>
    </div>
</article>