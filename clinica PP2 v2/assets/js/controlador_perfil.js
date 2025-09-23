 document.addEventListener('DOMContentLoaded', function() {
            const perfilId = json_encode($perfil_id);
            const doctorContainer = document.getElementById('doctor-container');
            const pacienteContainer = document.getElementById('paciente-container');
            const adminContainer = document.getElementById('admin-container');

            if (perfilId == 1) {
                pacienteContainer.style.display = 'block';
            } else if (perfilId == 2) {
                adminContainer.style.display = 'block';
            } else if (perfilId == 3) {
                doctorContainer.style.display = 'block';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });

        //hay que arreglar esto porque no lo encuentra-no lo lee