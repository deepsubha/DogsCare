;(function (ssaFormEmbed, undefined) {

	var appointmentId = null;
	var iframeLoaded = false;

	var updateFormField = function(e) {
		appointmentId = e.data.id;
		var container = e.source.frameElement.parentNode;
		container
			.querySelector('.ssa_appointment_form_field_appointment_id')
			.value = appointmentId
	}

	var updateAppointmentSelection = function(field) {
		if (iframeLoaded && field.value && field.value != appointmentId) { // Only update if the value is different
			appointmentId = field.value;
			var container = field.parentNode;
			container.querySelector('iframe').contentWindow.postMessage({
				'ssaAppointmentId': appointmentId
			}, window.ssa.api.site_url);
		}
	}

	ssaFormEmbed.listen = function(e) {
		if (typeof e.data == 'object' && e.data.hasOwnProperty('ssaType')) {
			if (e.data.ssaType === 'appointment') {
				updateFormField(e);
			}
		}

		if (typeof e.data == 'object' && e.data.hasOwnProperty('iframe')) {
			if (e.data.iframe == 'loaded') {
				iframeLoaded = true;
				ssaFormEmbed.loadSaved();
			}
		}
	}

	ssaFormEmbed.loadSaved = function () {
		var appointmentFields = document.querySelectorAll('.ssa_appointment_form_field_appointment_id');

		if (!appointmentFields) {
			return;
		}

		// Loop through all fields
		Array.prototype.forEach.call(appointmentFields, function(el, index, array){
			// If field has a value on page load
		  updateAppointmentSelection(el);
			// If field changes
			el.addEventListener('change', function(){
				updateAppointmentSelection(this);
			});
		});
	}

}(window.ssaFormEmbed = window.ssaFormEmbed || {}));

window.addEventListener('message', ssaFormEmbed.listen, false);

function ssaReady(callback) {
  if (document.readyState !== 'loading') {
    callback();
  } else {
    document.addEventListener('DOMContentLoaded', callback);
  }
}

ssaReady(function() {
  ssaFormEmbed.loadSaved();
});