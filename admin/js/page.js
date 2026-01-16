/*
  Overview:
   - Canvas is now an iframe (#canvas-frame) loading ./blank.html
   - Drops go directly into the iframe's document.body
   - Keeps behavior: create elements, load components, reordering inside iframe, removal, contenteditable
   - Sidebar draggables set dataTransfer to allow cross-document dragging.
*/

/* Parent-side elements */
const draggables = document.querySelectorAll('.draggable');
let iframe = null;
const body = document.body;

/* Drag state (shared): */
let dragType = null;          // string like 'div', 'p', etc. (from sidebar)
let dragComponent = null;     // string like 'header' (from sidebar)
let draggedElement = null;    // if reordering an existing element (may reference element in iframe doc)
let draggedFromDoc = null;    // o

function bindSidebarDrags() {
    const draggables = document.querySelectorAll('.draggable');
    draggables.forEach(el => {
        el.addEventListener('dragstart', parentDragStartHandler);
    });
}

function parentDragStartHandler(e) {
    // ensure some dataTransfer payload so iframe will accept drag events (browser quirk)
    try {
        e.dataTransfer.setData('text/plain', e.target.dataset.element || e.target.dataset.component || '');
    } catch (err) {
        // ignore if browser disallows
    }
    e.dataTransfer.effectAllowed = 'copy';
    dragType = e.target.dataset.element || null;
    dragComponent = e.target.dataset.component || null;
    draggedElement = null;
    draggedFromDoc = null;
}

function initializePageBuilder() {
    const iframe = document.getElementById('canvas-frame');

    if (!iframe) {
        console.warn('Builder iframe not found.');
        return;
    }

    // 🆕 Re-bind sidebar draggables every time (in case they were dynamically added)
    bindSidebarDrags();

    // 🆕 Device width toggle buttons
    const desktopBtn = document.getElementById('desktopBtn');
    const tabletBtn  = document.getElementById('tabletBtn');
    const mobileBtn  = document.getElementById('mobileBtn');
    const body = document.body;

    function setMode(mode) {
        body.className = mode;
        document.querySelectorAll('#toolbar button').forEach(btn => btn.classList.remove('active'));
        if (mode === 'desktop') desktopBtn?.classList.add('active');
        if (mode === 'tablet')  tabletBtn?.classList.add('active');
        if (mode === 'mobile')  mobileBtn?.classList.add('active');
    }

    if (desktopBtn) desktopBtn.addEventListener('click', () => setMode('desktop'));
    if (tabletBtn)  tabletBtn.addEventListener('click', () => setMode('tablet'));
    if (mobileBtn)  mobileBtn.addEventListener('click', () => setMode('mobile'));

    // And attach sidebar draggable listeners, if those elements are inside subpage
    const draggables = document.querySelectorAll('.draggable');
    draggables.forEach(el => {
        el.addEventListener('dragstart', parentDragStartHandler);
    });

    // 🆕 Set default mode (optional)
    // setMode('desktop');
}

/* set dataTransfer for all sidebar draggables */
draggables.forEach(el => {
    el.addEventListener('dragstart', parentDragStartHandler);
});

/* Utility: create a removable button inside the provided document */
// function makeRemovable(el, doc) {
//     const removeBtn = doc.createElement('button');
//     removeBtn.textContent = '×';
//     removeBtn.className = 'remove-btn';
//     removeBtn.addEventListener('click', e => {
//         e.stopPropagation();
//         el.remove();
//     });
//     el.appendChild(removeBtn);
// }

/* Utility: make element reorderable (drag within its ownerDocument) */
function makeReorderable(el) {
    el.draggable = true;
    el.addEventListener('dragstart', e => {
        // when dragging a node from inside iframe, we record it
        dragType = null;
        dragComponent = null;
        draggedElement = el;
        draggedFromDoc = el.ownerDocument;
        // we don't set dataTransfer (sidebar does), but to be safe:
        try { e.dataTransfer.setData('text/plain', 'internal'); } catch (err) {}
        setTimeout(() => el.style.opacity = '0.3', 0);
    });
    el.addEventListener('dragend', e => {
        el.style.opacity = '1';
        draggedElement = null;
        draggedFromDoc = null;
    });
}

/* Create an insert marker element inside a given document */
function createMarker(doc) {
    const m = doc.createElement('div');
    m.className = 'insert-marker';
    return m;
}

