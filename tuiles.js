
const tuilesData = [
  { titre: "Service 1", lien: "#" },
  { titre: "Service 2", lien: "#" },
  { titre: "Service 3", lien: "#" },
  { titre: "Service 4", lien: "#" },
  { titre: "Service 5", lien: "#" }
];

const grilleTuiles = document.getElementById("grille-tuiles");

tuilesData.forEach(service => {

  const tuile = document.createElement("article");
  tuile.className = "tuile";


  const h4 = document.createElement("h4");
  h4.textContent = service.titre;


  const btn = document.createElement("button");
  btn.textContent = "Voir";
  btn.onclick = () => {
    window.location.href = service.lien;
  };


  tuile.appendChild(h4);
  tuile.appendChild(btn);
  grilleTuiles.appendChild(tuile);
});