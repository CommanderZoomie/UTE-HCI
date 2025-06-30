// script.js

document.addEventListener('DOMContentLoaded', function() {

    // Open modal by id and disable background scroll
    function openModal(modalId) {
        if (!modalId) return;
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';

            if (typeof modal.focus === 'function') {
                modal.setAttribute('tabindex', '-1');
                modal.focus();
            }
        }
    }

    // Close modal by id and restore scroll
    function closeModal(modalId) {
        if (!modalId) return;
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';

            // Reset forms inside modals on close
            const forms = modal.querySelectorAll('form');
            forms.forEach(form => form.reset());

            // Clear details modal content when closing
            if (modalId === 'viewDetailsModal') {
                const details = modal.querySelector('#eventDetailsContent');
                if (details) {
                    details.innerHTML = '';
                }
            }
        }
    }

    // Populate and open Edit Event modal with event data
    window.openEditEventModal = function(eventData) {
        openModal('editEventModal');

        document.getElementById('edit_evento_id').value = eventData.evento_id || '';
        document.getElementById('edit_titulo').value = eventData.evento_titulo || '';
        document.getElementById('edit_num_invitados').value = eventData.evento_invitados || '';
        document.getElementById('edit_fecha').value = eventData.evento_fecha || '';
        document.getElementById('edit_hora').value = eventData.evento_hora ? eventData.evento_hora.substring(0,5) : '';
        document.getElementById('edit_zona_horaria').value = eventData.evento_zona_horaria || '';

        if (eventData.evento_repeticion == 1) {
            document.getElementById('edit_repeticion_si').checked = true;
        } else {
            document.getElementById('edit_repeticion_no').checked = true;
        }

        if (eventData.evento_recordatorio == 1) {
            document.getElementById('edit_recordatorio_si').checked = true;
        } else {
            document.getElementById('edit_recordatorio_no').checked = true;
        }

        document.getElementById('edit_ubicacion_id').value = eventData.evento_ubicacion || '';
        document.getElementById('edit_contacto_id').value = eventData.evento_contacto || '';
        document.getElementById('edit_tipo_evento').value = eventData.evento_tipo || '';
        document.getElementById('edit_descripcion').value = eventData.evento_descripcion || '';
    };

    // Populate and open View Details modal with event data
    window.openViewDetailsModal = function(eventData) {
        openModal('viewDetailsModal');

        const content = document.getElementById('eventDetailsContent');
        if (!content) return;

        // Create details HTML safely (you can style this as needed)
        content.innerHTML = `
            <h3>${escapeHtml(eventData.evento_titulo)}</h3>
            <p><strong>Fecha:</strong> ${escapeHtml(eventData.evento_fecha)}</p>
            <p><strong>Hora:</strong> ${escapeHtml(eventData.evento_hora)}</p>
            <p><strong>Zona Horaria:</strong> ${escapeHtml(eventData.evento_zona_horaria)}</p>
            <p><strong>Número de Invitados:</strong> ${escapeHtml(eventData.evento_invitados)}</p>
            <p><strong>Repetición:</strong> ${eventData.evento_repeticion == 1 ? 'Sí' : 'No'}</p>
            <p><strong>Recordatorio:</strong> ${eventData.evento_recordatorio == 1 ? 'Sí' : 'No'}</p>
            <p><strong>Ubicación:</strong> ${escapeHtml(eventData.ubicacion || 'N/A')}</p>
            <p><strong>Contacto:</strong> ${escapeHtml((eventData.contacto_nombre || '') + ' ' + (eventData.contacto_apellido || ''))}</p>
            <p><strong>Tipo de Evento:</strong> ${escapeHtml(eventData.evento_tipo)}</p>
            <p><strong>Descripción:</strong><br>${escapeHtml(eventData.evento_descripcion).replace(/\n/g, '<br>')}</p>
        `;
    };

    // Simple HTML escape function
    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, '&amp;')
                   .replace(/</g, '&lt;')
                   .replace(/>/g, '&gt;')
                   .replace(/"/g, '&quot;')
                   .replace(/'/g, '&#039;');
    }

    // Confirm deletion
    window.confirmDelete = function(eventoId, titulo) {
        if (confirm(`¿Está seguro de eliminar el evento "${titulo}"? Esta acción no se puede deshacer.`)) {
            window.location.href = `events.php?action=delete&id=${eventoId}`;
        }
    };

    // Hook open modal buttons (if any)
    const openModalButtons = document.querySelectorAll('[data-modal-target]');
    openModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-target');
            openModal(modalId);
        });
    });

    // Hook close modal buttons
    const closeButtons = document.querySelectorAll('.close-button, .btn-cancel[data-modal-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-close') || this.closest('.modal').id;
            closeModal(modalId);
        });
    });

    // Close modal when clicking outside content
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    });

    // Close modal on Escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModal = document.querySelector('.modal.show');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });
});
