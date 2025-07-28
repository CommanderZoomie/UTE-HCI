<?php
//languages/lang_en-us.php
// English - United States (en-us)


// includes/header.php
$lang = [
    // Menu - Navigation Bar
    'inicio' => 'Home',
    'eventos' => 'Events',
    'ubicaciones' => 'Locations',
    'contactos' => 'Contacts',
    'logout' => 'Logout',
    'ver_eventos' => 'View Events',
    'contactanos' => 'Contact Us',
    'login' => 'Login',

    // Extra (optional/future)
    'ayuda' => 'Need Help?',
    'idioma' => 'Language',
    'modo_oscuro' => 'Dark Mode',
    'modo_claro' => 'Light Mode',
    'bienvenido' => 'Welcome',
];


// includes/footer.php
$lang += [
    // Author info and copyright
    'autor_nombre' => 'Anthony Carmen',
    'autor_email' => 'anthony.carmen@ute.edu.ec',
    'autor_ubicacion' => 'UTE - Quito, Ecuador',
    'derechos' => 'All rights reserved 2025.',
];


// main/inicio.php
$lang += [
    // Hero Section
    'inicio_hero_title' => 'Welcome to EduEventos',
    'inicio_hero_description' => 'Organize and manage your educational events efficiently.',
    'inicio_get_started_btn' => 'Get Started',
    'inicio_hero_image_alt' => 'Organize Educational Events',
    'inicio_about_title' => 'About Us',
    'inicio_about_subtitle' => 'Your Comprehensive Event Management Platform',
    'inicio_about_paragraph' => 'EduEventos is a tool designed to simplify the planning, execution, and tracking of all types of educational events, from conferences to workshops.',
    'inicio_view_events_btn' => 'View Events',
    'inicio_upcoming_events_title' => 'Upcoming Events',
];


// main/contactanos.php
$lang += [
    // Error Messages
    'error_required_fields' => 'Please fill in the required fields (Name, Email, Message).',
    'error_invalid_email' => 'Invalid email format.',
    'error_db_connection' => 'Database connection error: PDO connection not available. Please check your db.php file.',
    'error_db_insert' => 'There was an error saving your message to the database. Please try again later.',
    'error_db' => 'Database error',

    // Success Messages
    'success_thanks_contact' => 'Thank you for your interest! We will be in touch soon.',

    // Form Labels and Texts
    'contact_heading' => 'Leave us your contact information.',
    'label_first_name' => 'First Name',
    'label_last_name' => 'Last Name',
    'label_phone' => 'Phone Number',
    'label_email' => 'Email',
    'label_country' => 'Country',
    'label_institution' => 'Educational Institution',
    'label_message' => 'Message',
    'button_send' => 'Send',

    // Additional Info
    'visit_us' => 'Or visit us in person',
    'map_alt' => 'Location on map',
    'address_line1' => 'UTE University',
    'address_line2' => 'Western Campus',
    'address_line3' => 'Av. Mariana de Jesús,',
    'address_line4' => 'Quito 170129',
    'address_line5' => 'Block Z - Office 8',
    'contact_person' => 'Eng. Anthony Carmen',
    'office_hours' => '09:00 to 16:00',
    'work_days' => 'Monday to Friday',
];


// main/ver_eventos.php
$lang += [
    // Page Title
    'ver_eventos_title' => 'View Events',

    // Table Headers
    'table_header_id' => 'ID',
    'table_header_titulo' => 'Title',
    'table_header_fecha' => 'Date',
    'table_header_hora' => 'Time',
    'table_header_ubicacion' => 'Location',
    'table_header_contacto' => 'Contact',
    'table_header_acciones' => 'Actions',

    // Messages
    'no_events_message' => 'No information available.',
    'error_loading_events' => 'Error loading events',

    // Modal Titles and Labels
    'modal_detalles_evento' => 'Event Details',
    'modal_label_id' => 'ID',
    'modal_label_invitados' => 'Guests',
    'modal_label_fecha' => 'Date',
    'modal_label_hora' => 'Time',
    'modal_label_zona_horaria' => 'Time Zone',
    'modal_label_repeticion' => 'Repetition',
    'modal_label_recordatorio' => 'Reminder',
    'modal_label_ubicacion' => 'Location',
    'modal_label_contacto' => 'Contact',
    'modal_label_tipo' => 'Event Type',
    'modal_label_descripcion' => 'Description',

    // Modal Buttons
    'modal_button_cerrar' => 'Close',
];


