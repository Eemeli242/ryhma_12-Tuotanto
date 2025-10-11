<?php
require 'config.php';
include 'header.php'; ?>
<link rel="stylesheet" href="style.css">


<main class="container">
    <h1 class="faq-title">Usein Kysytyt Kysymykset</h1>

    <div class="faq-container">
        <div class="faq-item">
            <div class="faq-question">Miten varaan mökin?</div>
            <div class="faq-answer"><p>Valitse haluamasi mökki, täytä varauslomake ja lähetä. Saat vahvistuksen sähköpostiisi.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Voinko perua varauksen?</div>
            <div class="faq-answer"><p>Kyllä, varauksen voi perua veloituksetta 7 päivää ennen varauksen alkua. Tämän jälkeen peruutuksesta veloitetaan 50% varauksen hinnasta.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Onko mökeissä lemmikit sallittuja?</div>
            <div class="faq-answer"><p>Osa mökeistä sallii lemmikit. Tarkista mökin tiedoista, onko lemmikit sallittu ennen varausta.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Mitä varustelua mökeissä on?</div>
            <div class="faq-answer"><p>Mökkien varustelut vaihtelevat mökeittäin. Tarkemmat tiedot löytyvät mökin kuvauksesta tai ottamalla yhteyttä vuokraajaan.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Voinko muuttaa varauksen ajankohtaa?</div>
            <div class="faq-answer"><p>Voit muuttaa varauksen ajankohtaa ottamalla yhteyttä asiakaspalveluun. Muutokset ovat mahdollisia, jos mökki on vapaana haluamallasi ajalla.</p></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Miten saan avaimet mökkiin?</div>
            <div class="faq-answer"><p>Avaimet saat saapumispäivänä sovitusta noutopisteestä tai mökin omistajalta. Tarkemmat ohjeet lähetetään varausvahvistuksen yhteydessä.</p></div>
        </div>
    </div>
    <div class="faq-small-info">
        <ul>
            <li>Tarvitsetko apua? Ota yhteyttä asiakaspalveluun.</li>
            <li>Kaikki mökit ovat vakuutettuja.</li>
            <li>Voit tuoda omat liinavaatteet tai vuokrata ne meiltä.</li>
            <li>Saapumis- ja lähtöajat: klo 15:00 ja klo 12:00.</li>
        </ul>
    </div>

    <!-- Popupin avaava nappi -->
    <button id="openContact" class="contact-btn">Ota yhteyttä</button>
    
    <!-- Popup (modal) -->
    <div id="contactModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Ota yhteyttä</h2>
        <form id="contactForm">
        <label for="name">Nimi</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Sähköposti</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Viesti</label>
        <textarea id="message" name="message" rows="4" required></textarea>

        <button type="submit" class="send-btn">Lähetä</button>
        </form>
    </div>
    </div>
</main>

<script>
// Popupin (modaalin) toiminnallisuus
const modal = document.getElementById('contactModal');
const btn = document.getElementById('openContact');
const closeBtn = document.querySelector('.close');

btn.onclick = () => modal.style.display = 'block';
closeBtn.onclick = () => modal.style.display = 'none';

// Sulje, jos käyttäjä klikkaa taustan puolelle
window.onclick = (e) => {
  if (e.target === modal) {
    modal.style.display = 'none';
  }
};

// Accordion-toiminnallisuus
const questions = document.querySelectorAll('.faq-question');
questions.forEach(q => {
    q.addEventListener('click', () => {
        q.classList.toggle('active');
        const answer = q.nextElementSibling;
        if (q.classList.contains('active')) {
            answer.style.maxHeight = answer.scrollHeight + 'px';
            answer.style.padding = '15px 20px';
        } else {
            answer.style.maxHeight = null;
            answer.style.padding = '0 20px';
        }
    });
});
</script>

<?php include 'footer.php'; ?>