/* Inject minimal styles into iframe doc so elements look right and marker works */
// function injectIframeStyles(doc) {
//     // avoid double-inject
//     if (doc.getElementById('builder-injected-styles')) return;
//     const style = doc.createElement('style');
//     style.id = 'builder-injected-styles';
//     style.textContent = `
//         .element { border: 1px dashed lightgray !important; position: relative; cursor: grab; }
//         .drop-zone { min-height: 20px; border: 1px dashed #ccc; padding: 5px; margin: 5px 0; border-radius: 5px; transition: background 0.2s, border-color 0.2s; }
//         .drop-zone.hover { border-color: #666; background: #f8f8f8; }
//         .remove-btn { all: unset; position: absolute; top: 4px; right: 4px; background: #ff4d4d; color: white; border-radius: 3px; font-size: 12px; padding: 2px 5px; cursor: pointer; opacity: 0; transition: opacity .2s; line-height: 1rem;}
//         .element:hover .remove-btn { opacity: 1; }
//         .insert-marker { height: 4px; background: #007bff; border-radius: 2px; margin: 2px 0; transition: opacity 0.2s; }
//     `;
//     doc.head.appendChild(style);
// }

/* Setup iframe drop behaviors when the iframe is loaded/ready */
function setupIframeCanvas() {
    // if (iframeSetupComplete) return;
    // iframeSetupComplete = true;

    iframe = document.getElementById('canvas-frame');

    if (!iframe) {
        console.warn('setupIframeCanvas called with null iframe');
        return;
    }
    
    const doc = iframe.contentDocument || iframe.contentWindow.document;
    if (!doc) {
        console.warn('iframe document is not accessible yet');
        return;
    }

    // ensure we can edit the body
    doc.body.contentEditable = 'true';

    // inject styles for marker, element etc.
    // injectIframeStyles(doc);

    // create one marker per iframe document
    const marker = createMarker(doc);

    // make sure existing children of body are droppable/reorderable if any
    Array.from(doc.body.children).forEach(child => {
        // only attach behavior to elements we create or to elements that look like drop-able
        if (!child.classList.contains('element')) {
            // leave as-is, but we can wrap or mark as drop-zone if needed
        }
        // if element already created by user and is editable, make it behave
        if (child.classList.contains('element')) {
            makeReorderable(child);
            // makeRemovable(child, doc);
        }
    });
    

    /* Helper: determine valid target for inserting: element or body */
    function findTarget(el) {
        // allow dropping on elements or body
        return el.closest('.element') || el.closest('body');
    }

    /* dragover on iframe document: show marker placement */
    doc.addEventListener('dragover', e => {
        e.preventDefault(); // allow drop
        const target = findTarget(e.target);
        if (!target) return;

        // remove previous marker placements
        // (marker will be inserted into the DOM below)
        // compute insertion point based on pointer Y
        const rect = target.getBoundingClientRect();
        const before = e.clientY < rect.top + rect.height / 3;
        const after  = e.clientY > rect.bottom - rect.height / 3;

        // remove marker if already in different place
        if (marker.parentNode && marker.parentNode !== target && !target.contains(marker)) {
            marker.remove();
        }

        // if target is body -> append/prepend appropriately
        if (target === doc.body) {
            // if body has children, decide where to place relative to last child under pointer
            const children = Array.from(doc.body.children);
            // find a child under the pointer, fallback to append
            let placed = false;
            for (const child of children) {
                const r = child.getBoundingClientRect();
                if (e.clientY < r.top + r.height/2) {
                    child.parentNode.insertBefore(marker, child);
                    placed = true;
                    break;
                }
            }
            if (!placed) doc.body.appendChild(marker);
        } else {
            // target is an element
            if (!before && !after) {
                // drop *into* the element
                target.appendChild(marker);
            } else if (before) {
                target.parentNode.insertBefore(marker, target);
            } else {
                target.parentNode.insertBefore(marker, target.nextSibling);
            }
        }
    });

    /* dragenter and dragleave for hover class */
    doc.addEventListener('dragenter', e => {
        const t = findTarget(e.target);
        if (t && t.classList) t.classList.add('hover');
    });
    doc.addEventListener('dragleave', e => {
        const t = findTarget(e.target);
        if (t && t.classList) t.classList.remove('hover');
    });

    /* drop handler in iframe doc */
    doc.addEventListener('drop', async e => {
        e.preventDefault();

        const target = findTarget(e.target) || doc.body;
        let newEl = null;

        // CASE A: reordering / moving an existing element dragged inside the iframe
        if (draggedElement && draggedFromDoc === doc) {
            // simple move
            newEl = draggedElement;
        }
        // CASE B: dragging an existing element FROM DIFFERENT DOC (rare) - import if possible
        else if (draggedElement && draggedFromDoc !== doc) {
            // import node (deep)
            try {
                newEl = doc.importNode(draggedElement, true);
            } catch (err) {
                newEl = draggedElement.cloneNode(true);
            }
        }
        // CASE C: sidebar element types (element tag names)
        else if (dragType) {
            newEl = doc.createElement(dragType);
            newEl.className = 'element';
            if (dragType !== 'img') newEl.setAttribute('contenteditable', 'true');
            newEl.innerHTML = dragType === 'img'
                ? '<img src="https://via.placeholder.com/150" alt="Image" style="max-width:100%;" />'
                : `${dragType} Element`;
            // attach behaviors for future reordering/removal
            makeReorderable(newEl);
            // makeRemovable(newEl, doc);
        }
        // CASE D: components (load via fetch in parent, inject into iframe doc)
        else if (dragComponent) {
            const filePath = `./data/components/${dragComponent}.html`;
            try {
                // // fetch in parent context (same-origin); then create element in iframe doc
                // const res = await fetch(filePath);
                // const html = await res.text();
                // newEl = doc.createElement('div');
                // newEl.className = 'element';
                // newEl.innerHTML = html;
                // makeReorderable(newEl);
                // makeRemovable(newEl, doc);

                const res = await fetch(filePath);
                const html = await res.text();

                const temp = document.createElement('template');
                temp.innerHTML = html.trim();
                const firstEl = temp.content.firstElementChild;

                if (!firstEl) {
                    console.error('Component file has no root element:', filePath);
                    dragType = dragComponent = draggedElement = draggedFromDoc = null;
                    return;
                }

                newEl = firstEl;
                newEl.classList.add('element');  // add your builder class
                makeReorderable(newEl);
                // makeRemovable(newEl, doc);
            } catch (err) {
                console.error('Failed to load component', filePath, err);
                dragType = dragComponent = draggedElement = draggedFromDoc = null;
                return;
            }
        }

        // Insert newEl at marker position if marker present in the same document
        if (newEl) {
            if (marker.parentNode && doc.body.contains(marker.parentNode)) {
                marker.parentNode.insertBefore(newEl, marker);
                marker.remove();
            } else {
                doc.body.appendChild(newEl);
            }
        }

        // reset drag state
        dragType = null;
        dragComponent = null;
        draggedElement = null;
        draggedFromDoc = null;
    }); /* end drop */

    // Also: allow selecting text/input inside iframe (contentEditable already set)
}

