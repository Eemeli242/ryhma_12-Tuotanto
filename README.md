# MökkiVuokraus – Ryhmä 12

Tämä on PHP-pohjainen mökkien vuokrausjärjestelmä, jonka avulla käyttäjät voivat selata vuokrattavia mökkejä sekä ilmoittaa omia mökkejään vuokralle.  
Sivusto perustuu käyttäjien syöttämään dataan: mökit, varaukset ja käyttäjien profiilit tallennetaan tietokantaan, ja backend hakee ja palauttaa tiedot dynaamisesti käyttäjän pyyntöihin.

## Sisällysluettelo
- [Johdanto](#johdanto)
- [Teknologiat](#teknologiat)
- [Asennus](#asennus)
- [Käyttöohjeet](#käyttöohjeet)
- [Roolit](#roolit)
- [Tietokanta](#tietokanta)
- [Kehitys](#kehitys)
- [Lisenssi](#lisenssi)

## Johdanto
Tämä sivusto on suunniteltu yksinkertaistamaan mökkien vuokraamista ja ilmoittamista.  
Käyttäjä voi:
- luoda käyttäjätilin ja muokata omia tietojaan
- lisätä mökkejä vuokrattavaksi
- tarkastella omia mökkejä ja niihin liittyviä varauksia
- selata muiden lisäämiä mökkejä
- vuokrata mökkejä
- sekä antaa arvioinnin varauksesta

Sovellus käyttää tietokantaa (phpmyadmin (sql)) mökkien, varausten ja käyttäjien tietojen tallentamiseen. PHP backendi käsittelee lomakkeet, validoi syötteen ja palauttaa pyydetyt tiedot näkymiin.

## Teknologiat
- **Backend**: PHP  
- **Frontend**: HTML, CSS, Bootstrap
- **Tietokanta**: MySQL  
- **Versionhallinta**: Git

## Asennus XAMPP:ille

Näin saat projektin toimimaan omalla koneellasi.

### 1️⃣ Asenna ja käynnistä XAMPP
1. Lataa [XAMPP](https://www.apachefriends.org)
2. Asenna ohjelma oletusasetuksilla
3. Käynnistä **XAMPP Control Panel**
4. Paina **Start** Apachelle ja MySQL:lle  
   → Molempien pitäisi näkyä vihreällä "Running"-tilassa

---
### 2️⃣ Lataa projekti GitHubista
Voit ladata projektin kahdella tavalla:

**Vaihtoehto A: Git (suositeltu)**  
1. Avaa komentorivi/terminal ja mene XAMPP:n `htdocs`-kansioon:
   ```bash
   cd C:\xampp\htdocs
   ```
2. Kloonaa projekti:
   ```bash
   git clone https://github.com/Eemeli242/ryhma_12-Tuotanto.git ryhma12
   ```
3. Nyt projektin koodi löytyy `C:\xampp\htdocs\ryhma12`-kansiosta.

**Vaihtoehto B: ZIP-paketti**  
1. Mene selaimella osoitteeseen:  
   [https://github.com/Eemeli242/ryhma_12-Tuotanto](https://github.com/Eemeli242/ryhma_12-Tuotanto)
2. Paina vihreää **Code**-painiketta ja valitse **Download ZIP**
3. Pura ZIP-tiedosto XAMPP:n `htdocs`-kansioon ja nimeä kansio esim. `ryhma12`

---


### 3️⃣ Luo tietokanta
1. Avaa selain ja mene osoitteeseen [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Valitse **Databases** → luo uusi tietokanta, esim. `lomamokit`
3. Tuo projektin mukana oleva SQL-tiedosto:
   - Valitse **Import**
   - Lataa `db.sql` (C:\xampp\htdocs\ryhma12)
   - Paina **Go**

---

### 4️⃣ Käynnistä projekti
Avaa selain ja mene osoitteeseen:

```
http://localhost/ryhma12
```

Jos kaikki on oikein, etusivu latautuu ja voit rekisteröityä/kirjautua sisään.

---

## Käyttöohjeet
- **Rekisteröityminen / kirjautuminen:** Luo tili tai kirjaudu sisään (eemeli, salasana123)
- **Mökkien lisääminen:** Käytä `add_cabin.php`-sivua lisätäksesi mökki vuokrattavaksi
- **Profiilin muokkaus:** Päivitä sähköpostisi ja profiilikuvasi `edit_profile.php`-sivulla
- **Dashboard:** Näe omat mökit ja niiden varaukset `dashboard.php`-sivulla

## Roolit
- **Käyttäjä:** voi selata ja varata mökkejä lisätä ja hallita omia mökkejään
- **Admin:** Adminkäyttäjä voivat hallita kaikkia käyttäjiä, mökkejä ja varauksia.

## Tietokanta
Projektissa käytetään relaatiotietokantaa, joka sisältää mm. seuraavat taulut:
- **users** – käyttäjien tiedot (nimi, sähköposti, salasana, profiilikuva)
- **cabins** – mökkien tiedot (nimi, sijainti, hinta, kuvaus, kuva, omistaja)
- **bookings** – varaustiedot (kuka varasi, mikä mökki, ajankohta)
- **reviews** - arvosana mökille (komentti, raiting, joka näkyy mökinvarauksessa sekä etusivulla (keskiarvo)

## Kehitys
Mahdollisia jatkokehityskohteita:
- Varausten maksujärjestelmä

## Lisenssi
Tämä projekti on avoimen lähdekoodin ja julkaistu [MIT-lisenssillä](https://opensource.org/licenses/MIT).
