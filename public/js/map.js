// Map-specific functionality
class MapManager {
    constructor(mapElementId, sitesData) {
        this.mapElementId = mapElementId;
        this.sitesData = sitesData;
        this.map = null;
        this.markers = [];
        this.clusterGroup = null;
        this.currentFilter = 'all';
        this.init();
    }

    init() {
        this.initializeMap();
        this.setupEventListeners();
        this.showMarkers('all');
    }

    initializeMap() {
        // Initialize map centered on SUMBAGTENG
        this.map = L.map(this.mapElementId).setView([0.5333, 101.4500], 7);

        // Add base layers
        const baseLayers = {
            "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }),
            "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; <a href="http://www.esri.com/">Esri</a>'
            })
        };

        baseLayers.OpenStreetMap.addTo(this.map);
        L.control.layers(baseLayers).addTo(this.map);
        L.control.scale({ imperial: false }).addTo(this.map);

        this.addSearchControl();
        this.addFullscreenControl();
    }

    createCustomIcon(status) {
        const color = status === 'Done' ? '#198754' : '#FFC107';
        const iconHtml = `
            <div style="
                background-color: ${color};
                width: 20px;
                height: 20px;
                border: 3px solid white;
                border-radius: 50%;
                box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            "></div>
        `;
        
        return L.divIcon({
            html: iconHtml,
            className: 'custom-marker',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });
    }

    showMarkers(filter = 'all') {
        this.currentFilter = filter;
        
        // Clear existing markers
        if (this.clusterGroup) {
            this.map.removeLayer(this.clusterGroup);
        }

        this.markers = [];

        // Filter sites based on current filter
        const filteredSites = this.sitesData.filter(site => {
            if (filter === 'all') return true;
            if (filter === 'done') return site.status === 'Done';
            if (filter === 'not-done') return site.status === 'Not Done';
            return true;
        });

        // Create markers
        filteredSites.forEach(site => {
            const lat = parseFloat(site.latitude);
            const lon = parseFloat(site.longitude);

            if (!isNaN(lat) && !isNaN(lon)) {
                const icon = this.createCustomIcon(site.status);
                const marker = L.marker([lat, lon], { icon });

                const popupContent = this.createPopupContent(site);
                marker.bindPopup(popupContent);

                this.markers.push(marker);
            }
        });

        // Add markers to cluster group
        this.clusterGroup = L.markerClusterGroup({
            maxClusterRadius: 50,
            spiderfyOnMaxZoom: true,
            showCoverageOnHover: true,
            zoomToBoundsOnClick: true
        });

        this.clusterGroup.addLayers(this.markers);
        this.map.addLayer(this.clusterGroup);
    }

    createPopupContent(site) {
        return `
            <div class="map-popup-content">
                <h6 class="text-primary mb-2">${site.site_name}</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            <span class="badge ${site.status === 'Done' ? 'bg-success' : 'bg-warning'}">
                                ${site.status}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Program:</strong></td>
                        <td>${site.program_type}</td>
                    </tr>
                    <tr>
                        <td><strong>City:</strong></td>
                        <td>${site.city}</td>
                    </tr>
                    <tr>
                        <td><strong>Partner:</strong></td>
                        <td>${site.partner_name}</td>
                    </tr>
                </table>
            </div>
        `;
    }

    addSearchControl() {
        const searchControl = L.control({ position: 'topright' });
        
        searchControl.onAdd = (map) => {
            const div = L.DomUtil.create('div', 'search-control');
            div.innerHTML = `
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" placeholder="Search city or site..." id="mapSearch">
                    <button class="btn btn-primary" type="button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            `;
            return div;
        };

        searchControl.addTo(this.map);

        // Add search functionality
        document.getElementById('searchButton').addEventListener('click', () => {
            this.performSearch();
        });

        document.getElementById('mapSearch').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.performSearch();
            }
        });
    }

    performSearch() {
        const query = document.getElementById('mapSearch').value.toLowerCase().trim();
        if (!query) return;

        const foundSite = this.sitesData.find(site => 
            site.city.toLowerCase().includes(query) || 
            site.site_name.toLowerCase().includes(query)
        );

        if (foundSite) {
            const lat = parseFloat(foundSite.latitude);
            const lon = parseFloat(foundSite.longitude);
            
            if (!isNaN(lat) && !isNaN(lon)) {
                this.map.setView([lat, lon], 13);
                
                // Find and open the marker's popup
                setTimeout(() => {
                    this.markers.forEach(marker => {
                        const markerLatLng = marker.getLatLng();
                        if (markerLatLng.lat === lat && markerLatLng.lng === lon) {
                            marker.openPopup();
                        }
                    });
                }, 500);
            }
        } else {
            alert('Site atau kota tidak ditemukan!');
        }
    }

    addFullscreenControl() {
        const fullscreenControl = L.control({ position: 'topright' });
        
        fullscreenControl.onAdd = (map) => {
            const div = L.DomUtil.create('div', 'fullscreen-control');
            div.innerHTML = `
                <button class="btn btn-light btn-sm" title="Fullscreen" id="fullscreenToggle">
                    <i class="fas fa-expand"></i>
                </button>
            `;
            return div;
        };

        fullscreenControl.addTo(this.map);

        document.getElementById('fullscreenToggle').addEventListener('click', () => {
            const mapContainer = document.getElementById(this.mapElementId);
            if (!document.fullscreenElement) {
                mapContainer.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        });
    }

    setupEventListeners() {
        // Filter buttons
        document.querySelectorAll('[data-filter]').forEach(button => {
            button.addEventListener('click', (e) => {
                const filter = e.target.getAttribute('data-filter');
                
                // Update active button
                document.querySelectorAll('[data-filter]').forEach(btn => {
                    btn.classList.remove('active');
                });
                e.target.classList.add('active');
                
                // Apply filter
                this.showMarkers(filter);
            });
        });
    }
}

// Initialize map when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof sites !== 'undefined' && document.getElementById('map')) {
        window.mapManager = new MapManager('map', sites);
    }
});