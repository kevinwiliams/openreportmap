import React, { useState } from 'react';
import '@material/web/drawer/drawer.js';
import '@material/web/textfield/filled-text-field.js';
import '@material/web/radio/radio.js';
import '@material/web/button/filled-button.js';
import axios from 'axios';

const ReportDrawer = () => {
  const [open, setOpen] = useState(false);
  const [formData, setFormData] = useState({
    report_type: 'Outage',
    severity: 'Low',
    description: '',
    lat: '',
    lng: '',
  });

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    axios.post('/api/reports', formData)
      .then(response => {
        console.log('Report submitted:', response.data);
        setOpen(false);
      })
      .catch(error => {
        console.error('Error submitting report:', error);
      });
  };

  return (
    <>
      <md-filled-button onClick={() => setOpen(true)}>Add Report</md-filled-button>
      <md-drawer open={open} modal>
        <div style={{ padding: '16px' }}>
          <h2>New Report</h2>
          <form onSubmit={handleSubmit}>
            <div>
              <md-radio name="report_type" value="Outage" checked={formData.report_type === 'Outage'} onchange={handleChange}>Outage</md-radio>
              <md-radio name="report_type" value="Blockage" checked={formData.report_type === 'Blockage'} onchange={handleChange}>Blockage</md-radio>
              <md-radio name="report_type" value="Damage" checked={formData.report_type === 'Damage'} onchange={handleChange}>Damage</md-radio>
              <md-radio name="report_type" value="Relief" checked={formData.report_type === 'Relief'} onchange={handleChange}>Relief</md-radio>
            </div>
            <md-filled-text-field
              label="Description"
              name="description"
              value={formData.description}
              onchange={handleChange}
            />
            <md-filled-text-field
              label="Latitude"
              name="lat"
              value={formData.lat}
              onchange={handleChange}
            />
            <md-filled-text-field
              label="Longitude"
              name="lng"
              value={formData.lng}
              onchange={handleChange}
            />
            <md-filled-button type="submit">Submit</md-filled-button>
          </form>
        </div>
      </md-drawer>
    </>
  );
};

export default ReportDrawer;
