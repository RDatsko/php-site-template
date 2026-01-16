<?php
session_start();

// Auto-logout after 30 minutes of inactivity
$timeout = 1800; // 30 minutes in seconds

if (isset($_SESSION['LAST_ACTIVE']) && (time() - $_SESSION['LAST_ACTIVE'] > $timeout)) {
    // Session timed out
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

// Handle form submission
$errors = [];

// Update last active time
$_SESSION['LAST_ACTIVE'] = time();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/layout/css/cosmuic.css">
<title>Administrator Panel</title>
<style>

/* Base Styles */
:root {
    --primary-color: #667eea;
    --primary-dark: #5a67d8;
    --secondary-color: #764ba2;
    --accent-color: #ff7e5f;
    --text-primary: #2d3748;
    --text-secondary: #4a5568;
    --bg-primary: #f7fafc;
    --bg-secondary: #edf2f7;
    --border-color: #e2e8f0;
    --success-color: #48bb78;
    --warning-color: #ed8936;
    --danger-color: #f56565;
    --white: #ffffff;
    --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
    --rounded-sm: 0.25rem;
    --rounded-md: 0.5rem;
    --rounded-lg: 0.75rem;
    --transition: all 0.2s ease;

    --awidth: 240px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: var(--font, "IBM Plex Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol");
    color: var(--text-primary);
    background-color: var(--bg-primary);
    line-height: 1.6;
    overflow: hidden;
}

body main {
    padding: 0;
    margin: 0;
}

body > header, header > span {
    height: 40px;
    align-items: center;
}

#app {
    display: flex;
    flex-direction: column;
    height: 100vh;
    overflow: hidden;
}

#app header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background-color: var(--white);
    box-shadow: var(--shadow-sm);
    z-index: 10;
}

#app main {
    display: flex;
    flex: 1;
    overflow: hidden;
    max-width: 100%;
}

#app aside {
    width: var(--awidth);
    min-width: var(--awidth);
    max-width: var(--awidth);
    background-color: var(--white);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
}

#app #display {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background-color: var(--white);
    border-right: 1px solid var(--border-color);
}

#app #details {
    width: 275px;
    border-right: none;
    display: flex;
    flex-direction: column;
}

#details {
    flex: 0 1 auto;
    height: 100%;
}

#app nav ul {
    all: unset;
    list-style: none;
}

#app nav li {
    all: unset;
    margin-bottom: 0.25rem;
}

#app nav a {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: var(--text-secondary);
    text-decoration: none;
    transition: var(--transition);
    border-radius: var(--rounded-sm);
}

#app nav li.active a {
    background-color: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
    font-weight: 500;
}

#filemgr {
    padding: 0.5rem;
    display: block;
    overflow: scroll;
    height: 100%;
    font-size: 0.75rem;
}

#filemgr ul {
    /* all: unset; */
    background-color: transparent;
    display: block;
}

#filemgr li {
    min-block-size: unset;
    display: block;
    padding: 0 0 0 1rem;
    padding: 2px 6px;
    border-radius: 4px;
    cursor: pointer;
}

#filemgr li, #filemgr span {
  user-select: none;
}

/* Folder label and file entry look */
#filemgr .folder-label,
#filemgr li[data-url] {
    display: block;              /* stack vertically */
  width: fit-content;          /* limit highlight to text width */
  padding: 2px 6px;
  border-radius: 4px;
  transition: background-color 0.15s, color 0.15s;
  user-select: none;
}

/* Hover color — only for the text area */
#filemgr .folder-label:hover,
#filemgr li[data-url]:hover {
  background-color: #e5e7eb; /* light gray */
  cursor: pointer;
}

/* Selected item */
#filemgr li.selected > .folder-label,
#filemgr li.selected[data-url] {
  background-color: #2563eb;
  color: white;
}

/* Highlight selected file/folder */
#filemgr li.selected {
  background-color: #2563eb; /* blue highlight */
  color: white;
}

