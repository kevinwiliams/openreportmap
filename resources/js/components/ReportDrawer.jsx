import React, { useCallback, useEffect, useMemo, useState } from 'react';
import { createPortal } from 'react-dom';
import axios from 'axios';
import { MapContainer, Marker, TileLayer, useMapEvents } from 'react-leaflet';
import L from 'leaflet';

import 'leaflet/dist/leaflet.css';

const REPORT_TYPES = [
  {
    value: 'Outage',
    emoji: '⚡',
    title: 'Outage',
    helper: 'Report utility disruptions such as power, water, data or banking outages.',
  },
  {
    value: 'Blockage',
    emoji: '🚧',
    title: 'Blockage',
    helper: 'Flag blocked roads, landslides, flood debris or impassable corridors.',
  },
  {
    value: 'Damage',
    emoji: '⚠️',
    title: 'Damage',
    helper: 'Capture structural damage, downed poles, broken bridges or major hazards.',
  },
  {
    value: 'Relief',
    emoji: '🏠',
    title: 'Relief Point',
    helper: 'List shelters, distribution points, medical tents and community aid hubs.',
  },
];

const BLOCKAGE_CATEGORIES = [
  { value: 'Tree / Debris', label: 'Tree or debris' },
  { value: 'Flooding', label: 'Flooding' },
  { value: 'Landslide', label: 'Landslide' },
  { value: 'Utility Pole', label: 'Utility pole' },
  { value: 'Vehicle', label: 'Overturned vehicle' },
  { value: 'Other', label: 'Other blockage' },
];

const DAMAGE_CATEGORIES = [
  { value: 'Critical Infrastructure', label: 'Critical infrastructure' },
  { value: 'Healthcare', label: 'Healthcare facility' },
  { value: 'School', label: 'School or campus' },
  { value: 'Bridge', label: 'Bridge / road surface' },
  { value: 'Residential', label: 'Residential damage' },
  { value: 'Commercial', label: 'Commercial damage' },
];

const RELIEF_POINT_GROUPS = [
  {
    heading: 'Basic Needs',
    options: [
      { value: 'basic_needs:shelter', label: 'Emergency shelter' },
      { value: 'basic_needs:food', label: 'Food distribution' },
      { value: 'basic_needs:water', label: 'Water distribution' },
    ],
  },
  {
    heading: 'Utilities & Services',
    options: [
      { value: 'utilities:charging', label: 'Device charging' },
      { value: 'utilities:banking', label: 'Mobile banking' },
    ],
  },
  {
    heading: 'Health & Wellbeing',
    options: [
      { value: 'health:clinic', label: 'Medical clinic' },
      { value: 'health:counselling', label: 'Counselling / psychosocial' },
    ],
  },
];

const SAFETY_HELPER = 'Check if there is a safety hazard on site. Provide quick notes for responders.';

const SEVERITY_LEVELS = ['Low', 'Medium', 'High', 'Critical'];

const DEFAULT_FORM = {
  report_type: 'Damage',
  disaster_id: '',
  country_code: 'JM',
  parish_code: '',
  community_geonames_id: '',
  precise_latitude: '',
  precise_longitude: '',
  location_description: '',
  severity: 'Medium',
  description: '',
  source_url: '',
  reporter_display: '',
  utility_type_id: '',
  provider_id: '',
  relief_point_type: '',
  relief_point_category: '',
  contact_phone: '',
  capacity: '',
  current_occupancy: '',
  operating_hours: '',
};

const DEFAULT_ICON = new L.Icon.Default();

function MapClickCapture({ onSelect }) {
  useMapEvents({
    click(event) {
      onSelect?.(event.latlng);
    },
  });
  return null;
}

function LocationPreviewMap({ center, marker, onSelect, disabled, fullscreen = false }) {
  if (!center) {
    return null;
  }

  return (
    <MapContainer
      center={[center.lat, center.lng]}
      zoom={15}
      scrollWheelZoom={fullscreen}
      style={{ height: fullscreen ? '100%' : 400, borderRadius: fullscreen ? 0 : 16 }}
    >
      <TileLayer
        attribution='&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
      />
      {!disabled && <MapClickCapture onSelect={onSelect} />}
      {marker ? <Marker position={[marker.lat, marker.lng]} icon={DEFAULT_ICON} /> : null}
    </MapContainer>
  );
}

