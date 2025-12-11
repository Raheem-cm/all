 import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './pages/Home';
import './App.css';

function App() {
  return (
    <Router>
      <div className="App">
        <header>
          <h1>CMAMBA-MOVE 🐍</h1>
          <nav>
            <a href="/">Home</a>
            <a href="/movies">Movies</a>
            <a href="/request">Request Movie</a>
          </nav>
        </header>
        
        <Routes>
          <Route path="/" element={<Home />} />
          {/* Utajaza njia zingine baadaye */}
        </Routes>
        
        <footer>
          <p>© 2023 CMAMBA-MOVE. All movies data from TMDB.</p>
        </footer>
      </div>
    </Router>
  );
}

export default App;
