<?php include('webpage_editor.php'); ?>

<div id="details">
    <div class="tabs" style="flex: 1 1 auto; height: 100%;">
        <input type="radio" name="tab" id="tab1" role="tab" checked>
        <label for="tab1" id="tab1-label">Blocks</label>
        <div>
            <h5>Components</h5>
            <div id="components" class="keepsize">
                <div class="draggable" draggable="false" data-component="addnew" style="cursor: pointer;">
                    <svg fill="#000000" style="width: 16px; height: 16px;" viewBox="0 0 24 24" version="1.2" baseProfile="tiny" xmlns="http://www.w3.org/2000/svg"><path d="M18 10h-4v-4c0-1.104-.896-2-2-2s-2 .896-2 2l.071 4h-4.071c-1.104 0-2 .896-2 2s.896 2 2 2l4.071-.071-.071 4.071c0 1.104.896 2 2 2s2-.896 2-2v-4.071l4 .071c1.104 0 2-.896 2-2s-.896-2-2-2z"/></svg>
                    <div style="font-weight: 700;">ADD NEW</div>
                </div>
                <div class="draggable" draggable="false" data-component="remove" style="cursor: pointer;">
                    <svg fill="#000000" style="width: 16px; height: 16px;" viewBox="0 0 24 24" id="minus" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="icon flat-line"><line id="primary" x1="19" y1="12" x2="5" y2="12" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></line></svg>
                    <div style="font-weight: 700;">REMOVE</div>
                </div>
                <select style="width: 100%; margin: .5em; padding: .25em;">
<?php
$dir = '../data/components/';

// Get all HTML files in the directory
$files = glob($dir . '*.html');

foreach ($files as $file) {
    // Get just the filename without the directory
    $filename = basename($file);
    
    // Remove the ".html" extension
    $componentName = pathinfo($filename, PATHINFO_FILENAME);
    
    // Capitalize the display name (optional)
    $displayName = ucfirst($componentName);

    // Output the HTML structure
    echo '<option>' . htmlspecialchars($displayName) . '</option>';
}
?>
                </select>
            </div>
<?php include('elements.php'); ?>
        </div>
        <input type="radio" name="tab" id="tab2" role="tab">
        <label for="tab2" id="tab2-label">Styles</label>
        <div aria-labelledby="tab2-label">
            <div class="inspection-info"></div>
        </div>
    </div>
</div>
