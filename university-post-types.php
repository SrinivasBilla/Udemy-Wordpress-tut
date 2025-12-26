<?php
/**
 * Plugin Name: Custom University Post Types
 * Description: This plugin performs CRUD Operations with Employees Table. Also on Activation it will create a dynamic wordpress page and it will have a shortcode.
 * Version: 1.0
 * Author: Billa
 */

function university_post_type() {
  register_post_type( "campus", array(
    'rewrite' => array('slug' => 'campuses'),
    'has_archive' => true,
    'public' => true,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Campuses',
      'add_new_item' => 'Add New Campus',
      'edit_item' => 'Edit Campus',
      'all_items' => 'All Campuses',
      'singular_name' => 'Campus'
    ),
    'menu_icon' => 'dashicons-location-alt'
  ));

  

  register_post_type( "event", array(
    'capability_type' => 'event',
	'map_meta_cap' => true,
    'rewrite' => array('slug' => 'event'),
    'has_archive' => true,
    'public' => true,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Events',
      'add_new_item' => 'Add New Event',
      'edit_item' => 'Edit Event',
      'all_items' => 'All Events',
      'singular_name' => 'Event'
    ),
    'menu_icon' => 'dashicons-calendar'
  ));
  register_post_type( 'program', array(
    'rewrite' => array('slag' => 'program'),
    'has_archive' => true,
    'public' => true,
    'show_in_rest' => true,
    'labels' => array (
      'name' => 'Programs',
      'add_new_item' => 'Add New Program',
      'edit_item' => 'Edit Program',
      'all_items' => 'All Programs',
      'singular_name' => 'program'
    ),
    'menu_icon' => 'dashicons-awards'
  ) );

  //Professor Post Type
  register_post_type( 'professor', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'thumbnail'),
    'public' => true,
    'show_in_rest' => true,
    'labels' => array (
      'name' => 'Professors',
      'add_new_item' => 'Add New Professor',
      'edit_item' => 'Edit Professor',
      'all_items' => 'All Professor',
      'singular_name' => 'professor'
    ),
    'menu_icon' => 'dashicons-lightbulb',
  ) );
  //Notes Post Type
  register_post_type( 'note', array(
    'capability_type' => 'note',
	'map_meta_cap' => true,
    'show_in_rest' => true,
    'supports' => array('title', 'editor'),
    'public' => false,
	'show_ui' => true,
    'show_in_rest' => true,
    'labels' => array (
      'name' => 'Notes',
      'add_new_item' => 'Add New Note',
      'edit_item' => 'Edit Note',
      'all_items' => 'All Note',
      'singular_name' => 'Note'
    ),
    'menu_icon' => 'dashicons-lightbulb',
  ) );
}

add_action( "init", "university_post_type" );