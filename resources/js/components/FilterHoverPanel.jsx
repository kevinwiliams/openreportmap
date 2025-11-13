import React, { useMemo, useState } from 'react';

const REPORT_TYPE_META = {
  Outage: { label: 'Outage', emoji: '⚡' },
  Blockage: { label: 'Blockage', emoji: '🚧' },
  Damage: { label: 'Damage', emoji: '⚠️' },
  Relief: { label: 'Relief Point', emoji: '🏠' },
};

const SEVERITY_META = {
  Low: { label: 'Low', color: '#f4b400' },
  Medium: { label: 'Medium', color: '#fbbc04' },
  High: { label: 'High', color: '#ea4335' },
  Critical: { label: 'Critical', color: '#c5221f' },
};

export default function FilterHoverPanel({
  filters,
  onFiltersChange,
  counts,
}) {
  const [activeTab, setActiveTab] = useState('resources');

  const summaryText = useMemo(() => {
    const activeTypes = filters.types.length === Object.keys(REPORT_TYPE_META).length
      ? 'All report types'
      : filters.types.join(', ');

    const activeSeverities = filters.severities.length === Object.keys(SEVERITY_META).length
      ? 'all severities'
      : filters.severities.join(', ');

    return `${activeTypes} • ${activeSeverities}`;
  }, [filters]);

  const toggleType = (type) => {
    const currentlyActive = filters.types.includes(type);
    const nextTypes = currentlyActive
      ? filters.types.filter((value) => value !== type)
      : [...filters.types, type];

    onFiltersChange({ ...filters, types: nextTypes });
  };

  const toggleSeverity = (severity) => {
    const currentlyActive = filters.severities.includes(severity);
    const nextSeverities = currentlyActive
      ? filters.severities.filter((value) => value !== severity)
      : [...filters.severities, severity];

    onFiltersChange({ ...filters, severities: nextSeverities });
  };

  return (
    <section className="hover-filter-panel" aria-label="Map filters and quick actions">
      <div className="hover-filter-panel__card">
        <header className="hover-filter-panel__header">
          <div className="hover-filter-panel__summary">
            <span style={{ fontSize: '0.75rem', textTransform: 'uppercase', letterSpacing: '.12em', color: 'rgba(31,31,31,.54)' }}>
              Quick filters
            </span>
            <strong style={{ fontSize: '1.05rem' }}>{summaryText}</strong>
          </div>
          <md-text-button
            onClick={() => onFiltersChange({
              types: Object.keys(REPORT_TYPE_META),
              severities: Object.keys(SEVERITY_META),
            })}
          >
            Reset
          </md-text-button>
        </header>

        <div className="hover-filter-panel__content">
          <div className="hover-filter-panel__pills" role="group" aria-label="Filter by report type">
            {Object.entries(REPORT_TYPE_META).map(([key, meta]) => {
              const isActive = filters.types.includes(key);
              const count = counts?.types?.[key] ?? 0;

              return (
                <md-filter-chip
                  key={key}
                  selected={isActive}
                  label={`${meta.emoji} ${meta.label}`}
                  supporting-text={count ? `${count} active` : undefined}
                  onClick={() => toggleType(key)}
                />
              );
            })}
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
            <span style={{ fontSize: '0.78rem', letterSpacing: '.08em', textTransform: 'uppercase', color: 'rgba(31,31,31,.58)' }}>
              Severity bands
            </span>

            <div className="hover-filter-panel__pills" role="group" aria-label="Filter by severity">
              {Object.entries(SEVERITY_META).map(([key, meta]) => {
                const isActive = filters.severities.includes(key);
                const count = counts?.severities?.[key] ?? 0;

                return (
                  <md-filter-chip
                    key={key}
                    selected={isActive}
                    label={meta.label}
                    supporting-text={count ? `${count}` : undefined}
                    style={{
                      '--md-filter-chip-selected-container-color': `${meta.color}20`,
                    }}
                    onClick={() => toggleSeverity(key)}
                  />
                );
              })}
            </div>
          </div>

          <div className="tabs-panel" role="region" aria-label="Community resources and contact quick links">
            <md-tabs
              active-tab-index={activeTab === 'resources' ? 0 : 1}
              onchange={(event) => {
                const index = event.target.activeTabIndex ?? 0;
                setActiveTab(index === 0 ? 'resources' : 'contacts');
              }}
            >
              <md-tab id="resources-tab" label="Resources" />
              <md-tab id="contacts-tab" label="Contacts" />
            </md-tabs>

            <div className={`tab-section ${activeTab === 'resources' ? 'tab-section--active' : ''}`}>
              <ul style={{ margin: '12px 0 0', padding: 0, listStyle: 'none', display: 'grid', gap: '12px' }}>
                <li><strong>Relief shelters:</strong> Tap any relief marker to view capacity + contact.</li>
                <li><strong>Utility status:</strong> Filter outages above and call providers directly.</li>
                <li><strong>Latest alerts:</strong> Disaster bulletin colour code matches the map badge.</li>
              </ul>
            </div>

            <div className={`tab-section ${activeTab === 'contacts' ? 'tab-section--active' : ''}`}>
              <ul style={{ margin: '12px 0 0', padding: 0, listStyle: 'none', display: 'grid', gap: '12px' }}>
                <li><strong>National Emergency:</strong> 119</li>
                <li><strong>Jamaica Red Cross:</strong> +1 (876) 984-7860</li>
                <li><strong>Utility hotlines:</strong> See outage markers for Digicel, FLOW, JPS &amp; NWC.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

export { REPORT_TYPE_META, SEVERITY_META };
