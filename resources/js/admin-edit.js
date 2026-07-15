// Inline edit mode for logged-in admins. Ported from the design's edit-mode
// behavior, but persisting to the Laravel CMS instead of localStorage.
(() => {
    const routes = window.GS_EDIT_ROUTES;
    if (!routes) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    let editing = false;
    let dragged = null;

    const fab = document.getElementById('gs-edit-fab');
    const toolbar = document.getElementById('gs-edit-toolbar');
    const toast = document.getElementById('gs-edit-toast');

    const showToast = (message, error = false) => {
        toast.textContent = message;
        toast.style.display = 'block';
        toast.style.borderColor = error ? '#c0442e' : 'rgba(255,255,255,.15)';
        clearTimeout(toast._t);
        toast._t = setTimeout(() => { toast.style.display = 'none'; }, 1800);
    };

    const post = async (url, body, method = 'POST') => {
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(body),
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        return response.json();
    };

    const editables = () => document.querySelectorAll('[data-edit-string], [data-edit-model]');
    const cards = () => document.querySelectorAll('.gs-service-card, .gs-proj');

    function applyEditState() {
        document.body.dataset.edit = editing ? 'true' : 'false';
        fab.style.background = editing ? 'var(--gs-accent, #2CA69C)' : '#0d1826';
        fab.querySelector('[data-fab-pencil]').style.display = editing ? 'none' : 'block';
        fab.querySelector('[data-fab-check]').style.display = editing ? 'block' : 'none';
        toolbar.style.display = editing ? 'flex' : 'none';

        editables().forEach((el) => { el.contentEditable = editing ? 'true' : 'false'; });
        cards().forEach((el) => { el.draggable = editing; });

        document.querySelectorAll('[data-section]').forEach((wrap) => {
            const chip = wrap.querySelector('[data-chip]');
            if (chip) chip.style.display = editing ? 'flex' : 'none';
            const visible = wrap.dataset.sectionVisible === '1';
            if (!visible) {
                wrap.style.display = editing ? '' : 'none';
                wrap.style.opacity = editing ? '.35' : '';
            }
        });
    }

    fab.addEventListener('click', () => {
        editing = !editing;
        applyEditState();
    });

    // --- Text edits: commit on blur (capture phase, like the design) ---
    document.addEventListener('blur', async (event) => {
        const el = event.target;
        if (!editing || !(el instanceof HTMLElement)) return;

        const value = el.textContent ?? '';
        const locale = document.documentElement.lang || 'en';

        try {
            if (el.dataset.editString) {
                await post(routes.inlineText, { type: 'string', key: el.dataset.editString, locale, value });
                showToast('Saved');
            } else if (el.dataset.editModel) {
                await post(routes.inlineText, {
                    type: 'model',
                    model: el.dataset.editModel,
                    id: Number(el.dataset.editId),
                    field: el.dataset.editField,
                    locale,
                    value,
                });
                showToast('Saved');
            }
        } catch {
            showToast('Save failed', true);
        }
    }, true);

    // Block navigation while editing (design behavior)
    document.addEventListener('click', (event) => {
        if (!editing) return;
        const link = event.target.closest('a');
        if (link && !link.closest('#gs-edit-toolbar')) {
            event.preventDefault();
        }
    }, true);

    // --- Section chips ---
    document.querySelectorAll('[data-section]').forEach((wrap) => {
        const key = wrap.dataset.section;
        const act = async (action) => {
            try {
                await post(routes.sections, { key, action });
                location.reload();
            } catch {
                showToast('Save failed', true);
            }
        };
        wrap.querySelector('[data-chip-up]')?.addEventListener('click', () => act('up'));
        wrap.querySelector('[data-chip-down]')?.addEventListener('click', () => act('down'));
        wrap.querySelector('[data-chip-toggle]')?.addEventListener('click', () => act('toggle'));
    });

    // --- Drag-reorder for service/project cards ---
    document.addEventListener('dragstart', (event) => {
        const card = event.target.closest?.('[data-item-model]');
        if (!editing || !card) return;
        dragged = card;
        card.style.opacity = '.5';
    });

    document.addEventListener('dragend', () => {
        if (dragged) dragged.style.opacity = '';
        document.querySelectorAll('[data-item-model]').forEach((c) => { c.style.outline = ''; });
        dragged = null;
    });

    document.addEventListener('dragover', (event) => {
        const over = event.target.closest?.('[data-item-model]');
        if (!editing || !dragged || !over || over === dragged) return;
        if (over.dataset.itemModel !== dragged.dataset.itemModel) return;
        event.preventDefault();
        over.style.outline = '2px dashed var(--gs-accent, #2CA69C)';
    });

    document.addEventListener('dragleave', (event) => {
        const over = event.target.closest?.('[data-item-model]');
        if (over && over !== dragged) over.style.outline = '';
    });

    document.addEventListener('drop', async (event) => {
        const over = event.target.closest?.('[data-item-model]');
        if (!editing || !dragged || !over || over === dragged) return;
        if (over.dataset.itemModel !== dragged.dataset.itemModel) return;
        event.preventDefault();

        const model = dragged.dataset.itemModel;
        const container = over.parentElement;
        const rect = over.getBoundingClientRect();
        const before = (event.clientX - rect.left) < rect.width / 2;
        container.insertBefore(dragged, before ? over : over.nextSibling);

        const ids = [...container.querySelectorAll(`[data-item-model="${model}"]`)]
            .map((el) => Number(el.dataset.itemId));

        try {
            await post(routes.reorder, { model, ids });
            showToast('Order saved');
        } catch {
            showToast('Save failed', true);
        }
    });

    // --- Media picker modal ---
    const modal = document.getElementById('gs-media-modal');
    let pickerTarget = null; // payload template for the inline-image API

    function openPicker(target) {
        pickerTarget = target;
        modal.className = 'open';
        modal.style.display = 'block';
        modal.innerHTML = `
            <div class="gsm-backdrop" data-gsm-close></div>
            <div class="gsm-panel">
                <div class="gsm-head">
                    <span class="gsm-title">Choose an image</span>
                    <button type="button" class="gsm-btn ghost" data-gsm-close>Close</button>
                </div>
                <div class="gsm-row">
                    <label class="gsm-btn" style="margin:0;">Upload new
                        <input type="file" accept="image/jpeg,image/png,image/webp" data-gsm-upload style="display:none;">
                    </label>
                    <input type="url" placeholder="…or paste an https:// image URL" data-gsm-url>
                    <button type="button" class="gsm-btn" data-gsm-use-url>Use URL</button>
                </div>
                <div class="gsm-grid" data-gsm-grid><span style="color:#6a7a8a;font-size:14px;">Loading media…</span></div>
            </div>`;
        loadMediaGrid();
    }

    function closePicker() {
        modal.className = '';
        modal.style.display = 'none';
        modal.innerHTML = '';
        pickerTarget = null;
    }

    async function loadMediaGrid() {
        const grid = modal.querySelector('[data-gsm-grid]');
        try {
            const response = await fetch(routes.media, { headers: { 'Accept': 'application/json' } });
            const { items } = await response.json();
            grid.innerHTML = items.length ? '' : '<span style="color:#6a7a8a;font-size:14px;">No uploads yet — upload one above.</span>';
            items.forEach((item) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'gsm-item';
                btn.innerHTML = `<img src="${item.url}" loading="lazy" alt=""><span>${item.name}</span>`;
                btn.addEventListener('click', () => saveImage(item.path));
                grid.appendChild(btn);
            });
        } catch {
            grid.innerHTML = '<span style="color:#c0442e;font-size:14px;">Could not load media library.</span>';
        }
    }

    async function saveImage(value) {
        if (!pickerTarget) return;
        try {
            await post(routes.inlineImage, { ...pickerTarget, value });
            closePicker();
            location.reload();
        } catch {
            showToast('Save failed', true);
        }
    }

    modal.addEventListener('click', (event) => {
        if (event.target.closest('[data-gsm-close]')) closePicker();
        if (event.target.closest('[data-gsm-use-url]')) {
            const url = modal.querySelector('[data-gsm-url]').value.trim();
            if (url) saveImage(url);
        }
    });

    modal.addEventListener('change', async (event) => {
        const input = event.target.closest('[data-gsm-upload]');
        if (!input || !input.files.length) return;
        const body = new FormData();
        body.append('file', input.files[0]);
        try {
            const response = await fetch(routes.mediaUpload, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body,
            });
            if (!response.ok) {
                const detail = response.status === 413
                    ? 'file too large'
                    : (await response.json().catch(() => ({})))?.message || `HTTP ${response.status}`;
                throw new Error(detail);
            }
            const media = await response.json();
            await saveImage(media.path);
        } catch (error) {
            showToast(`Upload failed — ${error.message || 'jpg/png/webp, max 10 MB'}`, true);
        }
    });

    // --- Image editing: click any [data-edit-image] element in edit mode ---
    document.addEventListener('click', (event) => {
        if (!editing) return;
        const el = event.target.closest?.('[data-edit-image]');
        if (!el) return;
        event.preventDefault();
        event.stopPropagation();

        if (el.dataset.imageSetting) {
            openPicker({ type: 'setting', key: el.dataset.imageSetting });
        } else {
            const card = el.closest('[data-item-model]');
            if (!card) return;
            openPicker({
                type: 'model',
                model: card.dataset.itemModel,
                id: Number(card.dataset.itemId),
                field: el.dataset.imageField || 'image',
            });
        }
    }, true);

    // --- Add / delete cards (services & projects) ---
    document.addEventListener('click', async (event) => {
        const addBtn = event.target.closest?.('[data-edit-add]');
        if (addBtn && editing) {
            try {
                await post(routes.items, { model: addBtn.dataset.editAdd });
                location.reload();
            } catch {
                showToast('Add failed', true);
            }
            return;
        }

        const delBtn = event.target.closest?.('[data-edit-delete]');
        if (delBtn && editing) {
            const card = delBtn.closest('[data-item-model]');
            if (!card || !confirm('Delete this item?')) return;
            try {
                await post(routes.items, {
                    model: card.dataset.itemModel,
                    id: Number(card.dataset.itemId),
                }, 'DELETE');
                card.remove();
                showToast('Deleted');
            } catch {
                showToast('Delete failed', true);
            }
        }
    });

    applyEditState();
})();
