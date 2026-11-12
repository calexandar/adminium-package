const TOOLBAR = [
    [{ header: [1, 2, 3, 4, 5, 6, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ color: [] }, { background: [] }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    [{ indent: '-1' }, { indent: '+1' }],
    [{ align: [] }],
    ['blockquote', 'code-block'],
    ['link', 'image', 'video'],
    ['clean'],
];

function isEmpty(quill) {
    return quill.getText().trim() === '' && !quill.root.querySelector('img, iframe, video, table');
}

function initEditor(textarea) {
    if (!(textarea instanceof HTMLTextAreaElement) || textarea.dataset.richEditor === 'true') {
        return null;
    }

    if (typeof window.Quill === 'undefined') {
        return null;
    }

    textarea.dataset.richEditor = 'true';
    textarea.classList.add('hidden');

    const wrapper = document.createElement('div');
    wrapper.className = 'rich-editor-wrapper';
    textarea.after(wrapper);

    const modes = document.createElement('div');
    modes.className = 'rich-editor-modes';
    modes.innerHTML = [
        '<button type="button" class="rich-editor-mode is-active" data-editor-mode="visual">Visual</button>',
        '<button type="button" class="rich-editor-mode" data-editor-mode="html">HTML</button>',
    ].join('');

    wrapper.appendChild(modes);

    const container = document.createElement('div');
    container.className = 'rich-editor';
    wrapper.appendChild(container);

    const sourceInput = document.createElement('textarea');
    sourceInput.className = 'rich-editor-source';
    sourceInput.spellcheck = false;
    wrapper.appendChild(sourceInput);

    const hint = document.createElement('p');
    hint.className = 'rich-editor-hint';
    hint.textContent = 'Raw HTML is saved as-is. Editing in Visual mode may remove custom classes.';
    wrapper.appendChild(hint);

    const counter = document.createElement('div');
    counter.className = 'rich-editor-counter';
    counter.setAttribute('aria-live', 'polite');
    wrapper.appendChild(counter);

    const quill = new window.Quill(container, {
        theme: 'snow',
        placeholder: textarea.getAttribute('placeholder') || '',
        modules: {
            toolbar: TOOLBAR,
        },
    });

    let mode = 'visual';
    let raw = textarea.value.trim();

    const limit = Number.parseInt(textarea.dataset.richCounter, 10);

    const updateCounter = () => {
        const length = Array.from(raw).length;

        if (Number.isFinite(limit)) {
            const left = limit - length;

            counter.textContent = left < 0 ? `${Math.abs(left)} characters over` : `${left} characters left`;
            counter.classList.toggle('is-over', left < 0);
        } else {
            counter.textContent = `${length} characters`;
            counter.classList.remove('is-over');
        }
    };

    if (raw.trim() !== '') {
        quill.clipboard.dangerouslyPasteHTML(raw, 'silent');
    }

    const setMode = (next) => {
        if (next === mode) {
            return;
        }

        if (next === 'html') {
            sourceInput.value = raw;
        } else {
            raw = sourceInput.value;
            textarea.value = raw;

            quill.setContents([], 'silent');

            if (raw.trim() !== '') {
                quill.clipboard.dangerouslyPasteHTML(raw, 'silent');
            }
        }

        mode = next;
        wrapper.classList.toggle('is-source', mode === 'html');

        modes.querySelectorAll('[data-editor-mode]').forEach((button) => {
            button.classList.toggle('is-active', button.dataset.editorMode === mode);
        });

        updateCounter();
    };

    modes.querySelectorAll('[data-editor-mode]').forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            setMode(button.dataset.editorMode);
        });
    });

    sourceInput.addEventListener('input', () => {
        raw = sourceInput.value;
        textarea.value = raw;
        updateCounter();
    });

    const toolbar = quill.getModule('toolbar');

    toolbar.addHandler('image', () => {
        const url = window.prompt('Image URL');

        if (!url) {
            return;
        }

        const range = quill.getSelection(true) || { index: quill.getLength() };
        quill.insertEmbed(range.index, 'image', url, 'user');
        quill.setSelection(range.index + 1, 0, 'silent');
    });

    quill.on('text-change', (delta, oldDelta, changeSource) => {
        if (changeSource !== 'user') {
            return;
        }

        raw = isEmpty(quill) ? '' : quill.root.innerHTML;
        textarea.value = raw;
        updateCounter();
    });

    const form = textarea.closest('form');

    form?.addEventListener('submit', () => {
        if (mode === 'html') {
            raw = sourceInput.value;
        }

        textarea.value = raw;
    });

    textarea.value = raw;
    updateCounter();

    return quill;
}

const pending = Array.isArray(window.CKEDITOR?.queue) ? window.CKEDITOR.queue : [];

window.CKEDITOR = {
    replace(target) {
        const element = typeof target === 'string' ? document.getElementById(target) : target;

        return element ? initEditor(element) : null;
    },
};

pending.forEach((target) => window.CKEDITOR.replace(target));