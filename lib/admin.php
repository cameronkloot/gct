<?php
//: lib/admin.php ://

//
//: Genesis
//

remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
remove_theme_support( 'genesis-seo-settings-menu' );
// remove_theme_support( 'genesis-admin-menu' );

remove_theme_support( 'genesis-inpost-layouts' );
remove_action( 'admin_menu', 'genesis_add_inpost_scripts_box' );

genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

//
//: Wordpress
//

add_filter( 'edit_post_link', '__return_false' );
add_filter('widget_text', 'do_shortcode');

add_action( 'widgets_init', 'gct_unregister_genesis_widgets', 20 );
function gct_unregister_genesis_widgets() {
  unregister_widget( 'Genesis_eNews_Updates' );
  unregister_widget( 'Genesis_Featured_Page' );
  unregister_widget( 'Genesis_Featured_Post' );
  unregister_widget( 'Genesis_Latest_Tweets_Widget' );
  unregister_widget( 'Genesis_Menu_Pages_Widget' );
  unregister_widget( 'Genesis_User_Profile_Widget' );
  unregister_widget( 'Genesis_Widget_Menu_Categories' );
  unregister_widget('WP_Widget_Pages');
  unregister_widget('WP_Widget_Calendar');
  unregister_widget('WP_Widget_Archives');
  unregister_widget('WP_Widget_Links');
  unregister_widget('WP_Widget_Meta');
  unregister_widget('WP_Widget_Search');
  //    unregister_widget('WP_Widget_Text');
  unregister_widget('WP_Widget_Categories');
  unregister_widget('WP_Widget_Recent_Posts');
  unregister_widget('WP_Widget_Recent_Comments');
  unregister_widget('WP_Widget_RSS');
  unregister_widget('WP_Widget_Tag_Cloud');
  unregister_widget('WP_Nav_Menu_Widget');
}

add_action( 'genesis_admin_before_metaboxes', 'gct_remove_genesis_theme_metaboxes' );
function gct_remove_genesis_theme_metaboxes( $hook ) {
  remove_meta_box( 'genesis-theme-settings-version',    $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-feeds',      $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-layout',     $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-header',     $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-nav', 	      $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-breadcrumb', $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-comments',   $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-posts',      $hook, 'main' );
  remove_meta_box( 'genesis-theme-settings-blogpage',   $hook, 'main' );
  // remove_meta_box( 'genesis-theme-settings-scripts',    $hook, 'main' );

  remove_meta_box( 'genesis-seo-settings-doctitle', $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-homepage', $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-dochead',  $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-robots',   $hook, 'main' );
  remove_meta_box( 'genesis-seo-settings-archives', $hook, 'main' );
}

add_action( 'admin_bar_menu', 'pd_admin_bar_remove_menu_items', 99 );
function pd_admin_bar_remove_menu_items( $wp_admin_bar ) {
  $wp_admin_bar->remove_node( 'wp-logo' );
  $wp_admin_bar->remove_node( 'comments' );
}

add_action( 'admin_menu', 'pd_remove_menus' );
function pd_remove_menus(){
  global $menu;
  // remove_menu_page( 'edit-comments.php' );
  // remove_menu_page( 'tools.php' );

  remove_meta_box('postcustom', 'post', 'normal');
  remove_meta_box('commentstatusdiv', 'post', 'normal');
  remove_meta_box('commentsdiv', 'post', 'normal');
}

add_action( 'wp_dashboard_setup', 'pd_disable_dashboard_widgets' );
function pd_disable_dashboard_widgets() {
  remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_quigct_press', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_secondary', 'dashboard', 'side' );
  remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
  remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
  remove_meta_box( 'rg_forms_dashboard', 'dashboard', 'normal' );
}

add_filter( 'admin_footer_text', 'gct_admin_footer_text' );
function gct_admin_footer_text() {
  echo '<p style="float:right">Powered by <a href="http://darkspire.media/" target="_blank">Darkspire Media</a></p>';
  remove_filter( 'update_footer', 'core_update_footer' );
}

//
//: ACF
//

if ( function_exists( 'acf_add_options_page' ) ):

  acf_add_options_page( array(
    'page_title'    => 'Theme Settings',
    'menu_slug'     => 'theme_settings',
    'capability'    => 'manage_options',
    'position'      => '59.1',
  ) );

endif;


add_action( 'acf/input/admin_footer', 'gct_acf_admin_footer_metabox_title', 1 );
function gct_acf_admin_footer_metabox_title() {
  $field_groups = acf_get_field_groups( array( 'post_id' => get_the_id() ) );

  foreach ( $field_groups as $field_group ) {
    if ( !isset( $field_group['metabox_title'] ) || $field_group['metabox_title'] == '' ) continue;
    ?>
    <script type="text/javascript">
    document.getElementById('acf-<?php echo $field_group['key'] ?>').getElementsByTagName('h2')[0].childNodes[0].innerHTML = '<?php echo $field_group['metabox_title'] ?>';
    </script>
    <?php
  }
}

add_action( 'acf/render_field_group_settings', 'gct_render_options' );
function gct_render_options( $field_group ) {

  if ( $field_group['style'] == 'default' ):

    $metabox_title = isset( $field_group['metabox_title'] ) ? $field_group['metabox_title'] : '';
    acf_render_field_wrap(array(
      'label'         => __('Metabox Title','acf'),
      'instructions'  => '',
      'type'          => 'text',
      'name'          => 'metabox_title',
      'prefix'        => 'acf_field_group',
      'value'         => $metabox_title,
    ));

  endif;

  $custom_css = isset( $field_group['custom_css'] ) ? $field_group['custom_css'] : '';
  acf_render_field_wrap(array(
    'label'         => __('Custom CSS','acf'),
    'instructions'  => '',
    'type'          => 'textarea',
    'name'          => 'custom_css',
    'prefix'        => 'acf_field_group',
    'value'         => $custom_css,
  ));
}

add_action( 'acf/input/admin_head', 'gct_acf_admin_head' );
function gct_acf_admin_head() {
  $field_groups = acf_get_field_groups( array( 'post_id' => get_the_id() ) );
  foreach ( $field_groups as $field_group ) {
    if ( !isset( $field_group['custom_css'] ) ) continue;
    echo sprintf( '<style type="text/css">%s</style>', $field_group['custom_css'] );
  }
  echo '<style type="text/css">.hide-label .acf-label{display:none;}</style>';
}

//: END lib/admin.php ://
