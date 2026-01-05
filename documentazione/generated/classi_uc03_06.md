 # Classi Comuni UC03, UC04, UC05, UC06

Diagramma delle classi ottimizzato per massimizzare la riutilizzabilità e limitare il numero di classi, coprendo la visualizzazione e prenotazione di Campi (UC03-UC04) e Corsi (UC05-UC06).

### Backend - Repository Layer

Si utilizza un approccio dove le risorse (Campi/Corsi) possono essere gestite genericamente, ma le prenotazioni sono divise per gestire le specificità (es. slot temporali per campi vs posti limitati per corsi).

```mermaid
classDiagram

class Repository {
    <<interface>>
    +findById(id: string)
    +findAll(filter: Object)
}

class ResourceRepository {
    %% Gestisce sia Campi che Corsi
    +getAll(type: ResourceType) Resource[]
    +getResourceDetails(id: string) Resource
}

class BookingRepository {
    <<interface>>
    +createBooking(booking: Booking) Booking
    +findBookings(userId: string) Booking[]
}

class FieldBookingRepository {
    +findOverlaps(fieldId: string, start: Date, end: Date) FieldBooking[]
}

class CourseBookingRepository {
    +countEnrollments(lessonId: string) int
}

ResourceRepository --|> Repository
BookingRepository --|> Repository
FieldBookingRepository --|> BookingRepository
CourseBookingRepository --|> BookingRepository

class Resource {
    +id: string
    +type: Enum (FIELD|COURSE)
    +name: string
    +capacity: int
    +price: float
}

class Booking {
    <<abstract>>
    +id: string
    +userId: string
    +date: Date
    +status: string
}

class FieldBooking {
    +fieldId: string
    +startTime: Time
    +endTime: Time
}

class CourseBooking {
    +courseId: string
    +lessonId: string
}

Booking <|-- FieldBooking
Booking <|-- CourseBooking

FieldBookingRepository ..> FieldBooking
CourseBookingRepository ..> CourseBooking
ResourceRepository ..> Resource
```

### Backend - Service Layer

I servizi sono divisi per gestire la logica specifica di prenotazione.

```mermaid
classDiagram

class ResourceService {
    -resourceRepo: ResourceRepository
    +getAvailableResources(type: ResourceType) ResourceDTO[]
    +checkAvailability(resourceId: string, date: Date) boolean
}

class BookingService {
    <<interface>>
    +createBooking(request: BookingRequest) Response
}

class FieldBookingService {
    -bookingRepo: FieldBookingRepository
    -resourceRepo: ResourceRepository
    +createBooking(request: FieldBookingRequest) Response
    +checkSlotAvailability(fieldId: string, start: Date, end: Date) boolean
}

class CourseBookingService {
    -bookingRepo: CourseBookingRepository
    -resourceRepo: ResourceRepository
    +createBooking(request: CourseBookingRequest) Response
    +checkSpotAvailability(lessonId: string) boolean
}

BookingService <|.. FieldBookingService
BookingService <|.. CourseBookingService

FieldBookingService --> FieldBookingRepository
CourseBookingService --> CourseBookingRepository
FieldBookingService --> ResourceService
CourseBookingService --> ResourceService
```

### Backend - Controller Layer

Controller specifici per gestire le diverse logiche di prenotazione.

```mermaid
classDiagram

class BaseController {
    <<abstract>>
    +sendResponse(data: Object)
    +handleError(err: Error)
}

class ResourceController {
    -service: ResourceService
    +listResources(req: Request)
    +getResourceDetails(req: Request)
}

class BookingController {
    <<abstract>>
    +createBooking(req: Request)
}

class FieldBookingController {
    -service: FieldBookingService
    +createBooking(req: Request)
}

class CourseBookingController {
    -service: CourseBookingService
    +createBooking(req: Request)
}

ResourceController --|> BaseController
BookingController --|> BaseController
FieldBookingController --|> BookingController
CourseBookingController --|> BookingController

ResourceController --> ResourceService
FieldBookingController --> FieldBookingService
CourseBookingController --> CourseBookingService
```

### Frontend - View e Presenter

Frontend ottimizzato con componenti riutilizzabili (`ResourceCard`) e Presenter base. Si sfrutta l'ereditarietà o la configurazione per adattare la vista a Campi o Corsi.

```mermaid
classDiagram

class View {
    <<interface>>
    +render()
    +showError(msg: string)
}

class GenericResourceView {
    %% Vista lista parametrica per Campi e Corsi
    -container: HTMLElement
    -cardFactory: CardFactory
    +displayResources(items: Item[])
    +onSelect(callback: Function)
}

class BookingSummaryView {
    %% Vista riepilogo per conferma UC04 e UC06
    +displaySummary(data: BookingData)
    +onConfirm(callback: Function)
}

class ResourceCard {
    %% UI Component riutilizzabile per Campo e Corso
    -data: Object
    +render() string
}

class ResourcePresenter {
    %% Logica comune per caricamento dati
    -view: GenericResourceView
    -service: APIService
    +loadResources(type: string)
    +selectResource(id: string)
}

class BookingPresenter {
    %% Logica comune prenotazione
    -view: BookingSummaryView
    -service: APIService
    +initialize(bookingData: Object)
    +confirmBooking()
}

GenericResourceView ..|> View
BookingSummaryView ..|> View
GenericResourceView ..> ResourceCard : uses
ResourcePresenter --> GenericResourceView
BookingPresenter --> BookingSummaryView

ResourcePresenter ..> ResourceController : calls API (via HTTP)
BookingPresenter ..> BookingController : calls API (via HTTP)
```
