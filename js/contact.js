document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            console.log('Form data:', Object.fromEntries(formData)); // Debug line
            
            fetch('config/process_contact.php', {  // Changed path
                method: 'POST',
                body: formData
            })
            .then(response => response.text())  // Changed to text() to see raw response
            .then(data => {
                console.log('Raw response:', data);  // Debug line
                try {
                    const jsonData = JSON.parse(data);
                    if (jsonData.success) {
                        alert('Message sent successfully!');
                        this.reset();
                    } else {
                        alert(jsonData.message || 'Failed to send message');
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Server response error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred. Please try again later.');
            });
        });
    }
});