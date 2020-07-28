=== Simply Schedule Appointments ===
Contributors:      croixhaug, nataliemac
Donate link:       https://simplyscheduleappointments.com
Tags:              appointment scheduling, booking, scheduling, scheduler, classes
Requires at least: 4.8
Tested up to:      5.4.3
Stable tag:        1.3.9.0
License:           GPLv2
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

Allow your customers to easily book appointments through your website. **Get set up in just five minutes** using our unique setup wizard, and manage your scheduling availability and upcoming appointments through the WP admin.

== Description ==

Simply Schedule Appointments is an easy-to-use, flexible, and beautiful plugin for accepting appointments online through your website. No more phone calls or back-and-forth emails trying to find a time that works. Simply Schedule Appointments is quick and simple to set up for you, and easy and fast for your customers and clients to book appointments. Get set up to take your first appointment in less than 5 minutes!

Simply Schedule Appointments is perfect if you want to let your customers schedule phone calls, meetings, or coaching sessions. Tame your schedule and easily manage when you're available for appointments and when you don't want to be disturbed. Easily limit how many daily appointments you accept and set the minimum time between appointments. Offer different types of appointments. For example, you could make a short intro call available to anyone, and then let established customers schedule a longer meeting.

Simply Schedule Appointments contains all your data about your appointments and customers on your own WordPress site. We do not connect out to a third-party appointments service. Own your own data!

