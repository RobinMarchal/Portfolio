const cookieBanner = document.getElementById('cookie-banner');
const cookieInformAndAsk = document.getElementById('cookie-inform-and-ask');
const cookieMoreButton = document.getElementById('cookie-more-button');
const gaCancelButton = document.getElementById('ga-cancel-button');
const gaConfirmButton = document.getElementById('ga-confirm-button');

// Lorsque l’utilisateur a décidé de s’opposer à l’utilisation des cookies, il faut… poser des cookies
function processCookieConsent() {
    // 1. On récupère l'éventuel cookie indiquant le choix passé de l'utilisateur
    const consentCookie = Cookies.getJSON('hasConsent');
    // 2. On récupère la valeur "doNotTrack" du navigateur
    const doNotTrack = navigator.doNotTrack || navigator.msDoNotTrack;
  
    // 3. Si le cookie existe et qu'il vaut explicitement "false" ou que le "doNotTrack" est défini à "OUI"
    //    l'utilisateur s'oppose à l'utilisation des cookies. On exécute une fonction spécifique pour ce cas.
    if (doNotTrack === 'yes' || doNotTrack === '1' || consentCookie === false) {
      reject();
      return;
    }
  
    // 4. Si le cookie existe et qu'il vaut explicitement "true", on démarre juste Google Analytics
    if (consentCookie === true) {
      startGoogleAnalytics();
      return;
    }
  
    // 5. Si le cookie n'existe pas et que le "doNotTrack" est défini à "NON", on crée le cookie "hasConsent" avec pour
    //    valeur "true" pour une durée de 13 mois (la durée maximum autorisée) puis on démarre Google Analytics
    if (doNotTrack === 'no' || doNotTrack === '0') {
      Cookies.set('hasConsent', true, { expires: 395 });
      startGoogleAnalytics();
      return;
    }
  
    // 6. Si le cookie n'existe pas et que le "doNotTrack" n'est pas défini, alors on affiche le bandeau et on crée les listeners
    cookieBanner.classList.add('active');
    cookieMoreButton.addEventListener('click', onMoreButtonClick, false);
    document.addEventListener('click', onDocumentClick, false);
}

// Enregistrer le choix de l’utilisateur pendant la durée maximum légale (13 mois)
const GA_PROPERTY = 'UA-127488021-1'
const GA_COOKIE_NAMES = ['__utma', '__utmb', '__utmc', '__utmz', '_ga', '_gat'];

function reject() {
    // création du cookie spécifique empêchant Google Analytics de démarrer
    Cookies.set(`ga-disable-${GA_PROPERTY}`, true, { expires: 395 });
    // insertion de cette valeur dans l'objet window
    window[`ga-disable-${GA_PROPERTY}`] = true;

    // création du cookie précisant le choix utilisateur
    Cookies.set('hasConsent', false, { expires: 395 });

    // suppression de tous les cookies précédemment créés par Google Analytics
    GA_COOKIE_NAMES.forEach(cookieName => Cookies.remove(cookieName));
}

// Lorsque l’utilisateur clique n’importe où sur la page
function onDocumentClick(event) {
    const target = event.target;
    
    // Si l'utilisateur a cliqué sur le bandeau ou le bouton dans ce dernier alors on ne fait rien.
    if (target.id === 'cookie-banner'
      || target.parentNode.id === 'cookie-banner'
      || target.parentNode.parentNode.id === 'cookie-banner'
      || target.id === 'cookie-more-button') {
      return;
    }
    
    event.preventDefault();
    
    // On crée le cookie signifiant le consentement de l'utilisateur et on démarre Google Analytics
    Cookies.set('hasConsent', true, { expires: 365 });
    startGoogleAnalytics();
    
    // On cache le bandeau
    cookieBanner.className = cookieBanner.className.replace('active', '').trim();
    
    // On supprime le listener sur la page et celui sur le bouton du bandeau
    document.removeEventListener('click', onDocumentClick, false);
    cookieMoreButton.removeEventListener('click', onMoreButtonClick, false);
}

// Lorsque l’utilisateur sur le bouton “En savoir plus ou s’opposer” du bandeau
function onMoreButtonClick(event) {
    event.preventDefault();
    
    // On affiche la boîte de dialogue permettant à l'utilisateur de faire son choix
    cookieInformAndAsk.classList.add('active');
    
    // On cache le bandeau
    cookieBanner.className = cookieBanner.className.replace('active', '').trim();
    
    // On crée les listeners sur les boutons de la boîte de dialogue
    gaCancelButton.addEventListener('click', onGaCancelButtonClick, false);
    gaConfirmButton.addEventListener('click', onGaConfirmButtonClick, false);
    
    // On supprime le listener sur la page et celui sur le bouton du bandeau
    document.removeEventListener('click', onDocumentClick, false);
    cookieMoreButton.removeEventListener('click', onMoreButtonClick, false);
}

// Lorsque l’utilisateur accepte l’utilisation des cookies sur la boîte de dialogue
function onGaConfirmButtonClick(event) {
    event.preventDefault();
    
    // On crée le cookie signifiant le consentement de l'utilisateur et on démarre Google Analytics
    Cookies.set('hasConsent', true, { expires: 365 });
    startGoogleAnalytics();
    
    // On cache la boîte de dialogue
    cookieInformAndAsk.className = cookieBanner.className.replace('active', '').trim();
    
    // On supprime les listeners sur les boutons de la boîte de dialogue
    gaCancelButton.removeEventListener('click', onGaCancelButtonClick, false);
    gaConfirmButton.removeEventListener('click', onGaConfirmButtonClick, false);
}

// Lorsque l’utilisateur refuse l’utilisation des cookies sur la boîte de dialogue
function onGaCancelButtonClick(event) {
    event.preventDefault();
    
    // On lance la procédure de refus de l'utilisation des cookies
    reject();
    
    // On cache la boîte de dialogue
    cookieInformAndAsk.className = cookieBanner.className.replace('active', '').trim();
    
    // On supprime les listeners sur les boutons de la boîte de dialogue
    gaCancelButton.removeEventListener('click', onGaCancelButtonClick, false);
    gaConfirmButton.removeEventListener('click', onGaConfirmButtonClick, false);
}