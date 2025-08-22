<?php

get_header(); 
pageBanner( array(
  'title' => get_the_archive_title(  ),
  'subtitle' => get_the_archive_description(  ),
))
?>


<div class="container container--narrow page-section">
<?php
  while(have_posts()) {
    the_post(); 
    get_template_part('template-parts/content-evnet');
   }
  echo paginate_links();
?>
</div>

<?php get_footer();

?>