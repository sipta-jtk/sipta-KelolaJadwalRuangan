<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
</head>
<body>
    <p>Logging out, please wait...</p>

    <div class="container mt-5 text-center">
        <p>You have been logged out successfully.</p>
        
        <div class="mt-4">
            <a href="/penjadwalan-ruangan" class="btn btn-primary me-2">
                Return to Penjadwalan Ruangan
            </a>
            
            @if(Session::has('sipta_environment') && Session::get('sipta_environment') === 'dev')
                <a href="https://polban-space.cloudias79.com/sipta-dev/" class="btn btn-secondary">
                    Return to SIPTA Dev
                </a>
            @else
                <a href="https://polban-space.cloudias79.com/sipta/" class="btn btn-secondary">
                    Return to SIPTA
                </a>
            @endif
        </div>
    </div>
    
    <script>
        // Clear any client-side stored tokens
        sessionStorage.removeItem('sipta_token');
        localStorage.removeItem('sipta_token');
        
        // Redirect after clearing storage
        window.location.href = "{{ $redirect_url }}";
    </script>
</body>
</html>