"use strict";

// Sélection des sections et des boutons
const pages = [
  {
    page: document.querySelector(".Homepage"),
    button: document.querySelector("#homePage"),
    buttons: document.querySelector("#homePages"),
  },
  {
    page: document.querySelector(".aboutus"),
    button: document.querySelector("#aboutUs"),
    buttons: document.querySelector("#aboutUss"),
  },
  {
    page: document.querySelector(".Servicepage"),
    button: document.querySelector("#servicePage"),
    buttons: document.querySelector("#servicePages"),
  },
  {
    page: document.querySelector(".projectpage"),
    button: document.querySelector("#projectPage"),
    buttons: document.querySelector("#projectPages"),
  },
  {
    page: document.querySelector(".contactpage"),
    button: document.querySelector("#contactPage"),
    buttons: document.querySelector("#contactPages"),
  },
];

const menuicon = document.getElementById("menuicon");
const listemenusmall = document.querySelector(".listemenusmall");
const iconbarre = document.getElementById("iconbarre");

iconbarre.addEventListener("click", function () {
  if (listemenusmall.style.display == "none") {
    listemenusmall.style.display = "flex";
    iconbarre.style.transform = "rotateZ(90deg)";
  } else {
    iconbarre.style.transform = "rotateZ(0deg)";
    setTimeout(() => {
      listemenusmall.style.display = "none";
    }, 500);
  }
});

const transbarre = document.getElementById("transbarre");

// Garder la position initiale de la barre
let initialBarPosition = 0;
let currentTranslation = 0;

// Fonction pour masquer toutes les pages
function hideAllPages() {
  pages.forEach(({ page, button, buttons }) => {
    page.style.display = "none";
    button.style.backgroundColor = "white";
    button.style.color = "black";
    button.style.textDecoration = "none";
    button.style.padding = "0px";
    button.style.borderRadius = "0%";
    button.style.boxShadow = "0px 0px 0px black";
    buttons.style.backgroundColor = "transparent";
  });
}

// Fonction pour styliser le bouton actif
function styleActiveButton(button) {
  button.style.backgroundColor = "rgb(255, 127, 42)";
  button.style.color = "white";
  button.style.textDecoration = "none";
  button.style.padding = "10px";
  button.style.borderRadius = "5%";
  button.style.boxShadow = "4px 4px 6px black";
}
function styleActiveButtons(button) {
  button.style.backgroundColor = "rgb(255, 127, 42)";
}

// Fonction pour déplacer la barre de transition
function moveTransBar(button) {
  // S'assurer que tous les éléments sont complètement chargés et rendus
  requestAnimationFrame(() => {
    const buttonRect = button.getBoundingClientRect();

    // Calculer la position absolue à laquelle la barre devrait être
    const targetPosition = buttonRect.left;

    // Calculer le déplacement nécessaire depuis la position initiale
    const newTranslation = targetPosition - initialBarPosition;

    // Appliquer la nouvelle translation
    transbarre.style.transform = `translateX(${newTranslation}px)`;
    currentTranslation = newTranslation;

    // Optionnel: ajuster la largeur de la barre pour qu'elle corresponde au bouton
    transbarre.style.width = `${buttonRect.width}px`;
  });
}

// Fonction pour ajouter des événements aux boutons
function addEventListeners() {
  pages.forEach(({ page, button, buttons }) => {
    button.addEventListener("click", function () {
      hideAllPages(); // Masquer toutes les pages
      page.style.display = "grid"; // Afficher la page correspondante
      styleActiveButton(button); // Styliser le bouton actif
      moveTransBar(button); // Déplacer la barre de transition
    });
    buttons.addEventListener("click", function () {
      hideAllPages(); // Masquer toutes les pages
      page.style.display = "grid"; // Afficher la page correspondante
      styleActiveButtons(buttons); // Styliser le bouton actif
    });
  });
}

const contactfrservice = document.getElementById("contactfrservice");

contactfrservice.addEventListener("click", function () {
  hideAllPages(); // Masquer toutes les pages
  pages[4].page.style.display = "grid"; // Afficher la page correspondante
  styleActiveButton(pages[4].button); // Styliser le bouton actif
  moveTransBar(pages[4].button);
});

const buttonproject = document.querySelector(".buttonproject");
buttonproject.addEventListener("click", function () {
  hideAllPages(); // Masquer toutes les pages
  pages[3].page.style.display = "grid"; // Afficher la page correspondante
  styleActiveButton(pages[3].button); // Styliser le bouton actif
  moveTransBar(pages[3].button);
});

// Initialisation : masquer toutes les pages sauf la première (Homepage par défaut)
function init() {
  hideAllPages();
  pages[0].page.style.display = "grid"; // Afficher la première page par défaut
  styleActiveButton(pages[0].button); // Styliser le premier bouton par défaut

  // Attendre que tout soit chargé avant de positionner la barre
  window.addEventListener("load", () => {
    // Capture la position initiale de la barre une seule fois
    const barRect = transbarre.getBoundingClientRect();
    initialBarPosition = barRect.left;
    console.log("Position initiale de la barre:", initialBarPosition);

    moveTransBar(pages[0].button); // Positionner la barre sous le premier bouton
  });

  addEventListeners(); // Ajouter les événements de clic aux boutons
}

// Appeler l'initialisation
init();

const flechleft = document.getElementById("flechleft");
const flechright = document.getElementById("flechright");
const service = document.querySelectorAll(".service");
const services = document.querySelector(".services");

flechleft.addEventListener("click", function translationservice() {
  // service.forEach(element => {
  //   element.style.transform=`translateX(300px)`;
  //   console.log("left");

  // });
  services.scrollBy({
    left: -365,
    behavior: "smooth", // Défilement fluide
  });
});
flechright.addEventListener("click", function translationservice() {
  services.scrollBy({
    left: 365,
    behavior: "smooth", // Défilement fluide
  });
});
