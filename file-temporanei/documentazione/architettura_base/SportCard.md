## Componenti della Vista

### SportCard

`SportCard` è un componenteriutilizzabile che rappresenta una singola card per lo sport.

**Responsabilità:**
- Renderizzare visivamente uno sport (immagine, nome, prezzo)
- Gestire l'interazione utente 
- Notificare la selezione dello sport tramite Observer Pattern

**Attributi:**
- `data-id`: ID dello sport
- `data-sport`: Nome dello sport
- `data-image`: URL dell'immagine
- `data-price`: Prezzo orario

**Eventi emessi:**
- `SPORT_SELECTED_EVENT`: Quando l'utente clicca sulla card
