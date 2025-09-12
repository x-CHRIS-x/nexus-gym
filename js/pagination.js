// Simple pagination functionality
document.addEventListener('DOMContentLoaded', function() {
    // Pagination buttons
    const pageButtons = document.querySelectorAll('.page-btn');
    pageButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const pageNumber = this.textContent.trim();
            if (pageNumber !== '‹ Previous' && pageNumber !== 'Next ›') {
                console.log('Navigating to page:', pageNumber);
            }
        });
    });
});
