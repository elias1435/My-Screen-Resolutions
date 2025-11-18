<?php


/**
 * Show screen resolution to admins only (front-end).
 * Add this to your theme's functions.php or a small site plugin.
 */
add_action('wp_footer', function () {
    // Only show to users who can access admin settings (i.e., admins)
    if ( ! is_user_logged_in() || ! current_user_can('manage_options') ) {
        return;
    }
    // Avoid showing inside wp-admin
    if ( is_admin() ) {
        return;
    }
    ?>
    <div id="wp-admin-screen-res"
         style="position:fixed;bottom:12px;left:12px;z-index:999999;
                background:#111;color:#fff;padding:2px 5px;border-radius:8px;
                font:10px/1.4 system-ui, -apple-system, Segoe UI, Roboto, Arial;
                box-shadow:0 4px 16px rgba(0,0,0,.2);">
        Screen Resolution: <span id="wp-admin-screen-res-val">detecting…</span>
    </div>
    <script>
        (function () {
            function update() {
                var s = window.screen || {};
                var w = s.width || window.innerWidth || 0;
                var h = s.height || window.innerHeight || 0;
                var dpr = (window.devicePixelRatio || 1);
                // Format like "1920×1080 (DPR 1.00)"
                var text = w + "×" + h + " (DPR " + dpr.toFixed(2) + ")";
                var el = document.getElementById('wp-admin-screen-res-val');
                if (el) el.textContent = text;
            }
            // Initial render
            update();
            // Update if devicePixelRatio or screen metrics change (rare, but handle zoom/scale)
            window.addEventListener('resize', update);
            // Some browsers change DPR on zoom; listen for it if available
            if (window.matchMedia) {
                try {
                    // Re-run when zoom/scale changes (heuristic)
                    ['1', '1.25', '1.5', '2', '3', '4'].forEach(function (scale) {
                        var mq = window.matchMedia("(resolution: " + scale + "dppx)");
                        if (mq && mq.addEventListener) mq.addEventListener('change', update);
                    });
                } catch (e) { /* noop */ }
            }
        }());
    </script>
    <style>
        @media print { #wp-admin-screen-res { display:none !important; } }
    </style>
    <?php
});
