q=e=>document.querySelector(e);
let darkMode = localStorage.getItem("darkMode");

setDarkMode((darkMode!="undefined" && darkMode=="true"))

function setDarkMode(b) {
  localStorage.setItem("darkMode", b);
  if (b) {
    q('#theme').href='/css/dark.css';
  } else {
    q('#theme').href='/css/light.css';
  }
}