.toolbar {
    all: unset;
    /* width: 100%; */
    padding: 10px;
    display: flex;
    flex-direction: row;
    gap: 6px;
    border-bottom: 1px solid var(--border-color);
    /* min-height: 48px;
    max-height: 48px; */
}

.toolbar button {
    all: unset;
    padding: 3px;
    border: 1px solid #aaa;
    background: #fff;
    color: black;
    cursor: pointer;
    border-radius: 4px;
    transition: background 0.2s;
    line-height: 1rem;
    margin: 0;
    max-width: 26px;
}

.toolbar button::before, .toolbar button::after {
    all: unset;
}

.toolbar button > svg {
    width: 18px;
    height: 18px;
}

.toolbar button.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}


#canvas-wrap {
    flex: 1;
    background: #eee;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    overflow: auto;
    padding: 10px;
    box-sizing: border-box;
}

/* iframe canvas */
#canvas-frame {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: width 0.3s, height 0.3s;
    width: 1200px;
    height: calc(100vh - 140px);
    box-sizing: border-box;
}

#canvas-wrap.preview {
    position: absolute;
    top: -64px;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 0;
    z-index: 20000;
}

#canvas-wrap.preview > #canvas-frame {
    width: 100%;
    height: 100%;
    border-radius: 0;
    border: 0;
}

/* device widths - these apply to the FRAME so the inner page appears narrower */
.desktop #canvas-frame { width: 1200px; }
.tablet  #canvas-frame { width: 768px; }
.mobile  #canvas-frame { width: 375px; }

.draggable {
    display: block;
    padding: 8px;
    background: #ddd;
    margin-bottom: 8px;
    cursor: grab;
    border-radius: 4px;
    text-align: center;

    user-select: none;
    width: 45%;
    min-width: 45px;
    padding: .5em;
    box-sizing: border-box;
    /* min-height: 90px; */
    cursor: all-scroll;
    font-size: 11px;
    font-weight: lighter;
    text-align: center;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    border: 1px solid rgba(0, 0, 0, .2);
    border-radius: 3px;
    margin: 10px 2.5% 5px;
    box-shadow: 0 1px 0 0 rgba(0, 0, 0, .15);
    transition: all .2s ease 0s;
    transition-property: box-shadow, color;
}

.draggable > svg {
    width: 24px;
    height: 24px;
}

.draggable > div {
    display: flex;
    flex: 1 1 0;
    padding-left: .5rem;
    align-items: center;
}

/* These are kept for preview in the parent (used by new elements inside the iframe too, via injected styles) */
.drop-zone {
    min-height: 20px;
    border: 1px dashed #ccc;
    padding: 5px;
    margin: 5px 0;
    border-radius: 5px;
    transition: background 0.2s, border-color 0.2s;
}

.drop-zone.hover {
    border-color: #666;
    background: #f8f8f8;
}

.element {
    padding: 10px;
    background: #e8e8ff;
    margin: 5px 0;
    border-radius: 5px;
    position: relative;
    cursor: grab;
    display: block;
}

.element button.remove-btn {
    all: unset;
    position: absolute;
    top: 4px;
    right: 4px;
    background: #ff4d4d;
    color: white;
    border: none;
    border-radius: 3px;
    font-size: 12px;
    padding: 2px 5px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}

.element:hover button.remove-btn {
    opacity: 1;
}

.insert-marker {
    height: 4px;
    background: #007bff;
    border-radius: 2px;
    margin: 2px 0;
    transition: opacity 0.2s;
}

.placeholder {
    border: 2px dashed #007bff;
    margin: 5px 0;
    height: 20px;
    border-radius: 5px;
    /* background: repeating-linear-gradient(45deg, #007bff22, #007bff22 10px, #fff 10px, #fff 20px); */
}

#templates, #components, #elements {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    /* overflow-y: auto; */
    overflow: hidden auto;
}

.keepsize {
    flex: 0 0 auto;
}

#preview-area {
    flex: 1;
    display: flex;
    flex-direction: column;
    max-width: calc(100vw - 200px);
}

