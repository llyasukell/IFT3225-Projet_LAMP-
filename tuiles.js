
const tuilesData = [
  { titre: "Service 1", lien: "#",image: "img/service1.png" },
  { titre: "Service 2", lien: "#",image: "img/service1.png" },
  { titre: "Service 3", lien: "#",image: "img/service1.png" },
  { titre: "Service 4", lien: "#",image: "img/service1.png" },
  { titre: "Service 5", lien: "#",image: "img/service1.png" }
];

const grilleTuiles = document.getElementById("grille-tuiles");

tuilesData.forEach(service => {

  const tuile = document.createElement("article");
  tuile.className = "tuile";


  const h4 = document.createElement("h4");
  h4.textContent = service.titre;

  const img = document.createElement("img");
   img.src = service.image; 
   img.alt = service.titre
   img.className = "image-tuile";



  const btn = document.createElement("button");
  
  

  btn.textContent = "Voir";
  btn.onclick = () => {
    window.location.href = service.lien;
  };

  tuile.appendChild(img);
  tuile.appendChild(h4);
  tuile.appendChild(btn);
  grilleTuiles.appendChild(tuile);
});