async function resizeImage(file, maxDimension = 1600) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => {
      const img = new Image();
      img.onload = () => {
        const canvas = document.createElement('canvas');
        let { width, height } = img;

        if (width > height && width > maxDimension) {
          height = Math.round((height * maxDimension) / width);
          width = maxDimension;
        } else if (height > maxDimension) {
          width = Math.round((width * maxDimension) / height);
          height = maxDimension;
        }

        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);

        canvas.toBlob((blob) => {
          if (!blob) {
            reject(new Error('Failed to resize image'));
            return;
          }
          const resizedFile = new File([blob], file.name, { type: file.type });
          resolve(resizedFile);
        }, file.type, 0.9);
      };
      img.onerror = reject;
      img.src = reader.result;
    };
    reader.onerror = reject;
    reader.readAsDataURL(file);
  });
}

const adjectives = ['Brave', 'Calm', 'Vivid', 'Kind', 'Bold', 'Gentle', 'Bright', 'Steady'];
const animals = ['Hummingbird', 'Lion', 'Pelican', 'Marlin', 'Dolphin', 'Ibis', 'Turtle', 'Egret'];

const randomDisplayName = () => {
  const adj = adjectives[Math.floor(Math.random() * adjectives.length)];
  const animal = animals[Math.floor(Math.random() * animals.length)];
  return `${adj} ${animal}`;
};

