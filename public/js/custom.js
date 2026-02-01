// Add custom sidebar class on page load
document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('sidebar-mini-custom');
    
    // Remove sidebar-collapse toggle behavior
    var pushmenu = document.querySelector('[data-widget="pushmenu"]');
    if (pushmenu) {
        pushmenu.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
    }
});
