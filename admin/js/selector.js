function setupIframeInspector(iframeQ) {

  const marker = document.querySelector('.marker');
  const inspectionCheckbox = document.querySelector('.inspection-checkbox');
  const inspectionStatusText = document.querySelector('.inspection-status-text');
  const inspectionInfo = document.querySelector('.inspection-info');
  let onInspection = true;

  // Function to toggle outlines
  function toggleOutlines(enabled) {
    const doc = iframe.contentDocument;
    if (!doc) return;

    const elements = doc.querySelectorAll('body *:not(script):not(style)');
    elements.forEach(el => {
      if (enabled) {
        el.style.outline = '1px dashed #ccc';
      } else {
        el.style.outline = '';
      }
    });
  }

  inspectionCheckbox.addEventListener('change', (e) => {
    toggleOutlines(inspectionCheckbox.checked);
  });

  inspectionCheckbox.addEventListener('input', (e) => {
    onInspection = e.target.checked;
    if (!onInspection) {
      marker.style.setProperty('--marker-width', 0);
      marker.style.setProperty('--marker-height', 0);
      marker.style.setProperty('--marker-top', 0);
      marker.style.setProperty('--marker-left', 0);
    }
    inspectionStatusText.textContent = onInspection ? 'ON' : 'OFF';
  });

  function updateInspectionInfo(element) {
    if (!element) return;

    const elementTag = element.tagName;
    const classList = element.classList.value || '(none)';
    let attrList = '';

    for (const attr of element.attributes) {
      if (attr.name !== 'class') {
        attrList += `<i>${attr.name}:</i> ${attr.value}<br/>`;
      }
    }

    inspectionInfo.innerHTML = `
      <b>Element Tag:</b> ${elementTag}<br/>
      <b>Element Classes:</b> ${classList}<br/>
      <b>Attributes List:</b><br/>${attrList}
    `;
  }

  iframeQ.addEventListener('load', () => {
    const iframeDoc = iframeQ.contentDocument || iframeQ.contentWindow.document;

    iframeDoc.addEventListener('click', (e) => {
      if (!onInspection) return;
      if (inspectionCheckbox.checked) { toggleOutlines(true); }

      e.preventDefault();
      e.stopPropagation();

      const elementToInspect = e.target;
      if (!elementToInspect.getBoundingClientRect) return;

      const rect = elementToInspect.getBoundingClientRect();
      const iframeRect = iframeQ.getBoundingClientRect();

    //   marker.style.setProperty('--marker-width', rect.width + 'px');
    //   marker.style.setProperty('--marker-height', rect.height + 'px');
    //   marker.style.setProperty('--marker-top', iframeRect.top + rect.top + 'px');
    //   marker.style.setProperty('--marker-left', iframeRect.left + rect.left + 'px');

const padding = 2; // tweak if needed
marker.style.setProperty('--marker-top', (iframeRect.top + 1 + rect.top - padding) + 'px');
marker.style.setProperty('--marker-left', (iframeRect.left + 1 + rect.left - padding) + 'px');
marker.style.setProperty('--marker-width', (rect.width + padding * 2) + 'px');
marker.style.setProperty('--marker-height', (rect.height + padding * 2) + 'px');

      updateInspectionInfo(elementToInspect);
    });
  });

  // Trigger load handler if iframe is already ready (for srcdoc)
  if (iframeQ.contentDocument && iframeQ.contentDocument.readyState === 'complete') {
    const event = new Event('load');
    iframeQ.dispatchEvent(event);
  }
}
