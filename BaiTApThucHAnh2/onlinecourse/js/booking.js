// Booking page functionality
document.addEventListener('DOMContentLoaded', () => {
    // Get movie ID from URL if it exists
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('movie');

    // Populate movie select dropdown
    const movieSelect = document.getElementById('movieSelect');
    movies.forEach(movie => {
        const option = document.createElement('option');
        option.value = movie.id;
        option.textContent = movie.title;
        movieSelect.appendChild(option);
    });

    // Set selected movie if movieId exists
    if (movieId) {
        movieSelect.value = movieId;
    }

    // Set minimum date to today
    const dateInput = document.getElementById('date');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    dateInput.value = today;

    // Handle form submission
    const bookingForm = document.getElementById('bookingForm');
    bookingForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = {
            movie: movieSelect.value,
            date: dateInput.value,
            time: document.getElementById('time').value,
            tickets: document.getElementById('tickets').value
        };

        // Here you would typically send this data to a server
        // For now, we'll just show an alert
        alert(`Booking confirmed!\n\nMovie: ${movieSelect.options[movieSelect.selectedIndex].text}\nDate: ${formData.date}\nTime: ${formData.time}\nTickets: ${formData.tickets}`);
        
        // Reset form
        bookingForm.reset();
    });
});