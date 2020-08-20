<!-- START: RUN GD RATING SYSTEM PRO JAVASCRIPT -->
<script type="text/javascript">
    ;(function($, window, document, undefined) {
        $(document).ready(function(){
            if (typeof window.wp.gdrts.core !== "undefined") {
                window.wp.gdrts.core.run();
            } else {
                if (window.console) {
                    console.log("INIT ERROR: GD Rating System - JavaScript not initialized properly.");
                }
            }
        });
    })(jQuery, window, document);
</script>
<!-- START: RUN GD RATING SYSTEM PRO JAVASCRIPT -->
