<?php
/**
 * Appointment Booked (to Staff)
 * *
 * This template can be overridden by copying it to wp-content/themes/your-theme/ssa/notifications/email/text/booked-staff.php
 * Note: this is just the default template that is used as a starting pont.
 * Once the user makes edits in the SSA Settings interface, 
 * the template stored in the database will be used instead
 *
 * @see         https://simplyscheduleappointments.com
 * @author      Simply Schedule Appointments
 * @package     SSA/Templates
 * @version     1.3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<?php  echo sprintf( __( '%s just booked an appointment on %s', 'simply-schedule-appointments' ), '{{ Appointment.customer_information.Name }}', '{{ Global.home_url }}' ); ?> 
 
<?php echo __( 'Appointment Details:', 'simply-schedule-appointments' );?> 
<?php echo sprintf( __( 'Starting at %s', 'simply-schedule-appointments' ), '{{ Appointment.start_date | date("F d, Y g:ia (T)", Appointment.date_timezone) }}' );?> 
 
{% if Appointment.AppointmentType.instructions %}
<?php echo sprintf( __( 'Instructions: %s', 'simply-schedule-appointments' ), '{{ Appointment.AppointmentType.instructions|raw }}' ); ?> 
{% endif %}
 
<?php echo sprintf( __( 'Type: %s', 'simply-schedule-appointments' ), '{{ Appointment.AppointmentType.title|raw }}' ); ?> 

<?php echo __( 'Customer details:' ) ?> 
{{ Appointment.customer_information_summary }}