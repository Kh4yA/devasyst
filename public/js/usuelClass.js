export class GestionMessageFash {/**
    * 
    * @param {HTMLElement} elts  - Élément HTML pour afficher les messages flash.
    */
       constructor(elts) {
           this.elts = elts;
       }
       flash() {
           this.elts.forEach(elt => {
               setTimeout(() => {
                   elt.remove()
               }, 3000)
           })
       }
   }