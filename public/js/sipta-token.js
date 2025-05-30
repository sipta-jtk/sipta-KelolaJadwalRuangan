/**
 * SIPTA Token Helper
 * This script manages the SIPTA token for authentication across pages
 */
(function() {
    // When document is ready
    $(document).ready(function() {
        // Get token from URL if present
        const urlParams = new URLSearchParams(window.location.search);
        const urlToken = urlParams.get('token');
        
        // If token is in URL, store it in sessionStorage
        if (urlToken) {
            sessionStorage.setItem('sipta_token', urlToken);
        }
        
        // Add token to all AJAX requests
        $(document).ajaxSend(function(e, xhr, options) {
            // First try to get token from meta tag
            let token = $('meta[name="sipta-token"]').attr('content');
            
            // If not in meta tag, check sessionStorage
            if (!token) {
                token = sessionStorage.getItem('sipta_token');
            }
            
            // If we have a token, add it to the request
            if (token) {
                // If the URL already has query parameters, append the token
                if (options.url.indexOf('?') !== -1) {
                    options.url += '&token=' + token;
                } else {
                    options.url += '?token=' + token;
                }
            }
        });
        
        // Add token to links with data-append-token attribute
        $('a[data-append-token="true"]').each(function() {
            const token = $('meta[name="sipta-token"]').attr('content') || 
                          sessionStorage.getItem('sipta_token');
            
            if (token) {
                let href = $(this).attr('href');
                const separator = href.indexOf('?') !== -1 ? '&' : '?';
                $(this).attr('href', href + separator + 'token=' + token);
            }
        });
    });
})();