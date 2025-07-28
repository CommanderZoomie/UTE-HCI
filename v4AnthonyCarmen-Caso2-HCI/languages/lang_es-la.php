<?php
//languages/lang_es-la.php
// Spanish - Latin American (es-la)


// includes/header.php
$lang = [
    // Menu - Navigation Bar
    'inicio' => 'Inicio',
    'eventos' => 'Eventos',
    'ubicaciones' => 'Ubicaciones',
    'contactos' => 'Contactos',
    'logout' => 'Cerrar sesión',
    'ver_eventos' => 'Ver Eventos',
    'contactanos' => 'Contáctanos',
    'login' => 'Iniciar sesión',

    // Extra (optional/future)
    'ayuda' => '¿Necesitas ayuda?',
    'idioma' => 'Idioma',
    'modo_oscuro' => 'Modo Oscuro',
    'modo_claro' => 'Modo Claro',
    'bienvenido' => 'Bienvenido',
];


// includes/footer.php
$lang += [
    // Author info and copyright
    'autor_nombre' => 'Anthony Carmen',
    'autor_email' => 'anthony.carmen@ute.edu.ec',
    'autor_ubicacion' => 'UTE - Quito, Ecuador',
    'derechos' => 'Todos los derechos reservados 2025.',
];


// main/inicio.php
$lang += [
    // Hero Section
    'inicio_hero_title' => 'Bienvenido a EduEventos',
    'inicio_hero_description' => 'Organiza y gestiona tus eventos educativos de manera eficiente.',
    'inicio_get_started_btn' => 'Comenzar',
    'inicio_hero_image_alt' => 'Organiza Eventos Educativos',
    'inicio_about_title' => 'Sobre Nosotros',
    'inicio_about_subtitle' => 'Tu Plataforma Integral de Gestión de Eventos',
    'inicio_about_paragraph' => 'EduEventos es una herramienta diseñada para simplificar la planificación, ejecución y seguimiento de todo tipo de eventos educativos, desde conferencias hasta talleres.',
    'inicio_view_events_btn' => 'Ver Eventos',
    'inicio_upcoming_events_title' => 'Próximos eventos',
];


// main/contactanos.php
$lang += [
    // Error Messages
    'error_required_fields' => 'Por favor, complete los campos obligatorios (Nombre, Correo Electrónico, Mensaje).',
    'error_invalid_email' => 'Formato de correo electrónico inválido.',
    'error_db_connection' => 'Error de conexión a la base de datos: La conexión PDO no está disponible. Por favor, verifica tu archivo db.php.',
    'error_db_insert' => 'Hubo un error al guardar tu mensaje en la base de datos. Por favor, inténtalo de nuevo más tarde.',
    'error_db' => 'Error de base de datos',

    // Success Messages
    'success_thanks_contact' => '¡Gracias por tu interés! Estamos en contacto pronto.',

    // Form Labels and Texts
    'contact_heading' => 'Déjanos tu información de contacto.',
    'label_first_name' => 'Nombres',
    'label_last_name' => 'Apellidos',
    'label_phone' => 'Número Telefónico',
    'label_email' => 'Correo Electrónico',
    'label_country' => 'País',
    'label_institution' => 'Institución Educativa',
    'label_message' => 'Mensaje',
    'button_send' => 'Enviar',

    // Additional Info
    'visit_us' => 'O visítanos en persona',
    'map_alt' => 'Ubicación en el mapa',
    'address_line1' => 'Universidad UTE',
    'address_line2' => 'Campus Occidental',
    'address_line3' => 'Av. Mariana de Jesús,',
    'address_line4' => 'Quito 170129',
    'address_line5' => 'Bloque Z - Oficina 8',
    'contact_person' => 'Ing. Anthony Carmen',
    'office_hours' => '09h00 a 16h00',
    'work_days' => 'Lunes a Viernes',
];


