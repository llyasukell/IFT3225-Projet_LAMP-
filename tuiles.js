const grilleTuiles = document.getElementById("grille-tuiles");

fetch('get_trips.php')
  .then(response => response.json())
  .then(tuilesData => {
    
    if(tuilesData.length === 0) {
        grilleTuiles.innerHTML = "<p>Aucun voyage pour le moment.</p>";
        return;
    }

    tuilesData.forEach(voyage => {
      const tuile = document.createElement("article");
      tuile.className = "tuile";

      // 1. Image du voyage
      const img = document.createElement("img");
      img.src = voyage.image_path ? "uploads/" + voyage.image_path : "img/service1.png";
      img.className = "image-tuile";

      // 2. Nom de l'utilisateur (Auteur)
      const auteur = document.createElement("p");
      auteur.className = "nom-auteur";
      auteur.textContent = "Par : " + voyage.author_name;

      // 3. Titre du voyage
      const h4 = document.createElement("h4");
      h4.textContent = voyage.title;

      // 4. Bouton Voir (Affiche la description dans une alerte)
      const btn = document.createElement("button");
      btn.textContent = "Voir les détails";
      btn.onclick = () => {
        alert("🌍 Pays : " + voyage.location + "\n\n📝 Description : \n" + voyage.description);
      };

      // On ajoute tout à la tuile
      tuile.appendChild(img);
      tuile.appendChild(auteur); // Affiche le nom de l'user
      tuile.appendChild(h4);
      tuile.appendChild(btn);
      grilleTuiles.appendChild(tuile);
    });
    
  })
  .catch(err => console.error("Erreur :", err));