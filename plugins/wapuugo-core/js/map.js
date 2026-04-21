document.addEventListener('DOMContentLoaded', function() {
    const mapContainer = document.getElementById('wg-map-container');

    if (mapContainer && typeof WgAdventureData !== 'undefined') {
        
        console.log("Dane pobrane z PHP:", WgAdventureData.progress);
        
        mapContainer.innerHTML = `
            <h3>Witaj!</h3>
            <p>Twój obecny poziom to: <strong>${WgAdventureData.progress.current_level}</strong></p>
            <p>Zgromadzone XP: <strong>${WgAdventureData.progress.total_xp}</strong></p>
        `;
    }
});