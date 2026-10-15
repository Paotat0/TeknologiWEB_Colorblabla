let palettes = [
    { id: 1, name: "Sunset Vibes", colors: ["#FF5733", "#FF8D1A", "#FFC300"], isFavorite: false },
    { id: 2, name: "Ocean Blue", colors: ["#0077B6", "#00B4D8", "#90E0EF"], isFavorite: true },
    { id: 3, name: "Forest Green", colors: ["#2D6A4F", "#40916C", "#74C69D"], isFavorite: false }
];

const container = document.getElementById('palette-container');
const searchInput = document.getElementById('searchInput');
const statTotal = document.getElementById('total-palettes');
const statFav = document.getElementById('total-favorites');
const statActivity = document.getElementById('latest-activity');

function renderPalettes(data) {
    container.innerHTML = '';
    let favCount = 0;

    data.forEach(palette => {
        if (palette.isFavorite) favCount++;

        const card = document.createElement('div');
        card.className = 'palette-card';
        
        
        const colorBlocks = palette.colors.map(color => 
            `<div class="color-block" style="background-color: ${color};" title="${color}"></div>`
        ).join('');

        card.innerHTML = `
            <h3>${palette.name}</h3>
            <div class="colors">${colorBlocks}</div>
            <div class="actions">
                <button class="fav-btn ${palette.isFavorite ? 'active' : ''}" onclick="toggleFavorite(${palette.id})">
                    ${palette.isFavorite ? '❤️' : '🤍'}
                </button>
                <div>
                    <select id="export-format-${palette.id}" class="export-select">
                        <option value="css">CSS</option>
                        <option value="json">JSON</option>
                        <option value="png">PNG</option>
                    </select>
                    <button class="export-btn" onclick="exportPalette(${palette.id})">Export</button>
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    
    statTotal.innerText = palettes.length;
    statFav.innerText = favCount;
}

searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase();
    const filtered = palettes.filter(p => 
        p.name.toLowerCase().includes(query) || 
        p.colors.some(c => c.toLowerCase().includes(query))
    );
    renderPalettes(filtered);
    updateActivity(`Mencari palet: "${query}"`);
});

window.toggleFavorite = function(id) {
    const palette = palettes.find(p => p.id === id);
    if (palette) {
        palette.isFavorite = !palette.isFavorite;
        renderPalettes(palettes); // Re-render dengan input search yang kosong (reset)
        searchInput.value = ''; 
        updateActivity(`Mengubah status favorit untuk "${palette.name}"`);
    }
}

window.exportPalette = function(id) {
    const palette = palettes.find(p => p.id === id);
    const format = document.getElementById(`export-format-${id}`).value;

    if (format === 'json') {
        const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(palette, null, 2));
        triggerDownload(dataStr, `${palette.name}.json`);
        updateActivity(`Mengekspor JSON untuk "${palette.name}"`);
    } 
    else if (format === 'css') {
        let cssText = `/* ${palette.name} Palette */\n:root {\n`;
        palette.colors.forEach((color, index) => {
            cssText += `  --color-${index + 1}: ${color};\n`;
        });
        cssText += `}`;
        const dataStr = "data:text/css;charset=utf-8," + encodeURIComponent(cssText);
        triggerDownload(dataStr, `${palette.name}.css`);
        updateActivity(`Mengekspor CSS untuk "${palette.name}"`);
    }
    else if (format === 'png') {
        .
        alert(`Fitur export PNG untuk palet ${palette.name} membutuhkan library html2canvas. Data CSS/JSON sudah berfungsi!`);
    }
}

function triggerDownload(dataUrl, filename) {
    const downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataUrl);
    downloadAnchorNode.setAttribute("download", filename);
    document.body.appendChild(downloadAnchorNode); 
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}

function updateActivity(text) {
    statActivity.innerText = text;
}

renderPalettes(palettes);