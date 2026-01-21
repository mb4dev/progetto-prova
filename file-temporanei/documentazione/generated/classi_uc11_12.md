# Classi UC11, UC12 - Modulo Amministrazione Risorse

Questo modulo gestisce le operazioni di backoffice per l'amministratore, specificamente la creazione, modifica e cancellazione di Campi sportivi (UC11) e Corsi/Lezioni (UC12).

## Backend - Repository Layer

Il `ResourceRepository` (visto in UC03/UC06) viene qui utilizzato nella sua interezza, includendo i metodi di scrittura riservati all'admin.

```mermaid
classDiagram

class Repository {
    <<interface>>
}

class ResourceRepository {
    <<interface>>
    +findById(id: string)
    +save(resource: Resource)
    +delete(id: string)
    +findAll(type: ResourceType)
}

class FieldRepository {
    +saveField(field: Field)
}

class CourseRepository {
    +saveCourse(course: Course)
    +addLesson(courseId: string, lesson: Lesson)
}

class Resource {
    <<abstract>>
    +id: string
    +name: string
    +active: boolean
}

ResourceRepository <|-- FieldRepository
ResourceRepository <|-- CourseRepository
ResourceRepository --|> Repository
ResourceRepository ..> Resource
```

## Backend - Service Layer

Services dedicati alla gestione del ciclo di vita delle risorse, inclusi controlli di integrità (es. non cancellare campi con prenotazioni future).

```mermaid 
classDiagram

class ResourceAdminService {
    <<interface>>
    +createResource(data: Object) Response
    +updateResource(id: string, data: Object) Response
    +deleteResource(id: string) Response
}

class FieldAdminService {
    -fieldRepo: FieldRepository
    -bookingRepo: FieldBookingRepository
    +deleteResource(id: string)
}

class CourseAdminService {
    -courseRepo: CourseRepository
    -bookingRepo: CourseBookingRepository
    +deleteResource(id: string)
    +scheduleLesson(courseId: string, data: Object)
}

ResourceAdminService <|.. FieldAdminService
ResourceAdminService <|.. CourseAdminService

%% Dipendenza per check integrità
FieldAdminService ..> FieldBookingRepository : check bookings before delete
```

## Backend - Controller Layer

```mermaid
classDiagram

class BaseController {
    <<abstract>>
}

class AdminResourceController {
    -fieldService: FieldAdminService
    -courseService: CourseAdminService
    +handleAction(resourceType: string, action: string, data: Object)
}

AdminResourceController --|> BaseController
AdminResourceController --> FieldAdminService
AdminResourceController --> CourseAdminService
```

## Frontend - View e Presenter

L'interfaccia di amministrazione richiede tabelle dati (DataGrid) e form complessi per l'editing.

```mermaid 
classDiagram

    class View {
        <<interface>>
    }

    class AdminDashboardView {
        +renderSidebar()
        +renderContent(html: string)
    }

    class ResourceManagerView {
        %% Vista CRUD generica
        +showList(items: Array)
        +showForm(schema: Object, data: Object)
        +bindSave(callback: Function)
        +bindDelete(callback: Function)
    }

    class AdminPresenter {
        -view: ResourceManagerView
        -api: APIService
        +loadResources(type: string)
        +saveResource(type: string, data: Object)
        +deleteResource(type: string, id: string)
    }

    AdminDashboardView ..|> View
    ResourceManagerView ..|> View
    
    AdminPresenter --> ResourceManagerView
    AdminPresenter --> AdminDashboardView
```

---

## Diagrammi di Attività

### UC11, UC12 - Gestione Risorse (CRUD)

```mermaid
flowchart TD
    Start([Inizio]) --> Login([Login admin])
    Login --> SelectRes[Selezione admin dashboard]
    SelectRes --> ShowList[Visualizza Elenco]
    
    ShowList --> UserAction{Azione?}
    
    UserAction -->|Crea Nuovo| FormNew[Form Nuova Risorsa]
    UserAction -->|Modifica| FormEdit[Form Modifica]
    UserAction -->|Elimina| ConfirmDel{Conferma eliminazione?}
    
    FormNew --> InputData[Inserimento Dati]
    FormEdit --> InputData
    
    InputData --> Submit[Salva]
    
    Submit --> Validate{Dati validi?}
    Validate -->|No| ShowErr[Mostra Errore]
    ShowErr --> InputData
    
    Validate -->|Sì| SaveDB[Salva nel DB]
    
    ConfirmDel -->|Sì| CheckDepend{Ci sono prenotazioni?}
    CheckDepend -->|Sì| BlockDel[Errore: Impossibile eliminare]
    CheckDepend -->|No| ExecDel[Esegui Cancellazione]
    
    SaveDB --> Refresh[Aggiorna Elenco]
    ExecDel --> Refresh
    BlockDel --> ShowList
    Refresh --> ShowList
```

---

## Diagrammi di Sequenza

### UC11, UC12 - Salvataggio Risorsa (Create/Update)

```mermaid
sequenceDiagram
    autonumber
    
    actor Admin
    participant View as View
    participant Pres as AdminPresenter
    participant API as APIService
    participant Ctrl as AdminResourceController
    participant Svc as ResourceAdminService
    participant Repo as ResourceRepository
    
    Admin->>View: Compila form e clicca "Salva"
    View->>Pres: event()
    
    Pres->>API: createResource(type)
    API->>Ctrl: /admin/newresource
    Ctrl->>Svc: createResource(data)
    
    Svc->>Svc: validate(data)
    alt Invalid
        Svc-->>Ctrl: Error(Validation)
        Ctrl-->>API: 400 Bad Request
        API-->>Pres: {success: false, errors}
        Pres->>View: showFormErrors(errors)
    else Valid
        Svc->>Repo: save(resource)
        Repo-->>Svc: resourceId
        Svc-->>Ctrl: Success(resource)
        Ctrl-->>API: 200 OK
        API-->>Pres: {success: true, data}
        Pres->>View: showSuccess("Risorsa salvata")
    end
```
