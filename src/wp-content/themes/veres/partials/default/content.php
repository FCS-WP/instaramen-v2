<?php
/**
 * Default post entry layout
 *
 * @package Veres WordPress theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$title_tag = 'h2';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( ['post_item'] ); ?>>
    <div class="post_item-inner">
        <?php
        if(has_post_thumbnail()):
            ?>
            <div class="post_item-thumbnail">
                <a href="<?php the_permalink(); ?>" class="post_item-thumbnail-link">
                    <figure class="post_item-thumbnail-figure figure__object_fit">
                        <?php the_post_thumbnail('full'); ?>
                    </figure>
                </a>
            </div>
        <?php
        endif;
        ?>
        <div class="post_item-content">
            <div class="entry-meta">
                <?php

                if(is_sticky() && !is_paged()){
                    echo sprintf('<span class="sticky-post">%s</span>', esc_html__('Featured', 'veres'));
                }

                veres_entry_meta_item_category_list('<div class="post__terms entry-meta__item">', '</div>');

                ?>
            </div>
            <?php
            echo sprintf(
                '<header class="post_item-content-header"><%1$s class="post_item-content-title"><a href="%2$s" rel="bookmark">%3$s</a></%1$s></header>',
                esc_attr($title_tag),
                esc_url(get_the_permalink()),
                get_the_title()
            );
            ?>
            <div class="entry-meta">
                <?php

                echo sprintf(
                    '<span class="post__date entry-meta__item"><time datetime="%1$s" title="%1$s">%2$s</time></span>',
                    esc_attr( get_the_date( 'c' ) ),
                    esc_html( get_the_date('F j, Y') )
                );

                echo sprintf(
                    '<span class="post__author entry-meta__item"><a href="%1$s" class="posted-by__author" rel="author">%3$s %2$s</a></span>',
                    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                    esc_html( get_the_author() ),
                    esc_html__('by ', 'veres')
                );

                ?>
            </div>
            <div class="entry-excerpt">
                <?php the_excerpt(); ?>
            </div>
            <div class="entry-more">
                <a href="<?php the_permalink(); ?>" class="button button-readmore"><?php echo get_post_type() === 'post' ? esc_html__('Read more', 'veres') : esc_html__('View more', 'veres'); ?></a>
            </div>
        </div>
    </div>
</article>