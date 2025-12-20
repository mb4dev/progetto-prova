# Indice Architettura Base

Questo indice fornisce una panoramica di tutte le interfacce e classi astratte documentate per l'architettura del progetto.

## 📚 Documenti Principali

- **[PATTERNS.md](PATTERNS.md)**: Descrizione dettagliata di tutti i design pattern utilizzati nel progetto
- **[TEMPLATE.md](TEMPLATE.md)**: Template per la documentazione di nuove interfacce e classi astratte

## Struttura della Documentazione

Ogni interfaccia e classe astratta è documentata in un file separato che include:
- Descrizione dello scopo
- Diagramma della struttura
- Responsabilità
- Implementazioni concrete
- Dipendenze

---

## 🔧 Backend

### Routing e Request Handling

| File | Tipo | Descrizione |
|------|------|-------------|
| [Router.md](Router.md) | Classe Astratta | Gestione del routing delle richieste HTTP |
| [URLParser.md](URLParser.md) | Interfaccia | Parsing delle URL delle richieste |
| [ResponseStrategy.md](ResponseStrategy.md) | Interfaccia | Invio risposte HTTP (Strategy Pattern) |

### Controller Layer

| File | Tipo | Descrizione |
|------|------|-------------|
| [Controller.md](Controller.md) | Classe Astratta | Base per tutti i controller |

### Service Layer

| File | Tipo | Descrizione |
|------|------|-------------|
| [FieldService.md](FieldService.md) | Interfaccia | Logica business per i campi sportivi |
| [BookingService.md](BookingService.md) | Interfaccia | Logica business per le prenotazioni |

### Repository Layer

| File | Tipo | Descrizione |
|------|------|-------------|
| [Repository.md](Repository.md) | Classe Astratta | Base per l'accesso ai dati (Repository Pattern) |
| [FieldRepository.md](FieldRepository.md) | Classe Astratta | Repository per i campi sportivi |
| [BookingRepository.md](BookingRepository.md) | Classe Astratta | Repository per le prenotazioni |

---

## Frontend

### MVP Pattern

| File | Tipo | Descrizione |
|------|------|-------------|
| [View.md](View.md) | Interfaccia | Componenti di visualizzazione (MVP) |
| [Presenter.md](Presenter.md) | Classe Astratta | Logica di presentazione (MVP) |
| [SubView.md](SubView.md) | Interfaccia | View per sotto-sezioni navigabili |
| [SubPresenter.md](SubPresenter.md) | Interfaccia | Presenter per sotto-sezioni navigabili |

### Comunicazione e Servizi

| File | Tipo | Descrizione |
|------|------|-------------|
| [Observer.md](Observer.md) | Interfaccia | Pattern Observer per eventi |
| [APIService.md](APIService.md) | Interfaccia | Chiamate HTTP al backend |

---

## 🔄 Pattern Utilizzati

- **MVP (Model-View-Presenter)**: Separazione tra logica di presentazione e visualizzazione
- **Observer**: Comunicazione event-driven tra View e Presenter
- **Repository**: Astrazione dell'accesso ai dati
- **Strategy**: Diverse strategie per l'invio delle risposte
- **Factory**: Creazione dinamica dei controller

---

## 📖 Come Usare Questa Documentazione

1. **Per comprendere l'architettura generale**: Leggere il file principale `Architettura base.md`
2. **Per dettagli su un componente specifico**: Consultare il file dedicato in questa directory
3. **Per implementare nuove funzionalità**: Seguire i pattern e le interfacce documentate
4. **Per estendere il sistema**: Creare nuove implementazioni delle interfacce/classi astratte esistenti

---

## 🔗 Collegamenti Rapidi

- [Torna all'Architettura Base](../Architettura%20base.md)
- [Casi d'Uso](../Casi%20d'uso.md)
- [Documentazione Completa](../Documentazione.md)
