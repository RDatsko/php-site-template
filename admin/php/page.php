<?php include('webpage_editor.php'); ?>

<div id="details">
    <div class="tabs" style="flex: 1 1 auto; height: 100%;">
        <input type="radio" name="tab" id="tab1" role="tab" checked>
        <label for="tab1" id="tab1-label">Blocks</label>
        <div>
            <h5>Components</h5>
            <div id="components">
                <!-- <div class="draggable" draggable="true" data-component="header">
                    <svg fill="#000000" viewBox="0 0 1920 1920" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0 213.333C0 95.5126 95.5126 0 213.333 0H1706.67C1824.49 0 1920 95.5126 1920 213.333V1706.67C1920 1824.49 1824.49 1920 1706.67 1920H213.333C95.5126 1920 0 1824.49 0 1706.67V213.333ZM213.333 106.667C154.423 106.667 106.667 154.423 106.667 213.333V1706.67C106.667 1765.58 154.423 1813.33 213.333 1813.33H1706.67C1765.58 1813.33 1813.33 1765.58 1813.33 1706.67V213.333C1813.33 154.423 1765.58 106.667 1706.67 106.667H213.333ZM266.667 746.667C237.211 746.667 213.333 722.788 213.333 693.333V373.333C213.333 343.878 237.211 320 266.667 320H1653.33C1682.79 320 1706.67 343.878 1706.67 373.333V693.333C1706.67 722.788 1682.79 746.667 1653.33 746.667H266.667ZM346.667 426.667C331.939 426.667 320 438.606 320 453.333V613.333C320 628.061 331.939 640 346.667 640H1573.33C1588.06 640 1600 628.061 1600 613.333V453.333C1600 438.606 1588.06 426.667 1573.33 426.667H346.667Z"></path>
                    </svg>
                    <div>Header</div>
                </div>
                <div class="draggable" draggable="true" data-component="map">
                    <svg viewBox="0 0 24 24">
                        <path fill="currentColor" d="M20.5,3L20.34,3.03L15,5.1L9,3L3.36,4.9C3.15,4.97 3,5.15 3,5.38V20.5A0.5,0.5 0 0,0 3.5,21L3.66,20.97L9,18.9L15,21L20.64,19.1C20.85,19.03 21,18.85 21,18.62V3.5A0.5,0.5 0 0,0 20.5,3M10,5.47L14,6.87V18.53L10,17.13V5.47M5,6.46L8,5.45V17.15L5,18.31V6.46M19,17.54L16,18.55V6.86L19,5.7V17.54Z"></path>
                    </svg>
                    <div>Map</div>
                </div> -->
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
    echo '<div class="draggable" draggable="true" data-component="' . htmlspecialchars($componentName) . '">
            <div>' . htmlspecialchars($displayName) . '</div>
          </div>';
}
?>
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
