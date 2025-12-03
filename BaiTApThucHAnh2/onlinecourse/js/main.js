// Sample movie data
const movies = [
    {
        id: 1,
        title: "Movie Title 1",
        description: "An exciting new release",
        image: "images/placeholder1.jpg",
        duration: "2h 30min",
        rating: "PG-13"
    },
    {
        id: 2,
        title: "Movie Title 2",
        description: "A thrilling adventure",
        image: "images/placeholder2.jpg",
        duration: "1h 45min",
        rating: "PG"
    },
    {
        id: 3,
        title: "Movie Title 3",
        description: "An epic journey",
        image: "images/placeholder3.jpg",
        duration: "2h 15min",
        rating: "PG-13"
    }
];

// Function to create movie cards
function createMovieCard(movie) {
    // Provide width/height and lazy loading to avoid layout shifts and reduce repaint storms
    return `
        <div class="movie-card">
            <img src="${movie.image}" alt="${movie.title}" width="400" height="300" loading="lazy" decoding="async" onerror="this.src='images/placeholder.jpg'" />
            <div class="movie-info">
                <h3>${movie.title}</h3>
                <p>${movie.description}</p>
                <p>Duration: ${movie.duration}</p>
                <p>Rating: ${movie.rating}</p>
                <a href="pages/booking.html?movie=${movie.id}" class="book-btn">Book Now</a>
            </div>
        </div>
    `;
}

// Function to display movies
function displayMovies() {
    const movieGrid = document.getElementById('movieGrid');
    if (movieGrid) {
        movieGrid.innerHTML = movies.map(movie => createMovieCard(movie)).join('');
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
    displayMovies();
});