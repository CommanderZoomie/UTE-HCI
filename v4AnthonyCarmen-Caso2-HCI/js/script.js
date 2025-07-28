// js/script.js
// Ensure the DOM is fully loaded before running the script
document.addEventListener('DOMContentLoaded', function() {
    const themeToggleBtn = document.getElementById('toggle-theme');
    const body = document.body;

    function applyTheme(theme) {
        if (theme === 'light') {
            body.classList.add('light-mode');
            themeToggleBtn.innerHTML = '☀️'; // Sun icon for light mode
            themeToggleBtn.setAttribute('aria-pressed', 'true');
        } else {
            body.classList.remove('light-mode');
            themeToggleBtn.innerHTML = '🌙'; // Moon icon for dark mode
            themeToggleBtn.setAttribute('aria-pressed', 'false');
        }
    }

    // --- IMPORTANT INITIALIZATION LOGIC ---
    // 1. Get the theme currently rendered by PHP (from the body class).
    const phpRenderedTheme = body.classList.contains('light-mode') ? 'light' : 'dark';

    // 2. Apply this theme to ensure the button icon is correct
    //    This also acts as a fallback if localStorage somehow doesn't have a value.
    applyTheme(phpRenderedTheme);

    // 3. Ensure localStorage is in sync with the PHP-rendered theme.
    //    This is crucial for the inline script on subsequent page loads.
    localStorage.setItem('theme', phpRenderedTheme);


    // Send theme change to server via AJAX
    function saveThemeToServer(theme) {
        fetch('/settings/set_theme.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'theme=' + encodeURIComponent(theme),
            credentials: 'same-origin' // Important for PHP session cookie
        })
        .then(response => {
            if (!response.ok) { // Check if the HTTP status is not 2xx
                // If set_theme.php sends an error status, parse and throw
                return response.json().then(errData => { throw new Error(errData.message || 'Server error'); });
            }
            return response.json(); // Parse the JSON response
        })
        .then(data => {
            if (data.status === 'success') {
                console.log('Theme saved successfully to session:', data.theme);
                // Also save to localStorage AFTER successful server update
                // This keeps localStorage consistent with the server-side session.
                localStorage.setItem('theme', data.theme);
            } else {
                console.error('Server reported an error saving theme:', data.message);
                // Optionally: If server failed, you might want to revert the UI theme
                // applyTheme(theme === 'light' ? 'dark' : 'light');
            }
        })
        .catch(err => {
            console.error('Network or server error saving theme:', err);
            // Optionally: Inform the user or revert UI on network errors
            // alert('Could not save theme preference. Please try again.');
        });
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            const newTheme = body.classList.contains('light-mode') ? 'dark' : 'light';
            applyTheme(newTheme); // Apply immediately on click for responsiveness
            saveThemeToServer(newTheme); // Send the new theme to server and localStorage
        });
    }

    // --- Global Modal Functions ---

    /**
     * Opens a modal by setting its display style to 'flex' and managing ARIA attributes and focus.
     * @param {string} modalId The ID of the modal element to open.
     */
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex'; // Set to 'flex' for centering with align-items/justify-content
            modal.setAttribute('aria-hidden', 'false');

            // Focus trap setup
            const focusableElementsString = 'a[href], area[href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), button:not([disabled]), iframe, object, embed, [tabindex="0"], [contenteditable]';
            const focusableElements = modal.querySelectorAll(focusableElementsString);
            if (focusableElements.length) {
                focusableElements[0].focus(); // Focus the first focusable element
            }

            // Trap focus within the modal
            function trapFocus(event) {
                const focusable = Array.from(focusableElements);
                const firstFocusable = focusable[0];
                const lastFocusable = focusable[focusable.length - 1];

                if (event.key === 'Tab' || event.keyCode === 9) {
                    if (event.shiftKey) { // shift + tab
                        if (document.activeElement === firstFocusable) {
                            event.preventDefault();
                            lastFocusable.focus();
                        }
                    } else { // tab
                        if (document.activeElement === lastFocusable) {
                            event.preventDefault();
                            firstFocusable.focus();
                        }
                    }
                }
                if (event.key === 'Escape' || event.keyCode === 27) {
                    closeModal(modalId);
                }
            }

            modal.addEventListener('keydown', trapFocus);
            // Store the handler so it can be removed later
            modal._trapFocusHandler = trapFocus;

            // Close on click outside modal content
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        }
    };

    /**
     * Closes a modal by setting its display style to 'none' and managing ARIA attributes.
     * @param {string} modalId The ID of the modal element to close.
     */
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');

            // Remove focus trap listener
            if (modal._trapFocusHandler) {
                modal.removeEventListener('keydown', modal._trapFocusHandler);
                delete modal._trapFocusHandler;
            }
        }
    };

    // Event listeners for all close buttons (common for all modals)
    document.querySelectorAll('.close-button, .btn-cancel[data-modal-close]').forEach(button => {
        button.addEventListener('click', function () {
            const modalId = this.dataset.modalClose || (this.closest('.modal') ? this.closest('.modal').id : null);
            if (modalId) closeModal(modalId);
        });
    });

    // Event listeners for buttons that open modals (common pattern)
    document.querySelectorAll('[data-modal-target]').forEach(button => {
        button.addEventListener('click', function() {
            const targetModalId = this.dataset.modalTarget;
            if (targetModalId) {
                openModal(targetModalId);
            }
        });
    });

    // --- Specific Modal Handlers (can be defined globally or within relevant pages if needed) ---

    // For View Event Details Modal (from ver_events.php and events.php)
    window.showEventDetails = function(eventData) {
        // These language variables will be passed from PHP via the onclick attribute
        const yes = eventData.lang_yes || 'Yes';
        const no = eventData.lang_no || 'No';
        const notApplicable = eventData.lang_na || 'N/A';
        const langCode = eventData.lang_code || 'en-US';

        document.getElementById('detail_titulo').innerText = eventData.evento_titulo || notApplicable;
        document.getElementById('detail_id').innerText = eventData.evento_id || notApplicable;
        document.getElementById('detail_invitados').innerText = eventData.evento_invitados ?? '0'; // Use ?? for null/undefined check

        if(eventData.evento_fecha) {
            // Combine date and time for correct Date object parsing
            let d = new Date(eventData.evento_fecha + 'T' + eventData.evento_hora);
            document.getElementById('detail_fecha').innerText = d.toLocaleDateString(langCode, { day: '2-digit', month: 'short', year: 'numeric' });
        } else {
            document.getElementById('detail_fecha').innerText = notApplicable;
        }

        document.getElementById('detail_hora').innerText = eventData.evento_hora ? eventData.evento_hora.substring(0,5) : notApplicable;
        document.getElementById('detail_zona_horaria').innerText = eventData.evento_zona_horaria || notApplicable;
        document.getElementById('detail_repeticion').innerText = (eventData.evento_repeticion == 1) ? yes : no;
        document.getElementById('detail_recordatorio').innerText = (eventData.evento_recordatorio == 1) ? yes : no;
        document.getElementById('detail_ubicacion').innerText = eventData.ubicacion || notApplicable;

        let contacto = ((eventData.contacto_nombre || '') + ' ' + (eventData.contacto_apellido || '')).trim();
        document.getElementById('detail_contacto').innerText = contacto || notApplicable;

        document.getElementById('detail_tipo').innerText = eventData.evento_tipo || notApplicable;
        document.getElementById('detail_descripcion').innerText = eventData.evento_descripcion || notApplicable;

        openModal('viewEventModal');
    };

    // For Add Event Modal
    window.resetAddEventForm = function() {
        document.getElementById('addEventForm').reset();
        // Manually set default radio buttons and timezone if needed, as form.reset might not revert them
        document.getElementById('add_repeticion_no').checked = true;
        document.getElementById('add_recordatorio_no').checked = true;
        document.getElementById('add_zona_horaria').value = 'UTC-5'; // Assuming this is your default
    };

    // For Edit Event Modal
    window.openEditEventModal = function(eventData) {
        // Populate form fields
        document.getElementById('edit_evento_id').value = eventData.evento_id || '';
        document.getElementById('edit_titulo').value = eventData.titulo || '';
        document.getElementById('edit_num_invitados').value = eventData.num_invitados ?? '';
        document.getElementById('edit_fecha').value = eventData.fecha || '';
        document.getElementById('edit_hora').value = eventData.hora ? eventData.hora.substring(0, 5) : '';
        document.getElementById('edit_zona_horaria').value = eventData.zona_horaria || '';

        // Set radio buttons
        document.getElementById('edit_repeticion_si').checked = (eventData.repeticion == 1);
        document.getElementById('edit_repeticion_no').checked = (eventData.repeticion == 0);

        document.getElementById('edit_recordatorio_si').checked = (eventData.recordatorio == 1);
        document.getElementById('edit_recordatorio_no').checked = (eventData.recordatorio == 0);

        // Set dropdowns
        // Ensure options are loaded in PHP for these dropdowns
        document.getElementById('edit_ubicacion_id').value = eventData.ubicacion_id || '';
        document.getElementById('edit_contacto_id').value = eventData.contacto_id || '';
        
        document.getElementById('edit_tipo_evento').value = eventData.tipo_evento || '';
        document.getElementById('edit_descripcion').value = eventData.descripcion || '';

        openModal('editEventModal');
    };

    // --- Custom Confirmation Modal for Delete Actions ---
    let confirmCallback = null; // Stores the function to call on confirmation

    /**
     * Opens a generic confirmation modal.
     * @param {string} title The title of the confirmation.
     * @param {string} message The message to display.
     * @param {string} confirmText The text for the confirm button.
     * @param {string} cancelText The text for the cancel button.
     * @param {function} callback The function to execute if confirmed.
     */
    window.confirmAction = function(title, message, confirmText, cancelText, callback) {
        document.getElementById('confirmActionTitle').innerText = title;
        document.getElementById('confirmActionMessage').innerText = message;
        document.getElementById('confirmActionButton').innerText = confirmText;
        document.getElementById('cancelActionButton').innerText = cancelText;

        confirmCallback = callback; // Store the callback

        openModal('confirmActionModal');
    };

    // Event listener for the confirm button in the custom modal
    document.getElementById('confirmActionButton').addEventListener('click', function() {
        if (confirmCallback) {
            confirmCallback(); // Execute the stored callback
        }
        closeModal('confirmActionModal');
    });

    // Event listener for the cancel button in the custom modal
    document.getElementById('cancelActionButton').addEventListener('click', function() {
        closeModal('confirmActionModal');
    });

    // Specific delete confirmation for events (called from PHP)
    window.confirmDeleteEvent = function(eventoId, titulo, confirmPrompt, confirmWarning, confirmBtnText, cancelBtnText) {
        const title = confirmPrompt + `"${titulo}"?`;
        const message = confirmWarning;
        const callback = () => {
            window.location.href = `events.php?action=delete&id=${eventoId}`;
        };
        confirmAction(title, message, confirmBtnText, cancelBtnText, callback);
    };

}); // End DOMContentLoaded
