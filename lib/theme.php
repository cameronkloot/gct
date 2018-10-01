<?php
//: lib/theme.php ://

//
//: Genesis
//

//: Tells Genesis to use HTML5 markup
add_theme_support( 'html5' );

//: Fucking comments
remove_action( 'genesis_comments', 'genesis_do_comments' );

//: Prevents users from zooming like dicks, adjust or remove if needed
add_action( 'genesis_meta', 'gct_viewport_meta_tag_output' );
function gct_viewport_meta_tag_output() {
  ?>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <?php
}

//: Remove default Genesis templates
add_filter( 'theme_page_templates', 'gct_remove_genesis_page_templates' );
function gct_remove_genesis_page_templates( $page_templates ) {
  unset( $page_templates['page_archive.php'] );
  unset( $page_templates['page_blog.php' ] );
  return $page_templates;
}

//: Adjust settings without need of the admin menu page
add_filter('genesis_options', 'gct_set_genesis_defaults', 10, 2);
function gct_set_genesis_defaults( $options, $setting ) {
  if ( $setting == 'genesis-settings' ) {
    $options['content_archive'] = 'excerpts';
    $options['comments_posts'] = false;
    // $options['posts_nav'] = 'prev-next';
  }
  return $options;
}

//
//: Gravity Forms
//

add_filter( 'gform_init_scripts_footer', '__return_true' );
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
add_filter( 'gform_confirmation_anchor', '__return_true');

add_filter( 'gform_tabindex', 'gct_disable_gform_tabindex', 10, 2 );
function gct_disable_gform_tabindex( $tab_index, $form = false ) {
  return -1;
}

add_filter( 'gform_field_css_class', 'gct_gform_field_custom_type_class', 10, 3 );
function gct_gform_field_custom_type_class( $classes, $field, $form ) {

  //: Only add classes for front end forms
  if ( ! is_admin() ) {

    //: Add field type as a css selector
    $classes .= sprintf( ' gfield_%s', esc_attr( $field->type ) );

    //: Single text fields with input match '99999' are Zip fields
    if ( $field['inputMask'] && $field['inputMaskValue'] == '99999' ) {
      $classes .= ' gfield_zip';
    }

    //: Add 'small', 'medium', or 'large' if set as a property
    if ( isset( $field['size'] ) ) {
      $classes .= sprintf( ' %s', esc_attr( $field['size'] ) );
    }

  }
  return $classes;
}

// add_filter( 'gform_notification', 'gct_change_notification_email', 10, 3 );
function gct_change_notification_email( $notification, $form, $entry ) {
  if ( $notification['name'] == 'Admin Notification' ) {
    return null;
  }
  return $notification;
}

add_filter( 'gform_cdata_open', 'gct_wrap_gform_cdata_open' );
function gct_wrap_gform_cdata_open( $content = '' ) {
	$content = 'document.addEventListener( "DOMContentLoaded", function() { ';
	return $content;
}

add_filter( 'gform_cdata_close', 'gct_wrap_gform_cdata_close' );
function gct_wrap_gform_cdata_close( $content = '' ) {
	$content = ' }, false );';
	return $content;
}