/* inspector styles */
.inspector-element {
    position: absolute;
    pointer-events: none;
    border: 2px solid tomato;
    /* transition: all 200ms; */
    background-color: rgba(180, 187, 105, 0.2);
}


















  .inspection-info {
    grid-row: 2;
    grid-column: 1;
    padding: 20px;
    overflow-wrap: break-word;
  }

  .inspection-status {
    grid-row: 1;
    grid-column: 2;
  }

  .inspector-root {
    grid-row: 2;
    grid-column: 2;
  }

  .inspection-checkbox {
    width: 1rem;
    height: 1rem;
  }

  .normal-container {
    width: 300px;
    height: 100%;
    border: 1px solid blue;
  }

  .marker {
    pointer-events: none;
    width: var(--marker-width, 0);
    height: var(--marker-height, 0);
    position: fixed;
    top: var(--marker-top, 0);
    left: var(--marker-left, 0);
    border: 2px solid #3b97e3;
    /* background: rgba(255, 0, 0, 0.2); */
    /* transition: all 0.2s; */
    z-index: 99999;
  }

  .marker-toolbar {
    position: absolute;
    top: -28px;
    right: -2px;
    height: 28px;
    width: 100px;
    background-color: #3b97e3;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    flex-wrap: wrap;
    align-content: space-between;
    pointer-events: auto;
    z-index: 100000;
}

.markerBtn {
    width: 16px;
    height: 16px;
    color: white;
    display: flex;
    margin: 4px;
}

/* .tabs > label {
    height: 46px;
    display: flex;
    align-items: center
} */

.tabs > div {
    display: none;
    flex-direction: column;
    height: calc(100% - 48px);
}

.tabs > input[type=radio]:checked + label + div {
    display: flex;
}
</style>
</head>
<body>

<div id="app">
    <header><span>Logo</span><nav><a href="./logout.php">Logout</a></nav></header>
    <main>
        <aside>
            <div class="toolbar">Site Sections</div>
            <nav>
                <ul>
                    <li><a href="" class="nav-link" id="medialibrary"><i></i> Media Library</a></li>
                    <li><a href="" class="nav-link" id="component_editor"><i></i> Components</a></li>
                    <li><a href="" class="nav-link" id="template_editor"><i></i> Template</a></li>
                    <li class="active"><a href="" class="nav-link" id="page"><i></i> Page</a></li>
                </ul>
            </nav>
            <div id="filemgr">

            </div>
        </aside>
        <div id="subpage" style="display: flex; flex: 1 1 auto; flex-wrap: wrap; width: calc(100vw - var(--awidth) - 20px);">
            <?php include('./php/page.php'); ?>
        </div>
        <div class="marker">
            <div class="marker-toolbar">
                <div class="markerBtn">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M13,20H11V8L5.5,13.5L4.08,12.08L12,4.16L19.92,12.08L18.5,13.5L13,8V20Z"></path>
                    </svg>
                </div>
                <div class="markerBtn" draggable="true">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M13,6V11H18V7.75L22.25,12L18,16.25V13H13V18H16.25L12,22.25L7.75,18H11V13H6V16.25L1.75,12L6,7.75V11H11V6H7.75L12,1.75L16.25,6H13Z"></path>
                    </svg>
                </div>
                <div class="markerBtn">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,21H8V7H19M19,5H8A2,2 0 0,0 6,7V21A2,2 0 0,0 8,23H19A2,2 0 0,0 21,21V7A2,2 0 0,0 19,5M16,1H4A2,2 0 0,0 2,3V17H4V3H16V1Z"></path>
                    </svg>
                </div>
                <div class="markerBtn">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.nav-link');
    const subpage = document.getElementById('subpage');
    const iframe = document.getElementById('canvas-frame');

    // setupPageBuilder();
    setupIframeInspector(iframe);

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            const pageId = this.id; // e.g., "medialibrary"
            const phpFile = `./php/${pageId}.php`; // e.g., "medialibrary.php"

            const marker = document.querySelector('.marker');
            marker.style.setProperty('--marker-width', 0);
            marker.style.setProperty('--marker-height', 0);
            marker.style.setProperty('--marker-top', 0);
            marker.style.setProperty('--marker-left', 0);

            fetch(phpFile)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.text();
                })
                .then(html => {
                    subpage.innerHTML = html;

                    // Update active class in the nav
                    document.querySelectorAll('#app nav li').forEach(li => li.classList.remove('active'));
                    this.parentElement.classList.add('active');
                })
                .then(() => {
                    if (pageId === 'page') {
                        //   Wait a tick for the new DOM to settle
                        requestAnimationFrame(() => {
                            // Re-run page builder setup
                            if (typeof setupPageBuilder === 'function') {
                                setupPageBuilder();
                            }

                            // Re-run selector setup
                            const iframeQ = document.getElementById('canvas-frame');
                            if (iframeQ && typeof setupIframeInspector === 'function') {
                                setupIframeInspector(iframeQ);
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error(`Error loading ${phpFile}:`, error);
                    subpage.innerHTML = `<p style="color: red;">Failed to load ${phpFile}.</p>`;
                });
        });
    });
});
</script>

