// limite de voyages par page pour la pagination
const LIMIT = 15;
let searchTimeout;

//  PARTIE EXPLORE 
const grilleTuiles = document.getElementById("grille-tuiles");
const paginationExplore = document.getElementById("pagination-explore");
const searchExplore = document.getElementById("search-explore");
const filterRegionExplore = document.getElementById("filter-region");
const sortExplore = document.getElementById("sort-explore");

let currentPageExplore = 1;
let currentSearchExplore = '';
let currentRegionExplore = '';
let currentSortExplore = 'recent';

function loadExploreTrips(page = 1, search = '', region = '', sort = 'recent') {
    let url = `get_trips.php?page=${page}&limit=${LIMIT}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (region) url += `&region=${encodeURIComponent(region)}`;
    if (sort) url += `&sort=${encodeURIComponent(sort)}`;

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
                    // Photo de profil de l'auteur
                    const profPic = voyage.author_pic ? 'uploads/' + voyage.author_pic : 'https://www.w3schools.com/howto/img_avatar.png';

                    const tuile = document.createElement("article");
                    tuile.className = "tuile";
                    tuile.innerHTML = `
                        <img src="${voyage.image_path ? 'uploads/' + voyage.image_path : 'img/service1.png'}" class="image-tuile">
                        <span class="tag-continent ${voyage.region ? voyage.region.toLowerCase() : ''}">
                                ${voyage.region ? voyage.region.toUpperCase() : 'VOYAGE'}
                        </span>
                        <span class="likes" id="count-${voyage.id}">${voyage.like_count || 0} LIKES</span>
                        
                        <div class="auteur-container" style="display: flex; align-items: center; gap: 8px; margin: 5px 0;">
                            <img src="${profPic}" class="avatar-auteur" style="width: 25px; height: 25px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd;">
                            <p class="nom-auteur" style="margin: 0;">Par : ${voyage.author_name}</p>
                        </div>

                        <h4>${voyage.title}</h4>
                        <div class="boutons-actions">
                            <button class="btn-info" id="info-${voyage.id}">Voir les détails</button>
                            ${(typeof IS_CONNECTED !== 'undefined' && IS_CONNECTED) 
                                ? `<button class="btn-like" onclick="gererLike(${voyage.id})">❤️</button>` 
                                : ''}
                        </div>
                    `;
                    grilleTuiles.appendChild(tuile);

                    // Création de la modale au clic
                    document.getElementById(`info-${voyage.id}`).onclick = () => {
                        // Préparation des images supplémentaires
                        let extraImagesHTML = '';
                        if (voyage.extra_photos && voyage.extra_photos.length > 0) {
                            voyage.extra_photos.forEach(photo => {
                                // Ajout de onclick pour agrandir l'image
                                extraImagesHTML += `<img src="uploads/${photo}" onclick="ouvrirPleinEcran(this.src)" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; cursor: pointer;">`;
                            });
                        } else {
                            extraImagesHTML = '<p style="color: #666; font-size: 0.9em;">Aucune photo supplémentaire.</p>';
                        }

                        // Création du conteneur de la modale
                        const modal = document.createElement('div');
                        modal.style.cssText = "position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); display:flex; justify-content:center; align-items:center; z-index:1000;";
                        modal.innerHTML = `
                            <div style="background:white; padding:20px; border-radius:10px; max-width:600px; width:90%; text-align:center; max-height: 80vh; overflow-y: auto;">
                                <h2 style="margin-bottom: 10px;">${voyage.title} <span style="font-size: 0.7em; color: gray;">(${voyage.location})</span></h2>
                                <p style="margin-bottom: 20px; line-height: 1.5;">${voyage.description || 'Aucune description'}</p>
                                
                                <div style="display:flex; flex-wrap:wrap; justify-content:center; gap: 10px; margin-top:15px;">
                                    ${extraImagesHTML}
                                </div>
                                
                                <button id="close-modal-${voyage.id}" style="margin-top:20px; padding:10px 20px; cursor:pointer; background:#333; color:white; border:none; border-radius:5px;">Fermer</button>
                            </div>
                        `;
                        
                        document.body.appendChild(modal);
                        
                        // Fermeture de la modale
                        document.getElementById(`close-modal-${voyage.id}`).onclick = () => {
                            document.body.removeChild(modal);
                        };
                    };
                });
            }

            if (paginationExplore) {
                generatePagination(totalPages, page, paginationExplore, (newPage) => {
                    currentPageExplore = newPage;
                    loadExploreTrips(newPage, currentSearchExplore, currentRegionExplore, currentSortExplore);
                });
            }
        });
}

// Listeners Explore
if (searchExplore) {
    searchExplore.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        currentSearchExplore = e.target.value;
        searchTimeout = setTimeout(() => loadExploreTrips(1, currentSearchExplore, currentRegionExplore, currentSortExplore), 300);
    });
}
if (filterRegionExplore) {
    filterRegionExplore.addEventListener('change', (e) => {
        currentRegionExplore = e.target.value;
        loadExploreTrips(1, currentSearchExplore, currentRegionExplore, currentSortExplore);
    });
}
if (sortExplore) {
    sortExplore.addEventListener('change', (e) => {
        currentSortExplore = e.target.value;
        loadExploreTrips(1, currentSearchExplore, currentRegionExplore, currentSortExplore);
    });
}

//  PARTIE MES VOYAGES 
const maGrillePerso = document.getElementById("grille-mes-voyages");
const paginationMesVoyages = document.getElementById("pagination-mesvoyages");
const searchMesVoyages = document.getElementById("search-mes-voyages");
const sortMesVoyages = document.getElementById("sort-mes-voyages");

let currentPageMesVoyages = 1;
let currentSearchMesVoyages = '';
let currentSortMesVoyages = 'recent';

function loadMesVoyages(page = 1, search = '', sort = 'recent') {
    let url = `get_user_trips.php?page=${page}&limit=${LIMIT}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (sort) url += `&sort=${encodeURIComponent(sort)}`;

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
                    loadMesVoyages(newPage, currentSearchMesVoyages, currentSortMesVoyages);
                });
            }
        });
}

if (searchMesVoyages) {
    searchMesVoyages.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        currentSearchMesVoyages = e.target.value;
        searchTimeout = setTimeout(() => {
            loadMesVoyages(1, currentSearchMesVoyages, currentSortMesVoyages);
        }, 300);
    });
}
if (sortMesVoyages) {
    sortMesVoyages.addEventListener('change', (e) => {
        currentSortMesVoyages = e.target.value;
        loadMesVoyages(1, currentSearchMesVoyages, currentSortMesVoyages);
    });
}

//  CHARGEMENT INITIAL 
if (grilleTuiles) {
    loadExploreTrips(1, '', '', 'recent');
}

if (maGrillePerso) {
    loadMesVoyages(1, '', 'recent');
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

// FONCTION POUR OUVRIR UNE IMAGE EN PLEIN ÉCRAN
function ouvrirPleinEcran(src) {
    const overlay = document.createElement('div');
    overlay.style.cssText = "position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.9); display:flex; justify-content:center; align-items:center; z-index:2000; cursor:zoom-out;";
    
    const img = document.createElement('img');
    img.src = src;
    img.style.cssText = "max-width:90%; max-height:90%; border-radius:5px; box-shadow: 0 0 20px rgba(0,0,0,0.5);";
    
    overlay.appendChild(img);
    overlay.onclick = () => document.body.removeChild(overlay);
    document.body.appendChild(overlay);
}