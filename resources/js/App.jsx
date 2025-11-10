import React from 'react';
import Map from './components/Map';
import ReportDrawer from './components/ReportDrawer';

function App() {
  return (
    <div className="App">
      <header className="navbar">
        <h1>OpenReportMap</h1>
        <button>Add Report</button>
      </header>
      <main>
        <Map />
        <ReportDrawer />
      </main>
    </div>
  );
}

export default App;
