const LIMIT = 15;
let searchTimeout;

//  PARTIE EXPLORE 
const grilleTuiles = document.getElementById("grille-tuiles");
const paginationExplore = document.getElementById("pagination-explore");
const searchExplore = document.getElementById("search-explore");
let currentPageExplore = 1;
let currentSearchExplore = '';

function loadExploreTrips(page = 1, search = '') {
    let url = `get_trips.php?page=${page}&limit=${LIMIT}`;
    if (search) {
        url += `&search=${encodeURIComponent(search)}`;
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            const voyages = data.trips;
            const total = data.total;
            const totalPages = Math.ceil(total / LIMIT);

            grilleTuiles.innerHTML = '';
            if (voyages.length === 0) {
                grilleTuiles.innerHTML = "<p>Aucun voyage trouvé.</p>";
            } else {
                voyages.forEach(voyage => {
                    const tuile = document.createElement("article");
                    tuile.className = "tuile";
                    tuile.innerHTML = `
                        <img src="${voyage.image_path ? 'uploads/' + voyage.image_path : 'img/service1.png'}" class="image-tuile">
                        <span class="tag-continent ${voyage.region ? voyage.region.toLowerCase() : ''}">
                                ${voyage.region ? voyage.region.toUpperCase() : 'VOYAGE'}
                        </span>
                        <span class="likes" id="count-${voyage.id}">${voyage.like_count || 0} LIKES</span>
                        <p class="nom-auteur">Par : ${voyage.author_name}</p>
                        <h4>${voyage.title}</h4>
                        <div class="boutons-actions">
                            <button class="btn-info" id="info-${voyage.id}">Voir les détails</button>
                            ${(typeof IS_CONNECTED !== 'undefined' && IS_CONNECTED) 
                                ? `<button class="btn-like" onclick="gererLike(${voyage.id})">❤️</button>` 
                                : ''}
                        </div>
                    `;
                    grilleTuiles.appendChild(tuile);

                    document.getElementById(`info-${voyage.id}`).onclick = () => {
                        alert(` Pays : ${voyage.location}\n\n Description : ${voyage.description || 'Aucune description'}`);
                    };
                });
            }

            if (paginationExplore) {
                generatePagination(totalPages, page, paginationExplore, (newPage) => {
                    currentPageExplore = newPage;
                    loadExploreTrips(newPage, currentSearchExplore);
                });
            }
        });
}

if (searchExplore) {
    searchExplore.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const term = e.target.value;
        currentSearchExplore = term;
        currentPageExplore = 1;
        searchTimeout = setTimeout(() => {
            loadExploreTrips(1, term);
        }, 300);
    });
}

//  PARTIE MES VOYAGES 
const maGrillePerso = document.getElementById("grille-mes-voyages");
const paginationMesVoyages = document.getElementById("pagination-mesvoyages");
const searchMesVoyages = document.getElementById("search-mes-voyages");
let currentPageMesVoyages = 1;
let currentSearchMesVoyages = '';

