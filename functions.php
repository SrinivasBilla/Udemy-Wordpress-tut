<?php 

function pageBanner( $args = NULL ) {

  if(!isset($args['title'])) {
    $args['title'] = get_the_title( );
  }
  if(!isset($args['subtitle'])) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }
  if(!isset($args['photo'])) {
    if(get_field('page_banner_background_image')) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBannerImage'];
    } else {
      $args['photo'] = get_template_directory_uri() . "/images/ocean.jpg";
    }
    }
  ?>
  <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
      <div class="page-banner__content container c-white">
        <h1 class="headline headline--large"><?php echo $args['title']; ?></h1>
          <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
          </div>
      </div>
</div>

<?php
}

function billa_enqueue_styles() {
    wp_enqueue_style('custom-google-fonts', "custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style('font-awsome', "//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style('billa-style-index', get_template_directory_uri(  ) . "/build/index.css");
    wp_enqueue_style('billa-style', get_template_directory_uri(  ) . "/build/style-index.css");
    wp_enqueue_script( "index-js", get_template_directory_uri() . "/build/index.js", array('jquery'), '1.0', true );

    wp_localize_script('index-js', 'universityData', array(
      'root_url' => get_site_url( )
    ));
}
add_action( "wp_enqueue_scripts", "billa_enqueue_styles" );

function university_features() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size( 'professorLandScape', '400', '260', true );
  add_image_size( 'professorPortrait', '480', '650', true );
  add_image_size( 'pageBannerImage', 1500, 360, true );
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query) {
  if(!is_admin() AND is_post_type_archive( 'program' ) AND $query->is_main_query()) {
    $query->set('posts_per_page', -1);
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
  }
  if (!is_admin() && is_post_type_archive( 'event' ) AND $query->is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', array(
      array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      )
      ));
  }}
add_action('pre_get_posts', 'university_adjust_queries');

function universityMapKey($api) {
  $api['key'] = 'AIzaSyATkASkLoeA5zpgHsaubPqY7dKctmVBK6w';
  return $api;
}

add_filter( 'acf/fields/google_maps/api', 'universityMapKey' );