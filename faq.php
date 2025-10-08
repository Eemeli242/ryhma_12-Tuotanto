<?php
require 'config.php';
include 'header.php'; ?>
<link rel="stylesheet" href="style.css">

<main class="container">
    <h1>Usein Kysytyt Kysymykset</h1>

    <div class="faq-item">
        <div class="faq-question">Miten varaan mökin?</div>
        <div class="faq-answer"><p>Valitse haluamasi mökki, tarkista vapaat päivät ja suorita varauslomake maksamalla varaus.</p></div>
    </div>

    <!-- muut faq-itemit -->

    <div class="faq-small-info">
        <h2>Lisätietoja</h2>
        <ul>
            <li>123</li>
            <li>123</li>
            <li>123</li>
            <li>123</li>
        </ul>
    </div>
    
</main>

<script>
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
