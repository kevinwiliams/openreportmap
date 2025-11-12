import React, { useEffect, useMemo, useState } from 'react';
import {
  Circle,
  MapContainer,
  TileLayer,
  useMap,
  useMapEvents,
} from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';

const DEFAULT_CENTER = [18.1096, -77.2975];

const markerClusterAsset = (() => {
  let loader;
  return () => {
    if (loader) {
      return loader;
    }

    loader = new Promise((resolve, reject) => {
      if (window.L && window.L.markerClusterGroup) {
        resolve();
        return;
      }

      const css = document.createElement('link');
      css.rel = 'stylesheet';
      css.href = 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css';
      document.head.appendChild(css);

      const css2 = document.createElement('link');
      css2.rel = 'stylesheet';
      css2.href = 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css';
      document.head.appendChild(css2);

      const script = document.createElement('script');
      script.src = 'https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js';
      script.async = true;
      script.onload = () => resolve();
      script.onerror = (error) => reject(error);
      document.body.appendChild(script);
    });

    return loader;
  };
})();

const severityToColor = (severity) => {
  switch (severity) {
    case 'Critical':
      return '#b91c1c';
    case 'High':
      return '#f97316';
    case 'Medium':
      return '#facc15';
    case 'Low':
    default:
      return '#22c55e';
  }
};

const createMarkerIcon = (severity) => {
  const color = severityToColor(severity);
  return L.divIcon({
    className: 'report-marker',
    html: `<span style="display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;background:${color};box-shadow:0 6px 12px rgba(15,23,42,.25);color:white;font-size:14px;font-weight:700;">${severity?.[0] ?? '•'}</span>`
  });
};

function MapEventsBridge({ onBoundsChange, onMapClick, onReady }) {
  const map = useMapEvents({
    moveend() {
      const bounds = map.getBounds();
      onBoundsChange?.({
        north: bounds.getNorth(),
        south: bounds.getSouth(),
        east: bounds.getEast(),
        west: bounds.getWest(),
      });
    },
    click(event) {
      onMapClick?.(event.latlng);
    },
  });

  useEffect(() => {
    onReady?.(map);
    if (onBoundsChange) {
      const bounds = map.getBounds();
      onBoundsChange({
        north: bounds.getNorth(),
        south: bounds.getSouth(),
        east: bounds.getEast(),
        west: bounds.getWest(),
      });
    }
  }, [map, onReady]);

  return null;
}

function ClusterLayer({ reports, filters, onMarkerSelect }) {
  const map = useMap();

  useEffect(() => {
    if (!reports?.length) {
      return undefined;
    }

    let clusterGroup;
    let cancelled = false;

    markerClusterAsset()
      .then(() => {
        if (cancelled) return;
        clusterGroup = L.markerClusterGroup({
          showCoverageOnHover: false,
          disableClusteringAtZoom: 16,
        });

        reports
          .filter((feature) => {
            const props = feature.properties ?? {};
            const typeOk = filters.types.includes(props.report_type ?? props.type ?? '');
            const severityOk = props.severity ? filters.severities.includes(props.severity) : true;
            return typeOk && severityOk && feature.geometry?.coordinates;
          })
          .forEach((feature) => {
            const [lng, lat] = feature.geometry.coordinates;
            const props = feature.properties ?? {};
            const marker = L.marker([lat, lng], {
              icon: createMarkerIcon(props.severity),
            });

            const popupHtml = `
              <div class="map-popup">
                <h3>${props.report_type ?? 'Report'}</h3>
                <p style="margin:0 0 8px">${props.description ? props.description.substring(0, 140) : 'No description provided.'}</p>
                <div class="map-popup__badge">Severity · ${props.severity ?? 'Unknown'}</div>
              </div>
            `;

            marker.bindPopup(popupHtml);
            marker.on('click', () => onMarkerSelect?.(feature));

            clusterGroup.addLayer(marker);
          });

        map.addLayer(clusterGroup);
      })
      .catch(() => {
        // ignore cluster loading errors, markers will simply not cluster
      });

    return () => {
      cancelled = true;
      if (clusterGroup) {
        clusterGroup.clearLayers();
        map.removeLayer(clusterGroup);
      }
    };
  }, [map, reports, filters.types, filters.severities, onMarkerSelect]);

  return null;
}

function CommunityBoundary({ community }) {
  if (!community?.latitude || !community?.longitude) {
    return null;
  }

  return (
    <Circle
      center={[community.latitude, community.longitude]}
      radius={800}
      pathOptions={{ color: '#2563eb', fillColor: '#2563eb', fillOpacity: 0.08 }}
    />
  );
}

export default function ReportMap({
  reports,
  filters,
  onBoundsChange,
  onMapClick,
  onMarkerSelect,
  focus,
  selectedCommunity,
}) {
  const [mapInstance, setMapInstance] = useState(null);

  useEffect(() => {
    if (!mapInstance || !focus) {
      return;
    }

    const { lat, lng, zoom } = focus;
    mapInstance.flyTo([lat, lng], zoom ?? mapInstance.getZoom(), {
      duration: 0.8,
    });
  }, [mapInstance, focus]);

  const mapCenter = useMemo(() => focus ? [focus.lat, focus.lng] : DEFAULT_CENTER, [focus]);

  return (
    <MapContainer
      className="map-shell"
      center={mapCenter}
      zoom={focus?.zoom ?? 9}
      zoomControl
      preferCanvas
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />

      <MapEventsBridge
        onBoundsChange={onBoundsChange}
        onMapClick={onMapClick}
        onReady={setMapInstance}
      />

      <ClusterLayer
        reports={reports}
        filters={filters}
        onMarkerSelect={onMarkerSelect}
      />

      <CommunityBoundary community={selectedCommunity} />
    </MapContainer>
  );
}
