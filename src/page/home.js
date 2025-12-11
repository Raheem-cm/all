 import React from 'react';
import MovieSection from '../components/MovieSection';
import './Home.css';

function Home() {
  return (
    <div className="home">
      <section className="hero">
        <h2>Karibu kwenye CMAMBA-MOVE</h2>
        <p>Pata movie zako zipendwa hapa. Ukikosa kitu, omba na tutakuletea!</p>
      </section>
      
      <MovieSection title="🔥 Inavuma Wiki Hii" category="trending" />
      <MovieSection title="😂 Vituko" category="comedy" />
      <MovieSection title="🔫 Vitendo" category="action" />
      <MovieSection title="🎬 Sinema za Kibenghi" category="bengali" />
      
      <section className="request-box">
        <h3>Hukupati unachotafuta?</h3>
        <button>+ OMBA MOVIE</button>
      </section>
    </div>
  );
}

export default Home;