/* When iframe finishes loading, initialize its drop listeners */
if (document.getElementById('canvas-frame')) {
    initializePageBuilder();
}

/* Device width toggles */
const desktopBtn = document.getElementById('desktopBtn');
const tabletBtn  = document.getElementById('tabletBtn');
const mobileBtn  = document.getElementById('mobileBtn');

function setMode(mode) {
    body.className = mode;
    document.querySelectorAll('#toolbar button').forEach(btn => btn.classList.remove('active'));
    if (mode === 'desktop') desktopBtn.classList.add('active');
    if (mode === 'tablet')   tabletBtn.classList.add('active');
    if (mode === 'mobile')   mobileBtn.classList.add('active');
    // The iframe frame width is controlled by CSS rules on the body class (see CSS above)
}

desktopBtn.addEventListener('click', () => setMode('desktop'));
tabletBtn.addEventListener('click', () => setMode('tablet'));
mobileBtn.addEventListener('click', () => setMode('mobile'));

function setupPageBuilder() {
    window.pageBuilderInitialized = false;
    initializePageBuilder();

    const iframe = document.getElementById('canvas-frame');
    if (!iframe) { return; }

    if (iframe.contentDocument?.readyState === 'complete') {
        setTimeout(() => { setupIframeCanvas(); }, 100);
    } else {
        iframe.addEventListener('load', () => { setupIframeCanvas(); }, { once: true });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupPageBuilder);
} else {
    setupPageBuilder();
}
