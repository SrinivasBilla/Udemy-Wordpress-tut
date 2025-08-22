<?php

get_header();
pageBanner( array(
  'title' => get_the_archive_title(  ),
  'subtitle' => get_the_archive_description(  )
)) 
?>

<div class="container container--narrow page-section">
<?php
  while(have_posts()) {
    the_post(); ?>
     <div class="event-summary">
            <a class="event-summary__date t-center" href="<?php the_permalink( ); ?>">
              <span class="event-summary__month"><?php the_time( 'M' ); ?></span>
              <span class="event-summary__day"><?php the_time( 'd' ); ?></span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title( ); ?></a></h5>
              <p><?php echo wp_trim_words( get_the_content(), 18 ); ?> <a href="<?php the_permalink( ); ?>" class="nu gray">Learn more</a></p>
            </div>
          </div>
  <?php }
  echo paginate_links();
?>
</div>

<?php get_footer();

?>