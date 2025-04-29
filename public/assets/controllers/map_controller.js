// assets/controllers/map_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        destination: String,
        apiKey: { type: String, default: '' }
    }

    async connect() {
        console.log('🔵 Map Controller INIT');
        this.showLoading(); // 🔥 Petit "Loading..." avant la carte

        if (!this.apiKeyValue) {
            this.showError('API KEY MISSING');
            return;
        }

        try {
            await this.loadGoogleMaps();
            console.log('🟢 Google Maps loaded');
            this.initMap();
        } catch (error) {
            console.error('🔴 MAP ERROR:', error);
            this.showError(error.message);
        }
    }

    showLoading() {
        this.element.innerHTML = `
            <div style="
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #666;
                font-size: 1.2rem;
                background: #f9f9f9;
            ">
                Chargement de la carte...
            </div>
        `;
    }

    showError(message) {
        this.element.innerHTML = `
            <div style="
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: red;
                font-weight: bold;
                background: #ffeaea;
                padding: 1rem;
            ">
                MAP ERROR: ${message}
            </div>
        `;
    }

    async loadGoogleMaps() {
        return new Promise((resolve, reject) => {
            if (window.google && window.google.maps) {
                console.log('ℹ️ Google Maps already loaded');
                return resolve();
            }

            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key=${this.apiKeyValue}&loading=async`;
            script.async = true;
            script.defer = true;

            script.onload = () => {
                console.log('✅ Google Maps script loaded');
                if (!window.google || !window.google.maps) {
                    return reject(new Error('Google objects not defined after load'));
                }
                resolve();
            };

            script.onerror = () => {
                reject(new Error('Failed to load Google Maps script'));
            };

            document.head.appendChild(script);
        });
    }

    initMap() {
        console.log('Initializing map on element:', this.element);

        // Fallback pour problème de hauteur
        if (this.element.offsetHeight === 0) {
            console.warn('Map container has zero height - applying emergency fix');
            this.element.style.height = '400px';
            this.element.style.visibility = 'visible';
        }

        // Instancier la map vide temporairement
        this.map = new google.maps.Map(this.element, {
            center: { lat: 0, lng: 0 },
            zoom: 2,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });

        // Geocoder la destination
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: this.destinationValue }, (results, status) => {
            if (status === "OK") {
                console.log('🗺️ Destination geocoded:', results[0].geometry.location);
                this.map.setCenter(results[0].geometry.location);
                this.map.setZoom(10); // Zoomer un peu plus
                new google.maps.Marker({
                    map: this.map,
                    position: results[0].geometry.location,
                    title: this.destinationValue
                });
            } else {
                console.error('❌ Geocode failed:', status);
                this.showError('Impossible de localiser la destination.');
            }
        });
    }
}
