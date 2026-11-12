function initCharacterCounters() {
    document.querySelectorAll('[data-char-counter]').forEach((field) => {
        if (field.dataset.charCounterReady === 'true') {
            return;
        }

        const limit = Number.parseInt(field.dataset.charCounter, 10);

        if (!Number.isFinite(limit)) {
            return;
        }

        field.dataset.charCounterReady = 'true';
        field.value = field.value.trim();

        const counter = document.createElement('p');
        counter.className = 'mt-1 text-xs text-gray-500 dark:text-gray-400';
        counter.setAttribute('aria-live', 'polite');
        field.insertAdjacentElement('afterend', counter);

        const update = () => {
            const left = limit - Array.from(field.value).length;

            counter.textContent = `${left} characters left`;
            counter.className = `mt-1 text-xs ${left < 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'}`;
        };

        field.addEventListener('input', update);
        update();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCharacterCounters);
} else {
    initCharacterCounters();
}