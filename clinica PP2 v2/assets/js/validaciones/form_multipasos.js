function calcularEdad(fechaString) {
        if (!fechaString) return 0;
        const hoy = new Date();
        const nac = new Date(fechaString);
        let edad = hoy.getFullYear() - nac.getFullYear();
        const m = hoy.getMonth() - nac.getMonth();
        if (m < 0 || (m === 0 && hoy.getDate() < nac.getDate())) {
            edad--;
        }
        return edad;
    }

    /* ---------- Mostrar / ocultar pasos y actualizar progreso ---------- */
    function mostrarPaso(paso) {
        document.querySelectorAll('.pagina').forEach(p => p.classList.remove('active'));
        const panel = document.getElementById(`pagina${paso}`);
        if (panel) panel.classList.add('active');
        actualizarProgreso(paso);
    }

    function actualizarProgreso(paso) {
        const steps = document.querySelectorAll('.progreso-item');
        steps.forEach((step, i) => {
            step.classList.remove('active','completed');
            if (i + 1 < paso) step.classList.add('completed');
            if (i + 1 === paso) step.classList.add('active');
        });
    }

    /* ---------- Manejo de errores visuales ---------- */
    function mostrarError(inputId, mensaje) {
        const input = document.getElementById(inputId);
        const err = document.getElementById(`error-${inputId}`);
        if (input) input.classList.add('error-input');
        if (err) err.innerText = mensaje;
        if (input) input.focus();
    }

    function limpiarErrores(arrIds) {
        arrIds.forEach(id => {
            const input = document.getElementById(id);
            const err = document.getElementById(`error-${id}`);
            if (input) input.classList.remove('error-input');
            if (err) err.innerText = '';
        });
    }

    /* ---------- Validacion del Paso 1 (cliente) ---------- */
    function validarPaso(paso) {
        if (paso === 1) {
            // ids reales de los inputs del paso 1
            const campos = ['nombre','apellido','fecha_nacimiento','sexo'];
            limpiarErrores(campos);

            let valid = true;
            const nombre = document.getElementById('nombre');
            const apellido = document.getElementById('apellido');
            const fecha = document.getElementById('fecha_nacimiento');
            const sexo = document.getElementById('sexo');

            if (!nombre || nombre.value.trim() === '') {
                mostrarError('nombre','El nombre es obligatorio');
                valid = false;
            }
            if (!apellido || apellido.value.trim() === '') {
                mostrarError('apellido','El apellido es obligatorio');
                valid = false;
            }
            if (!fecha || !fecha.value) {
                mostrarError('fecha_nacimiento','La fecha de nacimiento es obligatoria');
                valid = false;
            } else {
                const edad = calcularEdad(fecha.value);
                if (edad < 18) {
                    mostrarError('fecha_nacimiento','Debe ser mayor de 18 años');
                    valid = false;
                }
            }
            if (!sexo || sexo.value === '') {
                mostrarError('sexo','Seleccione una opción');
                valid = false;
            }

            if (valid) {
                mostrarPaso(2);
            }
        }
    }

    /* ---------- Validacion antes de submit (opcional) ---------- */
    document.getElementById('registroForm').addEventListener('submit', function(e) {
        // Validación simple del paso 2 en cliente (mejora UX; el servidor sigue validando)
        limpiarErrores(['nombre_usuario','email','password']);
        let ok = true;
        const username = document.getElementById('nombre_usuario');
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        if (!username || username.value.trim().length < 3) {
            mostrarError('nombre_usuario','El nombre de usuario debe tener al menos 3 caracteres');
            ok = false;
        }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
            mostrarError('email','Ingrese un email válido');
            ok = false;
        }
        if (!password || password.value.length < 6) {
            mostrarError('password','La contraseña debe tener al menos 6 caracteres');
            ok = false;
        }

        if (!ok) {
            e.preventDefault();
            // Mostrar el paso 2 si estaban en otro
            mostrarPaso(2);
        }
    });