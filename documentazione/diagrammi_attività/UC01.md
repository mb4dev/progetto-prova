``` mermaid
flowchart TD
    Start([Inizio])
    
    subgraph Frontend1["FRONTEND - Input"]
        Start --> Choice{Scegli azione}
        Choice -->|Login| LoginForm[Inserisci credenziali]
        Choice -->|Registrazione| RegisterForm[Inserisci dati]
        
        LoginForm --> SendLogin[Invia richiesta login]
        RegisterForm --> SendRegister[Invia richiesta registrazione]
    end
    
    subgraph Backend["BACKEND"]
        ValidateLogin[Valida credenziali]
        ValidateRegister[Valida dati registrazione]
        
        CheckComplete{Dati completi?}
        CheckUser{Utente esistente?}
        CheckPwd{Password corretta?}
        
        SaveDB[Salva in database]
        CheckSave{Salvato con successo?}
        
        ErrorResponse[Error Response]
        OkResponse[OK Response]
    end
    
    subgraph Frontend2["FRONTEND - Output"]
        ShowError[Mostra messaggio errore]
        ShowHome[Mostra Home Page]
        
        ShowError --> Choice
        ShowHome --> End([Fine])
    end
    
    SendLogin -.->|API call| ValidateLogin
    ValidateLogin --> CheckComplete
    CheckComplete -->|No| ErrorResponse
    CheckComplete -->|Sì| CheckUser
    CheckUser -->|No| ErrorResponse
    CheckUser -->|Sì| CheckPwd
    CheckPwd -->|No| ErrorResponse
    CheckPwd -->|Sì| OkResponse
    
    SendRegister -.->|API call| ValidateRegister
    ValidateRegister --> CheckComplete
    CheckComplete -->|Sì| SaveDB
    SaveDB --> CheckSave
    CheckSave -->|No| ErrorResponse
    CheckSave -->|Sì| OkResponse
    
    ErrorResponse -.->|Response| ShowError
    OkResponse -.->|Response| ShowHome
    
    style Frontend1 fill:#f0f9ff,stroke:#0284c7,stroke-width:2px
    style Backend fill:#fef3c7,stroke:#d97706,stroke-width:2px
    style Frontend2 fill:#f0f9ff,stroke:#0284c7,stroke-width:2px
```


