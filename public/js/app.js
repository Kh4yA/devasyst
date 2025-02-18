import { GestionMessageFash } from "./usuelClass"

const btn_prestation = document.getElementById('prestation');
const btn_prestationMobile = document.getElementById('prestationMobile');
const menu = document.querySelector('.menu-slide');
const menuMobile = document.querySelector('.menu-slide-mobile');

const openBurger = document.getElementById('open-burger')
const burger = document.querySelector('.burger')
const menuBurger = document.querySelector('.menu-burger')

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