// platform/dashboard.php
$lang += [
    'dashboard_welcome_title' => 'Welcome to the EduEventos dashboard', 
    'dashboard_intro_text' => 'Your management system is divided into 3 sections, which you can:',
    'action_view_info' => 'View Additional Information',
    'action_edit_info' => 'Edit Information',
    'action_delete_row' => 'Delete row',
    'action_add_new' => 'Add New',
    'action_add_new_details' => '(Event/Location/Contact)',
    'section_events_title' => 'Events',
    'section_events_desc_1' => 'Register events such as conferences, fairs, workshops, and seminars.',
    'section_events_desc_2' => 'Information: title, # of guests, date, time, timezone, repetition, reminder, event contact, event type, location, and description.',
    'manage_events_btn' => 'Manage Events',
    'section_locations_title' => 'Locations',
    'section_locations_desc_1' => 'Register places to hold events.',
    'section_locations_desc_2' => 'Information: title, address, city, country, and Google Maps link.',
    'manage_locations_btn' => 'Manage Locations',
    'section_contacts_title' => 'Contacts',
    'section_contacts_desc_1' => 'Register contacts, who are responsible for the events.',
    'section_contacts_desc_2' => 'Information: first names, last names, educational institution, country, ID, phone number, email, and upload their profile picture.',
    'footer_admin_contact' => '*Contact your administrator for additional information or IT support',
];


// platform/events.php
$lang += [
    // Messages (for the PHP logic)
    'error_event_required_fields' => 'Error: Mandatory event fields are incomplete.',
    'event_added_success' => 'Event added successfully.',
    'error_add_event' => 'Error adding event: ',
    'event_updated_success' => 'Event updated successfully.',
    'error_update_event' => 'Error updating event: ',
    'error_no_event_id' => 'Error: Event ID not specified for editing.',
    'event_deleted_success' => 'Event deleted successfully.',
    'error_delete_event' => 'Error deleting event: ',
    'error_load_events' => 'Error loading events: ',

    // Page Titles and Buttons
    'manage_events_title' => 'Event Management',
    'add_new_event_btn' => 'Add New Event',

    // Table Headers
    'table_header_title' => 'Title',
    'table_header_date' => 'Date',
    'table_header_time' => 'Time',
    'table_header_location' => 'Location',
    'table_header_contact' => 'Contact',
    'no_events_registered' => 'No events registered.',

    // Image alts/titles (JS functions as well)
    // Re-using common keys from contacts.php where applicable (e.g., 'table_header_id', 'table_header_actions', 'view_details_alt', 'view_details_title', 'not_applicable_abbr', 'cancel_btn', 'add_btn', 'save_changes_btn', 'close_modal_aria')
    'view_event_details_alert' => 'View event details for', // Note: no trailing space, event title is appended directly
    'edit_event_alt' => 'Edit',
    'edit_event_title' => 'Edit Event',
    'delete_event_alt' => 'Delete',
    'delete_event_title' => 'Delete Event',
    'confirm_delete_event_prompt' => 'Are you sure you want to delete the event "',
    'confirm_delete_event_warning' => 'This action cannot be undone.',

    // Modal elements (Add/Edit Event)
    'add_event_modal_title' => 'New Event',
    'edit_event_modal_title' => 'Edit Event',
    'label_title' => 'Title:',
    'label_num_guests' => 'Number of guests:',
    'label_date' => 'Date:',
    'label_time' => 'Time:',
    'label_timezone' => 'Timezone:',
    'label_repetition' => 'Repetition:',
    'yes' => 'YES',
    'no' => 'NO',
    'label_reminder' => 'Reminder:',
    'label_location' => 'Location:',
    'select_location_option' => 'Select Location',
    'label_event_contact' => 'Event Contact:',
    'select_contact_option' => 'Select Contact',
    'label_event_type' => 'Event Type:',
    'label_description' => 'Description:',

    // Date/Time formats for consistency (optional, but good practice)
    'date_format' => 'M d, Y', // e.g., Jul 27, 2025
    'time_format' => 'h:i A',   // e.g., 04:45 PM
];


// platform/locations.php
$lang += [
    // Locations Page Specific Translations
    // Messages (for the PHP logic)
    'error_location_required_fields' => 'Error: Mandatory location fields are incomplete.',
    'location_added_success' => 'Location added successfully.',
    'error_add_location' => 'Error adding location: ',
    'location_updated_success' => 'Location updated successfully.',
    'error_update_location' => 'Error updating location: ',
    'error_no_location_id' => 'Error: Location ID not specified for editing.',
    'location_deleted_success' => 'Location deleted successfully.',
    'error_delete_location' => 'Error deleting location: ',
    'error_load_locations' => 'Error loading locations: ',

    // Page Titles and Buttons
    'manage_locations_title' => 'Location Management',
    'add_new_location_btn' => 'Add New Location',

    // Table Headers
    'table_header_address' => 'Address',
    'table_header_city' => 'City',
    'table_header_gmaps_link' => 'Google Maps Link',
    'no_locations_registered' => 'No locations registered.',

    // Links/Image alts/titles (JS functions as well)
    // Re-using common keys from contacts/events where applicable (e.g., 'table_header_id', 'table_header_title', 'table_header_country', 'table_header_actions', 'view_details_alt', 'view_details_title', 'not_applicable_abbr', 'cancel_btn', 'add_btn', 'save_changes_btn', 'close_modal_aria')
    'view_map_link' => 'View Map',
    'view_location_details_alert' => 'View location details for:',
    'edit_location_alt' => 'Edit',
    'edit_location_title' => 'Edit Location',
    'delete_location_alt' => 'Delete',
    'delete_location_title' => 'Delete Location',
    'confirm_delete_location_prompt' => 'Are you sure you want to delete the location "',
    'confirm_delete_location_warning' => 'This may affect associated events.',

    // Modal elements (Add/Edit Location)
    'add_location_modal_title' => 'New Location',
    'edit_location_modal_title' => 'Edit Location',
    'label_address' => 'Address:',
    'label_city' => 'City:',
    'label_gmaps_link' => 'Google Maps Link:',
];


