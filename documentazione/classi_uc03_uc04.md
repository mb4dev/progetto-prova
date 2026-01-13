## Classi Comuni UC03 e UC04

Le seguenti classi sono condivise tra UC03 (Visualizzazione disponibilità) e UC04 (Prenotazione campo).

### Backend - Repository Layer

``` mermaid 

classDiagram

class BookingRepository {
    <<abstract>>
	+getOccupiedSlots(fieldId: int, date: Date) Slot[]
    +createBooking(booking: Booking) Booking
}

class FieldsRepository {
    <<abstract>>

    getFields() Field[]
}

class DefaultBookingRepository {
    +getOccupiedSlots(fieldId: int, date: Date) Slot[]
    +createBooking(booking: Booking) 
}

class DefaultFieldsRepository {
    getFields() Field[]
}

BookingRepository --|> Repository
FieldsRepository --|> Repository
DefaultBookingRepository --|> BookingRepository
DefaultFieldsRepository --|> FieldsRepository


DefaultBookingRepository ..> BookingConflictException : throws
DefaultBookingRepository ..> BookingNotFoundException : throws
DefaultFieldsRepository ..> FieldNotFoundException : throws


%%class BookingConflictException {}
%%class BookingNotFoundException {}


```

### Backend - Service Layer

```mermaid
classDiagram

class FieldBookingService {
    <<interface>>
    
    +getFields() Response
    +reserveField(booking: Booking) Response
    +getReservedSlot(fieldId: int, date: Date) Response
}

class DefaultFieldService {
    -fieldsRepository: FieldsRepository
    -bookingRepository: BookingRepository
}

FieldBookingService <|-- DefaultFieldService
```

### Backend - Controller Layer

```mermaid
classDiagram

class Controller {
    <<Controller>>
}

class FieldController {
    -fieldService: FieldService
}

class BookingController {
    -bookingService: BookingService
}

FieldController --|> Controller
BookingController --|> Controller
FieldController --> FieldService
BookingController --> BookingService
Controller --> Response
```

### Frontend - View e Presenter

```mermaid
classDiagram

class CampiView {
    -fields: Field[]
    -selectedField: Field
    -selectedDate: Date
    -occupiedSlots: Slot[]
    +render() 
    -renderCalendar() 
    -markOccupiedSlots() 
}

class CampiPresenter {
    -loadFields() 
    -loadOccupiedSlots(fieldId: int, startDate: string, endDate: string) 
}

class SportCard {
    -id: string
    -title: string
    -image: string
    -price: string
    +display(data: Object)
    +template() string
    #bindEvents()
}
SportCard ..|> View

CampiView ..> SportCard : contiene
CampiView ..|> View
BookingView ..|> View
CampiPresenter --|> Presenter
BookingPresenter --|> Presenter

CampiPresenter --> CampiView
BookingPresenter --> BookingView

CampiPresenter ..> Observer : subscribe
CampiView ..> Observer : notify
BookingPresenter ..> Observer : subscribe
BookingView ..> Observer : notify
```
