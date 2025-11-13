import React, { useMemo, useState } from 'react';

import { REPORT_TYPE_META, SEVERITY_META } from './FilterHoverPanel.jsx';

const STATUS_COLORS = {
  active: '#2563eb',
  unverified: '#f97316',
  community_verified: '#16a34a',
  resolved: '#059669',
};

export default function ReportDetailsSheet({
  report,
  onClose,
  onResolve,
  onFlag,
  isResolving,
}) {
  const [activeTab, setActiveTab] = useState('overview');

  const properties = report?.properties;

  const severityColor = useMemo(() => {
    if (!properties?.severity) return '#475569';
    const entry = SEVERITY_META[properties.severity];
    return entry?.color ?? '#475569';
  }, [properties?.severity]);

  const embedPreview = useMemo(() => {
    if (!properties?.embed_data || Object.keys(properties.embed_data).length === 0) {
      return null;
    }

    const data = properties.embed_data;

    let site = data.site_name ?? '';

    if (!site) {
      try {
        const targetUrl = data.url || properties.source_url || '';
        site = targetUrl ? new URL(targetUrl).hostname : '';
      } catch (error) {
        site = '';
      }
    }

    return {
      title: data.title ?? '',
      description: data.description ?? '',
      image: data.image ?? '',
      site,
    };
  }, [properties?.embed_data, properties?.source_url]);

  if (!report || !properties) {
    return null;
  }

  const reportMeta = REPORT_TYPE_META[properties.report_type] ?? {};
  const status = (properties.status ?? 'active').toString().toLowerCase();
  const statusColor = STATUS_COLORS[status] ?? '#2563eb';

  return (
    <aside className="details-sheet" aria-live="polite">
      <header className="details-sheet__header">
        <div>
          <p style={{ margin: 0, fontSize: '0.75rem', letterSpacing: '.12em', textTransform: 'uppercase', color: 'rgba(31,31,31,.58)' }}>
            {properties.country?.name ?? properties.country_code} · {properties.parish?.name ?? properties.parish_code}
          </p>
          <h2 style={{ margin: '4px 0 0', fontSize: '1.35rem', fontWeight: 700 }}>
            {reportMeta.emoji ? `${reportMeta.emoji} ` : ''}{reportMeta.label ?? properties.report_type}
          </h2>
        </div>
        <md-icon-button aria-label="Close details panel" onClick={onClose}>
          <span slot="icon">close</span>
        </md-icon-button>
      </header>

      <md-tabs
        active-tab-index={activeTab === 'overview' ? 0 : activeTab === 'resources' ? 1 : 2}
        onchange={(event) => {
          const index = event.target.activeTabIndex ?? 0;
          setActiveTab(index === 0 ? 'overview' : index === 1 ? 'resources' : 'contact');
        }}
      >
        <md-tab label="Overview" />
        <md-tab label="Resources" />
        <md-tab label="Contact" />
      </md-tabs>

      <div className="details-sheet__body">
        <div className="details-sheet__badges">
          <span className="map-popup__badge" style={{ background: `${severityColor}1a`, color: severityColor }}>
            Severity · {properties.severity ?? 'Unknown'}
          </span>
          <span className="map-popup__badge" style={{ background: `${statusColor}1a`, color: statusColor }}>
            Status · {properties.status ?? 'Active'}
          </span>
        </div>

        {activeTab === 'overview' && (
          <>
            {properties.description ? (
              <p style={{ margin: 0, lineHeight: 1.5 }}>{properties.description}</p>
            ) : (
              <p style={{ margin: 0, fontStyle: 'italic', color: 'rgba(31,31,31,.6)' }}>
                No description supplied.
              </p>
            )}

            {properties.location_description && (
              <div>
                <strong>Location</strong>
                <p style={{ margin: '4px 0 0' }}>{properties.location_description}</p>
              </div>
            )}

            {properties.photos?.length ? (
              <div>
                <strong>Photos</strong>
                <div className="details-sheet__photos">
                  {properties.photos.map((photo) => (
                    <a key={photo.id} href={photo.url} target="_blank" rel="noreferrer">
                      <img src={photo.thumbnail_url ?? photo.url} alt="Report attachment" loading="lazy" />
                    </a>
                  ))}
                </div>
              </div>
            ) : null}
          </>
        )}

        {activeTab === 'resources' && (
          <div style={{ display: 'grid', gap: '12px' }}>
            {properties.relief_point_type && (
              <p style={{ margin: 0 }}><strong>Relief type:</strong> {properties.relief_point_type}</p>
            )}
            {properties.capacity !== null && (
              <p style={{ margin: 0 }}><strong>Capacity:</strong> {properties.capacity}</p>
            )}
            {properties.current_occupancy !== null && (
              <p style={{ margin: 0 }}><strong>Current occupancy:</strong> {properties.current_occupancy}</p>
            )}
            {properties.operating_hours && (
              <p style={{ margin: 0 }}><strong>Operating hours:</strong> {properties.operating_hours}</p>
            )}
            {properties.utility_type && (
              <p style={{ margin: 0 }}><strong>Utility type:</strong> {properties.utility_type.display_name}</p>
            )}
            {properties.provider && (
              <p style={{ margin: 0 }}><strong>Provider:</strong> {properties.provider.provider_name}</p>
            )}
          </div>
        )}

        {activeTab === 'contact' && (
          <div style={{ display: 'grid', gap: '12px' }}>
            {properties.contact_phone && (
              <p style={{ margin: 0 }}><strong>Phone:</strong> {properties.contact_phone}</p>
            )}
            {properties.reporter_display && (
              <p style={{ margin: 0 }}><strong>Reported by:</strong> {properties.reporter_display}</p>
            )}
            {properties.source_url && (
              <p style={{ margin: 0 }}>
                <strong>Source:</strong>{' '}
                <a href={properties.source_url} target="_blank" rel="noreferrer">
                  {properties.source_platform ?? properties.source_type ?? 'Open link'}
                </a>
              </p>
            )}
            {embedPreview ? (
              <div className="source-preview-card details-sheet__embed-card">
                <div className="source-preview-card__body">
                  <span className="source-preview-card__site">{embedPreview.site}</span>
                  {embedPreview.title ? (
                    <strong className="source-preview-card__title">{embedPreview.title}</strong>
                  ) : null}
                  {embedPreview.description ? (
                    <p className="source-preview-card__description">{embedPreview.description}</p>
                  ) : null}
                </div>
                {embedPreview.image ? (
                  <img src={embedPreview.image} alt="Source preview" loading="lazy" />
                ) : null}
              </div>
            ) : null}
          </div>
        )}

        <div className="details-sheet__actions">
          <md-filled-button
            disabled={isResolving}
            onClick={() => onResolve(report)}
          >
            {isResolving ? <md-circular-progress indeterminate slot="icon" /> : null}
            Mark resolved
          </md-filled-button>
          <md-outlined-button onClick={() => onFlag?.(report)}>Flag</md-outlined-button>
        </div>
      </div>
    </aside>
  );
}
