<!-- Content Protection Script -->
<script>
    // Disable right-click context menu
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        return false;
    }, false);

    // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U
    document.addEventListener('keydown', function(e) {
        // F12
        if (e.keyCode === 123) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+I (Inspect)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 73) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+J (Console)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 74) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+Shift+C (Inspect Element)
        if (e.ctrlKey && e.shiftKey && e.keyCode === 67) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+U (View Source)
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
            return false;
        }
        
        // Ctrl+S (Save Page)
        if (e.ctrlKey && e.keyCode === 83) {
            e.preventDefault();
            return false;
        }
    }, false);

    // Disable text selection
    document.addEventListener('selectstart', function(e) {
        e.preventDefault();
        return false;
    }, false);

    // Disable copy
    document.addEventListener('copy', function(e) {
        e.preventDefault();
        return false;
    }, false);

    // Disable cut
    document.addEventListener('cut', function(e) {
        e.preventDefault();
        return false;
    }, false);

    // Disable drag
    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
        return false;
    }, false);

    // Detect DevTools
    (function() {
        const devtools = /./;
        devtools.toString = function() {
            this.opened = true;
        };
        
        const checkDevTools = setInterval(function() {
            console.log('%c', devtools);
            if (devtools.opened) {
                alert('Developer tools detected! This action has been logged.');
                window.location.href = 'unauthorized.php';
                clearInterval(checkDevTools);
            }
            devtools.opened = false;
        }, 1000);
    })();

    // Prevent printing
    window.addEventListener('beforeprint', function(e) {
        e.preventDefault();
        alert('Printing is disabled for security reasons.');
        return false;
    });

    // Disable certain console methods
    if (typeof console !== 'undefined') {
        console.log = function() {};
        console.warn = function() {};
        console.error = function() {};
        console.info = function() {};
        console.debug = function() {};
    }

    // Clear console periodically
    setInterval(function() {
        console.clear();
    }, 2000);

    // Watermark protection
    document.body.style.setProperty('user-select', 'none', 'important');
    document.body.style.setProperty('-webkit-user-select', 'none', 'important');
    document.body.style.setProperty('-moz-user-select', 'none', 'important');
    document.body.style.setProperty('-ms-user-select', 'none', 'important');
</script>

<style>
    /* Additional CSS protection */
    * {
        -webkit-user-select: none !important;
        -moz-user-select: none !important;
        -ms-user-select: none !important;
        user-select: none !important;
        -webkit-touch-callout: none !important;
    }
    
    /* Allow form inputs to be selectable */
    input, textarea, select {
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
        user-select: text !important;
    }
</style>
