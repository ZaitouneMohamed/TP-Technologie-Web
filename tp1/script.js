function changerTexte() {
    const texte = document.querySelector('#texte1');
    texte.innerText = 'Texte modifié !';
}

function changerCouleur() {
    const titre = document.querySelector('#titre2');
    titre.style.color = 'red';
}

function changerCouleurVert() {
    const titre = document.querySelector('#titre2');
    titre.style.color = 'green';
}

function changerCouleurBleu() {
    const titre = document.querySelector('#titre2');
    titre.style.color = 'blue';
}

function ajouterContenu() {
    const conteneur = document.querySelector('#conteneur3');
    conteneur.innerHTML = '<h4>Nouveau titre</h4><p>Nouveau paragraphe</p>';
}

function effacerContenu() {
    const conteneur = document.querySelector('#conteneur3');
    conteneur.innerHTML = '';
}

function ajouterClasse() {
    const paragraphe = document.querySelector('#paragraphe4');
    paragraphe.classList.add('rouge', 'grand-texte');
}

function enleverClasse() {
    const paragraphe = document.querySelector('#paragraphe4');
    paragraphe.classList.remove('rouge', 'grand-texte');
}

function basculerClasse() {
    const paragraphe = document.querySelector('#paragraphe4');
    paragraphe.classList.toggle('bordure');
}

function colorierTous() {
    const items = document.querySelectorAll('.item');
    items.forEach(item => {
        item.style.backgroundColor = 'yellow';
    });
}

function numeroteTous() {
    const items = document.querySelectorAll('.item');
    items.forEach((item, index) => {
        const texteSansNumero = item.textContent.replace(/^\d+\.\s*/, '');
        item.textContent = `${index + 1}. ${texteSansNumero}`;
    });
}

function changerFond(element, couleur) {
    document.body.style.backgroundColor = couleur;
    document.querySelectorAll('.boite').forEach(boite => {
        boite.classList.remove('actif');
    });
    element.classList.add('actif');
}

function reinitialiserFond() {
    document.body.style.backgroundColor = '';
    document.querySelectorAll('.boite').forEach(boite => {
        boite.classList.remove('actif');
    });
}

function afficherNom() {
    const nom = document.querySelector('#nom').value.trim();
    const affichage = document.querySelector('#affichage7');
    affichage.innerText = nom ? `Bonjour ${nom} !` : 'Votre nom apparaîtra ici';
}

function appliquerCouleur() {
    const couleur = document.querySelector('#couleur').value;
    const affichage = document.querySelector('#affichage7');
    affichage.style.color = couleur || 'black';
}

function ajouterTache() {
    const input = document.querySelector('#tache');
    const texte = input.value.trim();
    if (!texte) {
        return;
    }

    const liste = document.querySelector('#listeTaches');
    const li = document.createElement('li');
    li.innerHTML = `${texte} <button onclick="supprimerTache(this)">X</button>`;
    liste.appendChild(li);
    input.value = '';
}

function supprimerTache(bouton) {
    const li = bouton.parentElement;
    if (li) {
        li.remove();
    }
}

function effacerTout() {
    const liste = document.querySelector('#listeTaches');
    liste.innerHTML = '';
}

function incrementer() {
    const compteur = document.querySelector('#compteur');
    let valeur = parseInt(compteur.innerText, 10) || 0;
    valeur += 1;
    compteur.innerText = valeur;
    ajusterCouleur(valeur);
}

function decrementer() {
    const compteur = document.querySelector('#compteur');
    let valeur = parseInt(compteur.innerText, 10) || 0;
    valeur -= 1;
    compteur.innerText = valeur;
    ajusterCouleur(valeur);
}

function reinitialiser() {
    const compteur = document.querySelector('#compteur');
    compteur.innerText = '0';
    compteur.classList.remove('rouge', 'vert');
    compteur.style.color = 'black';
}

function ajusterCouleur(valeur) {
    const compteur = document.querySelector('#compteur');
    compteur.classList.remove('rouge', 'vert');
    if (valeur > 0) {
        compteur.classList.add('vert');
    } else if (valeur < 0) {
        compteur.classList.add('rouge');
    } else {
        compteur.style.color = 'black';
    }
}

function afficherOnglet(numero) {
    document.querySelectorAll('.contenu-onglet').forEach(contenu => {
        contenu.classList.add('cache');
        contenu.classList.remove('visible');
    });

    const contenuActif = document.querySelector(`#contenu${numero}`);
    if (contenuActif) {
        contenuActif.classList.remove('cache');
        contenuActif.classList.add('visible');
    }

    document.querySelectorAll('.onglet').forEach((onglet, index) => {
        onglet.classList.toggle('actif', index === numero - 1);
    });
}
