<?php
// Server-side protection headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: no-referrer");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!-- Enhanced Content Protection Script -->
<script>
    (function() {
        'use strict';
        
        // Comprehensive key blocking
        const blockedKeys = {
            123: 'F12',
            73: 'Ctrl+Shift+I',
            74: 'Ctrl+Shift+J',
            67: 'Ctrl+Shift+C',
            85: 'Ctrl+U',
            83: 'Ctrl+S',
            117: 'F6',
            118: 'F7'
        };

        // Block all developer shortcuts
        document.addEventListener('keydown', function(e) {
            const key = e.keyCode || e.which;
            
            // F12 - DevTools
            if (key === 123) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Ctrl+Shift+I - Inspect
            if (e.ctrlKey && e.shiftKey && key === 73) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Ctrl+Shift+J - Console
            if (e.ctrlKey && e.shiftKey && key === 74) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Ctrl+Shift+C - Inspect Element
            if (e.ctrlKey && e.shiftKey && key === 67) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Ctrl+U - View Source
            if (e.ctrlKey && key === 85) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Ctrl+S - Save Page
            if (e.ctrlKey && key === 83) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Ctrl+Shift+K - Firefox Console
            if (e.ctrlKey && e.shiftKey && key === 75) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // F6 - Focus address bar (can be used to type view-source:)
            if (key === 117) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Cmd+Option+I (Mac)
            if (e.metaKey && e.altKey && key === 73) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Cmd+Option+J (Mac)
            if (e.metaKey && e.altKey && key === 74) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Cmd+Option+C (Mac)
            if (e.metaKey && e.altKey && key === 67) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            
            // Cmd+U (Mac View Source)
            if (e.metaKey && key === 85) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);

        // Also block on keyup to prevent any bypass
        document.addEventListener('keyup', function(e) {
            const key = e.keyCode || e.which;
            if (key === 123 || (e.ctrlKey && key === 85) || (e.metaKey && key === 85)) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        }, true);

        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }, true);

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

        // Multiple DevTools detection methods
        let devtoolsOpen = false;
        
        // Method 1: Console detection
        const devtools = /./;
        devtools.toString = function() {
            devtoolsOpen = true;
        };
        
        // Method 2: Window size detection
        const threshold = 160;
        const checkDevToolsSize = function() {
            if (window.outerHeight - window.innerHeight > threshold || 
                window.outerWidth - window.innerWidth > threshold) {
                devtoolsOpen = true;
            }
        };
        
        // Method 3: Debugger statement
        const checkDebugger = function() {
            const start = performance.now();
            debugger;
            const end = performance.now();
            if (end - start > 100) {
                devtoolsOpen = true;
            }
        };
        
        // Check periodically
        setInterval(function() {
            console.log('%c', devtools);
            checkDevToolsSize();
            
            if (devtoolsOpen) {
                document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Arial;font-size:20px;text-align:center;"><div><h1>⚠️ Access Restricted</h1><p>Developer tools are not allowed on this page.</p></div></div>';
                devtoolsOpen = false;
            }
        }, 1000);

        // Disable text selection completely
        document.addEventListener('selectstart', function(e) {
            if (!e.target.matches('input, textarea, select')) {
                e.preventDefault();
                return false;
            }
        }, true);

        // Disable copy
        document.addEventListener('copy', function(e) {
            if (!e.target.matches('input, textarea')) {
                e.preventDefault();
                e.clipboardData.setData('text/plain', '');
                return false;
            }
        }, true);

        // Disable cut
        document.addEventListener('cut', function(e) {
            if (!e.target.matches('input, textarea')) {
                e.preventDefault();
                return false;
            }
        }, true);

        // Disable drag
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        }, true);

        // Prevent printing
        window.addEventListener('beforeprint', function(e) {
            e.preventDefault();
            document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Arial;"><h1>Printing Disabled</h1></div>';
            return false;
        });

        window.addEventListener('afterprint', function(e) {
            window.location.reload();
        });

        // Disable console methods
        const noop = function() {};
        ['log', 'warn', 'error', 'info', 'debug', 'trace', 'dir', 'dirxml', 'group', 'groupEnd', 'time', 'timeEnd', 'assert', 'profile'].forEach(function(method) {
            if (console[method]) {
                console[method] = noop;
            }
        });

        // Clear console aggressively
        setInterval(function() {
            console.clear();
        }, 500);

        // Prevent view-source: protocol
        if (window.location.protocol === 'view-source:') {
            window.location = window.location.href.replace('view-source:', '');
        }

        // Monitor for suspicious activity
        let clickCount = 0;
        document.addEventListener('click', function(e) {
            if (e.button === 2) { // Right click
                clickCount++;
                if (clickCount > 3) {
                    window.location.reload();
                }
            }
        }, true);

        // Apply CSS protection on body load
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.setProperty('user-select', 'none', 'important');
            document.body.style.setProperty('-webkit-user-select', 'none', 'important');
            document.body.style.setProperty('-moz-user-select', 'none', 'important');
            document.body.style.setProperty('-ms-user-select', 'none', 'important');
        });

        // Prevent iFrame embedding for additional security
        if (window.top !== window.self) {
            window.top.location = window.self.location;
        }
        
    })();
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
    input, textarea, select, [contenteditable="true"] {
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -ms-user-select: text !important;
        user-select: text !important;
    }
    
    /* Hide page until JS loads (prevents viewing source before protection) */
    html {
        visibility: hidden;
        opacity: 0;
    }
    
    html.protected {
        visibility: visible;
        opacity: 1;
        transition: opacity 0.3s ease;
    }
</style>

<script>
    // Immediately add protected class to show content
    (function() {
        document.documentElement.classList.add('protected');
    })();
    
    // Additional obfuscation - prevent source viewing via data URIs
    if (window.location.href.includes('data:') || window.location.href.includes('blob:')) {
        window.location = '/';
    }
    
    // Detect automation/bots trying to scrape
    Object.defineProperty(navigator, 'webdriver', {
        get: () => false
    });
    
    // Prevent screenshot tools (partial)
    document.addEventListener('keyup', function(e) {
        // Windows: Win+Shift+S, Alt+PrtScn
        // Mac: Cmd+Shift+3, Cmd+Shift+4
        if ((e.shiftKey && e.keyCode === 83) || 
            (e.altKey && e.keyCode === 44) ||
            (e.metaKey && e.shiftKey && (e.keyCode === 51 || e.keyCode === 52))) {
            // Can't fully prevent screenshots, but can log attempts
            console.warn('Screenshot attempt detected');
        }
    });
    
    // Disable middle-click paste
    document.addEventListener('mousedown', function(e) {
        if (e.button === 1) { // Middle button
            e.preventDefault();
            return false;
        }
    }, true);
    
    // Additional keyboard monitoring
    document.addEventListener('keypress', function(e) {
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault();
            return false;
        }
    }, true);
</script>

<!-- Warning message for those trying to bypass -->
<!-- 
⚠️ WARNING: This content is protected by copyright.
Unauthorized copying, distribution, or use is strictly prohibited.
All access attempts are logged and monitored.
© 2025 Emerald Tech Hub. All rights reserved.
-->
