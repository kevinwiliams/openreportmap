import React, { useEffect, useState } from 'react';
import { MapContainer, TileLayer, Marker, Popup } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import axios from 'axios';

const Map = () => {
  const [reports, setReports] = useState([]);

  useEffect(() => {
    // Fetch reports from the API
    axios.get('/api/reports')
      .then(response => {
        setReports(response.data);
      })
      .catch(error => {
        console.error('Error fetching reports:', error);
      });
  }, []);

  return (
    <MapContainer center={[18.1096, -77.2975]} zoom={9} style={{ height: 'calc(100vh - 64px)', width: '100%' }}>
      <TileLayer
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      />
      {reports.map(report => (
        <Marker key={report.id} position={[report.precise_latitude, report.precise_longitude]}>
          <Popup>
            <h2>{report.report_type}</h2>
            <p>{report.description}</p>
          </Popup>
        </Marker>
      ))}
    </MapContainer>
  );
};

export default Map;