<script>

// async function loadFileManager() {
//   const container = document.getElementById('filemgr');
//   container.innerHTML = 'Loading files...';

//   try {
//     const res = await fetch('/admin/data/filelist.txt');
//     if (!res.ok) throw new Error('Failed to load filelist.txt');
//     const text = await res.text();

//     const lines = text.split('\n').map(line => line.trim()).filter(Boolean);

//     // Build a tree structure to handle folders and files
//     const root = {};

//     lines.forEach(path => {
//       const parts = path.split('/');
//       let current = root;

//       parts.forEach((part, i) => {
//         const isFolder = (i !== parts.length - 1) || path.endsWith('/');

//         if (!current[part]) {
//           current[part] = isFolder ? {} : null;
//         }
//         current = current[part] || current;
//       });
//     });

//     // Recursive function to create nested lists
//     function createList(node, isRoot = false) {
//         const ul = document.createElement('ul');
//         for (const key in node) {
//             const li = document.createElement('li');
//             li.textContent = key;
//             if (node[key] !== null) {
//                 li.appendChild(createList(node[key]));
//             }
//             ul.appendChild(li);
//         }

//         if (isRoot) {
//             const rootLi = document.createElement('li');
//             rootLi.textContent = '/ [ ROOT ]';
//             rootLi.appendChild(ul);
//             return rootLi;
//         }
//         return ul;
//     }

//     container.innerHTML = '';
//     container.appendChild(createList(root, true));

//   } catch (err) {
//     container.innerHTML = `<p style="color:red;">Error loading file list: ${err.message}</p>`;
//   }
// }

// // Load file manager on page load or whenever you want
// document.addEventListener('DOMContentLoaded', loadFileManager);



















