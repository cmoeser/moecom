<?php
/**
 * @package WordPress
 * @subpackage Chip
 */
?>
	<div role="complementary">
    <ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : 	?>
               
			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h2>Author</h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

			<?php if ( is_404() || is_category() || is_day() || is_month() ||
						is_year() || is_search() || is_paged() ) {
			?> 

			<?php /* If this is a 404 page */ if (is_404()) { ?>
			<?php /* If this is a category archive */ } elseif (is_category()) { ?>
			<p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p>

			<?php /* If this is a yearly archive */ } elseif (is_day()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for the day <?php the_time('l, F jS, Y'); ?>.</p>

			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for <?php the_time('F, Y'); ?>.</p>

			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for the year <?php the_time('Y'); ?>.</p>

			<?php /* If this is a monthly archive */ } elseif (is_search()) { ?>
			<p>You have searched the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives
			for <strong>'<?php the_search_query(); ?>'</strong>. If you are unable to find anything in these search results, you can try one of these links.</p>

			<?php /* If this is a monthly archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<p>You are currently browsing the <a href="<?php echo bloginfo('url'); ?>/"><?php echo bloginfo('name'); ?></a> blog archives.</p>

			<?php } ?>

		<?php }?>
            
<?php
   if( function_exists('collapsArch') ) {
	   echo "<ul>";
	  collapsArch($defaults=array(
  'noTitle' => '',
  'inExcludeCat' => 'exclude',
  'inExcludeCats' => '',
  'inExcludeYear' => 'exclude',
  'inExcludeYears' => '',
  'sort' => 'DESC',
  'showPages' => false, 
  'linkToArch' => true,
  'showYearCount' => false,
  'expandCurrentYear' => true,
  'expandMonths' => true,
  'expandYears' => true,
  'expandCurrentMonth' => true,
  'showMonthCount' => true,
  'showPostTitle' => true,
  'expand' => '0',
  'showPostDate' => false,
  'postDateFormat' => 'm/d',
  'animate' => 0,
  'postTitleLength' => '',
  'debug' => '0',
  ));
  echo "</ul>";
     } else {
      echo "<ul>\n";
      wp_get_archives();
      echo "</ul>\n";
     }
          ?>  



			

			<?php endif; ?>
            </ul></div>
                


