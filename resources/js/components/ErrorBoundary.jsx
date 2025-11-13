import React from 'react';

export default class ErrorBoundary extends React.Component {
  constructor(props) {
    super(props);
    this.state = { 
      hasError: false,
      error: null,
      errorInfo: null
    };
  }

  static getDerivedStateFromError(error) {
    return { hasError: true };
  }

  componentDidCatch(error, errorInfo) {
    console.error('Error caught by boundary:', error);
    console.error('Error info:', errorInfo);
    this.setState({
      error,
      errorInfo
    });
  }

  render() {
    if (this.state.hasError) {
      return (
        <div style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          justifyContent: 'center',
          minHeight: '100vh',
          padding: '20px',
          fontFamily: 'system-ui, -apple-system, sans-serif',
          backgroundColor: '#f8f9fb',
        }}>
          <h1 style={{ color: '#dc2626', marginBottom: '10px' }}>Oops! Something went wrong</h1>
          <p style={{ color: '#666', marginBottom: '20px' }}>Please try refreshing the page</p>
          {process.env.NODE_ENV === 'development' && this.state.error && (
            <details style={{
              padding: '10px',
              backgroundColor: '#fee2e2',
              borderRadius: '4px',
              maxWidth: '600px',
              whiteSpace: 'pre-wrap',
              fontSize: '12px',
              color: '#7f1d1d',
              fontFamily: 'monospace',
            }}>
              <summary>Error Details</summary>
              {this.state.error.toString()}
              {this.state.errorInfo && this.state.errorInfo.componentStack}
            </details>
          )}
          <button
            onClick={() => window.location.reload()}
            style={{
              marginTop: '20px',
              padding: '10px 20px',
              backgroundColor: '#3b82f6',
              color: 'white',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer',
              fontSize: '16px',
            }}
          >
            Reload Page
          </button>
        </div>
      );
    }

    return this.props.children;
  }
}
