# OpenReportMap Setup - Changes Made & Recommendations

## ✅ Changes Completed

### 1. Created Laravel Blade Template
- **File**: `resources/views/app.blade.php`
- **Purpose**: Replaces the static `public/index.html`
- **Key Feature**: Uses `@vite()` directive to inject bundled assets
- **Advantages**:
  - Laravel handles page serving
  - CSRF protection available
  - Can access Laravel helpers/data
  - Proper asset versioning for caching

### 2. Updated Route
- **File**: `routes/web.php`
- **Change**: Route now returns `view('app')` instead of `view('welcome')`
- **Effect**: Homepage serves the new Blade template

### 3. Updated Vite Configuration
- **File**: `vite.config.js`
- **Change**: Entry point changed from `['resources/css/app.css', 'resources/js/app.js']` to `['resources/js/main.jsx']`
- **Why**: Single entry point for React app; CSS is imported within JSX

### 4. Updated Main JSX Entry
- **File**: `resources/js/main.jsx`
- **Change**: Added import for `bootstrap.js`
- **Purpose**: Ensures Axios is configured globally before app renders

---

## 📊 Application Flow

```
User visits http://localhost/ or your domain
    ↓
Laravel route → renders resources/views/app.blade.php
    ↓
@vite() injects:
  - main.jsx (bundled React app)
  - app.css (Tailwind styles)
    ↓
main.jsx loads:
  - App.jsx (main component)
  - index.css (component styles)
  - bootstrap.js (Axios setup)
    ↓
App.jsx initializes:
  - Fetches /api/countries
  - Fetches /api/disasters/active
  - Fetches /api/utility-types
  - Fetches /api/reports (every 10 seconds)
    ↓
Components render:
  - ReportMap (Leaflet map with markers)
  - ReportDrawer (form to add reports)
  - FilterHoverPanel (filter controls)
  - ReportDetailsSheet (details display)
```

---

## 🎯 API Endpoints (Already Working)

Your `routes/api.php` has all necessary endpoints:

```
GET    /api/countries
GET    /api/countries/{code}/parishes
GET    /api/parishes/{code}/communities
GET    /api/disasters/active
GET    /api/utility-types
GET    /api/providers
GET    /api/reports
GET    /api/embed/preview
POST   /api/reports
PATCH  /api/reports/{report}
DELETE /api/reports/{report}
```

---

## 🔧 Recommended Additional Changes

### 1. Remove or Repurpose public/index.html
- **Current**: Static HTML file trying to load React directly
- **Recommendation**: Delete it or keep as documentation
- **Reason**: Now handled by Laravel's Blade template

### 2. Add Error Boundary (Optional)
Create `resources/js/components/ErrorBoundary.jsx`:
```jsx
import React from 'react';

export default class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { hasError: false };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    console.error('Error caught:', error, errorInfo);
  }

  render() {
    if (this.state.hasError) {
      return <div>Something went wrong. Please refresh the page.</div>;
    }
    return this.props.children;
  }
}
```

Then wrap App in `main.jsx`:
```jsx
import ErrorBoundary from './components/ErrorBoundary';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <ErrorBoundary>
      <App />
    </ErrorBoundary>
  </React.StrictMode>,
);
```

### 3. Environment Configuration (Optional)
Create `.env.local` (or `.env`) if not exists:
```
VITE_API_BASE_URL=http://localhost:4000/api
```

Update `bootstrap.js`:
```javascript
window.axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL || '/api';
```

### 4. Add .gitignore Entry
If not already present in root `.gitignore`:
```
# Vite
dist/
public/hot
```

### 5. Update Layout Meta Tags (Optional)
In `resources/views/app.blade.php`, consider adding:
```blade
<meta name="description" content="OpenReportMap - Report utility outages and issues in real-time">
<meta name="theme-color" content="#6366f1">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
```

---

## 🚀 Development Workflow

### Start Development Server
```bash
# Terminal 1: Laravel dev server
php artisan serve --port=4000

# Terminal 2: Vite dev server
npm run dev
```

Your app will be available at `http://localhost:8000`

### Production Build
```bash
npm run build
php artisan build  # if using any optimization
```

---

## ✨ File Structure Summary

```
app.blade.php (new)
    ↓ serves
main.jsx
    ↓ imports
├── App.jsx (main component)
├── index.css (global styles)
├── bootstrap.js (Axios config)
└── components/
    ├── Map.jsx (Leaflet map)
    ├── ReportDrawer.jsx
    ├── FilterHoverPanel.jsx
    └── ReportDetailsSheet.jsx

resources/css/app.css (Tailwind CSS)
```

---

## ✅ Verification Checklist

- [ ] Run `npm install` (if needed)
- [ ] Run `npm run dev` to start Vite
- [ ] Run `php artisan serve` for Laravel
- [ ] Visit `http://localhost:8000`
- [ ] Verify map loads
- [ ] Check browser console for errors
- [ ] Test API calls (should see requests in Network tab)
- [ ] Try adding a report
- [ ] Test filters

---

## 🐛 Troubleshooting

If map doesn't load:
1. Check browser console for errors
2. Verify Vite is running (`npm run dev`)
3. Verify Laravel is running (`php artisan serve`)
4. Check if API endpoints are accessible (`http://localhost:8000/api/countries`)
5. Check CORS headers if API is on different domain

If styles are missing:
1. Ensure `npm run build` completed successfully
2. Check that Tailwind scans your JSX files
3. Verify `@vite()` directive is in blade template

