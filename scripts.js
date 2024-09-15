// script.js
document.addEventListener('DOMContentLoaded', function() {
    fetchClips();

    function fetchClips() {
        fetch('get_clips.php')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('clips-container');
                container.innerHTML = '';  // Effacer le contenu précédent
                data.forEach(clip => {
                    const clipElement = document.createElement('div');
                    clipElement.className = 'clip';
                    clipElement.innerHTML = `
                        <img src="${clip.thumbnail_url}" alt="${clip.title}">
                        <h3>${clip.title}</h3>
                        <p>Vues: ${clip.view_count}</p>
                        <a href="${clip.url}" target="_blank">Voir le clip</a>
                    `;
                    container.appendChild(clipElement);
                });
            });
    }
});
