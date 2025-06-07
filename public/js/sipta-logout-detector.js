// Check for logout cookie on page load
document.addEventListener('DOMContentLoaded', function() {
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    // Check if logout cookie exists
    if (getCookie('sipta_logout') === '1') {
        console.log('Detected logout action - clearing local storage');
        
        // Clear any stored tokens
        sessionStorage.removeItem('sipta_token');
        localStorage.removeItem('sipta_token');
        
        // Remove the cookie
        document.cookie = 'sipta_logout=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        
        // If on a protected page, redirect
        if (window.location.href.includes('/admin/')) {
            window.location.href = '/penjadwalan-ruangan/login';
        }
    }
});