async function loadFileManager() {
  const container = document.getElementById('filemgr');
  container.innerHTML = 'Loading files...';

  try {
    const res = await fetch('/admin/data/filelist.txt');
    if (!res.ok) throw new Error('Failed to load filelist.txt');
    const text = await res.text();

    const lines = text.split('\n').map(line => line.trim()).filter(Boolean);

    // Build a tree structure
const root = {};

lines.forEach(path => {
  const parts = path.split('/').filter(Boolean); // remove empty parts
  let current = root;

  parts.forEach((part, i) => {
    const isLast = i === parts.length - 1;
    const isFolder = !isLast || path.endsWith('/');

    if (!(part in current)) {
      current[part] = isFolder ? {} : null;
    }

    current = current[part] || current; // move into the folder
  });
});


    // Recursive function to create nested lists
// Recursive function to create nested lists
function createList(node, parentPath = "") {
  const ul = document.createElement("ul");

  // Separate folders and files
  const folders = [];
  const files = [];

  for (const key in node) {
    if (node[key] && typeof node[key] === "object") {
      folders.push(key);
    } else {
      files.push(key);
    }
  }

  // Sort each group alphanumerically
  folders.sort((a, b) => a.localeCompare(b, undefined, { numeric: true }));
  files.sort((a, b) => a.localeCompare(b, undefined, { numeric: true }));

  // Folders first
  for (const key of folders) {
    const li = document.createElement("li");
    const fullPath = parentPath ? parentPath + "/" + key : key;

    const folderLabel = document.createElement("span");
    folderLabel.textContent = "📁 " + key;
    folderLabel.style.cursor = "pointer";
    folderLabel.classList.add("folder-label");

    const subUl = createList(node[key], fullPath);
    subUl.style.display = "none";

    // Toggle expand/collapse
    folderLabel.addEventListener("click", () => {
      const isOpen = subUl.style.display === "block";
      subUl.style.display = isOpen ? "none" : "block";
      folderLabel.textContent = (isOpen ? "📁 " : "📂 ") + key;
    });

    li.appendChild(folderLabel);
    li.appendChild(subUl);
    ul.appendChild(li);
  }

  // Files next
  for (const key of files) {
    const li = document.createElement("li");
    const fullPath = parentPath ? parentPath + "/" + key : key;

    li.textContent = "📄 " + key;
    li.dataset.url = fullPath;
    ul.appendChild(li);
  }

  // Always add index.php if this is a folder (parentPath !== "")
  if (parentPath) {
    const indexLi = document.createElement("li");
    indexLi.textContent = "📄 index.php";
    indexLi.dataset.url = parentPath + "/index.php";
    ul.insertBefore(indexLi, ul.children[folders.length]); // after folders, before other files
  }

  return ul;
}


// --- Click event for loading files ---
document.addEventListener("click", (e) => {
  const li = e.target.closest("li[data-url]");
  if (!li) return;

  const iframe = document.getElementById("iframeCanvas");
  if (iframe) {
    iframe.removeAttribute("srcdoc");
    iframe.src = "pages/" + li.dataset.url.replaceAll("/", "_");
  }
});







    const rootUl = document.createElement('ul');
    const rootLi = document.createElement('li');
    rootLi.textContent =  "📁 /";

    const childList = createList(root);
    if (childList && childList.children.length > 0) rootLi.appendChild(childList);
    rootUl.appendChild(rootLi);

    container.innerHTML = '';
    container.appendChild(rootUl);

    //     // Add index.php for root
    // const rootIndex = document.createElement("li");
    // rootIndex.textContent = "📄 index.php";
    // rootIndex.dataset.url = "index.php";
    // childList.insertBefore(rootIndex, childList.firstChild);

        // Add root index.php **after folders but before files**
    const rootFilesStart = Array.from(childList.children).findIndex(li => !li.querySelector("span.folder-label"));
    const rootIndexLi = document.createElement("li");
    rootIndexLi.textContent = "📄 index.php";
    rootIndexLi.dataset.url = "index.php";
    if (rootFilesStart === -1) {
      childList.appendChild(rootIndexLi);
    } else {
      childList.insertBefore(rootIndexLi, childList.children[rootFilesStart]);
    }






// Prevent text selection in file manager
const filemgr = document.getElementById("filemgr");
filemgr.style.userSelect = "none";

// Handle single-click selection
filemgr.addEventListener("click", (e) => {
  const li = e.target.closest("li");
  if (!li || !filemgr.contains(li)) return;

  // Skip folders (no data-url means it’s a folder)
  if (!li.dataset.url) return;

  // Remove selection from all items
  filemgr.querySelectorAll("li.selected").forEach(el => el.classList.remove("selected"));

  // Add selection to clicked item
  li.classList.add("selected");
});

    container.addEventListener('dblclick', (e) => {
  const li = e.target.closest('li');
  if (!li || !li.dataset.url) return;

  const url = li.dataset.url;
  console.log(`[FileManager] Clicked: ${url}`);

  // ✅ Call parent function if inside an iframe, otherwise local
  if (typeof parent.openFileInIframe === 'function') {
    parent.openFileInIframe(url);
  } else if (typeof openFileInIframe === 'function') {
    openFileInIframe(url);
  } else {
    console.warn('[FileManager] openFileInIframe not found.');
  }
});

    // Serialize tree back to text
    // function flattenTree(node, prefix = '') {
    //   let result = [];
    //   for (const key in node) {
    //     const path = prefix ? `${prefix}/${key}` : key;
    //     if (node[key] && Object.keys(node[key]).length > 0) {
    //       result.push(`${path}/`);
    //       result = result.concat(flattenTree(node[key], path));
    //     } else {
    //       result.push(path);
    //     }
    //   }
    //   return result;
    // }

    // const serializedLines = flattenTree(root);
    // console.log('Serialized lines:', serializedLines);

    // // Send to server to save
    // await fetch('/admin/php/save_filelist.php', {
    //   method: 'POST',
    //   headers: {'Content-Type': 'application/json'},
    //   body: JSON.stringify({ filename: '../data/testbak.txt', lines: serializedLines })
    // });

    // Setup click handling for loading files into iframe
    // setupFileManagerClicks();

  } catch (err) {
    container.innerHTML = `<p style="color:red;">Error loading file list: ${err.message}</p>`;
  }
}

