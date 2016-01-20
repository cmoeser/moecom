<?php
/**
 * @package WordPress
 * @subpackage Chip
 */

get_header(); ?>
<div class="article-body-wrapper blog"> 
<!--Begin Blog content-->
    <div class="leftColumn">
        <div class=" blogNav">
            <?php get_sidebar(); ?>
        </div>
    </div>
    <div class="bodyContainer blogBody border-left">
        <div  role="main">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <div class="post" id="post-"
                <?php the_ID(); ?>">
                <h3>
                    <?php the_title(); ?>
                </h3>
                <div class="entry">
                    <?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

                    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

                </div>
            </div>
            <?php endwhile; endif; ?>
            <?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
        </div>

    </div>
</div>
<?php get_footer(); ?>
