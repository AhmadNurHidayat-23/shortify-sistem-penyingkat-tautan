const toast = document.querySelector('#toast');
let toastTimeout;

function notify(message) {
    clearTimeout(toastTimeout);
    toast.textContent = message;
    toast.hidden = false;
    toastTimeout = setTimeout(() => { toast.hidden = true; }, 4000);
}

async function copyLink(value) {
    if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(value);
        return;
    }

    const field = document.createElement('textarea');
    field.value = value;
    field.className = 'clipboard-field';
    field.setAttribute('readonly', '');
    document.body.append(field);
    field.select();
    try {
        if (!document.execCommand('copy')) {
            throw new Error('Salin tidak tersedia');
        }
    } finally {
        field.remove();
    }
}

document.querySelectorAll('[data-copy]').forEach((button) => {
    button.addEventListener('click', async () => {
        try {
            await copyLink(button.dataset.copy);
            notify('Tautan berhasil disalin. Siap dibagikan!');
        } catch {
            notify('Tautan gagal disalin. Pilih alamat tautan dan salin secara manual.');
        }
    });
});

const dialog = document.querySelector('#delete-dialog');
let pendingForm;

document.querySelectorAll('[data-delete-form]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        pendingForm = form;
        dialog.returnValue = 'cancel';
        dialog.showModal();
    });
});

dialog.addEventListener('close', () => {
    if (dialog.returnValue === 'confirm' && pendingForm) {
        HTMLFormElement.prototype.submit.call(pendingForm);
    }
    pendingForm = null;
});

const urlInput = document.querySelector('#original_url');
urlInput?.addEventListener('invalid', () => {
    if (urlInput.validity.valueMissing) {
        urlInput.setCustomValidity('Masukkan tautan yang ingin dipersingkat.');
    } else if (urlInput.validity.typeMismatch) {
        urlInput.setCustomValidity('Masukkan URL yang valid dengan awalan http:// atau https://.');
    }
});
urlInput?.addEventListener('input', () => urlInput.setCustomValidity(''));

document.querySelector('#shorten-form')?.addEventListener('submit', () => {
    const button = document.querySelector('#shorten-button');
    button.disabled = true;
    button.textContent = 'Memproses…';
});

window.addEventListener('pageshow', () => {
    const button = document.querySelector('#shorten-button');
    if (button) {
        button.disabled = false;
        button.innerHTML = 'Persingkat Tautan <span aria-hidden="true">↗</span>';
    }
});
