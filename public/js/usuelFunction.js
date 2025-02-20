export async function checkNotesOnLoad(notes) {
    // Parcourir chaque bouton note
    notes.forEach(async noteButton => {
        const noteId = noteButton.getAttribute('data-id');
        const fetchData = await fetch(`/users/displayNote/${noteId}`);
        const datas = await fetchData.json();
        if (datas.length !== 0) {
            noteButton.classList.remove('disable')
        }
    });
}
/**
 * 
 * @param {HTMLElement} $elt 
 * @param {string} a (element comprenant la valeur de reference)
 * @param {string} b (element a comparer)
 */
export function reavel($elt, a, b) {
    if (a === b) {
        $elt.classList.remove('d-none');
    } else {
        $elt.classList.add('d-none');
    }
}
// Mes fonction 
/**
 *  fonction qui controle si la longeur est compris entre {number}min et (number)max
 * @param {string} id 
 * @param {object} input
 * @param {string} messageErreur 
 * @param {number} min 
 * @param {number} max 
 * @returns  true ou false
 */
export function testLongueur(min, max, id, input, messageErreur) {
    //test la longeur de la chaine entre 2 et 50 caractere
    if (input.value.length < min || input.value.length >= max) {
        afficherErreur(id, messageErreur)
        return false
    } else {
        retireErreur(id)
        return true
    }
}
/**
 * function qui affiche un message d'erreur
 * @param {string} id 
 * @param {string} messageErreur 
 * retour rien
 */
export function afficherErreur(id, messageErreur) {
    let input = document.getElementById(id);
    let a = input.closest('.group-form input');
    if (a) {
        a.classList.add('input-error');
    }
    let boxError = document.getElementById('box-error');
    let existingError = document.getElementById(`error-${id}`);
    if (!existingError) {
        let p = document.createElement('p');
        p.className = "p-error large-12";
        p.setAttribute('id', `error-${id}`);
        p.textContent = messageErreur;
        boxError.appendChild(p);
    }
}
/**
 * Fonction qui retire l'erreur
 * @param {string} id 
 * return rien
 */
export function retireErreur(id) {
    let input = document.getElementById(id);
    if (input) {
        let a = input.closest('.group-form');
        if (a) {
            a.classList.remove('input-error');
        }
        let p = document.getElementById(`error-${id}`);
        if (p) {
            p.remove();
        }
    }
}
/**
 * function qui verifie si l'entrée ne contient pas de caractère non autorisé
 * @param {string} id 
 * @param {object} input 
 * @param {string} messageErreur 
 * @returns afficher une erreur et false / true si pas d'erreur
 */
export function testIsCaractere(id, input) {
    let reg = /^[a-zA-ZÀ-ÿ0-9'-]+(?:\s[a-zA-ZÀ-ÿ0-9'-]+)*$/
    if (!reg.test(input.value)) {
        afficherErreur(id, `Les caractères speciaux ne sont pas autorisé dans le champs ${id}`)
        return false
    } else {
        retireErreur(id)
        return true
    }
}
/**
 * Function qui verifie qu'il n y a pas d'injection de script
 * @param {string} id 
 * @param {object} input
 * @param {string} messageErreur 
 * @returns erreur si false / true si aps de probleme
 */
export function noScript(id, input) {
    let reg = /<script>/
    if (reg.test(input.value) === true) {
        afficherErreur(id, 'Les script sont interdit')
        return false
    } else {
        retireErreur(id)
        return true
    }
}
/**
 * function qui verifie que le champs n'est pas vide
 * @param {string} id 
 * @param {any} input
 * @param {string} messageErreur 
 * @returns erreur si false / true si pas de vide
 */
export function isEmpty(id, input) {
    if (input.value === '') {
        afficherErreur(id, `Le champs ${id} ne peut pas être vide.`)
        console.log('c\'est vide');
        return false
    } else {
        retireErreur(id)
        return true
    }
}
/**
 * Fonction qui verifie les emails 
 * @param {string} id 
 * @param {object} input 
 * @returns true si ok / false si erreur 
 */
export function verifEmail(id, input) {
    let reg = /(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/
    if (!reg.test(input.value)) {
        afficherErreur(id, "Vous devez entrer un email valide au format email@exemple.fr")
        return false
    } else {
        retireErreur(id)
        return true
    }
}
export function onlyNum(id, input) {
    const reg = /^[0-9]+$/;
    const valeur = input.value;
    if (valeur === '') {
        retireErreur(id);
        return true;
    }
    if (!reg.test(valeur)) {
        afficherErreur(id, `Le numéro de ${id} doit être exclusivement composé de chiffres`);
        return false;
    } else {
        retireErreur(id)
        return true;
    }
}
export function verifPass(id, input) {
    const reg = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d!@#$%^&*()_+\-=\[\]{};':\"\\\\|,.<>\/?]{8,}$/
    if (!reg.test(input.value)) {
        afficherErreur(id, `Le mot de passe doit contenir au moins 8 caractères
            - 1 majuscule
            - 1 chiffre
            - 1 caractère spécial`)
        return false
    } else {
        retireErreur(id)
        return true
    }
}
export function downloadAndRedirect(btns, currentPage) {
    btns.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault(); // Empêche la navigation par défaut du lien
            console.log(this);
            // Récupère l'URL de téléchargement depuis l'attribut href
            const fileUrl = link.href;
            console.log(fileUrl);
            
            // Crée un lien temporaire pour lancer le téléchargement
            const tempLink = document.createElement('a');
            tempLink.href = fileUrl;
            tempLink.download = '';
            // document.body.appendChild(tempLink);
            // // tempLink.click();
            // document.body.removeChild(tempLink);
            // setTimeout(() => {
            //     window.location.href = '/utilisateurs?page='+currentPage;
            // }, 1000); // Ajustez le délai si nécessaire
        });
    });
}
