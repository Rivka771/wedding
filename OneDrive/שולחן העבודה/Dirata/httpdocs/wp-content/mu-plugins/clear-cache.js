jQuery(document).ready(function($) {
    // Add click event to the clear cache button
    $('#wp-admin-bar-clear_cache_button a').on('click', function(e) {
        e.preventDefault();

        // Add spinner and change text to "מנקה קאש..."
        $(this).html('<span class="spinner-border"></span> מנקה מטמון...');

        // Perform AJAX request to clear the cache
        $.ajax({
            url: clear_cache_vars.ajax_url,  // AJAX URL provided by wp_localize_script
            type: 'post',
            data: {
                action: 'clear_cache',       // Action that triggers the cache clear
                nonce: clear_cache_vars.nonce // Security nonce
            },
            success: function(response) {
                if (response.success) {
                    // Simulate a delay of 2 seconds before showing success message
                    setTimeout(function() {
                        $('#wp-admin-bar-clear_cache_button a').text('המטמון נוקה בהצלחה!');
                    }, 2000); // 2000ms = 2 seconds
                } else {
                    // Display error message immediately
                    $('#wp-admin-bar-clear_cache_button a').text('שגיאה בניקוי המטמון');
                }
            },
            error: function() {
                // Handle error
                $('#wp-admin-bar-clear_cache_button a').text('שגיאה בניקוי המטמון');
            }
        });
    });
});
