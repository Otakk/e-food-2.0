
// panier modal

let openPanier = document.getElementsByClassName("cart_container")[0];
let panier = document.getElementsByClassName("basket")[0];
let closePanier = document.getElementsByClassName("fermer")[0];
let panier_font = document.getElementById("basket_font");

openPanier.addEventListener("click", function () {
    panier.style.right = "0";
    panier_font.style.display = "block";
  });

closePanier.addEventListener("click", function () {
  panier.style.right = "-400px";
  panier_font.style.display = "none";
});

panier_font.addEventListener("click", function () {
  panier.style.right = "-400px";
  panier_font.style.display = "none";
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


// login modal

let open_login_modal = document.querySelector("#connexion");
let background_modal = document.querySelector("#login_modal_container");
let login_modal = document.querySelector("#login_modal");
let register_modal = document.querySelector("#register_modal");
let close_login_modal = document.querySelector("#close_login_modal_border");
let close_register_modal = document.querySelector("#close_register_modal");
let close_login_modal_bg = document.querySelectorAll(".WayToCloseModal");
let click_here_login = document.querySelector("#click_here_login");
let click_here_register = document.querySelector("#click_here_register");
let isOpen2 = false;
let delay = 1000;

open_login_modal.addEventListener("click", function () {
  background_modal.classList.add("active");
  login_modal.classList.add("active");
  document.body.style.overflow = "hidden";
});

close_login_modal.addEventListener("click", function () {
  background_modal.classList.remove("active");
  login_modal.classList.remove("active");
  document.body.style.overflow = "initial";
});

close_register_modal.addEventListener("click", function () {
  background_modal.classList.remove("active");
  register_modal.classList.remove("active");
});

Array.from(close_login_modal_bg).forEach((e) => {
  e.addEventListener("click", function () {
    background_modal.classList.remove("active");
    login_modal.classList.remove("active");
    register_modal.classList.remove("active");
    document.body.style.overflow = "initial";
  });
});

click_here_login.addEventListener("click", function () {
  login_modal.classList.remove("active");
  setTimeout(function () {
    register_modal.classList.add("active");
  }, 600);
});

click_here_register.addEventListener("click", function () {
  register_modal.classList.remove("active");
  setTimeout(function () {
    login_modal.classList.add("active");
  }, 600);
});

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