add_filter( 'gform_ajax_spinner_url', 'gct_gf_spinner_replace', 10, 2 );
function gct_gf_spinner_replace( $image_src, $form ) {
	return  'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}

//
//: Other
//

add_filter( 'wpseo_metabox_prio', '__return_null'); //: Puts Yoast metabox as low as possible

//
//: Wordpress
//

add_filter( 'excerpt_length', 'gct_excerpt_length' );
function gct_excerpt_length( $length ) {
  return 35;
}

add_filter('excerpt_more', 'gct_get_read_more_link');
add_filter( 'the_content_more_link', 'gct_get_read_more_link' );
function gct_get_read_more_link() {
  return '...';
}

//
//: GCT
//

//: Enqueue fonts instead of using @import
add_action( 'wp_enqueue_scripts', 'gct_enqueue_styles');
function gct_enqueue_styles() {
  wp_enqueue_style( 'roboto', '//fonts.googleapis.com/css?family=Roboto:300,300italic,500,700,700italic' );
  wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' );
}

// add_action( 'wp_enqueue_scripts', 'gct_enqueue_scripts', 99 );
function gct_enqueue_scripts() {
  wp_enqueue_script( 'slicknav', esc_url( get_stylesheet_directory_uri() ) . '/assets/js/jquery.slicknav.min.js', array( 'jquery' ), false, true );
  wp_enqueue_script( 'functions', esc_url( get_stylesheet_directory_uri() ) . '/assets/js/jquery.functions.js', array( 'jquery' ), false, true );
}

add_filter( 'genesis_favicon_url', 'gct_favicon_url' );
function gct_favicon_url() {
  $url = function_exists( 'get_field' ) ? esc_url( get_field( 'favicon', 'option' ) ) : false;
  return $url;
}

//: Add custom image sizes here
add_action( 'after_setup_theme', 'gct_setup_image_sizes' );
function gct_setup_image_sizes() {
  // add_image_size( 'featured-square', 600, 600, true );
}

//: Not mine originally. Useful for upsizing shit images to fit the theme correctly
//: Does not replace kicking whoever is using bad images
add_filter( 'image_resize_dimensions', 'gct_image_crop_dimensions', 10, 6 );
function gct_image_crop_dimensions( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
  if ( !$crop ) return null;
  //: 150, 150 so anything above default WP 'thumbnail' will be upsized
  if ( $orig_w <= 150 || $orig_h <= 150 ) return;

  $aspect_ratio = $orig_w / $orig_h;
  $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

  $crop_w = round($new_w / $size_ratio);
  $crop_h = round($new_h / $size_ratio);

  $s_x = floor( ($orig_w - $crop_w) / 2 );
  $s_y = floor( ($orig_h - $crop_h) / 2 );

  if( is_array( $crop ) ) { if( $crop[ 0 ] === 'left' ) { $s_x = 0; } else if( $crop[ 0 ] === 'right' ) { $s_x = $orig_w - $crop_w;} if( $crop[ 1 ] === 'top' ) { $s_y = 0; } else if( $crop[ 1 ] === 'bottom' ) { $s_y = $orig_h - $crop_h; } }

  return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}

//: Scripts ://

add_action( 'wp_head', 'ck_header_scripts', 999 );
function ck_header_scripts() {
  if ( $code = get_field( 'header_scripts', 'option' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }

  if ( $code = get_field( 'header_scripts'  ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

add_action( 'genesis_after', 'ck_before_scripts', 1 );
function ck_before_scripts() {
  if ( $code = get_field( 'body_scripts', 'option' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }

  if ( $code = get_field( 'body_scripts' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

add_action( 'wp_footer', 'ck_footer_scripts', 999 );
function ck_footer_scripts() {
  if ( $code = get_field( 'footer_scripts', 'option' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }

  if ( $code = get_field( 'footer_scripts' ) ) {
    echo "\n";
    echo $code;
    echo "\n";
  }
}

//: Include if Facebook sharing is required
// add_action( 'wp_head', 'gct_print_facebook_init' );
function gct_print_facebook_init() {
  $facebook_id = function_exists( 'get_field' ) ? get_field( 'facebook_id', 'option' ) : false;
  if ( $facebook_id !== false ) {
    echo '<script>window.fbAsyncInit = function(){FB.init({appId: \'' . esc_attr( $facebook_id ) . '\', status: true, cookie: true, xfbml: true });};(function(d, debug){var js, id = \'facebook-jssdk\', ref = d.getElementsByTagName(\'script\')[0];if(d.getElementById(id)) {return;}js = d.createElement(\'script\'); js.id = id; js.async = true;js.src = "//connect.facebook.net/en_US/all" + (debug ? "/debug" : "") + ".js";ref.parentNode.insertBefore(js, ref);}(document, /*debug*/ false));function postToShare(url){var obj = {method: \'share\',href: url};function callback(response){}FB.ui(obj, callback);}</script>' . "\n";
  }
}

//: END lib/theme.php ://
