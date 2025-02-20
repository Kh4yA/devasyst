import { GestionMessageFash } from "./usuelClass"
import { isEmpty, noScript, testIsCaractere, verifEmail } from "./usuelFunction";

const btn_prestation = document.getElementById('prestation');
const btn_prestationMobile = document.getElementById('prestationMobile');
const menu = document.querySelector('.menu-slide');
const menuMobile = document.querySelector('.menu-slide-mobile');

const openBurger = document.getElementById('open-burger')
const burger = document.querySelector('.burger')
const menuBurger = document.querySelector('.menu-burger')

const email = document.getElementById('email')
const formContact = document.getElementById('formContact')
const btnSubmit = document.querySelector('#formContact button')
const nom = document.getElementById('nom')
const prenom = document.getElementById('prenom')
const message = document.getElementById('message')


if(document.querySelectorAll('.alert' != null)){
  const flashMessages = document.querySelectorAll('.alert')
  
  const flashMessage = new GestionMessageFash(flashMessages)
  flashMessage.flash()
}

/**
 * ANIMATION DU MENU BURGER
 */
openBurger.addEventListener('click', () => {
  burger.classList.toggle('open')
  menuBurger.classList.toggle('d-none')
  if(!menuMobile.classList.contains('hidden')){
    menuMobile.classList.remove('visible')
}

})

btn_prestation.addEventListener('click', () => {
    menu.classList.add('visible');
    menu.classList.remove('hidden');
    console.log('click');
    
});
btn_prestationMobile.addEventListener('click', () => {
    menuMobile.classList.add('visible')
    menuMobile.classList.remove('hidden')
});

menu.addEventListener('mouseleave', () => {
    menu.classList.add('hidden');
    menu.classList.remove('visible');
});
//Animation 
const ratio = .2
let options = {
    root: null,
    rootMargin: "0px",
    threshold: ratio,
  };
  
  let animatIntersect = function (entries, observer) {
    entries.forEach(entry => {
        if (entry.intersectionRatio > ratio) {
            entry.target.classList.add('anime-reveal');
            observer.unobserve(entry.target);
            console.log(entry.target);
        }
    });
};
  let observer = new IntersectionObserver(animatIntersect, options);
  (document.querySelectorAll('.anime').forEach(a =>{
    observer.observe(a)
  }))


if(document.querySelector('.why-choose-us') != null){
  document.addEventListener("DOMContentLoaded", function() {
    let section = document.querySelector(".why-choose-us");
    let observer = new IntersectionObserver(entries => {
        if (entries[0].isIntersecting) {
            section.classList.add("visible");
        }
    }, { threshold: 0.3 });
    observer.observe(section);
});
}
if(formContact){
    email.addEventListener('change', (e) => {
        checkEmail()
    })
    nom.addEventListener('change', (e) => {
        verifNom()
    })
    prenom.addEventListener('change', (e) => {
        verifPrenom()
    })
    message.addEventListener('change', (e) => {
      verifMessage()
    })
    // Ajout d'un indicateur de validation globale
    function allValid() {
        return  checkEmail() && verifNom() && verifPrenom() && verifMessage();
    }
    function verifNom() {
        return isEmpty(nom.id, nom) && noScript(nom.id, nom) && testIsCaractere(nom.id, nom);
    }
    
    function verifPrenom() {
        return isEmpty(prenom.id, prenom) && noScript(prenom.id, prenom) && testIsCaractere(prenom.id, prenom);
    }
    
    function checkEmail() {
        return isEmpty(email.id, email) && noScript(email.id, email) && verifEmail(email.id, email);
    }
    function verifMessage() {
      return isEmpty(message.id, message) && noScript(message.id, message)
    }
    formContact.addEventListener('submit', (event) => {
        event.preventDefault();
        if (allValid()) {
            console.log('On peut envoyer');
            btnSubmit.disabled = true
            formContact.submit();
        }else{
          console.log('On peut pas envoyer');
            btnSubmit.disabled = false
        }
    });
}