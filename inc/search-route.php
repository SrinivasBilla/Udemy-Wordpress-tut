<?php


add_action( 'rest_api_init', 'unversityRegisterSearch' );

function unversityRegisterSearch() {
  register_rest_route( 'unversity/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'unversitySearchResults'
  ) );
}
function unversitySearchResults($data) {
  $mainQuery = new WP_Query( array(
    'post_type' => array( 'post', 'page', 'professor', 'program', 'event', 'campus' ),
    's' => sanitize_text_field( $data['term']  )
  ));
  $results = array(
    'generalInfo' => array(),
    'events' => array(),
    'programs' => array(),
    'professors' => array(),
    'campuses' => array()
  );
  while($mainQuery->have_posts()) {
    $mainQuery->the_post();
    if(get_post_type() == 'post' OR get_post_type() == 'page') {
      array_push($results['generalInfo'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'postType' => get_post_type( ),
      'autherName' => get_the_author( )
    ));
    }
    if(get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));

      $description =  wp_trim_words( get_the_content(), 18 );

      array_push($results['events'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'month' => $eventDate->format('M'),
      'day' => $eventDate->format('d'),
      'description' => $description
    ));
    }
    if(get_post_type() == 'program') {
      $relatedCampuses = get_field('related_campuses');
      if($relatedCampuses) {
        foreach($relatedCampuses as $campus) {
          array_push($results['campuses'], array(
            'title' => get_the_title( $campus ),
            'permalinke' => get_the_permalink( $campus )
          ));
        }
      }

      array_push($results['programs'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'id' => get_the_ID(  )
    ));
    }
    if(get_post_type() == 'professor') {
      array_push($results['professors'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'image' => get_the_post_thumbnail_url( 0, 'professorLandScape' )
    ));
    }
    if(get_post_type() == 'campus') {
      array_push($results['campuses'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
    ));
    }
  }

  if($results['programs']) {
    $programsMetaQuery = array('relation' => 'OR');
  foreach($results['programs'] as $item) {
    array_push($programsMetaQuery, array(
        'key' => 'related_programs',
        'compare' => 'LIKE',
        'value' => '"' . $item['id'] . '"'
    ));
  }

  $professorsRelationshipQuery = new WP_Query(array(
    'post_type' => array('professor', 'event'),
    'meta_query' => $programsMetaQuery
    ));
  while($professorsRelationshipQuery->have_posts()) {
    $professorsRelationshipQuery->the_post();

    if(get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));

      $description =  wp_trim_words( get_the_content(), 18 );

      array_push($results['events'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'month' => $eventDate->format('M'),
      'day' => $eventDate->format('d'),
      'description' => $description
    ));
    }



    if(get_post_type() == 'professor') {
      array_push($results['professors'], array(
      'title' => get_the_title(),
      'permalink' => get_the_permalink(),
      'image' => get_the_post_thumbnail_url( 0, 'professorLandScape' )
    ));
    }
  }
  $results['professors'] = array_values(array_unique( $results['professors'], SORT_REGULAR ));
  $results['evnets'] = array_values(array_unique( $results['events'], SORT_REGULAR ));

  }

  
  return $results;
  
}