export default function ReportDrawer({
  open,
  onClose,
  onReportCreated,
  disasters,
  activeDisasterId,
  onActiveDisasterChange,
  countries,
  loadParishes,
  parishOptions,
  loadCommunities,
  communityOptions,
  utilityTypes,
  loadProviders,
  providerOptions,
  initialCoordinates,
  onConsumeInitialCoordinates,
  onFocusMap,
}) {
  const [form, setForm] = useState({ ...DEFAULT_FORM });
  const [communityQuery, setCommunityQuery] = useState('');
  const [selectedCommunity, setSelectedCommunity] = useState(null);
  const [photoFiles, setPhotoFiles] = useState([]);
  const [submitting, setSubmitting] = useState(false);
  const [error, setError] = useState('');
  const [locationMode, setLocationMode] = useState('pin');
  const [hazardChecked, setHazardChecked] = useState(false);
  const [hazardNotes, setHazardNotes] = useState('');
  const [sourcePreview, setSourcePreview] = useState(null);
  const [sourceLoading, setSourceLoading] = useState(false);
  const [sourceError, setSourceError] = useState('');
  const [previewedUrl, setPreviewedUrl] = useState('');
  const [isMapFullscreen, setIsMapFullscreen] = useState(false);

  useEffect(() => () => {
    photoFiles.forEach((preview) => URL.revokeObjectURL(preview.previewUrl));
  }, [photoFiles]);

  useEffect(() => {
    if (!open) {
      setForm({
        ...DEFAULT_FORM,
        disaster_id: activeDisasterId ?? '',
        reporter_display: localStorage.getItem('orm-display-name') || randomDisplayName(),
      });
      setCommunityQuery('');
      setSelectedCommunity(null);
      setPhotoFiles([]);
      setError('');
      setLocationMode('pin');
      setHazardChecked(false);
      setHazardNotes('');
      setSourcePreview(null);
      setSourceError('');
      setSourceLoading(false);
      setPreviewedUrl('');
      setIsMapFullscreen(false);
    }
  }, [open, activeDisasterId]);

  useEffect(() => {
    if (activeDisasterId) {
      setForm((prev) => ({ ...prev, disaster_id: activeDisasterId }));
    }
  }, [activeDisasterId]);

  useEffect(() => {
    if (initialCoordinates) {
      setForm((prev) => ({
        ...prev,
        precise_latitude: initialCoordinates.lat.toFixed(6),
        precise_longitude: initialCoordinates.lng.toFixed(6),
      }));
      setLocationMode('pin');
      onConsumeInitialCoordinates?.();
    }
  }, [initialCoordinates, onConsumeInitialCoordinates]);

  useEffect(() => {
    if (!form.source_url || form.source_url !== previewedUrl) {
      setSourcePreview(null);
    }

    if (!form.source_url) {
      setSourceError('');
    }
  }, [form.source_url, previewedUrl]);

  useEffect(() => {
    if (!selectedCommunity) {
      setIsMapFullscreen(false);
    }
  }, [selectedCommunity]);

  const currentParishes = useMemo(() => {
    if (!form.country_code) return [];
    return parishOptions[form.country_code] ?? [];
  }, [form.country_code, parishOptions]);

  const currentCommunities = useMemo(() => {
    if (!form.parish_code) return [];
    return communityOptions[form.parish_code] ?? [];
  }, [form.parish_code, communityOptions]);

  const currentProviders = useMemo(() => {
    if (!form.utility_type_id) return [];
    return providerOptions[form.utility_type_id] ?? [];
  }, [form.utility_type_id, providerOptions]);

  const filteredCommunities = useMemo(() => {
    const search = communityQuery.trim().toLowerCase();
    if (!search) return currentCommunities.slice(0, 30);
    return currentCommunities
      .filter((community) => community.name.toLowerCase().includes(search))
      .slice(0, 30);
  }, [communityQuery, currentCommunities]);

  const updateField = (field, value) => {
    setForm((prev) => ({ ...prev, [field]: value }));
  };

  const handleCountryChange = async (value) => {
    updateField('country_code', value);
    updateField('parish_code', '');
    updateField('community_geonames_id', '');
    setSelectedCommunity(null);
    await loadParishes(value);
  };

  const handleParishChange = async (value) => {
    updateField('parish_code', value);
    updateField('community_geonames_id', '');
    setSelectedCommunity(null);
    setCommunityQuery('');
    await loadCommunities(value);
  };

  const handleUtilityTypeChange = async (value) => {
    updateField('utility_type_id', value);
    updateField('provider_id', '');
    if (value) {
      await loadProviders(value, form.country_code);
    }
  };

  const handleSelectCommunity = (community) => {
    setSelectedCommunity(community);
    updateField('community_geonames_id', community.geonames_id);
    if (!form.precise_latitude || !form.precise_longitude) {
      updateField('precise_latitude', Number(community.latitude || 0).toFixed(6));
      updateField('precise_longitude', Number(community.longitude || 0).toFixed(6));
    }
    onFocusMap?.({ lat: Number(community.latitude), lng: Number(community.longitude), zoom: 14 }, community);
  };

  const handleMapSelect = (latlng) => {
    updateField('precise_latitude', latlng.lat.toFixed(6));
    updateField('precise_longitude', latlng.lng.toFixed(6));
  };

  const dropZoneHandler = useCallback(async (files) => {
    const allowed = Array.from(files).slice(0, 4 - photoFiles.length);
    const processed = [];
    for (const file of allowed) {
      if (!file.type.startsWith('image/')) continue;
      const resized = await resizeImage(file);
      const previewUrl = URL.createObjectURL(resized);
      processed.push({ file: resized, previewUrl });
    }
    setPhotoFiles((prev) => [...prev, ...processed].slice(0, 4));
  }, [photoFiles.length]);

  const handleFileChange = (event) => {
    if (!event.target.files) return;
    dropZoneHandler(event.target.files);
    event.target.value = '';
  };

  const removePhoto = (index) => {
    setPhotoFiles((prev) => {
      const next = [...prev];
      URL.revokeObjectURL(next[index]?.previewUrl);
      next.splice(index, 1);
      return next;
    });
  };

  const isCoordinateValid = useMemo(() => {
    if (!selectedCommunity || !form.precise_latitude || !form.precise_longitude) {
      return false;
    }
    const lat = Number(form.precise_latitude);
    const lng = Number(form.precise_longitude);
    if (Number.isNaN(lat) || Number.isNaN(lng)) return false;
    const latDiff = Math.abs(lat - Number(selectedCommunity.latitude ?? 0));
    const lngDiff = Math.abs(lng - Number(selectedCommunity.longitude ?? 0));
    return latDiff <= 0.5 && lngDiff <= 0.5;
  }, [selectedCommunity, form.precise_latitude, form.precise_longitude]);

  const isOutage = form.report_type === 'Outage';
  const isRelief = form.report_type === 'Relief';
  const isBlockage = form.report_type === 'Blockage';
  const isDamage = form.report_type === 'Damage';

  const isValid = useMemo(() => {
    if (!form.report_type || !form.disaster_id || !form.country_code || !form.parish_code || !form.community_geonames_id) {
      return false;
    }
    if (!form.precise_latitude || !form.precise_longitude || !isCoordinateValid) {
      return false;
    }
    if (!form.severity) {
      return false;
    }
    if (isOutage && (!form.utility_type_id || !form.provider_id)) {
      return false;
    }
    if (isRelief && (!form.relief_point_type || !form.contact_phone)) {
      return false;
    }
    return true;
  }, [form, isCoordinateValid, isOutage, isRelief]);

  const steps = useMemo(() => {
    const contextComplete = Boolean(
      form.disaster_id && form.country_code && form.parish_code && form.community_geonames_id,
    );
    const detailComplete = Boolean(
      form.report_type
      && form.severity
      && (!isOutage || (form.utility_type_id && form.provider_id))
      && (!isRelief || (form.relief_point_type && form.contact_phone)),
    );
    const locationComplete = Boolean(form.precise_latitude && form.precise_longitude && isCoordinateValid);
    const submitReady = Boolean(isValid);

    return [
      { id: 'context', label: 'Community', description: 'Disaster & locality', complete: contextComplete },
      { id: 'details', label: 'Details', description: 'Report type & severity', complete: detailComplete },
      { id: 'location', label: 'Location', description: 'Pin drop or coordinates', complete: locationComplete },
      { id: 'submit', label: 'Submit', description: 'Media & identity', complete: submitReady },
    ];
  }, [form, isCoordinateValid, isOutage, isRelief, isValid]);

  const activeStepIndex = useMemo(() => {
    const index = steps.findIndex((step) => !step.complete);
    return index === -1 ? steps.length - 1 : index;
  }, [steps]);

  const previewSite = useMemo(() => {
    if (!sourcePreview) {
      return '';
    }

    if (sourcePreview.site_name) {
      return sourcePreview.site_name;
    }

    try {
      const urlValue = sourcePreview.url || form.source_url;
      return urlValue ? new URL(urlValue).hostname : '';
    } catch (error) {
      return '';
    }
  }, [sourcePreview, form.source_url]);

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (!isValid) return;
    setSubmitting(true);
    setError('');

    try {
      const payload = new FormData();
      Object.entries(form).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
          payload.append(key, value);
        }
      });

      if (sourcePreview) {
        payload.append('embed_data', JSON.stringify(sourcePreview));
      }

      if (hazardChecked && hazardNotes) {
        payload.append('location_description', `${form.location_description ? `${form.location_description}\n` : ''}Safety hazard: ${hazardNotes}`);
      }

      photoFiles.forEach((photo) => {
        payload.append('photos[]', photo.file);
      });

      const response = await axios.post('/api/reports', payload, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });

      onReportCreated?.(response.data);
      setSubmitting(false);
      onClose?.();
    } catch (err) {
      setSubmitting(false);
      setError(err.response?.data?.message ?? 'Unable to submit report. Please try again.');
    }
  };

  const handleDisasterChange = (value) => {
    updateField('disaster_id', value);
    onActiveDisasterChange?.(value);
  };

  const handleReporterChange = (value) => {
    updateField('reporter_display', value);
    localStorage.setItem('orm-display-name', value);
  };

  const handleSourcePreview = async () => {
    if (!form.source_url) {
      setSourceError('Enter a link to preview.');
      setSourcePreview(null);
      return;
    }

    setSourceLoading(true);
    setSourceError('');

    try {
      const response = await axios.get('/api/embed/preview', {
        params: { url: form.source_url },
      });
      setSourcePreview(response.data);
      setPreviewedUrl(form.source_url);
    } catch (err) {
      const message = err.response?.data?.errors?.url?.[0]
        || err.response?.data?.message
        || 'Unable to load link preview.';
      setSourceError(message);
      setSourcePreview(null);
      setPreviewedUrl('');
    } finally {
      setSourceLoading(false);
    }
  };

  return (
    <>
      <md-drawer open={open} type="modal" onClosed={onClose} className="report-drawer">
      <form className="drawer-content" onSubmit={handleSubmit}>
        <div className="section-header">
          <h2>Create report</h2>
          <p className="section-subtitle">Share critical field updates with response teams in real time.</p>
        </div>

        <div className="stepper" role="list">
          {steps.map((step, index) => (
            <div
              key={step.id}
              className={`stepper__item${step.complete ? ' stepper__item--complete' : ''}${index === activeStepIndex ? ' stepper__item--active' : ''}`}
              role="listitem"
            >
              <span className="stepper__badge">{step.complete ? '✓' : index + 1}</span>
              <div className="stepper__body">
                <span className="stepper__title">{step.label}</span>
                <span className="stepper__description">{step.description}</span>
              </div>
            </div>
          ))}
        </div>

        <section className="drawer-section" aria-label="Disaster and location">
          <div className="section-header">
            <h2>Disaster context</h2>
            <p className="section-subtitle">Choose the active emergency and community before filling details.</p>
          </div>

          <md-filled-select
            label="Active disaster"
            required
            value={form.disaster_id}
            onInput={(event) => handleDisasterChange(event.target.value)}
          >
            <md-select-option value="" disabled>
              <div slot="headline">Select disaster</div>
            </md-select-option>
            {disasters.map((disaster) => (
              <md-select-option key={disaster.id} value={disaster.id}>
                <div slot="headline">{disaster.name}</div>
                <div slot="supporting-text">Severity: {disaster.severity}</div>
              </md-select-option>
            ))}
          </md-filled-select>

          <md-filled-select
            label="Country"
            value={form.country_code}
            onInput={(event) => handleCountryChange(event.target.value)}
          >
            {countries.map((country) => (
              <md-select-option key={country.country_code} value={country.country_code}>
                <div slot="headline">{country.flag_emoji} {country.name}</div>
              </md-select-option>
            ))}
          </md-filled-select>

          <md-filled-select
            label="Parish"
            required
            value={form.parish_code}
            onInput={(event) => handleParishChange(event.target.value)}
          >
            <md-select-option value="" disabled>
              <div slot="headline">Select parish</div>
            </md-select-option>
            {currentParishes.map((parish) => (
              <md-select-option key={parish.parish_code} value={parish.parish_code}>
                <div slot="headline">{parish.name}</div>
                <div slot="supporting-text">Communities: {parish.community_count}</div>
              </md-select-option>
            ))}
          </md-filled-select>

          <md-filled-text-field
            label="Search community"
            value={communityQuery}
            onInput={(event) => setCommunityQuery(event.target.value)}
            supporting-text="Start typing to filter communities"
          />

          <div className="community-search-results" role="list">
            {filteredCommunities.map((community) => {
              const active = form.community_geonames_id === community.geonames_id;
              return (
                <button
                  key={community.geonames_id}
                  type="button"
                  className={`community-result${active ? ' community-result--active' : ''}`}
                  onClick={() => handleSelectCommunity(community)}
                >
                  <span>{community.name}</span>
                  {active ? <span style={{ color: '#16a34a' }}>✓</span> : null}
                </button>
              );
            })}
          </div>

          {selectedCommunity ? (
            <div className="green-confirmation">
              ✓ {selectedCommunity.name}, {selectedCommunity.parish_code}
            </div>
          ) : null}
        </section>

        <section className="drawer-section" aria-label="Report type and severity">
          <div className="section-header">
            <h2>Report type</h2>
            <p className="section-subtitle">Pick the category that best matches what you see on the ground.</p>
          </div>

          <div className="report-type-grid">
            {REPORT_TYPES.map((type) => {
              const active = form.report_type === type.value;
              return (
                <button
                  key={type.value}
                  type="button"
                  className={`report-type-card${active ? ' report-type-card--active' : ''}`}
                  onClick={() => updateField('report_type', type.value)}
                >
                  <span className="report-type-card__emoji">{type.emoji}</span>
                  <span className="report-type-card__title">{type.title}</span>
                  <p className="helper-text">{type.helper}</p>
                </button>
              );
            })}
          </div>

          <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
            {SEVERITY_LEVELS.map((level) => (
              <md-assist-chip
                key={level}
                selected={form.severity === level}
                label={level}
                onClick={() => updateField('severity', level)}
              />
            ))}
          </div>
        </section>

        <section className="drawer-section" aria-label="Conditional fields">
          {isOutage && (
            <>
              <md-filled-select
                label="Utility type"
                required
                value={form.utility_type_id}
                onInput={(event) => handleUtilityTypeChange(event.target.value)}
              >
                <md-select-option value="" disabled>
                  <div slot="headline">Select utility</div>
                </md-select-option>
                {utilityTypes.map((utility) => (
                  <md-select-option key={utility.id} value={utility.id}>
                    <div slot="headline">{utility.display_name}</div>
                  </md-select-option>
                ))}
              </md-filled-select>

              <md-filled-select
                label="Provider"
                required
                value={form.provider_id}
                onInput={(event) => updateField('provider_id', event.target.value)}
              >
                <md-select-option value="" disabled>
                  <div slot="headline">Select provider</div>
                </md-select-option>
                {currentProviders.map((provider) => (
                  <md-select-option key={provider.id} value={provider.id}>
                    <div slot="headline">{provider.provider_name}</div>
                  </md-select-option>
                ))}
              </md-filled-select>
            </>
          )}

          {(isBlockage || isDamage) && (
            <md-filled-select
              label="Category"
              value={form.relief_point_category}
              onInput={(event) => updateField('relief_point_category', event.target.value)}
            >
              <md-select-option value="" disabled>
                <div slot="headline">Select category</div>
              </md-select-option>
              {(isBlockage ? BLOCKAGE_CATEGORIES : DAMAGE_CATEGORIES).map((category) => (
                <md-select-option key={category.value} value={category.value}>
                  <div slot="headline">{category.label}</div>
                </md-select-option>
              ))}
            </md-filled-select>
          )}

          {isRelief && (
            <>
              <md-filled-select
                label="Relief point type"
                required
                value={form.relief_point_type}
                onInput={(event) => updateField('relief_point_type', event.target.value)}
              >
                <md-select-option value="" disabled>
                  <div slot="headline">Select relief point type</div>
                </md-select-option>
                {RELIEF_POINT_GROUPS.map((group) => (
                  <React.Fragment key={group.heading}>
                    <md-select-option value={group.heading} disabled>
                      <div slot="headline" style={{ opacity: 0.7 }}>{group.heading}</div>
                    </md-select-option>
                    {group.options.map((option) => (
                      <md-select-option key={option.value} value={option.value}>
                        <div slot="headline">{option.label}</div>
                      </md-select-option>
                    ))}
                  </React.Fragment>
                ))}
              </md-filled-select>

              <div className="coordinate-inputs">
                <md-filled-text-field
                  type="number"
                  label="Capacity"
                  value={form.capacity}
                  onInput={(event) => updateField('capacity', event.target.value)}
                />
                <md-filled-text-field
                  type="number"
                  label="Current occupancy"
                  value={form.current_occupancy}
                  onInput={(event) => updateField('current_occupancy', event.target.value)}
                />
              </div>

              <md-filled-text-field
                label="Operating hours"
                value={form.operating_hours}
                onInput={(event) => updateField('operating_hours', event.target.value)}
              />

              <md-filled-text-field
                label="Contact phone"
                required
                value={form.contact_phone}
                onInput={(event) => updateField('contact_phone', event.target.value)}
              />

              <div className="success-banner" style={{ background: 'rgba(249, 115, 22, 0.12)', color: '#f97316' }}>
                ⚠️ Unverified relief point – double check before publishing.
              </div>
            </>
          )}

          <label style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
            <md-checkbox checked={hazardChecked} onChange={(event) => setHazardChecked(event.target.checked)} />
            <span>{SAFETY_HELPER}</span>
          </label>

          {hazardChecked && (
            <md-filled-text-area
              label="Hazard notes"
              value={hazardNotes}
              onInput={(event) => setHazardNotes(event.target.value)}
              supporting-text="Describe immediate safety risks"
            />
          )}
        </section>

        <section className="drawer-section" aria-label="Location">
          <div className="section-header">
            <h2>Pin the location</h2>
            <p className="section-subtitle">Switch between pin-drop and manual coordinates.</p>
          </div>

          <div className="location-toggle-row">
            <label style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
              <md-switch
                selected={locationMode === 'coordinates'}
                onChange={(event) => setLocationMode(event.target.selected ? 'coordinates' : 'pin')}
              />
              <span>{locationMode === 'coordinates' ? 'Manual coordinates' : 'Pin on map'}</span>
            </label>
            <label style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <md-checkbox
                checked={Boolean(form.precise_latitude && form.precise_longitude)}
                onChange={(event) => {
                  if (!event.target.checked) {
                    updateField('precise_latitude', '');
                    updateField('precise_longitude', '');
                  }
                }}
              />
              <span>I know the exact location</span>
            </label>
          </div>

          {locationMode === 'pin' ? (
            <>
              <div className={`location-map-wrapper${!selectedCommunity ? ' location-map-wrapper--disabled' : ''}`}>
                <LocationPreviewMap
                  center={selectedCommunity ? { lat: Number(selectedCommunity.latitude), lng: Number(selectedCommunity.longitude) } : null}
                  marker={form.precise_latitude && form.precise_longitude ? { lat: Number(form.precise_latitude), lng: Number(form.precise_longitude) } : null}
                  onSelect={handleMapSelect}
                  disabled={!selectedCommunity}
                />
                {selectedCommunity ? (
                  <md-icon-button
                    className="fullscreen-map-button"
                    aria-label="Open map fullscreen"
                    onClick={() => setIsMapFullscreen(true)}
                  >
                    <span slot="icon">open_in_full</span>
                  </md-icon-button>
                ) : (
                  <div className="location-map-wrapper__overlay">Select a community to enable the map.</div>
                )}
              </div>
              <div className="latlng-readout">
                <span>Latitude: {form.precise_latitude || '—'}</span>
                <span>Longitude: {form.precise_longitude || '—'}</span>
              </div>
            </>
          ) : (
            <div className="coordinate-inputs">
              <md-filled-text-field
                label="Latitude"
                value={form.precise_latitude}
                onInput={(event) => updateField('precise_latitude', event.target.value)}
                required
              />
              <md-filled-text-field
                label="Longitude"
                value={form.precise_longitude}
                onInput={(event) => updateField('precise_longitude', event.target.value)}
                required
              />
            </div>
          )}

          {selectedCommunity ? (
            <div className={`coordinate-validity ${isCoordinateValid ? 'coordinate-validity--valid' : 'coordinate-validity--invalid'}`}>
              {isCoordinateValid ? '✓ Coordinates are within community boundary' : 'Coordinates fall outside the selected community'}
            </div>
          ) : null}
        </section>

        <section className="drawer-section" aria-label="Media and source">
          <div className="section-header">
            <h2>Media & Source</h2>
            <p className="section-subtitle">Attach photos and reference links to support this report.</p>
          </div>

          <div
            className="photo-grid"
            onDragOver={(event) => event.preventDefault()}
            onDrop={(event) => {
              event.preventDefault();
              dropZoneHandler(event.dataTransfer.files);
            }}
          >
            {photoFiles.map((preview, index) => (
              <div className="photo-grid__item" key={preview.previewUrl}>
                <img src={preview.previewUrl} alt="Preview" />
                <button type="button" className="photo-grid__remove" onClick={() => removePhoto(index)}>
                  ×
                </button>
              </div>
            ))}
            {photoFiles.length < 4 ? (
              <label className="photo-grid__item" htmlFor="report-photos">
                <span>Add photo</span>
              </label>
            ) : null}
          </div>
          <input id="report-photos" type="file" accept="image/*" multiple hidden onChange={handleFileChange} />

          <div className="source-row">
            <md-filled-text-field
              label="Source link (optional)"
              type="url"
              supporting-text="Preview the link before submitting"
              value={form.source_url}
              onInput={(event) => updateField('source_url', event.target.value)}
            />
            <md-outlined-button type="button" onClick={handleSourcePreview} disabled={sourceLoading}>
              {sourceLoading ? <md-circular-progress indeterminate slot="icon" /> : null}
              Preview
            </md-outlined-button>
          </div>

          {sourceError ? <p className="error-text">{sourceError}</p> : null}

          {sourcePreview ? (
            <div className="source-preview-card">
              <div className="source-preview-card__body">
                {previewSite ? (
                  <span className="source-preview-card__site">{previewSite}</span>
                ) : null}
                {sourcePreview.title ? (
                  <strong className="source-preview-card__title">{sourcePreview.title}</strong>
                ) : null}
                {sourcePreview.description ? (
                  <p className="source-preview-card__description">{sourcePreview.description}</p>
                ) : null}
              </div>
              {sourcePreview.image ? (
                <img src={sourcePreview.image} alt="Preview" loading="lazy" />
              ) : null}
            </div>
          ) : null}
        </section>

        <section className="drawer-section" aria-label="Identity">
          <div className="section-header">
            <h2>Identity</h2>
            <p className="section-subtitle">Share a display name or remain anonymous.</p>
          </div>

          <div className="identity-row">
            <md-filled-text-field
              label="Display name"
              value={form.reporter_display}
              onInput={(event) => handleReporterChange(event.target.value)}
            />
            <md-checkbox
              checked={form.reporter_display === 'Anonymous'}
              onChange={(event) => handleReporterChange(event.target.checked ? 'Anonymous' : randomDisplayName())}
            />
            <span>Post anonymously</span>
          </div>
        </section>

        {error ? <p className="error-text">{error}</p> : null}

        <div className="submission-row">
          <md-filled-button type="submit" disabled={!isValid || submitting}>
            {submitting ? <md-circular-progress indeterminate slot="icon" /> : null}
            Submit report
          </md-filled-button>
          <md-text-button type="button" onClick={onClose}>Cancel</md-text-button>
        </div>
      </form>
    </md-drawer>
    {(isMapFullscreen && selectedCommunity)
      ? createPortal(
          <div className="fullscreen-map-overlay" role="dialog" aria-modal="true">
            <div className="fullscreen-map-overlay__backdrop" onClick={() => setIsMapFullscreen(false)} />
            <div className="fullscreen-map-overlay__content">
              <md-icon-button
                className="fullscreen-map-overlay__close"
                aria-label="Close fullscreen map"
                onClick={() => setIsMapFullscreen(false)}
              >
                <span slot="icon">close</span>
              </md-icon-button>
              <LocationPreviewMap
                center={{ lat: Number(selectedCommunity.latitude), lng: Number(selectedCommunity.longitude) }}
                marker={form.precise_latitude && form.precise_longitude ? { lat: Number(form.precise_latitude), lng: Number(form.precise_longitude) } : null}
                onSelect={handleMapSelect}
                fullscreen
              />
            </div>
          </div>,
          document.body,
        )
      : null}
    </>
  );
}
