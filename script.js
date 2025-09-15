document.addEventListener('DOMContentLoaded', function(){
  const form = document.getElementById('bookingForm');
  if (!form) return;

  const start = form.querySelector('[name="start_date"]');
  const end = form.querySelector('[name="end_date"]');
  const msg = document.getElementById('availabilityMsg');

  // Asetetaan minDate nykyhetkelle
  const today = new Date().toISOString().split('T')[0];
  start.setAttribute('min', today);
  end.setAttribute('min', today);

  function checkDates(){
    if (!start.value || !end.value) return;

    // Asetetaan lähdön minimi automaattisesti saapumispäivän jälkeen
    end.setAttribute('min', start.value);

    if (end.value <= start.value) { 
      msg.textContent = 'Palautteena: paluuajan tulee olla saapumisen jälkeen.';
      msg.style.color = 'red';
      return; 
    }

    const cabinId = form.querySelector('[name="cabin_id"]').value;
    fetch(`book.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `cabin_id=${encodeURIComponent(cabinId)}&start_date=${encodeURIComponent(start.value)}&end_date=${encodeURIComponent(end.value)}&check_only=1`
    })
    .then(r => r.text())
    .then(text => {
      if (text.trim() === 'OK') {
        msg.textContent = 'Mökki näyttää olevan vapaa!';
        msg.style.color = 'green';
      } else {
        msg.textContent = 'Valitettavasti mökki on varattu valitulle ajalle.';
        msg.style.color = 'red';
      }
    })
    .catch(() => { msg.textContent = ''; });
  }

  // Tarkistus heti kun käyttäjä valitsee päivämäärän
  start.addEventListener('change', checkDates);
  end.addEventListener('change', checkDates);
});
