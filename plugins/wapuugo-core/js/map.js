document.addEventListener('DOMContentLoaded', async function () {
    const mapContainer = document.getElementById('wg-map-container');
    if (!mapContainer || typeof WgAdventureData === 'undefined') return;

    const url = new URL(WgAdventureData.apiUrl);
    url.searchParams.set('course_id', WgAdventureData.courseId);

    try {
        const response = await fetch(url.toString(), {
            headers: {
                'X-WP-Nonce': WgAdventureData.nonce,
            },
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();

        console.log('Dane z REST API:', data);

        mapContainer.innerHTML = data.lessons.map(lesson => `
            <p>Lekcja ${lesson.id}: <strong>${lesson.status}</strong></p>
        `).join('');

    } catch (err) {
        console.error('Błąd pobierania postępów:', err);
        mapContainer.innerHTML = '<p>Nie udało się załadować mapy. Spróbuj odświeżyć stronę.</p>';
    }
});