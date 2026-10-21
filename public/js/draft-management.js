document.querySelectorAll('[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
});

document.querySelectorAll('.table-action-file').forEach((input) => {
    input.addEventListener('change', () => {
        const fileName = input.files && input.files.length > 0 ? input.files[0].name : 'No file chosen';
        const label = input.closest('.table-action-form')?.querySelector('.file-label');

        if (label) {
            label.textContent = fileName;
        }
    });
});

const administrationHospitalSelect = document.querySelector('#administration-hospital-select');
const administrationChecklistSelect = document.querySelector('#administration-checklist-select');

if (administrationHospitalSelect && administrationChecklistSelect) {
    const filterChecklistOptions = () => {
        const selectedHospitalId = administrationHospitalSelect.value;

        administrationChecklistSelect.disabled = selectedHospitalId === '';

        const placeholder = administrationChecklistSelect.querySelector('option:not([data-hospital-id])');

        if (placeholder) {
            placeholder.textContent = selectedHospitalId === ''
                ? 'Pilih rumah sakit terlebih dahulu'
                : 'Pilih jenis dokumen';
        }

        administrationChecklistSelect.querySelectorAll('option[data-hospital-id]').forEach((option) => {
            option.hidden = selectedHospitalId === '' || option.dataset.hospitalId !== selectedHospitalId;
            option.disabled = selectedHospitalId === '' || option.dataset.hospitalId !== selectedHospitalId;
        });

        const selectedOption = administrationChecklistSelect.selectedOptions[0];

        if (selectedOption?.disabled) {
            administrationChecklistSelect.value = '';
        }
    };

    administrationHospitalSelect.addEventListener('change', filterChecklistOptions);
    filterChecklistOptions();
}

document.querySelector('.mobile-menu-toggle')?.addEventListener('click', () => {
    document.body.classList.toggle('nav-open');
});

document.querySelector('.sidebar-scrim')?.addEventListener('click', () => {
    document.body.classList.remove('nav-open');
});

document.querySelectorAll('[data-open-details]').forEach((button) => {
    button.addEventListener('click', () => {
        const details = document.getElementById(button.dataset.openDetails);
        details.open = true;
        details.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
});