function loadMesVoyages(page = 1, search = '') {
    let url = `get_user_trips.php?page=${page}&limit=${LIMIT}`;
    if (search) {
        url += `&search=${encodeURIComponent(search)}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const voyages = data.trips;
            const total = data.total;
            const totalPages = Math.ceil(total / LIMIT);

            maGrillePerso.innerHTML = '';
            if (voyages.length === 0) {
                maGrillePerso.innerHTML = "<p>Aucun voyage trouvé.</p>";
            } else {
                voyages.forEach(voyage => {
                    const carte = document.createElement("div");
                    carte.className = "carte-voyage";
                    carte.innerHTML = `
                
                        <div class="carte-header">
                            
                            <span class="tag-continent ${voyage.region ? voyage.region.toLowerCase() : ''}">
                                ${voyage.region ? voyage.region.toUpperCase() : 'VOYAGE'}
                            </span>
                            <span class="likes" id="count-${voyage.id}">${voyage.like_count || 0} LIKES</span>
                        </div>
                        <img src="${voyage.image_path ? 'uploads/' + voyage.image_path : 'img/service1.png'}" class="img-voyage">
                        <div class="carte-info">
                            <p class="date">${voyage.travel_date}</p>
                            <p class="titre">${voyage.title}</p>
                        </div>
                        <div class="carte-actions">
                            <button class="btn-like" onclick="gererLike(${voyage.id})">❤️</button>
                            <button class="btn-modifier" onclick="window.location.href='modifier.php?id=${voyage.id}'">MODIFIER</button>
                            <button class="btn-supprimer" onclick="if(confirm('Supprimer ?')) window.location.href='delete_trip.php?id=${voyage.id}'">SUPPRIMER</button>
                        </div>
                    `;
                    maGrillePerso.appendChild(carte);
                });
            }

            if (paginationMesVoyages) {
                generatePagination(totalPages, page, paginationMesVoyages, (newPage) => {
                    currentPageMesVoyages = newPage;
                    loadMesVoyages(newPage, currentSearchMesVoyages);
                });
            }
        });
}

if (searchMesVoyages) {
    searchMesVoyages.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const term = e.target.value;
        currentSearchMesVoyages = term;
        currentPageMesVoyages = 1;
        searchTimeout = setTimeout(() => {
            loadMesVoyages(1, term);
        }, 300);
    });
}

//  CHARGEMENT INITIAL 
if (grilleTuiles) {
    loadExploreTrips(1, '');
}

if (maGrillePerso) {
    loadMesVoyages(1, '');
}

//  FONCTION DE PAGINATION 
function generatePagination(totalPages, currentPage, container, callback) {
    if (!container) return;
    container.innerHTML = '';

    if (totalPages <= 1) return;

    const first = document.createElement('button');
    first.textContent = '«';
    first.disabled = currentPage === 1;
    first.onclick = () => callback(1);
    container.appendChild(first);

    const prev = document.createElement('button');
    prev.textContent = '‹';
    prev.disabled = currentPage === 1;
    prev.onclick = () => callback(currentPage - 1);
    container.appendChild(prev);

    const maxVisible = 5;
    let start = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let end = Math.min(totalPages, start + maxVisible - 1);
    if (end - start + 1 < maxVisible) {
        start = Math.max(1, end - maxVisible + 1);
    }

    if (start > 1) {
        const dots = document.createElement('button');
        dots.textContent = '...';
        dots.disabled = true;
        container.appendChild(dots);
    }

    for (let i = start; i <= end; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if (i === currentPage) {
            btn.classList.add('actif');
        }
        btn.onclick = () => callback(i);
        container.appendChild(btn);
    }

    if (end < totalPages) {
        const dots = document.createElement('button');
        dots.textContent = '...';
        dots.disabled = true;
        container.appendChild(dots);
    }

    const next = document.createElement('button');
    next.textContent = '›';
    next.disabled = currentPage === totalPages;
    next.onclick = () => callback(currentPage + 1);
    container.appendChild(next);

    const last = document.createElement('button');
    last.textContent = '»';
    last.disabled = currentPage === totalPages;
    last.onclick = () => callback(totalPages);
    container.appendChild(last);
}

//  FONCTION LIKE 
function gererLike(tripId) {
    const formData = new FormData();
    formData.append('trip_id', tripId);

    fetch('like_trip.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.total !== undefined) {
            // On cherche tous les compteurs de ce voyage (dans Explore et Mes Voyages)
            const likeSpans = document.querySelectorAll(`[id="count-${tripId}"]`);
            likeSpans.forEach(span => {
                span.textContent = `${data.total} LIKES`;
            });
        }
    })
    .catch(err => console.error("Erreur de like:", err));
}

// Initialisation et Recherche
if (searchExplore) {
    searchExplore.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        currentSearchExplore = e.target.value;
        searchTimeout = setTimeout(() => loadExploreTrips(1, currentSearchExplore), 300);
    });
}