<?php
//: lib/common.php ://

function gct_encode_twitter( $text ) {
  return htmlspecialchars(urlencode(html_entity_decode($text, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
}

function gct_starts_with( $haystagct, $needle ) {
  return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

function gct_end_with( $haystack, $needle ) {
  return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function gct_heading( $heading ) {
  if ( $heading ):
  ?>
  <div class="heading-container">
    <h2 class="heading"><?php echo wp_kses_post( $heading ); ?></h2>
  </div>
  <?php
  endif;
}

function gct_button( $text, $link ) {
  if ( $text && $link ):
  ?>
  <div class="button-container">
    <a class="button" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $text ); ?></a>
  </div>
  <?php
  endif;
}

function gct_image_url( $image_id, $size = 'hero' ) {
  $image_array = wp_get_attachment_image_src( $image_id, $size );
  return $image_array !== false ? $image_array[0] : '';
}

//: END lib/common.php ://