function setupFileManagerClicks() {
  const container = document.getElementById('filemgr');
  const iframe = document.getElementById('canvas-frame');

  container.addEventListener('click', async (e) => {
    const li = e.target.closest('li');
    if (!li) return;

    // Skip root folder
    if (li.textContent === '/ [ ROOT ]') return;

    // Determine full path by walking up parents
    let pathParts = [];
    let current = li;
    while (current && current !== container) {
      if (current.tagName === 'LI' && current.textContent.trim() !== '/ [ ROOT ]') {
        const text = current.firstChild.nodeValue.trim();
        if (text) pathParts.unshift(text);
      }
      current = current.parentElement.closest('li') || current.parentElement;
    }
    const filePath = pathParts.join('/');

    // Skip folders
    if (li.querySelector('ul')) return;

    // Load file into iframe
    try {
      const res = await fetch(filePath);
      if (!res.ok) throw new Error(`Failed to load ${filePath}`);
      const html = await res.text();

      if (!iframe) {
        console.warn('No iframe found to load file.');
        return;
      }

      const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
      iframeDoc.open();
      iframeDoc.write(html);
      iframeDoc.close();

      console.log(`[FileManager] Loaded ${filePath} into iframe`);

      // Highlight selected file
      container.querySelectorAll('li').forEach(li => li.classList.remove('selected'));
      li.classList.add('selected');

    } catch (err) {
      console.error(err);
      alert(`Failed to load file: ${filePath}`);
    }
  });
}

document.addEventListener('DOMContentLoaded', loadFileManager);


function openFileInIframe(fileUrl) {
  const iframe = document.getElementById('canvas-frame');
  if (!iframe) {
    console.error('[openFileInIframe] iframe#canvas-frame not found');
    return;
  }

  // 1️⃣ Remove srcdoc if present
  if (iframe.hasAttribute('srcdoc')) {
    iframe.removeAttribute('srcdoc');
  }

  // 2️⃣ Clean and sanitize URL
  let cleaned = fileUrl.replace(/^\/+/, '');
  const safeUrl = cleaned.replace(/\//g, '_');
  const finalUrl = "/pages/" + safeUrl;

  console.log(`[openFileInIframe] Loading: ${finalUrl}`);
  iframe.src = finalUrl;

  // 3️⃣ When iframe finishes loading, reattach editor logic
  iframe.addEventListener('load', () => {
    console.log('[openFileInIframe] Iframe loaded; reinitializing builder.');
    if (typeof setupIframeCanvas === 'function') setupIframeCanvas();
    if (typeof setupIframeInspector === 'function') setupIframeInspector(iframe);
  }, { once: true });
}




</script>

<script src="./js/selector.js"></script>
<script src="./js/page.js"></script>
</body>
</html>