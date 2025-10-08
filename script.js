document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('bookingForm');
  if (!form) return;

  const start = form.querySelector('[name="start_date"]');
  const end = form.querySelector('[name="end_date"]');
  const msg = document.getElementById('availabilityMsg');
  const cabinIdInput = form.querySelector('[name="cabin_id"]');

  // Asetetaan minimi nykyhetkelle
  const today = new Date().toISOString().split('T')[0];
  start.setAttribute('min', today);
  end.setAttribute('min', today);

  function checkDates() {
    if (!start.value || !end.value) return;

    // Lähtöpäivä ei voi olla ennen saapumista
    end.setAttribute('min', start.value);

    if (end.value <= start.value) {
      msg.textContent = 'Palautteena: paluuajan tulee olla saapumisen jälkeen.';
      msg.style.color = 'red';
      return;
    }

    // Rakennetaan POST-parametrit siistimmin
    const params = new URLSearchParams({
      cabin_id: cabinIdInput.value,
      start_date: start.value,
      end_date: end.value,
      check_only: 1
    });

    fetch('book.php', {
      method: 'POST',
      body: params
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
      .catch(() => {
        msg.textContent = 'Tarkistuksessa tapahtui virhe.';
        msg.style.color = 'red';
      });
  }

  start.addEventListener('change', checkDates);
  end.addEventListener('change', checkDates);
});