// platform/contactos.php (contacts.php)
$lang += [
    // Messages (for the PHP logic)
    'error_required_fields' => 'Error: First Name, Last Name, and Email are required fields.',
    'error_invalid_email' => 'Error: Invalid email format.',
    'error_photo_upload' => 'Error uploading photo.',
    'error_photo_type' => 'Only JPG, JPEG, PNG, and GIF files are allowed for photos.',
    'contact_added_success' => 'Contact added successfully.',
    'error_add_contact' => 'Error adding contact: ',
    'contact_updated_success' => 'Contact updated successfully.',
    'error_update_contact' => 'Error updating contact: ',
    'error_no_contact_id' => 'Error: Contact ID not specified for editing.',
    'contact_deleted_success' => 'Contact deleted successfully.',
    'error_delete_contact' => 'Error deleting contact: ',
    'error_load_contacts' => 'Error loading contacts: ',

    // Page Titles and Buttons
    'manage_contacts_title' => 'Contact Management',
    'add_new_contact_btn' => 'Add New Contact',

    // Table Headers
    'table_header_id' => 'ID',
    'table_header_first_names' => 'First Names',
    'table_header_last_names' => 'Last Names',
    'table_header_institution' => 'Educational Institution',
    'table_header_country' => 'Country',
    'table_header_id_card' => 'ID Card',
    'table_header_phone' => 'Phone',
    'table_header_email' => 'Email',
    'table_header_photo' => 'Photo',
    'table_header_actions' => 'Actions',
    'no_contacts_registered' => 'No contacts registered.',

    // Image alts/titles (JS functions as well)
    'contact_photo_alt' => 'Contact Photo',
    'not_applicable_abbr' => 'N/A',
    'view_details_alt' => 'View',
    'view_details_title' => 'View Details',
    'view_contact_details_alert' => 'View contact details for:',
    'edit_contact_alt' => 'Edit',
    'edit_contact_title' => 'Edit Contact',
    'delete_contact_alt' => 'Delete',
    'delete_contact_title' => 'Delete Contact',
    'confirm_delete_contact_prompt' => 'Are you sure you want to delete the contact:',
    'confirm_delete_contact_warning' => 'This action cannot be undone.',

    // Modal elements (Add Contact)
    'close_modal_aria' => 'Close',
    'add_contact_modal_title' => 'New Contact',
    'label_first_names' => 'First Names:',
    'label_last_names' => 'Last Names:',
    'label_institution' => 'Educational Institution:',
    'label_country' => 'Country:',
    'label_id_card' => 'ID Card (CI):',
    'label_phone' => 'Phone Number:',
    'label_email' => 'Email Address:',
    'label_photo' => 'Photo:',
    'cancel_btn' => 'Cancel',
    'add_btn' => 'Add',

    // Modal elements (Edit Contact)
    'edit_contact_modal_title' => 'Edit Contact',
    'label_photo_optional' => 'Photo (leave blank to keep current):',
    'no_current_photo' => 'No current photo.',
    'save_changes_btn' => 'Save Changes',
];


// security/login.php
$lang += [
    // Page Title and Form Labels
    'login_title' => 'Login to the platform',
    'login_user' => 'Username',
    'login_pass' => 'Password',
    'login_button' => 'Login',

    // Links and Notices
    'login_forgot' => 'Forgot Username or Password?',
    'login_contact_admin' => 'Contact your administrator *',

    // Error Messages
    'login_error_empty' => 'Please enter username and password.',
    'login_error_invalid' => 'Incorrect username or password.',
];


// security/logout.php
$lang += [
    // Messages on logout
    'logout_thanks' => 'Thank you for using EduEventos',
    'logout_message' => 'You have successfully logged out.',
    'logout_home' => 'Home',
    'logout_login' => 'Login',
];
