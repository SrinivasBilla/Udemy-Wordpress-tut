<?php

get_header();
pageBanner( array(
  'title' => 'Past Events',
  'subtitle' => 'A recap of our past events.'
)) ?>


<div class="container container--narrow page-section">
<?php
$today = date('Ymd');
// Query for past events
$pastEvents = new WP_Query(array(
  'paged' => get_query_var( 'paged', 1 ),
  // 'posts_per_page' => 1,
  'post_type' => 'event',
  'meta_key' => 'event_date',
  'orderby' => 'meta_value_num',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'event_date',
      'compare' => '<',
      'value' => $today,
      'type' => 'numeric'
    )
    )
    ));
  while($pastEvents->have_posts()) {
    $pastEvents->the_post(); ?>
    <div class="post-item">
      <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
      <div class="generic-content">
        <?php the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading &raquo;</a></p>
      </div>

    </div>
  <?php }
  echo paginate_links(array(
    'total' => $pastEvents->max_num_pages
  ));
?>
</div>

<?php get_footer();

?>