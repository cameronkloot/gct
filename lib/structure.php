<?php
//: lib/structure.php ://

//
//: Layout
//

//: Sets the page style since that silly layouts metabox is removed
add_filter( 'genesis_pre_get_option_site_layout', 'ck_genesis_do_layout' );
function ck_genesis_do_layout( $option ) {
  if ( is_home() || is_front_page() || is_archive() ) {
    $option = 'full-width-content';
  }
  else {
    $option = 'content-sidebar';
  }
  return $option;
}

//
//: Header
//

add_filter( 'genesis_seo_title', 'ck_title_logo', 10, 3 );
function ck_title_logo( $title, $inside, $wrap ) {
  $output = sprintf( '<%1$s class="site-title">%2$s</%1$s>', $wrap, $inside );
  if ( function_exists( 'get_field' ) && get_field( 'logo', 'option' ) !== false ) {
    $logo = sprintf( '<a href="%s"><img class="logo" src="%s"></a>', esc_url( get_home_url() ), get_field( 'logo', 'option' ) );
    $output = $logo . $output;
  }
  return $output;
}

//: Removes secondary menu
add_theme_support( 'genesis-menus', array(
  'primary' => 'Primary Navigation Menu'
) );

//: Moves Primary Nav to the Site Header
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav' );

//
//: Inner
//

add_action( 'genesis_before_content_sidebar_wrap', 'ck_site_inner_wrap_open' );
function ck_site_inner_wrap_open() {
  echo '<div class="wrap">';
}

add_action( 'genesis_after_content_sidebar_wrap', 'ck_site_inner_wrap_close' );
function ck_site_inner_wrap_close() {
  echo '</div>';
}

unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar-alt' );

//
//: Footer
//

remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'ck_footer' );
function ck_footer() {
  //: Add an ACF option for copyright text
  ?>
  <p>Copyright &copy; <?php echo esc_attr( date( 'Y' ) ); ?></p>
  <?php
}

//
//: Parts
//

add_action( 'ck_social_links', 'ck_social_links_output' );
function ck_social_links_output() {

  if ( ! class_exists('acf') ) return '';

  if ( have_rows( 'social_networks', 'option' ) ):
  ?>
  <div class="social-links">
    <ul class="sl-container s-container">
      <?php while( have_rows( 'social_networks', 'option' ) ): the_row(); ?>
        <li><a href="<?php echo esc_url( get_sub_field( 'link' ) ); ?>" target="_blank">
          <?php
          $icon = get_sub_field( 'icon' );
          if ( strip_tags( $icon ) == $icon ) { //: class name ACF
            echo sprintf( '<i class="fa fa-fw %s"></i>', $icon );
          }
          else {
            echo $icon;
          }
          ?>
        </a></li>
      <?php endwhile; ?>
    </ul>
  </div>
  <?php
  endif;
}

//: END lib/structure.php ://
