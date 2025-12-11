 import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './MovieSection.css';

function MovieSection({ title, category }) {
  const [movies, setMovies] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchMovies = async () => {
      try {
        // HII NI API YA KUJARIBU - UTABADILISHA BAADAYE
        const response = await axios.get(
          `https://api.themoviedb.org/3/movie/popular?api_key=YOUR_TMDB_API_KEY&language=en-US&page=1`
        );
        setMovies(response.data.results.slice(0, 6));
      } catch (error) {
        console.error('Error loading movies:', error);
        // Data ya mfano ikiwa API haifanyi kazi
        setMovies([
          { id: 1, title: "Movie 1", poster_path: null },
          { id: 2, title: "Movie 2", poster_path: null },
          { id: 3, title: "Movie 3", poster_path: null },
          { id: 4, title: "Movie 4", poster_path: null },
        ]);
      } finally {
        setLoading(false);
      }
    };

    fetchMovies();
  }, [category]);

  if (loading) {
    return (
      <section className="movie-section">
        <h2>{title}</h2>
        <div className="loading">Inapakia {title.toLowerCase()}...</div>
      </section>
    );
  }

  return (
    <section className="movie-section">
      <h2>{title}</h2>
      <div className="movies-grid">
        {movies.map(movie => (
          <div key={movie.id} className="movie-card">
            <div className="movie-poster">
              {movie.poster_path ? (
                <img 
                  src={`https://image.tmdb.org/t/p/w200${movie.poster_path}`} 
                  alt={movie.title}
                />
              ) : (
                <div className="placeholder-poster">{movie.title}</div>
              )}
            </div>
            <h4>{movie.title}</h4>
          </div>
        ))}
      </div>
    </section>
  );
}

export default MovieSection;