// main/ver_eventos.php
$lang += [
    // Page Title
    'ver_eventos_title' => 'Ver Eventos',

    // Table Headers
    'table_header_id' => 'ID',
    'table_header_titulo' => 'Título',
    'table_header_fecha' => 'Fecha',
    'table_header_hora' => 'Hora',
    'table_header_ubicacion' => 'Ubicación',
    'table_header_contacto' => 'Contacto',
    'table_header_acciones' => 'Acciones',

    // Messages
    'no_events_message' => 'No hay información.',
    'error_loading_events' => 'Error al cargar eventos',

    // Modal Titles and Labels
    'modal_detalles_evento' => 'Detalles del Evento',
    'modal_label_id' => 'ID',
    'modal_label_invitados' => 'Invitados',
    'modal_label_fecha' => 'Fecha',
    'modal_label_hora' => 'Hora',
    'modal_label_zona_horaria' => 'Zona Horaria',
    'modal_label_repeticion' => 'Repetición',
    'modal_label_recordatorio' => 'Recordatorio',
    'modal_label_ubicacion' => 'Ubicación',
    'modal_label_contacto' => 'Contacto',
    'modal_label_tipo' => 'Tipo de Evento',
    'modal_label_descripcion' => 'Descripción',

    // Modal Buttons
    'modal_button_cerrar' => 'Cerrar',
];


// platform/dashboard.php
$lang += [
    'dashboard_welcome_title' => 'Bienvenida al dashboard de EduEventos',
    'dashboard_intro_text' => 'Tu sistema de gestión está dividido en 3 secciones, las cuales puedes:',
    'action_view_info' => 'Ver Información Adicional',
    'action_edit_info' => 'Editar Información',
    'action_delete_row' => 'Eliminar fila',
    'action_add_new' => 'Agregar Nuevo',
    'action_add_new_details' => '(Evento/Ubicación/Contacto)',
    'section_events_title' => 'Eventos',
    'section_events_desc_1' => 'Registrar eventos como conferencias, ferias, talleres y seminarios.',
    'section_events_desc_2' => 'Información: título, # de invitados, fecha, hora, zona horaria, repetición, recordatorio, contacto del evento, tipo de evento, ubicación y descripción.',
    'manage_events_btn' => 'Gestionar Eventos',
    'section_locations_title' => 'Ubicaciones',
    'section_locations_desc_1' => 'Registrar lugares donde realizar los eventos.',
    'section_locations_desc_2' => 'Información: título, dirección, ciudad, país y link de google maps.',
    'manage_locations_btn' => 'Gestionar Ubicaciones',
    'section_contacts_title' => 'Contactos',
    'section_contacts_desc_1' => 'Registrar contactos, los cuales que son responsables de los eventos.',
    'section_contacts_desc_2' => 'Información: nombres, apellidos, institución educativa, país, CI, número de teléfono, correo electrónico, y subir su foto de perfil.',
    'manage_contacts_btn' => 'Gestionar Contactos',
    'footer_admin_contact' => '*Contacta tu administrador por información adicional o para soporte TI',
];


// platform/events.php
$lang += [
    // Messages (for the PHP logic)
    'error_event_required_fields' => 'Error: Campos obligatorios de evento incompletos.',
    'event_added_success' => 'Evento agregado exitosamente.',
    'error_add_event' => 'Error al agregar evento: ', // Note: trailing space for concatenation
    'event_updated_success' => 'Evento actualizado exitosamente.',
    'error_update_event' => 'Error al actualizar evento: ', // Note: trailing space for concatenation
    'error_no_event_id' => 'Error: ID de evento no especificado para edición.',
    'event_deleted_success' => 'Evento eliminado exitosamente.',
    'error_delete_event' => 'Error al eliminar evento: ', // Note: trailing space for concatenation
    'error_load_events' => 'Error al cargar eventos: ', // Note: trailing space for concatenation

    // Page Titles and Buttons
    'manage_events_title' => 'Gestión de Eventos',
    'add_new_event_btn' => 'Agregar Nuevo Evento',

    // Table Headers
    'table_header_title' => 'Título',
    'table_header_date' => 'Fecha',
    'table_header_time' => 'Hora',
    'table_header_location' => 'Ubicación',
    'table_header_contact' => 'Contacto',
    'no_events_registered' => 'No hay eventos registrados.',

    // Image alts/titles (JS functions as well)
    // Re-using common keys from contacts.php where applicable (e.g., 'table_header_id', 'table_header_actions', 'view_details_alt', 'view_details_title', 'not_applicable_abbr', 'cancel_btn', 'add_btn', 'save_changes_btn', 'close_modal_aria')
    'view_event_details_alert' => 'Ver detalles del evento', // Note: no trailing space, event title is appended directly
    'edit_event_alt' => 'Editar',
    'edit_event_title' => 'Editar Evento',
    'delete_event_alt' => 'Eliminar',
    'delete_event_title' => 'Eliminar Evento',
    'confirm_delete_event_prompt' => '¿Está seguro de eliminar el evento "', // Note: trailing quote and space
    'confirm_delete_event_warning' => 'Esta acción no se puede deshacer.',

    // Modal elements (Add/Edit Event)
    'add_event_modal_title' => 'Nuevo Evento',
    'edit_event_modal_title' => 'Editar Evento',
    'label_title' => 'Título:',
    'label_num_guests' => 'Número de invitados:',
    'label_date' => 'Fecha:',
    'label_time' => 'Hora:',
    'label_timezone' => 'Zona Horaria:',
    'label_repetition' => 'Repetición:',
    'yes' => 'SI',
    'no' => 'NO',
    'label_reminder' => 'Recordatorio:',
    'label_location' => 'Ubicación:',
    'select_location_option' => 'Seleccionar Ubicación',
    'label_event_contact' => 'Contacto de evento:',
    'select_contact_option' => 'Seleccionar Contacto',
    'label_event_type' => 'Tipo de Evento:',
    'label_description' => 'Descripción:',

    // Date/Time formats for consistency (optional, but good practice)
    'date_format' => 'd M Y', // e.g., 27 Jul 2025
    'time_format' => 'H:i',   // e.g., 16:45
];


