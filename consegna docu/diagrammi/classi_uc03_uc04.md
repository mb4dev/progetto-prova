## Classi Comuni UC03 e UC04

Le seguenti classi sono condivise tra UC03 (Visualizzazione disponibilità) e UC04 (Prenotazione campo).

### Backend - Repository Layer

``` mermaid 
classDiagram

class Repository {}
class DefaultFieldRepository {}
class FieldNotFoundException {}

FieldRepository --|> Repository
DefaultFieldRepository --|> FieldRepository


FieldRepository ..> FieldNotFoundException : throws

class BookingRepository {
    <<interface>>
	+getOccupiedSlots(fieldId: int, startDate: Date, endDate: Date) Slot[]
    +createBooking(booking: Booking) Booking
}


class DefaultBookingRepository{
}

class BookingConflictException {}
class BookingNotFoundException {}


BookingRepository --|> Repository
DefaultBookingRepository --|> BookingRepository
Repository --> PDO : usa

DefaultFieldRepository ..> FieldNotFoundException : throws
DefaultBookingRepository ..> BookingConflictException : throws
DefaultBookingRepository ..> BookingNotFoundException : throws

FieldNotFoundException --|> Exception
BookingConflictException --|> Exception
BookingNotFoundException --|> Exception
```

### Backend - Service Layer

```mermaid
classDiagram

class FieldService {
    <<interface>>
    +getFields() Response
}

class DefaultFieldService {
    -fieldRepository: FieldRepository
}

class BookingService {
    <<interface>>
    +createBooking(userId: int, fieldId: int, startTime: string, endTime: string) Response
    +getOccupiedSlots(fieldId, startDate, endDate) Response
	-checkAvailability(fieldId, startTime, endTime)
}

class DefaultBookingService {
    -bookingRepository: BookingRepository
}

DefaultFieldService ..|> FieldService
DefaultFieldService --> FieldRepository

DefaultBookingService ..|> BookingService
DefaultBookingService --> BookingRepository

FieldService --> Response
BookingService --> Response
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

class BookingView {

}

class BookingPresenter {
}

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