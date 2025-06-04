<!DOCTYPE html>
<html>
<head>
    <title>Logging out...</title>
</head>
<body>
    <p>Logging out, please wait...</p>
    
    <script>
        // Clear any client-side stored tokens
        sessionStorage.removeItem('sipta_token');
        localStorage.removeItem('sipta_token');
        
        // Redirect after clearing storage
        window.location.href = "{{ $redirect_url }}";
    </script>
</body>
</html>