// platform/locations.php
$lang += [
    // Locations Page Specific Translations
    // Messages (for the PHP logic)
    'error_location_required_fields' => 'Error: Campos obligatorios de ubicación incompletos.',
    'location_added_success' => 'Ubicación agregada exitosamente.',
    'error_add_location' => 'Error al agregar ubicación: ', // Note: trailing space for concatenation
    'location_updated_success' => 'Ubicación actualizada exitosamente.',
    'error_update_location' => 'Error al actualizar ubicación: ', // Note: trailing space for concatenation
    'error_no_location_id' => 'Error: ID de ubicación no especificado para edición.',
    'location_deleted_success' => 'Ubicación eliminada exitosamente.',
    'error_delete_location' => 'Error al eliminar ubicación: ', // Note: trailing space for concatenation
    'error_load_locations' => 'Error al cargar ubicaciones: ', // Note: trailing space for concatenation

    // Page Titles and Buttons
    'manage_locations_title' => 'Gestión de Ubicaciones',
    'add_new_location_btn' => 'Agregar Nueva Ubicación',

    // Table Headers
    'table_header_address' => 'Dirección',
    'table_header_city' => 'Ciudad',
    'table_header_gmaps_link' => 'Link de Google Maps',
    'no_locations_registered' => 'No hay ubicaciones registradas.',

    // Links/Image alts/titles (JS functions as well)
    // Re-using common keys from contacts/events where applicable (e.g., 'table_header_id', 'table_header_title', 'table_header_country', 'table_header_actions', 'view_details_alt', 'view_details_title', 'not_applicable_abbr', 'cancel_btn', 'add_btn', 'save_changes_btn', 'close_modal_aria')
    'view_map_link' => 'Ver Mapa',
    'view_location_details_alert' => 'Ver detalles de la ubicación:', // Note: no trailing space, title is appended directly
    'edit_location_alt' => 'Editar',
    'edit_location_title' => 'Editar Ubicación',
    'delete_location_alt' => 'Eliminar',
    'delete_location_title' => 'Eliminar Ubicación',
    'confirm_delete_location_prompt' => '¿Estás seguro de que quieres eliminar la ubicación "', // Note: trailing quote and space
    'confirm_delete_location_warning' => 'Esto puede afectar eventos asociados.',

    // Modal elements (Add/Edit Location)
    'add_location_modal_title' => 'Nueva Ubicación',
    'edit_location_modal_title' => 'Editar Ubicación',
    'label_address' => 'Dirección:',
    'label_city' => 'Ciudad:',
    'label_gmaps_link' => 'Link de Google Maps:',
];


