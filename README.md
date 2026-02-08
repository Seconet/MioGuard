# MioGuard for Contact Form 7

**MioGuard for Contact Form 7** è un micro-plugin leggero per proteggere i moduli **Contact Form 7** da spam e bot, senza CAPTCHA, servizi esterni o plugin invasivi.
---

## 🔒 Funzionalità

- Utilizza le API native di WordPress (transients)
- Honeypot invisibile (campo generico anti-bot)
- Rate limit per IP (configurabile da admin)
- Compatibile con qualsiasi modulo Contact Form 7
- Nessun impatto su SEO o PageSpeed
- Nessuna pubblicità, nessun tracking

---

## ⚙️ Installazione

1. Carica la cartella `mioguard-for-contact-form-7` in `/wp-content/plugins/`
2. Attiva il plugin da **Plugin → Plugin installati**
3. Vai in **Impostazioni → MioGuard for Contact Form 7**
4. Imposta il rate limit (in minuti)
5. Imposta il campo del modulo da utilizzare come Honeypot

Valori predefiniti: 
**5 minuti**
**company_fax**


---

## 🛡️ Come funziona

### Honeypot
Il plugin intercetta un campo nascosto (es. `company_fax`).  
Se viene compilato → il messaggio viene bloccato.
**esempio completo:
<div style="position:absolute; left:-9999px; top:-9999px;">
  <label>Fax
    [text company_fax]
  </label>
</div>

### Rate Limit
Ogni IP può inviare **1 modulo ogni X minuti**.  
Se il limite non è trascorso, l’invio viene bloccato lato server.


---

## 🧩 Compatibilità

- ✔ Contact Form 7
- ✔ Temi custom
- ✔ Nessun child theme richiesto
- ❌ Non compatibile con altri form builder

---

## 📦 Requisiti

- WordPress 5.5+
- PHP 7.2+
- Contact Form 7 attivo

---

## 🧠 Filosofia

Questo plugin nasce per essere:
- semplice
- leggibile
- modificabile
- affidabile

Nessuna funzione inutile, solo ciò che serve davvero.

---

## ℹ️ Note

MioGuard for Contact Form 7 non modifica i moduli esistenti e non interferisce con l'invio dei messaggi legittimi.
È pensato per essere semplice, trasparente e facile da disinstallare.

---

## 📄 Licenza

GPL v2 o successiva

---

## 👤 Autore

Creato da **Seconet.it / Sergio Cornacchione**  
Sviluppato per uso reale in produzione.
