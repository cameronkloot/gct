<?php
//: lib/widgets.php ://

// add_action( 'widgets_init', 'ck_register_widgets' );
function ck_register_widgets() {
  if ( class_exists( 'ACF' ) ) {
    register_widget( 'form_widget' );
  }
}

class form_widget extends WP_Widget {
  function __construct() {
    parent::__construct( false, $name = 'GCT: Form' );
  }

  function widget( $args, $instance ) {

    $widget_id = 'widget_' . $args['widget_id'];

    ?>
    <section id="form-widget" class="widget form-widget">
      <div class="widget-wrap">
        <?php if ( $heading = get_field( 'heading', $widget_id ) ): ?>
          <h3 class="widget-title"><?php echo wp_kses_post( $heading ); ?></h3>
        <?php endif; ?>

        <?php if ( $description = get_field( 'description', $widget_id ) ): ?>
          <div class="description">
            <?php echo apply_filters( 'the_content', $description ); ?>
          </div>
        <?php endif; ?>

        <?php if ( $form = get_field( 'form', $widget_id ) ): ?>
          <div class="form">
            <?php gravity_form( $form['id'], false, false ); ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <?php
  }

  public function form( $instance ) {
    echo '<p>';
  }
}

//: END lib/widgets.php ://
