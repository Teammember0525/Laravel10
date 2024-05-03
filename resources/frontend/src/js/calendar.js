jQuery(document).ready(function () {
	jQuery("#add-event").submit(function () {
		alert("Submitted");
		var values = {};
		$.each($('#add-event').serializeArray(), function (i, field) {
			values[field.name] = field.value;
		});
		console.log(
			values
		);
	});
});

(function () {
	'use strict';
	// ------------------------------------------------------- //
	// Calendar
	// ------------------------------------------------------ //
	jQuery(function () {
		// page is ready
		jQuery('#calendar').fullCalendar({
			themeSystem: 'bootstrap4',
			// emphasizes business hours
			businessHours: false,
			defaultView: 'month',
			// event dragging & resizing
			editable: true,
			// header
			header: {
				left: 'prev',
				center: 'title',
				right: 'next'
			},
			events: [
				{
					title: 'Birthday',
					description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu pellentesque nibh. In nisl nulla, convallis ac nulla eget, pellentesque pellentesque magna.',
					start: '2020-11-4',
					end: '2020-11-5',
					className: 'fc-bg-expired',
					icon: "birthday-cake"
				},
				{
					title: 'Asteroids delux, argus, art of fighting, alpha mission',
					description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu pellentesque nibh. In nisl nulla, convallis ac nulla eget, pellentesque pellentesque magna.',
					start: '2020-11-16T20:00:00',
					end: '2020-11-23T22:30:00',
					className: 'fc-bg-active',
					icon: "cutlery",
					allDay: false
				},
				{
					title: 'Asteroids delux, argus, art',
					description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu pellentesque nibh. In nisl nulla, convallis ac nulla eget, pellentesque pellentesque magna.',
					start: '2020-11-23T20:00:00',
					end: '2020-11-25T22:30:00',
					className: 'fc-bg-upcoming',
					icon: "cutlery",
					allDay: false
				},
			],
			dayClick: function () {
				jQuery('#modal-view-event-add').modal();
			},
			eventClick: function (event, jsEvent, view) {
				jQuery('.event-icon').html("<i class='fa fa-" + event.icon + "'></i>");
				jQuery('.event-title').html(event.title);
				jQuery('.event-body').html(event.description);
				jQuery('.eventUrl').attr('href', event.url);
				jQuery('#calendarModal').modal();
			},
		})
	});

})(jQuery);