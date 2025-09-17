# Criptlog – Libreria PHP Vigenère 2D Avanzata ~ By HackNow Team

## 1. About

**Criptlog** è una libreria PHP per cifratura e decifratura di testi basata su **algoritmo Vigenère bidimensionale avanzato**. Supporta caratteri personalizzati, numeri, simboli e shift configurabili per ogni lettera della chiave.

---

## 2. A cosa serve

Protegge dati sensibili come password, messaggi o testi riservati, con un metodo più complesso della Vigenère classica.

---

## 3. Vantaggi di Criptlog

* Personalizzazione completa del **charset** e dei **valori di shift**.
* Supporto numeri e simboli.
* Modalità **bidimensionale** con shift complesso.
* Facile integrazione senza dipendenze esterne.
* Modalità `modular` o `saturate` per gestione dei bordi.

---

## 4. Feature principali

### 4.1 Charset personalizzabile

```php
$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
```

### 4.2 CharMap randomizzata (esempio)

| Carattere | Shift |
| --------- | ----- |
| a         | 5     |
| b         | 13    |
| c         | 2     |
| d         | 8     |
| e         | 19    |
| f         | 4     |
| g         | 22    |
| h         | 11    |
| i         | 7     |
| j         | 16    |
| k         | 3     |
| l         | 9     |
| m         | 20    |
| n         | 1     |
| o         | 14    |
| p         | 18    |
| q         | 6     |
| r         | 10    |
| s         | 23    |
| t         | 12    |
| u         | 17    |
| v         | 15    |
| w         | 21    |
| x         | 24    |
| y         | 25    |
| z         | 26    |

> 🔹 L’utente può scegliere charset e charMap per **aumentare sicurezza**.

---

## 5. Implementazione

```php
require_once 'src/Vigenere2D.php';
use HackNOW\CriptLog\Vigenere2D;

$charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$charMap = [ /* vedi tabella random */ ];

$vigenere = new Vigenere2D($charset, $charMap, true, 'modular', false);

$plaintext = "Hello123";
$key = "T35";

$encrypted = $vigenere->encrypt($plaintext, $key);
$decrypted = $vigenere->decrypt($encrypted, $key);

echo "Encrypted: $encrypted\n";
echo "Decrypted: $decrypted\n";
```

---

## 6. Tabelle di cifratura

### 6.1 Tabella ASCII bidimensionale A-Z × A-Z

```
     A  B  C  D  E  F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z
A    A  B  C  D  E  F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z
B    B  C  D  E  F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z  A
C    C  D  E  F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z  A  B
D    D  E  F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z  A  B  C
E    E  F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z  A  B  C  D
F    F  G  H  I  J  K  L  M  N  O  P  Q  R  S  T  U  V  W  X  Y  Z  A  B  C  D  E
...
```

> 🔹 Ogni riga rappresenta la **posizione iniziale della lettera del plaintext**, ogni colonna la **lettera della chiave**. Il risultato è il carattere cifrato.

### 6.2 Esempio pratico

Plaintext: `HELLO`
Chiave: `KEY`

| P | K | C (Encrypted) |
| - | - | ------------- |
| H | K | R             |
| E | E | I             |
| L | Y | J             |
| L | K | W             |
| O | E | S             |

---

## 7. Come contribuire

* Fork del repository
* Apri pull request per bugfix o nuove funzionalità
* Segnala issues per problemi o suggerimenti

---

## 8. Licenza

Criptlog è distribuito sotto **Apache License 2.0**.
Puoi usare, modificare e distribuire il software **rispettando i termini della licenza**.

```text
                                 Apache License
                           Version 2.0, January 2004
                        http://www.apache.org/licenses/

Copyright 2025 hacknow.blog

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
```

### Dettagli della licenza

* **Permessi concessi**:

  * Uso, copia, modifica e distribuzione del software.
  * Creazione di opere derivate.

* **Obblighi**:

  * Inserire sempre una copia della licenza con il software.
  * Indicare eventuali modifiche apportate ai file originali.
  * Mantenere le note di copyright e attribuzione nei file sorgente.

* **Limitazioni**:

  * Nessuna garanzia: il software è fornito "AS IS".
  * Gli autori non sono responsabili per danni derivanti dall’uso del software.


