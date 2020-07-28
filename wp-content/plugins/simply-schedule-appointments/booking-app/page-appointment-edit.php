<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo apply_filters( 'ssa_appointment_edit_page_title', __( 'Edit Appointment', 'simply-schedule-appointments' ) ); ?></title>
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php
    global $ssa_current_appointment_id;
    if ( empty( $ssa_current_appointment_id ) ) {
      die( 'An error occurred, please check the URL' );
    }
    $shortcode = '[ssa_booking edit="'.$ssa_current_appointment_id.'"';
    if ( ! empty( $_GET['paypal_success'] ) || ! empty( $_GET['paypal_cancel'] ) ) {
      $shortcode .= ' view="confirm_payment"';
    }
    $shortcode .= ']';
    echo do_shortcode( $shortcode );
    ?>
  </body>
  <?php wp_footer(); ?>
</html>
