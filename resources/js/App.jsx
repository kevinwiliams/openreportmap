import React, { useCallback, useEffect, useMemo, useState } from 'react';
import axios from 'axios';
import L from 'leaflet';

import ReportMap from './components/Map';
import ReportDrawer from './components/ReportDrawer';
import FilterHoverPanel from './components/FilterHoverPanel';
import ReportDetailsSheet from './components/ReportDetailsSheet';

import '@material/web/top-app-bar/top-app-bar.js';
import '@material/web/iconbutton/icon-button.js';
import '@material/web/button/filled-button.js';
import '@material/web/progress/circular-progress.js';

import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

L.Icon.Default.mergeOptions({
  iconRetinaUrl: markerIcon2x,
  iconUrl: markerIcon,
  shadowUrl: markerShadow,
});

function App() {
  const [drawerOpen, setDrawerOpen] = useState(false);
  const [reports, setReports] = useState([]);
  const [loadingReports, setLoadingReports] = useState(false);
  const [disasters, setDisasters] = useState([]);
  const [activeDisasterId, setActiveDisasterId] = useState('');
  const [countries, setCountries] = useState([]);
  const [parishCache, setParishCache] = useState({});
  const [communityCache, setCommunityCache] = useState({});
  const [utilityTypes, setUtilityTypes] = useState([]);
  const [providerCache, setProviderCache] = useState({});
  const [filters, setFilters] = useState({
    types: ['Outage', 'Blockage', 'Damage', 'Relief'],
    severities: ['Low', 'Medium', 'High', 'Critical'],
  });
  const [bounds, setBounds] = useState(null);
  const [mapFocus, setMapFocus] = useState(null);
  const [highlightCommunity, setHighlightCommunity] = useState(null);
  const [initialCoordinates, setInitialCoordinates] = useState(null);
  const [selectedReport, setSelectedReport] = useState(null);
  const [resolvingId, setResolvingId] = useState(null);
  const [lastError, setLastError] = useState('');

  useEffect(() => {
    const bootstrap = async () => {
      try {
        const [countriesResponse, disastersResponse, utilitiesResponse] = await Promise.all([
          axios.get('/api/countries'),
          axios.get('/api/disasters/active'),
          axios.get('/api/utility-types'),
        ]);

        setCountries(countriesResponse.data);
        setDisasters(disastersResponse.data);
        setUtilityTypes(utilitiesResponse.data);

        if (disastersResponse.data.length) {
          setActiveDisasterId(disastersResponse.data[0].id);
        }
      } catch (error) {
        setLastError('Unable to load reference data.');
      }
    };

    bootstrap();
  }, []);

  const fetchReports = useCallback(async (silent = false) => {
    if (!activeDisasterId) return;
    if (!silent) setLoadingReports(true);

    try {
      const params = { disaster_id: activeDisasterId };
      if (bounds) {
        params.bounds = `${bounds.north},${bounds.south},${bounds.east},${bounds.west}`;
      }
      const response = await axios.get('/api/reports', { params });
      setReports(response.data?.features ?? []);
      setLastError('');
    } catch (error) {
      setLastError('Unable to load reports.');
    } finally {
      if (!silent) setLoadingReports(false);
    }
  }, [activeDisasterId, bounds]);

  useEffect(() => {
    fetchReports();
  }, [fetchReports]);

  useEffect(() => {
    if (!activeDisasterId) return;
    const interval = setInterval(() => fetchReports(true), 10000);
    return () => clearInterval(interval);
  }, [activeDisasterId, fetchReports]);

  const loadParishes = useCallback(async (countryCode) => {
    if (!countryCode) return [];
    if (parishCache[countryCode]) return parishCache[countryCode];
    const response = await axios.get(`/api/countries/${countryCode}/parishes`);
    setParishCache((prev) => ({ ...prev, [countryCode]: response.data }));
    return response.data;
  }, [parishCache]);

  const loadCommunities = useCallback(async (parishCode) => {
    if (!parishCode) return [];
    if (communityCache[parishCode]) return communityCache[parishCode];
    const response = await axios.get(`/api/parishes/${parishCode}/communities`);
    setCommunityCache((prev) => ({ ...prev, [parishCode]: response.data }));
    return response.data;
  }, [communityCache]);

  const loadProviders = useCallback(async (utilityTypeId, countryCode) => {
    if (!utilityTypeId) return [];
    if (providerCache[utilityTypeId]) return providerCache[utilityTypeId];
    const response = await axios.get('/api/providers', {
      params: { utility_type_id: utilityTypeId, country_code: countryCode },
    });
    setProviderCache((prev) => ({ ...prev, [utilityTypeId]: response.data }));
    return response.data;
  }, [providerCache]);

  const handleReportCreated = (feature) => {
    setReports((prev) => [feature, ...prev.filter((existing) => existing.id !== feature.id)]);
  };

  const counts = useMemo(() => {
    const typeCounts = {};
    const severityCounts = {};
    reports.forEach((feature) => {
      const type = feature.properties?.report_type;
      const severity = feature.properties?.severity;
      if (type) typeCounts[type] = (typeCounts[type] ?? 0) + 1;
      if (severity) severityCounts[severity] = (severityCounts[severity] ?? 0) + 1;
    });
    return { types: typeCounts, severities: severityCounts };
  }, [reports]);

  const handleBoundsChange = (incomingBounds) => {
    setBounds(incomingBounds);
  };

  const handleMapClick = (latlng) => {
    setInitialCoordinates(latlng);
    setDrawerOpen(true);
  };

  const handleMarkerSelect = (feature) => {
    setSelectedReport(feature);
  };

  const handleResolveReport = async (feature) => {
    setResolvingId(feature.id);
    try {
      const response = await axios.patch(`/api/reports/${feature.id}`, { status: 'Resolved' });
      const updatedFeature = response.data;
      setReports((prev) => prev.map((item) => (item.id === updatedFeature.id ? updatedFeature : item)));
      setSelectedReport(updatedFeature);
    } catch (error) {
      setLastError('Unable to update report status.');
    } finally {
      setResolvingId(null);
    }
  };

  const handleFlagReport = async (feature) => {
    try {
      const response = await axios.patch(`/api/reports/${feature.id}`, { is_flagged: 1 });
      const flagged = response.data;
      setReports((prev) => prev.map((item) => (item.id === flagged.id ? flagged : item)));
      setSelectedReport(flagged);
    } catch (error) {
      setLastError('Unable to flag report.');
    }
  };

  const filteredReports = useMemo(() => {
    return reports.filter((feature) => {
      const props = feature.properties ?? {};
      const typeOk = filters.types.includes(props.report_type);
      const severityOk = props.severity ? filters.severities.includes(props.severity) : true;
      return typeOk && severityOk;
    });
  }, [reports, filters]);

  const consumeInitialCoordinates = () => setInitialCoordinates(null);

  useEffect(() => {
    if (!selectedReport) return;
    const updated = reports.find((feature) => feature.id === selectedReport.id);
    if (updated && updated !== selectedReport) {
      setSelectedReport(updated);
    }
  }, [reports, selectedReport]);

  return (
    <div className="App">
      <md-top-app-bar headline="OpenReportMap" className="navbar">
        <md-icon-button slot="navigationIcon" aria-label="Refresh" onClick={() => fetchReports()}>
          <span slot="icon">refresh</span>
        </md-icon-button>
        <md-filled-button slot="actionItems" onClick={() => setDrawerOpen(true)}>
          Add report
        </md-filled-button>
      </md-top-app-bar>

      <main>
        <div className="map-shell">
          <ReportMap
            reports={filteredReports}
            filters={filters}
            onBoundsChange={handleBoundsChange}
            onMapClick={handleMapClick}
            onMarkerSelect={handleMarkerSelect}
            focus={mapFocus}
            selectedCommunity={highlightCommunity}
          />

          <FilterHoverPanel
            filters={filters}
            onFiltersChange={setFilters}
            counts={counts}
          />

          {selectedReport ? (
            <ReportDetailsSheet
              report={selectedReport}
              onClose={() => setSelectedReport(null)}
              onResolve={handleResolveReport}
              onFlag={handleFlagReport}
              isResolving={resolvingId === selectedReport.id}
            />
          ) : null}

          <div className="floating-add-button">
            <md-filled-button onClick={() => setDrawerOpen(true)}>Report +</md-filled-button>
          </div>

          {loadingReports ? (
            <div style={{ position: 'absolute', top: 120, left: 32, display: 'flex', alignItems: 'center', gap: '12px', background: 'rgba(255,255,255,0.9)', padding: '12px 16px', borderRadius: '16px', boxShadow: '0 12px 24px rgba(15,23,42,0.12)' }}>
              <md-circular-progress indeterminate />
              <span>Refreshing reports…</span>
            </div>
          ) : null}

          {lastError ? (
            <div style={{ position: 'absolute', bottom: 24, left: 24, background: 'rgba(239,68,68,0.92)', color: 'white', padding: '12px 16px', borderRadius: '16px', boxShadow: '0 14px 32px rgba(239,68,68,0.35)' }}>
              {lastError}
            </div>
          ) : null}
        </div>
      </main>

      <ReportDrawer
        open={drawerOpen}
        onClose={() => setDrawerOpen(false)}
        onReportCreated={handleReportCreated}
        disasters={disasters}
        activeDisasterId={activeDisasterId}
        onActiveDisasterChange={setActiveDisasterId}
        countries={countries}
        loadParishes={loadParishes}
        parishOptions={parishCache}
        loadCommunities={loadCommunities}
        communityOptions={communityCache}
        utilityTypes={utilityTypes}
        loadProviders={loadProviders}
        providerOptions={providerCache}
        initialCoordinates={initialCoordinates}
        onConsumeInitialCoordinates={consumeInitialCoordinates}
        onFocusMap={(focus, community) => {
          setMapFocus(focus);
          setHighlightCommunity(community);
        }}
      />
    </div>
  );
}

export default App;
