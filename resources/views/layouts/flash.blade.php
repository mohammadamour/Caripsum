@if (session('success'))
    <div class="alert alert-success flash-message" style="position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; box-shadow: 0 4px 6px rgba(0,0,0,0.1); opacity: 1; transition: opacity 1s ease-out;">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger flash-message" style="position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; box-shadow: 0 4px 6px rgba(0,0,0,0.1); opacity: 1; transition: opacity 1s ease-out;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger flash-message" style="position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; box-shadow: 0 4px 6px rgba(0,0,0,0.1); opacity: 1; transition: opacity 1s ease-out;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashMessages = document.querySelectorAll('.flash-message');
        
        if (flashMessages.length > 0) {
            setTimeout(function() {
                flashMessages.forEach(function(message) {
                    // Start fading out
                    message.style.opacity = '0';
                    
                    // Remove from DOM after fade completes (1s matches css transition)
                    setTimeout(function() {
                        message.remove();
                    }, 1000);
                });
            }, 4000); // Wait 4 seconds before starting fade
        }
    });
</script>
