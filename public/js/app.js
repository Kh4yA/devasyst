import { GestionMessageFash } from "./usuelClass"

const btn_prestation = document.getElementById('prestation');
const menu = document.querySelector('.menu-slide');

const openBurger = document.getElementById('open-burger')
const burger = document.querySelector('.burger')
const header = document.querySelector('.header-nav')

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
  header.classList.toggle('active')
})

btn_prestation.addEventListener('click', () => {
    menu.classList.add('visible');
    menu.classList.remove('hidden');
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
        if(entry.intersectionRatio > ratio){
            entry.target.classList.add('anime-left-visible')
            entry.target.classList.add('anime-right-visible')
            entry.target.classList.add('anime-visible')
            observer.unobserve(entry.target)
            console.log(entry.target);
            
        }
    });
  }
  let observer = new IntersectionObserver(animatIntersect, options);
  (document.querySelectorAll('.anime-left, .anime-right, .anime').forEach(a =>{
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