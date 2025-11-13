import React from 'react';
import ReactDOM from 'react-dom/client';
import App from './App.jsx';
import ErrorBoundary from './components/ErrorBoundary.jsx';
// Import Material Web from node_modules so Vite can bundle it and resolve its
// internal bare imports (like "tslib"). Loading the package via Vite ensures
// proper dependency resolution and avoids browser-side bare-import errors.
import '@material/web/all.js';
import './index.css';
import './bootstrap';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <ErrorBoundary>
      <App />
    </ErrorBoundary>
  </React.StrictMode>,
);
