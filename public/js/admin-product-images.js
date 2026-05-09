document.addEventListener('DOMContentLoaded', () => {
    const uploadSections = document.querySelectorAll('[data-image-inputs]');
    const categorySelects = document.querySelectorAll('[data-category-select]');
    const removeImageForm = document.getElementById('admin-remove-image-form');
    const removeImageButtons = document.querySelectorAll('[data-remove-product-image]');

    uploadSections.forEach((section) => {
        const list = section.querySelector('[data-image-inputs-list]');
        const addButton = section.querySelector('[data-add-image-input]');
        const startReplaceButton = section.querySelector('[data-start-image-replace]');

        if (!list || !addButton) {
            return;
        }

        const maxImages = Number(section.dataset.maxImages || 5);
        const inputPrefix = addButton.dataset.inputPrefix || 'product-image';
        const startsEmpty = section.dataset.startEmpty === 'true';

        const createField = (number, required = false) => {
            const field = document.createElement('div');
            field.className = 'form-field';

            const label = document.createElement('label');
            label.htmlFor = `${inputPrefix}-${number}`;
            label.textContent = `Photo ${number}`;

            const input = document.createElement('input');
            input.id = `${inputPrefix}-${number}`;
            input.name = 'images[]';
            input.type = 'file';
            input.accept = 'image/*';
            input.required = required;

            field.appendChild(label);
            field.appendChild(input);

            return field;
        };

        const updateButtonState = () => {
            const count = list.querySelectorAll('input[type="file"]').length;
            addButton.hidden = count === 0 || count >= maxImages;
        };

        addButton.addEventListener('click', () => {
            const currentCount = list.querySelectorAll('input[type="file"]').length;

            if (currentCount >= maxImages) {
                updateButtonState();
                return;
            }

            list.appendChild(createField(currentCount + 1));
            updateButtonState();
        });

        if (startReplaceButton) {
            startReplaceButton.addEventListener('click', () => {
                if (list.querySelectorAll('input[type="file"]').length === 0) {
                    list.appendChild(createField(1, true));
                }

                startReplaceButton.hidden = true;
                updateButtonState();
            });
        }

        if (startsEmpty) {
            addButton.hidden = true;
        } else {
            updateButtonState();
        }
    });

    categorySelects.forEach((categorySelect) => {
        const form = categorySelect.closest('form');
        const lineSelect = form?.querySelector('[data-line-select]');

        if (!lineSelect) {
            return;
        }

        const syncLineOptions = () => {
            const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
            const selectedCategorySlug = selectedCategory?.dataset.categorySlug || '';

            Array.from(lineSelect.options).forEach((option) => {
                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                option.hidden = option.dataset.categorySlug !== selectedCategorySlug;
            });

            const currentLineOption = lineSelect.options[lineSelect.selectedIndex];
            const currentLineMatches = currentLineOption && !currentLineOption.hidden;

            if (!currentLineMatches) {
                lineSelect.value = '';
            }
        };

        categorySelect.addEventListener('change', syncLineOptions);
        syncLineOptions();
    });

    if (removeImageForm) {
        const imagePathInput = removeImageForm.querySelector('input[name="image_path"]');

        removeImageButtons.forEach((button) => {
            button.addEventListener('click', () => {
                if (!imagePathInput) {
                    return;
                }

                imagePathInput.value = button.dataset.imagePath || '';
                removeImageForm.submit();
            });
        });
    }
});
