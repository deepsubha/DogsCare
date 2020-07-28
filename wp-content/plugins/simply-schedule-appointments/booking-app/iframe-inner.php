<!DOCTYPE html>
<?php
$ssa = ssa();
$ssa_settings = $ssa->settings->get();
$ssa_settings = $ssa->settings->remove_unauthorized_settings_for_current_user( $ssa_settings );
$ssa_appointment_types = $ssa->appointment_type_model->query( array(
  'fetch' => array(
    'has_sms' => true,
  ),
) );


function ssa_get_language_attributes( $doctype = 'html' ) {
  $attributes = array();

  $is_rtl = SSA_Translation::is_rtl();
  $lang = SSA_Translation::get_locale();
  $lang = str_replace( '_', '-', $lang );

  if ( $is_rtl ) {
    $attributes[] = 'dir="rtl"';
  }

  $attributes[] = 'lang="' . esc_attr( $lang ) . '"';

  $output = implode( ' ', $attributes );

  return $output;
}
?>
<html <?php echo ssa_get_language_attributes(); ?>>
  <head>
    <meta charset="utf-8">
    <title><?php the_title(); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex" />
    <link rel='stylesheet' id='ssa-unsupported-style'  href='<?php echo $ssa->url( 'assets/css/unsupported.css?ver='.$ssa::VERSION ); ?>' type='text/css' media='all' />
    <link rel='stylesheet' id='ssa-booking-material-icons-css'  href='<?php echo $ssa->url( 'assets/css/material-icons.css?ver='.$ssa::VERSION ); ?>' type='text/css' media='all' />
    <link rel='stylesheet' id='ssa-booking-roboto-font-css'  href='<?php echo $ssa->url( 'assets/css/roboto-font.css?ver='.$ssa::VERSION ); ?>' type='text/css' media='all' />
    <link rel='stylesheet' id='ssa-booking-style-css'  href='<?php echo $ssa->url( 'booking-app/dist/static/css/app.css?ver='.$ssa::VERSION ); ?>' type='text/css' media='all' />
    <link rel="stylesheet" href='<?php echo $ssa->url( 'assets/css/iframe-inner.css?ver='.$ssa::VERSION ); ?>'>
    <link rel='https://api.w.org/' href='<?php echo home_url( 'wp-json/' ); ?>' />
    <link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo home_url( 'xmlrpc.php?rsd' ); ?>" />
    <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo home_url( 'wp-includes/wlwmanifest.xml' ); ?>" />
    <link rel="alternate" type="application/json+oembed" href="<?php echo home_url( 'wp-json/oembed/1.0/embed?url=http%3A%2F%2Fssa.dev%2Fbooking-test%2F' ); ?>" />
    <link rel="alternate" type="text/xml+oembed" href="<?php echo home_url( 'wp-json/oembed/1.0/embed?url=http%3A%2F%2Fssa.dev%2Fbooking-test%2F&#038;format=xml' ); ?>" />

    <?php $booking_css_url = $ssa->templates->locate_template_url( 'booking-app/custom.css' ); ?>
    <?php /* Apply styles from settings to view */ ?>
    <?php
      $ssa_styles = $ssa->styles_settings->get();

      // if we have style settings on the GET parameters, merge them with the styles settings
      $styles_params = array();

      if( isset( $_GET['accent_color'] ) && ! empty( $_GET['accent_color'] ) ) {
        $accent_color = $ssa->styles->hex_to_rgba( '#'. $_GET['accent_color'] );
        if( $accent_color ) {
          $styles_params['accent_color'] = $accent_color;
        }
      }

      if( isset( $_GET['background'] ) && ! empty( $_GET['background'] ) ) {
        $background = $ssa->styles->hex_to_rgba( '#'. $_GET['background'] );
        if( $background ) {
          $styles_params['background'] = $background;
        }
      }

      if( isset( $_GET['font'] ) && ! empty( $_GET['font'] ) ) {
        $styles_params['font'] = esc_attr( $_GET['font'] );
      }

      if( isset( $_GET['padding'] ) && ! empty( $_GET['padding'] ) ) {
        $styles_params['padding'] = esc_attr( $_GET['padding'] );
      }

      $ssa_styles = wp_parse_args( $styles_params, $ssa_styles );

      /* Use luminosity contrast to determine if the accent color should have black or white text */
      $contrast_ratio = $ssa->styles->get_contrast_ratio( $ssa_styles['accent_color'] );

      // Set accent contrast based on luminosity of the color
      if ($contrast_ratio > 10 ) {
        $ssa_styles['accent_contrast'] = 'black';
      } else {
        $ssa_styles['accent_contrast'] = 'white';
      }

      // Separate padding value into integer and units
      $padding_atts = $ssa->styles->get_style_atts_from_string( $ssa_styles['padding'] );

      // Attach Google stylesheet if necessary
      $system_fonts = array('Arial', 'Arial Black', 'Courier New', 'Georgia', 'Helvetica', 'Roboto', 'Tahoma', 'Times New Roman', 'Trebuchet MS', 'Verdana');
      $is_system_font = in_array($ssa_styles['font'], $system_fonts);

      if ( !$is_system_font ) : ?>
        <link rel='dns-prefetch' href='//fonts.googleapis.com' />
        <link href="https://fonts.googleapis.com/css?family=<?php echo $ssa_styles['font']; ?>" rel="stylesheet">
      <?php endif; ?>
    <style>
      /* Background color */
      html body,
      html body.md-theme-default {
        background: <?php echo $ssa_styles['background']; ?>;
        padding: <?php echo $padding_atts['value'] . $padding_atts['unit']; ?>
      }

      /* Accent color and accent contrast */
      html .md-theme-default.md-button:not([disabled]).md-primary.md-icon-button:not(.md-raised),
      html .select2-results__options .select2-results__option[aria-selected=true],
      html .md-theme-default.md-button:not([disabled]).md-primary:not(.md-icon-button),
      html .md-theme-default.md-input-container.md-input-focused label,
      html .md-theme-default.md-input-container.md-input-focused .md-icon:not(.md-icon-delete),
      html .md-theme-default.time-select.md-button:not([disabled]).md-raised:not(.md-icon-button),
      html .appointment-actions .md-button,
      html .md-theme-default.md-checkbox.md-primary .md-ink-ripple,
      html .md-theme-default.md-radio.md-primary .md-ink-ripple,
      html .md-theme-default.md-radio.md-primary.md-checked .md-ink-ripple {
        color: <?php echo $ssa_styles['accent_color']; ?>;
      }
      html legend.md-subheading .md-icon.md-theme-default {
        color: rgba(0,0,0,0.54);
      }
      html .md-card.selectable.light-green:hover,
      html .md-card.selectable.light-green:focus,
      html .md-theme-default.md-button:not([disabled]).md-primary.md-raised,
      html .md-theme-default.md-button:not([disabled]).md-primary.md-fab,
      html .md-theme-default.md-button:not([disabled]).md-primary.md-raised:hover,
      html .md-theme-default.md-button:not([disabled]).md-primary.md-raised:focus,
      html .md-theme-default.md-button:not([disabled]).md-primary.md-fab:hover,
      html .md-theme-default.md-button:not([disabled]).md-primary.md-fab:focus,
      html .book-day button.md-whiteframe.selectable:focus,
      html .book-day button.md-whiteframe.selectable:hover,
      html .book-day button.md-whiteframe.selectable:focus,
      html .md-theme-default.md-input-container.md-input-focused:after,
      html .md-theme-default.time-select.md-button:not([disabled]).md-raised:not(.md-icon-button):hover,
      html .md-theme-default.time-select.md-button:not([disabled]).md-raised:not(.md-icon-button):focus {
        background-color: <?php echo $ssa_styles['accent_color']; ?>;
        color: <?php echo $ssa_styles['accent_contrast']; ?>;
      }
      html .md-card.selectable.light-green {
        border-left-color: <?php echo $ssa_styles['accent_color']; ?>;
      }
      html .select2-search--dropdown .select2-search__field:focus {
        border-bottom-color: <?php echo $ssa_styles['accent_color']; ?>;
      }
      html .md-theme-default.md-spinner .md-spinner-path {
        stroke: <?php echo $ssa_styles['accent_color']; ?>;
      }

      /* Checkboxes and Radios */
      html .md-theme-default.md-checkbox.md-primary.md-checked .md-checkbox-container {
        background-color: <?php echo $ssa_styles['accent_color']; ?>;
        border-color: <?php echo $ssa_styles['accent_color']; ?>;
      }
      html .md-theme-default.md-checkbox.md-primary.md-checked .md-checkbox-container:after {
        border-color: <?php echo $ssa_styles['accent_contrast']; ?>;
      }
      html .md-theme-default.md-radio.md-primary .md-radio-container:after {
        background-color: <?php echo $ssa_styles['accent_color']; ?>;
      }
      html .md-theme-default.md-radio.md-primary.md-checked .md-radio-container {
        border-color: <?php echo $ssa_styles['accent_color']; ?>;
      }


      /* Contrast Mode */
      <?php if ( $ssa_styles['contrast']) : ?>
        html body,
        html body.md-theme-default {
          color: white;
        }
        html .time-listing-icon {
          fill: white;
        }
      <?php endif; ?>

      /* Font family */
      html body,
      html body.md-theme-default,
      html .book-day button.md-whiteframe.selectable,
      html .book-day button.md-whiteframe.disabled,
      html .book-day button.md-whiteframe.selectable,
      html .book-day button.md-whiteframe.disabled {
        font-family: <?php echo $ssa_styles['font']; ?>;
      }
    </style>

    <style>
      <?php echo $ssa_styles['css']; ?>
    </style>

    <link rel='stylesheet' id='ssa-booking-custom-css'  href='<?php echo $booking_css_url; ?>' type='text/css' media='all' />
    <?php

    // BEGIN: Deprecated
    if ( wp_style_is( 'ssa-custom' ) ){
      $wp_styles = wp_styles();
      foreach ($wp_styles->queue as $handle_key => $handle) {
        if ( $handle === 'ssa-custom' ) {
          continue;
        }

        wp_dequeue_style( $handle );
      }

      wp_print_styles();
    }
    // END: Deprecated
    ?>
    <?php do_action( 'ssa_booking_head' ); ?>
  </head>
  <body <?php body_class(); ?>>
      <?php echo do_shortcode( '[ssa_booking]' ); ?>
  <script type="text/javascript">
    var ssa = <?php echo json_encode( $ssa->bootstrap->get_api_vars() ); ?>;
    var ssa_settings = <?php echo json_encode( $ssa_settings ); ?>;
    var ssa_appointment_types = <?php echo json_encode( $ssa_appointment_types ); ?>;
    var ssa_translations = <?php echo json_encode( $ssa->shortcodes->get_translations() ); ?>;
    var ssa_customer_information_defaults = <?php echo json_encode( $ssa->customer_information->get_defaults() ); ?>;
  </script>

  <?php if ( $ssa->settings_installed->is_activated( 'stripe' ) ): ?>
    <script type='text/javascript' src='https://checkout.stripe.com/checkout.js'></script>
  <?php endif ?>

  <script type='text/javascript' src='<?php echo $ssa->url( 'assets/js/unsupported-min.js?ver='.$ssa::VERSION ); ?>'></script>
  <script type='text/javascript' src='<?php echo $ssa->url( 'booking-app/dist/static/js/manifest.js?ver='.$ssa::VERSION ); ?>'></script>
  <script type='text/javascript' src='<?php echo $ssa->url( 'booking-app/dist/static/js/vendor.js?ver='.$ssa::VERSION ); ?>'></script>
  <script type='text/javascript' src='<?php echo $ssa->url( 'booking-app/dist/static/js/app.js?ver='.$ssa::VERSION ); ?>'></script>
  <script type='text/javascript' src='<?php echo $ssa->url( 'assets/js/iframe-inner.js?ver='.$ssa::VERSION ); ?>'></script>
  <?php do_action( 'ssa_booking_footer' ); ?>
  </body>
</html>
