# FieldRepository (Classe Astratta)

Questa è la classe astratta utilizzata nel progetto per l'**accesso ai dati dei campi sportivi** nel database.

## Descrizione

La classe astratta `FieldRepository` estende `Repository` e definisce i metodi specifici per gestire le operazioni CRUD sui campi sportivi.

## Struttura

```mermaid
classDiagram
class FieldRepository {
    <<abstract>>
    +getAll() Field[]
    +getById(id: int) Field
    +getByType(sport: string) Field[]
}
```

## Responsabilità

- Recuperare tutti i campi sportivi
- Recuperare un campo specifico per ID
- Filtrare i campi per tipo di sport

## Implementazioni

Le seguenti classi estendono questa classe astratta:

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
    #executeQuery(sql: string, params: array) array
}

class FieldRepository {
    <<abstract>>
    +getAll() Field[]
    +getById(id: int) Field
    +getByType(sport: string) Field[]
}

class DefaultFieldRepository {
    +getAll() Field[]
    +getById(id: int) Field
    +getByType(sport: string) Field[]
    -mapRowToField(row: array) Field
}

Repository <|-- FieldRepository
FieldRepository <|-- DefaultFieldRepository
```

### DefaultFieldRepository
- **Scopo**: Implementazione standard del repository per campi sportivi
- **Utilizzo**: Utilizzato in produzione per accesso ai dati dei campi
- **Caratteristiche**: Query SQL ottimizzate, mapping automatico row→Field

## Eccezioni

Può lanciare:
- `FieldNotFoundException`: quando un campo non viene trovato

## Dipendenze

Il seguente diagramma mostra le relazioni e dipendenze di questa classe:

```mermaid
classDiagram
class Repository {
    <<abstract>>
    #connection: PDO
}

class FieldRepository {
    <<abstract>>
    +getAll() Field[]
    +getById(id: int) Field
}

class Field {
    +id: int
    +name: string
    +sport: string
    +pricePerHour: float
}

class FieldService {
    -fieldRepository: FieldRepository
}

class DefaultFieldRepository {
    +getAll() Field[]
}

class FieldNotFoundException {
    +message: string
}

Repository <|-- FieldRepository : estende
FieldRepository --> Field : restituisce
FieldService --> FieldRepository : utilizza
DefaultFieldRepository --|> FieldRepository : estende
DefaultFieldRepository ..> FieldNotFoundException : throws
```

### Relazioni
- **Estende**: `Repository` - eredita connessione PDO e metodi base
- **Restituisce**: `Field` - entità campo sportivo
- **Utilizzata da**: `FieldService` - per logica business campi
- **Implementata da**: `DefaultFieldRepository`
- **Eccezioni**: Lancia `FieldNotFoundException`

