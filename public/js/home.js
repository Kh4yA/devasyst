document.addEventListener("DOMContentLoaded", function() {
  const banner = document.getElementById("cookie-banner");
  const acceptBtn = document.getElementById("accept-cookies");
  const declineBtn = document.getElementById("decline-cookies");
// Si les coockies sont accepter on cache la baniere sinon on affiche
  if (!localStorage.getItem("cookiesAccepted")) {
    banner.style.display = "block";
  }else{
    banner.style.display = "none";
  }

  acceptBtn.addEventListener("click", function() {
    localStorage.setItem("cookiesAccepted", "true");
    banner.style.display = "none";
    loadGoogleAnalytics();
  });

  declineBtn.addEventListener("click", function() {
    localStorage.setItem("cookiesAccepted", "false");
    banner.style.display = "none";
  });

  function loadGoogleAnalytics() {
    let script = document.createElement("script");
    script.src = "https://www.googletagmanager.com/gtag/js?id=GTM-KPLKC2D6";
    script.async = true;
    document.head.appendChild(script);

    script.onload = function() {
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag("js", new Date());
      gtag("config", "GTM-KPLKC2D6");
    };
  }

  if (localStorage.getItem("cookiesAccepted") === "true") {
    loadGoogleAnalytics();
  }
});
