document.addEventListener("DOMContentLoaded", function () {
    /* KATEGÓRIA MEGJELENÍTÉSE */
    window.kategoriaMegjelenites = function (kategoriaId) {
        let kategoriak = document.getElementsByClassName("kategoria");
        for (let i = 0; i < kategoriak.length; i++) {
            kategoriak[i].style.display = "none";
        }
        let kivalasztott = document.getElementById(kategoriaId);
        if (kivalasztott) {
            kivalasztott.style.display = "block";
        }
    };

    // Kosár inicializálása sessionStorage-ból
    let kosar = JSON.parse(sessionStorage.getItem("kosar")) || [];
    let felhasznaloNev = sessionStorage.getItem("felhasznaloNev") || "";
    let felhasznaloEmail = sessionStorage.getItem("felhasznaloEmail") || "";

    function mentKosar() {
        sessionStorage.setItem("kosar", JSON.stringify(kosar));
    }

    function frissitKosar() {
        const kosarLista = document.getElementById("kosar-lista");
        if (!kosarLista) return;

        kosarLista.innerHTML = "";

        if (kosar.length === 0) {
            kosarLista.innerHTML = "<li>A kosár üres.</li>";
        } else {
            kosar.forEach((item, index) => {
                let li = document.createElement("li");
                li.innerHTML = `${item.nev} - ${item.ar} Ft/nap - ${item.napok} nap 
                <button class="torles-gomb" data-index="${index}" style="border:none; background:none; cursor:pointer; font-size:16px; color:red;">❌</button>`;
                kosarLista.appendChild(li);
            });
        }

        let hiddenField = document.getElementById("foglalas-autonev-hidden");
        if (hiddenField) {
            hiddenField.value = JSON.stringify(kosar);
        }

        document.querySelectorAll(".torles-gomb").forEach(button => {
            button.addEventListener("click", function () {
                let index = this.getAttribute("data-index");
                kosar.splice(index, 1);
                mentKosar();
                frissitKosar();
            });
        });
    }

    window.hozzaadKosarhoz = function (autoNev, ar) {
        let foglalasiNapok = parseInt(document.getElementById("foglalas-ido").value) || 1;
        let marVan = kosar.find(a => a.nev === autoNev);
        if (marVan) {
            marVan.napok = foglalasiNapok;
        } else {
            kosar.push({ nev: autoNev, ar: ar, napok: foglalasiNapok });
        }
        mentKosar();
        frissitKosar();
        alert(`${autoNev} hozzáadva a kosárhoz ${foglalasiNapok} napra!`);
    };

    // Szürkézés és felirat a foglalt autóknál
    const foglaltAutok = window.foglaltAutok || {};
    document.querySelectorAll(".auto").forEach(autoDiv => {
        let nevElem = autoDiv.querySelector("h3");
        if (!nevElem) return;

        let nev = nevElem.textContent.trim();
        if (foglaltAutok[nev]) {
            autoDiv.style.opacity = 0.4;
            let gomb = autoDiv.querySelector("button");
            if (gomb) {
                gomb.disabled = true;
                gomb.textContent = "Már lefoglalva";
                gomb.style.cursor = "not-allowed";
            }

            let kozlemeny = document.createElement("p");
            kozlemeny.style.color = "#666";
            kozlemeny.style.fontSize = "0.9em";
            kozlemeny.textContent = `Eddig lefoglalva: ${foglaltAutok[nev]}`;
            autoDiv.appendChild(kozlemeny);
        }
    });

    // 🗓️ Foglalás dátum mező korlátozása: csak holnaptól
    const datumMezo = document.getElementById("foglalas-datum");
    if (datumMezo) {
        const holnap = new Date();
        holnap.setDate(holnap.getDate() + 1);
        const isoDatum = holnap.toISOString().split("T")[0];
        datumMezo.setAttribute("min", isoDatum);
    }

    // Napok számának figyelése – frissítés kosárban
    const foglalasIdoInput = document.getElementById("foglalas-ido");
    if (foglalasIdoInput) {
        foglalasIdoInput.addEventListener("input", function () {
            let ujNapok = parseInt(this.value) || 1;
            kosar.forEach(auto => auto.napok = ujNapok);
            mentKosar();
            frissitKosar();
        });
    }

    // Foglalás előtt még egyszer frissítjük a kosarat rejtett mezőbe
    const foglalasUrlap = document.getElementById("foglalas-urlap");
    if (foglalasUrlap) {
        foglalasUrlap.addEventListener("submit", function(e) {
            document.getElementById("foglalas-autonev-hidden").value = JSON.stringify(kosar);
        });
    }

    frissitKosar();

    /* MODÁLIS ABLAKOK KEZELÉSE */
    let overlay = document.getElementById("overlay");
    let regisztracioElem = document.getElementById("regisztracio");
    let belepesElem = document.getElementById("belepes");

    let regisztracioGomb = document.getElementById("regisztracio-gomb");
    if (regisztracioGomb) {
        regisztracioGomb.addEventListener("click", function () {
            regisztracioElem.style.display = "block";
            belepesElem.style.display = "none";
            overlay.style.display = "block";
        });
    }

    let belepesGomb = document.getElementById("belepes-gomb");
    if (belepesGomb) {
        belepesGomb.addEventListener("click", function () {
            belepesElem.style.display = "block";
            regisztracioElem.style.display = "none";
            overlay.style.display = "block";
        });
    }

    function zarasFelulet() {
        if (regisztracioElem) regisztracioElem.style.display = "none";
        if (belepesElem) belepesElem.style.display = "none";
        if (overlay) overlay.style.display = "none";
    }

    if (overlay) {
        overlay.addEventListener("click", zarasFelulet);
    }

    window.velemenyHozzaadasa = function () {
        let autoNev = document.getElementById("velemeny-autonev").value;
        let szoveg = document.getElementById("velemeny-szoveg").value;
        if (autoNev && szoveg) {
            let lista = document.getElementById("velemenyek-lista");
            lista.innerHTML += `<p><strong>${autoNev}</strong>: ${szoveg}</p>`;
            alert("Köszönjük a véleményed!");
            document.getElementById("velemeny-urlap").reset();
        } else {
            alert("Kérjük, tölts ki minden mezőt!");
        }
    };

    window.kapcsolatHozzaadasa = function () {
        let nev = document.getElementById("kapcsolat-nev").value;
        let email = document.getElementById("kapcsolat-email").value;
        let uzenet = document.getElementById("kapcsolat-uzenet").value;
        if (nev && email && uzenet) {
            alert(`Köszönjük, ${nev}! Válaszunkat hamarosan elküldjük.`);
            document.getElementById("kapcsolat-urlap").reset();
        } else {
            alert("Kérjük, tölts ki minden mezőt!");
        }
    };
    const keresoInput = document.getElementById("auto-kereso");
if (keresoInput) {
    keresoInput.addEventListener("input", function () {
        const szuro = this.value.toLowerCase().trim();
        document.querySelectorAll(".auto").forEach(auto => {
            const nev = auto.querySelector("h3")?.textContent.toLowerCase() || "";
            const marka = auto.querySelector("p")?.textContent.toLowerCase() || "";
            if (nev.includes(szuro) || marka.includes(szuro)) {
                auto.style.display = "block";
            } else {
                auto.style.display = "none";
            }
        });

        // Üres keresésnél visszamutatjuk mindet
        if (szuro === "") {
            document.querySelectorAll(".auto").forEach(auto => {
                auto.style.display = "block";
            });
        }
    });
}
// 🔍 KERESÉS ÉS SZŰRÉS FUNKCIÓK

// Lefut amikor betölt az oldal
window.addEventListener("DOMContentLoaded", () => {
    const keresoInput = document.getElementById("kereso-input");
    const szuroSelect = document.getElementById("szuro-select");
    const kategoriakSelect = document.getElementById("kategoriak-select");

    // 🔍 Keresés vagy szűrés indítása
    function frissitAutoLista() {
        const kifejezes = keresoInput.value.toLowerCase();
        const szuro = szuroSelect.value;
        const kategoria = kategoriakSelect.value;

        document.querySelectorAll(".auto").forEach(auto => {
            const nev = auto.querySelector("h3").textContent.toLowerCase();
            const leiras = auto.querySelector("p").textContent.toLowerCase();
            const ar = parseInt(auto.querySelector("p:nth-of-type(2)").textContent.replace(/\D/g, ""));
            const kategoriaElem = auto.classList.contains(kategoria);

            let megjelenit = true;

            if (kategoria !== "osszes" && !kategoriaElem) {
                megjelenit = false;
            }

            if (!nev.includes(kifejezes) && !leiras.includes(kifejezes)) {
                megjelenit = false;
            }

            auto.style.display = megjelenit ? "block" : "none";
        });

        // Rendezes
        const autokContainer = document.querySelectorAll(".kategoria");
        autokContainer.forEach(kat => {
            let autok = Array.from(kat.querySelectorAll(".auto"));
            let rendezett = autok.sort((a, b) => {
                const nevA = a.querySelector("h3").textContent;
                const nevB = b.querySelector("h3").textContent;
                const arA = parseInt(a.querySelector("p:nth-of-type(2)").textContent.replace(/\D/g, ""));
                const arB = parseInt(b.querySelector("p:nth-of-type(2)").textContent.replace(/\D/g, ""));

                if (szuro === "abc_az") return nevA.localeCompare(nevB);
                if (szuro === "abc_za") return nevB.localeCompare(nevA);
                if (szuro === "ar_nov") return arA - arB;
                if (szuro === "ar_csok") return arB - arA;

                return 0;
            });

            rendezett.forEach(auto => kat.appendChild(auto));
        });
    }

    keresoInput.addEventListener("input", frissitAutoLista);
    szuroSelect.addEventListener("change", frissitAutoLista);
    kategoriakSelect.addEventListener("change", () => {
        document.querySelectorAll(".kategoria").forEach(kat => kat.style.display = "none");
        if (kategoriakSelect.value === "osszes") {
            document.querySelectorAll(".kategoria").forEach(kat => kat.style.display = "block");
        } else {
            const aktiv = document.getElementById(kategoriakSelect.value);
            if (aktiv) aktiv.style.display = "block";
        }
        frissitAutoLista();
    });
});


});
