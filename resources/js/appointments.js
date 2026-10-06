document.addEventListener('DOMContentLoaded', () => {

    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
    const barberSelect = document.getElementById('barber');
    const dateInput = document.getElementById('date');
    const slotsDiv = document.getElementById('slots');
    const scheduledAt = document.getElementById('scheduled_at');

    const servicesCount = document.getElementById('services-count');
    const servicesDuration = document.getElementById('services-duration');
    const servicesTotal = document.getElementById('services-total');

    if (!serviceCheckboxes.length || !dateInput || !slotsDiv) return;

    const oldScheduledAt = scheduledAt.value;

    function updateServicesSummary() {

        const selectedServices = Array.from(serviceCheckboxes)
            .filter(checkbox => checkbox.checked);

        if (!selectedServices.length) {
            servicesCount.textContent = 'Seleccioná uno o más servicios';
            servicesDuration.textContent = '';
            servicesTotal.textContent = '';

            return;
        }

        const totalDuration = selectedServices.reduce((total, checkbox) => {
            return total + Number(checkbox.dataset.duration || 0);
        }, 0);

        const totalPrice = selectedServices.reduce((total, checkbox) => {
            return total + Number(checkbox.dataset.price || 0);
        }, 0);

        servicesCount.textContent =
            `${selectedServices.length} ${selectedServices.length === 1 ? 'servicio seleccionado' : 'servicios seleccionados'}`;

        servicesDuration.textContent =
            `Duración: ${totalDuration} min`;

        servicesTotal.textContent =
            `Total: Gs. ${totalPrice.toLocaleString('es-PY')}`;
    }

    function loadSlots() {

        const selectedServices = Array.from(serviceCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        const barberId = barberSelect?.value;
        const date = dateInput.value;

        if (!selectedServices.length || !date) {
            scheduledAt.value = '';

            slotsDiv.innerHTML =
                '<p class="col-span-3 text-neutral-400">Seleccioná servicio y fecha</p>';

            return;
        }

        const params = new URLSearchParams();

        params.append('date', date);

        selectedServices.forEach(serviceId => {
            params.append('service_ids[]', serviceId);
        });

        if (barberId) {
            params.append('barber_id', barberId);
        }

        const url = `/turnos/slots?${params.toString()}`;

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
                                    b.classList.remove(
                                        'bg-yellow-500',
                                        'text-black'
                                    );
                                });

                            btn.classList.add(
                                'bg-yellow-500',
                                'text-black'
                            );

                            scheduledAt.value = `${date} ${slot.time}`;
                        };

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

    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateServicesSummary();
            loadSlots();
        });
    });

    barberSelect?.addEventListener('change', loadSlots);
    dateInput.addEventListener('change', loadSlots);

    updateServicesSummary();

    if (dateInput.value) {
        loadSlots();
    }
});