// platform/contactos.php (contacts.php)
$lang += [
    'error_required_fields' => 'Error: Nombres, Apellidos y Correo son campos obligatorios.',
    'error_invalid_email' => 'Error: Formato de correo electrónico inválido.',
    'error_photo_upload' => 'Error al subir la foto.',
    'error_photo_type' => 'Solo se permiten archivos JPG, JPEG, PNG y GIF para la foto.',
    'contact_added_success' => 'Contacto agregado exitosamente.',
    'error_add_contact' => 'Error al agregar contacto: ', // Note: trailing space for concatenation
    'contact_updated_success' => 'Contacto actualizado exitosamente.',
    'error_update_contact' => 'Error al actualizar contacto: ', // Note: trailing space for concatenation
    'error_no_contact_id' => 'Error: ID de contacto no especificado para edición.',
    'contact_deleted_success' => 'Contacto eliminado exitosamente.',
    'error_delete_contact' => 'Error al eliminar contacto: ', // Note: trailing space for concatenation
    'error_load_contacts' => 'Error al cargar contactos: ', // Note: trailing space for concatenation

    // Page Titles and Buttons
    'manage_contacts_title' => 'Gestión de Contactos',
    'add_new_contact_btn' => 'Agregar Nuevo Contacto',

    // Table Headers
    'table_header_id' => 'ID',
    'table_header_first_names' => 'Nombres',
    'table_header_last_names' => 'Apellidos',
    'table_header_institution' => 'Institución Educativa',
    'table_header_country' => 'País',
    'table_header_id_card' => 'CI',
    'table_header_phone' => 'Teléfono',
    'table_header_email' => 'Correo',
    'table_header_photo' => 'Foto',
    'table_header_actions' => 'Acciones',
    'no_contacts_registered' => 'No hay contactos registrados.',

    // Image alts/titles (JS functions as well)
    'contact_photo_alt' => 'Foto de Contacto',
    'not_applicable_abbr' => 'N/A',
    'view_details_alt' => 'Ver',
    'view_details_title' => 'Ver Detalles',
    'view_contact_details_alert' => 'Ver detalles del contacto:', // Note: trailing space for concatenation
    'edit_contact_alt' => 'Editar',
    'edit_contact_title' => 'Editar Contacto',
    'delete_contact_alt' => 'Eliminar',
    'delete_contact_title' => 'Eliminar Contacto',
    'confirm_delete_contact_prompt' => '¿Está seguro de que desea eliminar el contacto:', // Note: trailing space for concatenation
    'confirm_delete_contact_warning' => 'Esta acción no se puede deshacer.',

    // Modal elements (Add Contact)
    'close_modal_aria' => 'Cerrar', // For ARIA label on close button
    'add_contact_modal_title' => 'Nuevo Contacto',
    'label_first_names' => 'Nombres:',
    'label_last_names' => 'Apellidos:',
    'label_institution' => 'Institución Educativa:',
    'label_country' => 'País:',
    'label_id_card' => 'CI (Cédula de Identidad):',
    'label_phone' => 'Número Telefónico:',
    'label_email' => 'Correo Electrónico:',
    'label_photo' => 'Foto:',
    'cancel_btn' => 'Cancelar',
    'add_btn' => 'Agregar',

    // Modal elements (Edit Contact)
    'edit_contact_modal_title' => 'Editar Contacto',
    'label_photo_optional' => 'Foto (dejar en blanco para mantener la actual):',
    'no_current_photo' => 'No hay foto actual.',
    'save_changes_btn' => 'Guardar Cambios',
];


// security/login.php
$lang += [
    // Page Title and Form Labels
    'login_title' => 'Iniciar sesión en la plataforma',
    'login_user' => 'Usuario',
    'login_pass' => 'Contraseña',
    'login_button' => 'Ingresar',

    // Links and Notices
    'login_forgot' => '¿Olvidó Usuario o Contraseña?',
    'login_contact_admin' => 'Contacta tu administrador *',

    // Error Messages
    'login_error_empty' => 'Por favor, ingrese usuario y contraseña.',
    'login_error_invalid' => 'Usuario o contraseña incorrectos.',
];


// security/logout.php
$lang += [
    // Messages on logout
    'logout_thanks' => 'Gracias por usar EduEventos',
    'logout_message' => 'Has cerrado sesión correctamente.',
    'logout_home' => 'Inicio',
    'logout_login' => 'Iniciar Sesión',
];
