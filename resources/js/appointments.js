document.addEventListener('DOMContentLoaded', () => {

    const serviceSelect = document.getElementById('service');
    const barberSelect  = document.getElementById('barber');
    const dateInput     = document.getElementById('date');
    const slotsDiv      = document.getElementById('slots');
    const scheduledAt   = document.getElementById('scheduled_at');

    // Si no estamos en la vista de turnos → salir
    if (!serviceSelect || !dateInput || !slotsDiv) return;

    const oldScheduledAt = scheduledAt.value;

    function loadSlots() {
        const serviceId = serviceSelect.value;
        const barberId  = barberSelect?.value;
        const date      = dateInput.value;

        if (!serviceId || !date) {
            slotsDiv.innerHTML =
                '<p class="col-span-3 text-neutral-400">Seleccioná servicio y fecha</p>';
            return;
        }

        let url = `/turnos/slots?service_id=${serviceId}&date=${date}`;

        if (barberId) {
            url += `&barber_id=${barberId}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(slots => {

                slotsDiv.innerHTML = '';

                if (!slots.length) {
                    scheduledAt.value = '';

                    slotsDiv.innerHTML =
                        '<p class="col-span-3 text-neutral-400">No hay horarios disponibles</p>';

                    return;
                }

                slots.forEach(slot => {

                    const btn = document.createElement('button');

                    btn.type = 'button';
                    btn.textContent = slot.time;

                    if (!slot.available) {

                        btn.disabled = true;

                        btn.className =
                            'bg-neutral-700 text-neutral-500 py-2 rounded-lg cursor-not-allowed';

                    } else {

                        btn.className =
                            'bg-neutral-900 border border-neutral-700 hover:bg-yellow-500 hover:text-black py-2 rounded-lg transition';

                        btn.onclick = () => {

                            document
                                .querySelectorAll('#slots button')
                                .forEach(b => {
                                    b.classList.remove('bg-yellow-500', 'text-black');
                                });

                            btn.classList.add('bg-yellow-500', 'text-black');

                            scheduledAt.value = `${date} ${slot.time}`;
                        };

                        // Recuperar horario seleccionado anteriormente
                        if (oldScheduledAt === `${date} ${slot.time}`) {

                            btn.classList.add(
                                'bg-yellow-500',
                                'text-black'
                            );

                            scheduledAt.value = oldScheduledAt;
                        }
                    }

                    slotsDiv.appendChild(btn);
                });
            })
            .catch(() => {

                scheduledAt.value = '';

                slotsDiv.innerHTML =
                    '<p class="col-span-3 text-red-500">Error cargando horarios</p>';
            });
    }

    serviceSelect.addEventListener('change', loadSlots);
    barberSelect?.addEventListener('change', loadSlots);
    dateInput.addEventListener('change', loadSlots);

    // Cargar horarios automáticamente si ya tenemos servicio y fecha después de volver de una validación
    if (serviceSelect.value && dateInput.value) {
        loadSlots();
    }
});