<?php
//: lib/entries.php ://

add_action( 'genesis_before', 'ck_before_entries' );
function ck_before_entries() {

  //: Info and meta will often be removed straight up. Replace with filter if needed
  remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
  remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

  //: Filter priority of '0' places the output before the <header> tag
  add_action( 'genesis_entry_header', 'ck_entry_thumbnail_ouput', 0 );

}

add_action( 'ck_entry_thumbnail', 'ck_entry_thumbnail_ouput' );
function ck_entry_thumbnail_ouput() {
  if ( has_post_thumbnail() ):
    $size = 'full'; // eg: $size = is_archive() ? 'feature' : 'full';
    echo '<div class="entry-thumbnail">';

      //: TODO Add filter for linked or not
      echo is_archive() || is_front_page() ? sprintf( '<a href="%s">', get_permalink() ) : '';

      the_post_thumbnail( $size ); //: TODO Escape output

      echo is_archive() || is_front_page() ? '</a>' : '';

    echo '</div>';
  endif;
}


//: END lib/entries.php ://
