document.addEventListener('DOMContentLoaded', () => {
 
    // Función para traer estudiantes

    async function fetchStudents() {

        try {

            const response = await fetch(`${NeurekaGS.rest_url}neureka/v1/students`, {

                method: 'GET',

                headers: { 'X-WP-Nonce': NeurekaGS.nonce }

            });

            const students = await response.json();
 
            // Mostrar en tu dashboard

            const container = document.querySelector('.class-content p');

            container.innerHTML = students.length

                ? students.map(s => `<div class="student">${s.name} (${s.email})</div>`).join('')

                : 'No students yet.';

        } catch (err) {

            console.error('Error fetching students:', err);

        }

    }
 
    // Llamar al cargar la página

    fetchStudents();
 
    // Función para agregar un estudiante

    const form = document.querySelector('.class-add-student form');

    form.addEventListener('submit', async (e) => {

        e.preventDefault();

        const name = form.students.value;
 
        try {

            const response = await fetch(`${NeurekaGS.rest_url}neureka/v1/students`, {

                method: 'POST',

                headers: {

                    'X-WP-Nonce': NeurekaGS.nonce,

                    'Content-Type': 'application/json'

                },

                body: JSON.stringify({ name })

            });
 
            const result = await response.json();

            console.log('Student added:', result);
 
            // Limpiar input y actualizar lista

            form.students.value = '';

            fetchStudents();
 
        } catch (err) {

            console.error('Error adding student:', err);

        }

    });
 
});

 