Full documentation available [at our website](https://simplyscheduleappointments.com/documentation/).

= Who is this for? =

Simply Schedule Appointments is ideal for anyone who needs to easily book appointments with clients and customers:

* Entrepreneurs
* Personal trainers
* Yoga studios
* Web developers
* Consultants
* Personal/Business Coaches
* Lawyers
* Language / ESL tutors
* Wedding coordinators
* Contractors
* Handyman services
* Dress shops
* Car dealers
* Boutiques
* Exercise studios
* Dance studios offering classes
* Fitness professionals
* Nutritionists
* Music teachers scheduling lessons
* Workshop teachers
* Hair salons
* Bakeries

Simply Schedule Appointments is easy to install and set up, easy to manage, and offers a great experience for your customers who need to book appointments. SSA has just the features you need without all the extra bloat and needless settings found in other appointment booking plugins.

It's built with you and your customer in mind and is accessible for everyone. It's beautifully designed and offers a great user experience all around - for you and for your customers.

Simply Schedule Appointments is built with the latest technology - including Vue.js and the WordPress REST API. That means our UI is snappy, highly responsive, and a joy to use.

= Flexible, fast, and powerful =

Other appointment scheduling plugins are either too simple or too complicated. They lack the basic features you need and want, or, if they do have those features, as you grow and add services, staff, and locations, they become difficult and confusing to use.

Simply Schedule Appointments has all the features you need - without all the confusion and clutter of settings that don't apply to you. Simply Schedule Appointments is ready to grow with your business, but helps you avoid unnecessary complexity when it's not needed.

We're also relying on the latest and greatest technologies for Simply Schedule Appointments. Our responsive and intuitive interface is built using Vue.js and the WordPress REST API. That allows us to keep the plugin fast and efficient.

Simply Schedule Appointments is fully responsive and will work easily on any device - even touch screens. So whether you're managing your appointment availability from your tablet or your customer is booking an appointment from their smart phone, SSA will work dependably and reliably.

= Usable and accessible =

We design our products with people in mind, first and foremost. We're always thinking about the ways that people will be using our products, what they'll expect, and what features they'll want and need. Our focus on usability results in products that are easy to install and set up, easy to manage, and a joy to use. We believe in making things as clear and as simple as possible, while still maintaining the flexibility and features our customers will need.

We have a wide range of people who use our products - people who can see and people who can't, people who can hear and people who can't, people who have control over the movement of their fingers and hands and those who don't. We believe that everyone deserves access to everything the web has to offer, so we build our products with accessibility in mind. You'll never have to worry that a customer will find themselves unable to schedule an appointment because of their abilities or the device they're using.

= Deep integrations with all the tools and plugins you love =

Free integrations included in all editions:

* Logged in WordPress users
* [Beaver Builder modules](https://simplyscheduleappointments.com/beaver-builder-appointment-scheduling/)
* [Elementor widgets](https://simplyscheduleappointments.com/elementor-appointment-booking/)
* [Divi modules](https://simplyscheduleappointments.com/integrations/divi-booking-widgets/)

Integrations included in premium editions:

* Google Calendar
* Mailchimp
* Stripe
* Paypal
* Zapier Webhooks
* Twilio for SMS reminders
* Gravity Forms
* Formidable Forms
* Google Analytics
* Google Tag Manager
* Facebook pixel
* Segment


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Select the Appointments option in the Admin menu to set up and configure the plugin.

= Manual Installation =

1. Upload the entire `/simply-schedule-appointments` directory to the `/wp-content/plugins/` directory.
1. Activate Simply Schedule Appointments through the 'Plugins' menu in WordPress.

= After Installing =

1. You'll see a new item on the left-hand menu called 'Appointments'. Click that to get started.
1. Our start-up wizard will walk you through the basic settings and setting up your first appointment type.
1. You may use the `[ssa_booking]` shortcode on any post or page to add an appointment booking form for your customers.

For more details, please see our [detailed documentation](https://simplyscheduleappointments.com/documentation/).

== Frequently Asked Questions ==

= How do I show the booking form? =

When you install and activate Simply Schedule Appointments, we'll automatically create a page called 'Schedule an Appointment'. This is a full-screen booking form that you can link to.

If you'd rather embed your booking form in an existing page or post, you can do so by adding the `[ssa_booking]` shortcode.

If you use Gutenberg, we also provide a Gutenberg block for the appointment booking form.

= Where can I read the documentation? =

The full documentation is available at [our website](https://simplyscheduleappointments.com/documentation/).

= Will I end up being double-booked? =

Nope. Once a customer has booked an appointment time, that time is no longer available for booking. So you'll never find yourself double-booked. Additionally, we do some extra checks while a customer is booking an appointment just to be sure their selected time is still available. If someone else books their selected time while they're in the process of booking, we'll let them know and ask them to select another time. We also offer Google Calendar syncing in our Plus package.

= Can I customize the appearance of the booking form? =

Yes, you can. We offer settings for color and font that will let you customize the form to blend seamlessly into any theme. You can additionally add custom CSS to customize the form even further.



== Screenshots ==


== Changelog ==

###1.3.9.0 ###
* Added: Classes and Group Events // SSA_PLUS
* Improved: Automatically prevent accidental whitespace in appointment type slugs

###1.3.8.6 ###
* Fixed: Issue switching from availability blocks to start times

###1.3.8.5 ###
* Added: Date range filter for list of appointments
* Improved: Compatibility with installations with WP core files in a different directory
* Improved: Compatibility with some shared hosts

###1.3.8.4 ###
* Added: CSS class in the booking form for the appointment type being booked
* Fixed: Stripe payments bug affecting appointment types using capacity
* Fixed: Bug preventing CSV export of appointments when customer information contained special characters
* Fixed: Bug affecting some sites where times in the past might show up as available

###1.3.8.3 ###
* Added: Developer setting to enqueue SSA scripts on all pages (needed for some sites loading the booking form with AJAX)
* Added: More styling options to the booking form's gutenberg block
* Fixed: Error affecting the editing experience for notifications on some sites
* Fixed: Conflict with WP Rocket lazy loading

###1.3.8.2 ###
* Fixed: SSA Divi module only worked with Divi Builder plugin and not the Divi theme

###1.3.8.1 ###
* Improved: Elementor integration now has more styling options
* Fixed: Error affecting the admin appointment filtering on some sites
* Fixed: Errors on sites where REST API is blocked

###1.3.8.0 ###
* Added: Allow multiple simultaneous bookings of the same appointment type // SSA_PLUS
* Added: Ability to export appointments to CSV
* Added: Ability to filter appointment views by status and type
* Added: Language packs for Hungarian, Turkish, Russian, and Estonian
* Added: Developer settings screen for beta/developer settings
* Added: Divi modules for embedding booking forms and upcoming appointments
* Improved: Support for embedding booking form in Elementor popup
* Improved: Beaver Builder module has more options and settings
* Improved: Minified unsupported.js
* Improved: New option for embedding booking form (API)
* Improved: Added CSS classes for more flexibility in styling booking form
* Fixed: Stripe SDK updated // SSA_PRO
* Fixed: Load local copies of Google fonts and icons in booking form
* Fixed: Back button bug in booking form integration
* Fixed: Accessible labels for phone number fields in booking forms

###1.3.7.5 ###
* Improved: Formidable Forms integration: localized date formatting // SSA_PLUS
* Improved: Updated Stripe API integration // SSA_PRO

###1.3.7.3 ###
* Fixed: markup for the booking form and confirmation screen

###1.3.7.2 ###
* Fixed: Non-breaking space entitites inserted into subject line of notifications
* Improved: Consistent markup for the booking form and confirmation screen

###1.3.7.1 ###
* Added: Support for defining set start times for booking appointments
* Improved: Send customer name to MailChimp // SSA_PLUS
* Improved: Provide filter for MailChimp field mapping // SSA_PLUS

###1.3.6.10 ###
* Improved: Better accessibility for edit buttons on settings screen
* Improved: Updated version of Material Icon font
* Fixed: Display icons for radio and checkbox fields on booking form // SSA_PLUS
* Fixed: Made more strings translatable
* Fixed: Browser autofill interfering with phone number validation when booking appointments

###1.3.6.9 ###
* Fixed: Outlook bug caused by X-WR-CALNAME tag in ICS files
* Fixed: Google Font dependency causing slow load times on some sites

###1.3.6.8 ###
* Added: Swedish (Svenska) translation

###1.3.6.7 ###
* Added: Danish (Dansk) translation

###1.3.6.6 ###
* Improved: Set booking app frame to noindex

###1.3.6.5 ###
* Fixed: Appointment type availability not editable for customers using translated date/time strings
* Fixed: Typo in translated strings

###1.3.6.4 ###
* Improved: Translations for German (Formal) and Spanish (Venezuela)

###1.3.6.3 ###
* Improved: Hide timezone warning for locked timezones on appointment types
* Improved: Made two additional strings translatable
* Fixed: Remove conflict with the LanguageTool browser addon when editing notifications

###1.3.6.2 ###
* Added: Italian translation
* Fixed: Back button functionality in booking form

###1.3.6.1 ###
* Fixed: Permissions on a couple API endpoints

###1.3.6.0 ###
* Added: Integration with Members plugin for advanced custom user permissions/capabilities https://wordpress.org/plugins/members/
* Improved: Translate day and month names properly in notifications
* Improved: Performance of admin app

###1.3.5.3 ###
* Improved: Automatic translation for default date format in email and SMS notifications

###1.3.5.2 ###
* Improved: Better automatic translation for default date formats in non-English languages

###1.3.5.1 ###
* Improved: Decreased load time for booking form

###1.3.5.0 ###
* Added: Additional CSS class for styling

###1.3.4.0 ###
* Added: Integration with Formidable Forms // SSA_PLUS
* Fixed: Checkbox field type throwing Twig error in Notifications // SSA_PLUS
* Fixed: Custom style not applied for appointment type focus in Firefox
* Fixed: Form labels not translatable for default fields // SSA_PLUS
* Fixed: Conflict with Mesmerize theme
* Improved: Contrast between available and unavailable days in monthly booking view

###1.3.3.1 ###
* Fixed: Incorrect timezone showing in notifications for some users

###1.3.3.0 ###
* Added: Easily download and install language packs, even if they aren't complete
* Fixed: Catch fatal Twilio error // SSA_PRO
* Fixed: Layout for loading settings
* Improved: Submitting support ticket through the plugin

###1.3.2.3 ###
* Improved: Layout of booking form

###1.3.2.2 ###
* Improved: Update list of Google fonts in style settings
* Improved: Better UX for admin and booking apps on slow servers
* Fixed: SMS appearing disabled after saving Twilio credentials // SSA_PRO
* Fixed: Issues with embedding multiple booking forms on the same page

###1.3.2.1 ###
* Improved: Better handling for description of notifications on appointment types // SSA_PRO
* Improved: Show a warning if an offset is selected instead of a timezone
* Improved: Hide the reorder button for appointment types if there's only one
* Fixed: Disabled dates in weekly view not inheriting the custom font selection in styles
* Fixed: Improper validation applied to text fields named 'Phone' // SSA_PLUS

###1.3.1.0 ###
* Added: Event tracking – SSA can post events to your analytics or advertising tools as your customers go through the booking process // SSA_PRO
* Added: Elementor integration – new SSA widgets so you can easily drag booking forms (or a summary of the logged in user's upcoming appointments) right onto your page
* Added: Logged in users' information is automatically filled in the booking form (except for administrators since you are likely booking an appointment for your customer)
* Added: [ssa_upcoming_appointments] shortcode to display the logged in user's upcoming appointments
* Improved: Deleting appointment types API call works now on servers that restrict use of the DELETE method
* Improved: Assign customer's user id based on email address even when user is logged out
* Improved: Display of timezone in customer email notifications
* Fixed: Google Calendar validation error when credentials are empty // SSA_PRO

###1.3.0.2 ###
* Improved: Timezone detection and display
* Improved: Stripe: customize the description that shows on your customers' credit card statement // SSA_PRO
* Fixed: Extra check for appointment availability before processing Stripe payment // SSA_PRO
* Fixed: Stripe payment confirmation not redirecting to thank you // SSA_PRO

###1.3.0.1 ###
* Fixed: Unable to add an appointment type if the wizard is skipped
* Fixed: Unable to enable Google Calendar in the wizard // SSA_PLUS
* Fixed: Unable to manage and add customer information fields in the wizard
* Fixed: Custom styles apply to date selection buttons when booking an appointment

###1.3.0.0 ###
* Added: Send custom SMS reminders and notifications // SSA_PRO
* Added: Preview for notifications - see what your notifications will look like
* Improved: Enable notifications to be disabled - all or individually
* Improved: Easier keyboard focus for selecting a date in the booking form
* Improved: Mailchimp authorization UX // SSA_PLUS
* Fixed: Bug when cloning notifications

###1.2.9.1 ###
* Improved: More robust availability checking for people with lots of booked appointments
* Fixed: Bug that prevented being able to delete appointment types

###1.2.9.0 ###
* Added: Custom reminder notifications (send X days before/after appointment is booked or appointment start time) // SSA_PRO
* Added: Ability to reorder appointment types

###1.2.8.0 ### // SSA_PRO
* Added: Paypal payments // SSA_PRO

###1.2.7.4 ###
* Improved: Added pagination for appointments for admins
* Improved: Added shortcode instructions to final wizard screen
* Improved: Accessibility fixes for the booking form
* Improved: Swapped out user Gravatars for Unavatars
* Improved: Subtle transitions to booking form
* Fixed: Mobile view of availability for admins not fully visible

###1.2.7.3 ###
* Improved: Buffers won't availability of booking at business start time
* Improved: Upgrade to latest version of Select2
* Improved: Validating booking notice and advance so that booking notice can't be greater than advance
* Improved: Validate URLs for web hooks // SSA_PRO
* Improved: Styles updated for radio buttons and checkboxes on booking form // SSA_PLUS
* Improved: Show customer and author of appointments if applicable (if they have a WP user account)
* Improved: Added ability to delete appointments from the appointment detail view with warning message
* Fixed: Add to Calendar button on booking form using SSA business name instead of site name


###1.2.7.1 ###
* Fixed: Bug introduced by WooCommerce v3.6

###1.2.7.0 ###
* Added: UI for customizing both customer and admin notification emails, plus ability to send different notification messages per appointment type
* Added: Ability to add custom instructions to custom customer information fields // SSA_PLUS
* Fixed: When using advanced scheduling options, an availability window less than 24 hours resulted in no appointment times being available to book

###1.2.6.12 ###
* Fixed: Issue showing homepage instead of booking form on some sites

###1.2.6.11 ###
* Improved: Google Calendar authorization // SSA_PLUS
* Improved: WPML compatibility with ?lang= permalink structure // SSA_PLUS
* Fixed: 404 error (or showing homepage) instead of booking app when using certain themes/plugins
* Fixed: Google Calendar bug when excluding a deleted calendar from availability // SSA_PLUS
* Fixed: Bug with availability in booking form
* Fixed: Potential conflict with 2 booking forms embedded in the same page

###1.2.6.8 ###
* Improved: Handling of errors preventing appointment booking
* Fixed: Conflict with plugins that incorrectly modify admin body class

###1.2.6.7 ### // SSA_PRO
* Improved: Webhook payloads now include date "parts" for more advanced use cases // SSA_PRO

###1.2.6.6 ###
* Improved: Booking form resizing
* Improved: Show helpful message if JavaScript is disabled or browser doesn't have the capability to run SSA
* Improved: Rescheduling of appointments
* Improved: When rescheduling, link to (now) canceled old appointment
* Improved: When rescheduling appointment, link payment made on original appointment for tracking // SSA_PRO

###1.2.6.5 ###
* Improved: Remove restricted width of admin scrollbar
* Fixed: Conflict with other MailChimp plugins // SSA_PLUS
* Fixed: Unable to go back to the first week when booking an appointment
* Fixed: Cancel link in confirmation email not working
* Fixed: Able to remove required email and name customer information fields


###1.2.6.4 ###
* Improved: Better messaging when there aren't any appointment types
* Improved: Set timezone to local even if wizard is skipped
* Improved: Better feedback about saving in bulk edit mode for appointment types
* Improved: Clearer choices for Google Calendar syncing // SSA_PLUS
* Improved: Better handling of email validation when booking appointment
* Fixed: Mailchimp opt-in text required when editing appointment type // SSA_PLUS
* Fixed: Google Calendar not displaying connection in wizard // SSA_PLUS

###1.2.6.3 ###
* Fixed: Issue with monthly booking display

###1.2.6.2 ###
* Added: Developer filter for advanced customization needs
* Fixed: Issue with monthly booking display

###1.2.6.0 ###
* Added: Accept payments with Stripe when appointments are booked // SSA_PRO
* Added: New timing interval so appointments can be booked every 20 minutes
* Added: New monthly view option for booking appointments
* Improved: Bulk editing
* Improved: Better messaging when deleting an appointment type
* Improved: Make more reasonable PHP recommendations on the support tab
* Improved: Require customer email address to be properly formatted as an email address
* Improved: Better explanation of appointment time taken when two customers try to book the same appointment time at the same time
* Fixed: Possibility of negative buffer times and booking notices. It turns out that time travel is dangerous.
* Fixed: Number inputs in Firefox now display at correct width
* Fixed: Handle clash between availability, blackout dates, and booking notices more gracefully
* Fixed: MailChimp opt-in box now reliably appears for customers booking appointments // SSA_PLUS

###1.2.5.0 ###
* Added: Gutenberg block for Appointment Booking Form

###1.2.4.0 ###
* Added: Show “instructions” field to customer on the appointment confirmation screen
* Added: Easy button to copy shortcode to clipboard on single-appointment edit screen
* Improved: Proactively prevent double-booking by notifying customer right after they select a time that's no longer available
* Improved: Faster cancelation process
* Improved: Bulk editing mode instructions

###1.2.3.0 ###
* Added: View individual appointment details in the admin interface
* Improved: Show warnings on modules that are enabled but not actively configured
* Fixed: Bug affecting blackout dates in some timezones
* Fixed: Email notifications containing escaped formatting when customers filled in fields with special characters

###1.2.2.0 ###
* Added: In-plugin support tools to help with troubleshooting server issues and sending debug information to the SSA team
* Improved: Performance of Availability Window
* Improved: Handle unusual timezone settings with some servers/plugins
* Improved: Prevention of double-booking on sites
* Improved: Google Calendar support for all-day events // SSA_PLUS
* Updated: Google Calendar setup documentation // SSA_PLUS
* Fixed: Sometimes days without any availability showed up as clickable in the week view
* Fixed: Google Calendar authentication during the setup process // SSA_PLUS

###1.2.1.8 ### // SSA_PLUS
* Improved: Google Calendar error checking // SSA_PLUS

###1.2.1.7 ###
* Improved: Added error handling for PHP 5.3.x (SSA requires 5.5.9+, 7.x recommended)

###1.2.1.6 ###
* Improved: Error messages
* Improved: Prevention of double-booking on sites with heavy traffic

###1.2.1.5 ###
* Improved: Availability start date

###1.2.1.4 ###
* Fixed: Bug with availability (only affecting dates more than 7 weeks away)

###1.2.1.3 ###
* Improved: Added Custom CSS field to the "Styles" settings for the booking app
* Improved: Newly created appointment types now show up without having to refresh the page
* Fixed: Bug with availability windows for far-off future events

###1.2.1.2 ###
* Fixed: Incompatibility with older PHP versions (introduced in 1.2.1.1)

###1.2.1.1 ###
* Added: Ability to define the timezone as locked or localized (on your appointment types) which makes it easy to differentiate between phone calls/virtual meetings vs. physical/in-person events
* Added: Support for custom CSS files for admin-app and booking-app (which can be overridden in your theme)
* Improved: Spacing in booking form
* Improved: Interationalized email notifications

###1.2.0.3 ###
* Improved: Compatibility with servers that have aggressive caching
* Fixed: Broken "Back to WordPress" button on sites installed in a subdirectory
* Fixed: Another incompatibility with some themes/hosts that have custom handling for 404 pages

###1.2.0.2 ###
* Fixed: Incompatibility with some themes/hosts that have custom handling for 404 pages

###1.2.0.1 ###
* Added: Webhooks module to enable better integration & automation with other platforms // SSA_PRO
* Improved: Added Appt Type Instructions field to email notifications

###1.1.9.5 ###
* Added: WP Filter so developers can modify the email notification template
* Improved: Added Appt Type Instructions field to email notifications

###1.1.9.4 ###
* Improved: Layout of appointment type selection in the booking interface
* Improved: Setup Wizard process
* Improved: More readable appointment type slugs when cloning an existing type
* Improved: Moved Availability Window settings into the Availability section
* Improved: Availability & Booking windows default to midnight instead of current time
* Fixed: Bug enforcing booking window restrictions, even when Advanced Scheduling Options is disabled

###1.1.9.3 ###
* Fixed: Bug preventing some Elementor installations from showing the booking form properly

###1.1.9.2 ###
* Fixed: Bug affecting Blackout Dates functionality

###1.1.9.1 ###
* Added: Filter to allow separate availability for each appointment type (to allow custom implementations via code)

###1.1.9.0 ###
* Added: Advanced scheduling options for appointment types - set a booking window, set an availability window, and limit how far into the future customers can book appointments
* Improved: Layout of appointment type selection in the booking form
* Improved: Appointment types get better slugs
* Improved: Booking form will now fast-forward to the first available appointment slot if it's after the current week
* Fixed: If there is only one appointment type, skip appointment type selection in the booking form


= 1.1.8.3 ### // SSA_PLUS =
* Fixed: rest_no_route error on some environments // SSA_PLUS

= 1.1.8.2 =
* Improved: Handling appointments connected to now-deleted appointment types
* Improved: Display of timezone in admin view
* Improved: Display of appointment types list when there are no appointment types defined

= 1.1.8.1 =
* Improved: Appointment Cancelation/Reschedule interface
* Improved: Cancelation synchronization with Google Calendar // SSA_PLUS
* Fixed: PHP Notices (non-fatal errors)

= 1.1.7.1 ### // SSA_PLUS =
* Fixed: Bug affecting "checkbox" customer fields // SSA_PLUS

= 1.1.7.0 =
* Added: Easily customize the styling and appearance of your booking forms to match your site (in the new Style Settings)

= 1.1.6.0 =
* Improved: Better handling of auto-zoom behavior on iPhone
* Improved: Default padding & spacing around booking form
* Fixed: Changes to the date/time format didn't always save
* Fixed: Google Calendar caching times as unavailable even when module is disabled // SSA_PLUS

= 1.1.5.2 =
* Added: submenus for quicker access in wp-admin sidebar
* Improved: Compatibility with plugins that replace wp_mail() function
* Fixed: Email headers for best handling of from name and reply-to, along with support for WP Mail SMTP for custom From addresses

= 1.1.5.0 =
* Improved: Added support for custom CSS in booking app
* Improved: Use from contact name, business name, and contact email as From address for email notification to customer
* Improved: Line endings / spacing in email notifications
* Fixed: Canceled events were not always made available for booking again
* Fixed: Potential issue with height of sections when editing appointment types
* Fixed: Partially cut off bulk editing menu

= 1.1.4.1 =
* Improved: Layout of booking app for mobile devices
* Fixed: Warnings in javascript console

= 1.1.4.0 =
* Added: Two modes of bulk editing for appointment types - quickly edit multiple appointment types
* Improved: Appointment type editing - easier to navigate all available options
* Fixed: Manage License button text now translateable // SSA_PLUS
* Improved: Linked MailChimp API instructions to make it easier to find your API Key // SSA_PLUS
* Fixed: Allow 0 value for pre- and post- appointment buffers and for booking notice

= 1.1.3.1 =
* Fixed http/https error on some WordPress server setups

= 1.1.3.0 =
* Added "Instructions" field to appointment types so you can tell your customers information about the appointment (like where to meet, if you'll contact them or they should contact you at the appointment time, etc.) This field also shows up in the notes for the event they add to their calendar.

= 1.1.2.3 ### // SSA_PLUS =
* Fixed: Mailchimp issue with some existing appointment types // SSA_PLUS

= 1.1.2.1 =
* Added: MailChimp integration // SSA_PLUS
* Added: Bulk editing
* Improved: Appointment Type Editing

= 1.1.1.0 =
* Improved custom field options for collecting information from customers // SSA_PLUS
* Fixed issue with availability when user had blacked out every day in the next 30 days

= 1.1.0.1 =
* Improved handling when trying to book an appointment when there is no availability in next 30 days
* Fixed issue where the availability time increment wasn't reflected on the frontend booking form. (ie. show appointments available every 15/30/60 minutes)

= 1.1.0.0 =
* Added: Customize the information you collect from customers

= 1.0.9.9 =
* Fixed issue with logged-in non-admin users unable to book some appointment types
* Fixed issue with Google Calendar syncing for newly-booked events // SSA_PLUS

= 1.0.9.7 =
* Fixed blackout dates applying even when feature is disabled
* Fixed issue with setup wizard

= 1.0.9.6 =
* Fixed issue preventing some appointments from showing up in admin view

= 1.0.9.4 =
* Fixed issue when WP is in a subdirectory instead of at the root of the domain

= 1.0.9.3 =
* Fixed API conflict with other plugins

= 1.0.9.1 =
* Added Google Calendar integration, so you never double-book again. Automatically exclude events from your Google Calendar so nobody can book during that time // SSA_PLUS
* Overhaul to Admin UI and Booking UI
* New wizard for easy setup

= 1.0.8.0 =
* Added Blackout Dates feature, so you can block off vacation days that you don't want to book any appointments



