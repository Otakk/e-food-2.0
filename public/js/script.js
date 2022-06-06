// panier modal

let openPanier = document.getElementsByClassName("cart_container");
let panier = document.getElementsByClassName("panier")[0];
let closePanier = document.getElementsByClassName("fermer")[0];
let isOpen2 = false;

Array.from(openPanier).forEach((x) => {
  console.log(x);
  x.addEventListener("click", function () {
    panier.style.display = "block";
    isOpen2 = true;
  });

  closePanier.addEventListener("click", function () {
    panier.style.display = "none";
    isOpen2 = false;
  });
});

// nav modal categorie
let home_modal_categorie = document.querySelector("#modal_categorie");
let open_modal_categorie = document.querySelector("#dropdown_categorie");
let isOpen_categorie = false;

open_modal_categorie.addEventListener("click", function () {
  if (isOpen_categorie == false) {
    home_modal_categorie.style.display = "flex";
    isOpen_categorie = true;
  } else {
    home_modal_categorie.style.display = "none";
    isOpen_categorie = false;
  }
});

// menu burger modal

let openMenuBurger = document.getElementById("menu");
let Menu = document.getElementsByClassName("menu_modal")[0];
let closeMenu = document.getElementById("menu_close");
let isOpen3 = false;

openMenuBurger.addEventListener("click", function () {
  Menu.style.display = "block";
  isOpen3 = true;
});

closeMenu.addEventListener("click", function () {
  console.log(closeMenu);
  Menu.style.display = "none";
  isOpen3 = false;
});

// Pour modifier la quantité

let qty = document.querySelectorAll("#qty");
Array.from(qty).forEach((element) => {
  element.addEventListener("change", function () {
    var id = element.getAttribute("data-id");

    console.log(this.value);
    window.location.href = `/panier/update/${id}/${parseInt(this.value)}`;
  });
});

// hover du boutons des cards produits

function mouseOver(id) {
  let dd = document.querySelector(`.left_modal${id}`);
  dd.style.right = "-60px";
  dd.style.transition ='ease-in-out 250ms'
}

function mouseOut(id) {
  let dd = document.querySelector(`.left_modal${id}`);
 
  setTimeout(slowAlert(dd), 20000)
 
}

function slowAlert(dd) {
  dd.style.right = "-5px";
  dd.style.transition ="ease-in-out 5000ms"
}


// nav modal name

let home_modal = document.querySelector("#modal");
let open_modal = document.querySelector("#dropdown");
let isOpen = false;

open_modal.addEventListener("click", function () {
  if (isOpen == false) {
    home_modal.style.display = "flex";
    isOpen = true;
  } else {
    home_modal.style.display = "none";
    isOpen = false;
  }
});