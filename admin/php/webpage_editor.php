<div id="display">
    <div id="toolbar" class="toolbar">
        <button id="desktopBtn" class="active">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M21,16H3V4H21M21,2H3C1.89,2 1,2.89 1,4V16A2,2 0 0,0 3,18H10V20H8V22H16V20H14V18H21A2,2 0 0,0 23,16V4C23,2.89 22.1,2 21,2Z"></path>
            </svg>
        </button>
        <button id="tabletBtn">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19,18H5V6H19M21,4H3C1.89,4 1,4.89 1,6V18A2,2 0 0,0 3,20H21A2,2 0 0,0 23,18V6C23,4.89 22.1,4 21,4Z"></path>
            </svg>
        </button>
        <button id="mobileBtn">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M17,19H7V5H17M17,1H7C5.89,1 5,1.89 5,3V21A2,2 0 0,0 7,23H17A2,2 0 0,0 19,21V3C19,1.89 18.1,1 17,1Z"></path>
            </svg>
        </button>
        <span id="tabletBtn" class="" style="flex: 1 1 auto;"></span>
        <input class="inspection-checkbox" type="checkbox" />
        <button for="" id="dashes">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M15,5H17V3H15M15,21H17V19H15M11,5H13V3H11M19,5H21V3H19M19,9H21V7H19M19,21H21V19H19M19,13H21V11H19M19,17H21V15H19M3,5H5V3H3M3,9H5V7H3M3,13H5V11H3M3,17H5V15H3M3,21H5V19H3M11,21H13V19H11M7,21H9V19H7M7,5H9V3H7V5Z"></path>
            </svg>
        </button>
        <button id="preview">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9M12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17M12,4.5C7,4.5 2.73,7.61 1,12C2.73,16.39 7,19.5 12,19.5C17,19.5 21.27,16.39 23,12C21.27,7.61 17,4.5 12,4.5Z"></path>
            </svg>
        </button>
        <button id="undo">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M20 13.5C20 17.09 17.09 20 13.5 20H6V18H13.5C16 18 18 16 18 13.5S16 9 13.5 9H7.83L10.91 12.09L9.5 13.5L4 8L9.5 2.5L10.92 3.91L7.83 7H13.5C17.09 7 20 9.91 20 13.5Z"></path>
            </svg>
        </button>
        <button id="redo">
            <svg style="display: block; max-width:22px" viewBox="0 0 24 24">
                <path fill="currentColor" d="M10.5 18H18V20H10.5C6.91 20 4 17.09 4 13.5S6.91 7 10.5 7H16.17L13.08 3.91L14.5 2.5L20 8L14.5 13.5L13.09 12.09L16.17 9H10.5C8 9 6 11 6 13.5S8 18 10.5 18Z"></path>
            </svg>
        </button>
    </div>
    <div id="canvas-wrap" class="edit">
        <iframe id="canvas-frame" srcdoc='<!DOCTYPE html>
<html lang="en">
<head>
<meta charset=""UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Document</title>
</head>
<body>

</body>
</html>' title="Canvas"></iframe>
    </div>
</div>