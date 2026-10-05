document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('products-container');
    const addButton = document.getElementById('add-product');
    const template = document.getElementById('product-row-template');

    if (!container || !addButton || !template) {
        return;
    }

    let index = parseInt(container.dataset.nextIndex || 1);

    addButton.addEventListener('click', () => {
        const row = template.content.cloneNode(true);

        row.querySelectorAll('[data-field]').forEach((element) => {
            const field = element.dataset.field;

            element.name = `items[${index}][${field}]`;
        });

        container.appendChild(row);

        index++;
    });

    container.addEventListener('click', (event) => {
        const button = event.target.closest('.remove-product');

        if (!button) {
            return;
        }

        const row = button.closest('.product-row');

        if (row) {
            row.remove();
        }
    });
});