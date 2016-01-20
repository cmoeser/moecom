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

        <div role="main">

            <?php if (have_posts()) : ?>

            <?php while (have_posts()) : the_post(); ?>

            <div
                <?php post_class() ?> id="post-<?php the_ID(); ?>">
                <h3>
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?>
                    </a>
                </h3>
                <small>
                    <?php the_time('F jS, Y') ?>
                    <!-- by <?php the_author() ?> -->
                </small>

                <div class="entry">
                    <?php the_content('Read the rest of this entry &raquo;'); ?>
                </div>

                <p class="postmetadata">
                    <?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?>
                </p>
            </div>

            <?php endwhile; ?>

            <div class="navigation">
                <div class="floatLeft">
                    <?php next_posts_link('&laquo; Older Entries') ?>
                </div>
                <div class="floatRight">
                    <?php previous_posts_link('Newer Entries &raquo;') ?>
                </div>
            </div>

            <?php else : ?>

            <h2 class="center">Not Found</h2>
            <p class="center">Sorry, but you are looking for something that isn't here.</p>
            <?php get_search_form(); ?>

            <?php endif; ?>

        </div>

</div>
</div>
<?php get_footer(); ?>
