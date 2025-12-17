### Backend
``` mermaid
classDiagram

class Controller {
	<<abstract>>
	+resolveAction(action: string)
	#response(response: Response)
}

class AuthController {
	-service: AuthService
	+resolveAction(action: string)
	-getBody() array
}

AuthController --|> Controller

class AuthRepository {
	<<interface>>
	+findByEmail(email: string) User|null
	+insertUser(user: User) bool
}

class DefaultAuthRepository {
	-connection: PDOConnection
	+findByEmail(email: string) User|null
	+insertUser(user: User) bool
}
DefaultAuthRepository --|> AuthRepository

class User {
	+email: string
	+name: string
	+password: string
}

class PasswordValidator {
	<<interface>>
	+validate(password: string, hash: string) bool
}

class DefaultPasswordValidator {
	+validate(password: string, hash: string) bool
}
DefaultPasswordValidator --|> PasswordValidator

class AuthService {
	<<interface>>
	+login(user: User) Response
	+register(user: User) Response
}

class DefaultAuthService {
	-repository: AuthRepository
	-tokenManager: JwtTokenManager
	-passChecker: PasswordValidator
	+login(user: User) Response
	+register(user: User) Response
}
DefaultAuthService --|> AuthService
DefaultAuthService --> AuthRepository
DefaultAuthService --> JwtTokenManager
DefaultAuthService --> PasswordValidator

class Response {
	+code: int
	+success: bool
	+jsonData: string
}

class ControllerFactory {
	+create(controllerName: string) Controller
}

class URLParser {
	+parse(url: string) ParsedURL
}

class ParsedURL {
	+controller: string
	+action: string
	+params: array
}

class Router {
	-parser: URLParser
	-factory: ControllerFactory
	+dispatch(url: string)
}

class JwtTokenManager {
	<<interface>>
	+encode(data: User) string
	+decode(token: string) object
}

class DefaultJwtTokenManager {
	+encode(data: User) string
	+decode(token: string) object
}
DefaultJwtTokenManager --|> JwtTokenManager

AuthController --> AuthService
Router --> ControllerFactory
Router --> URLParser
ControllerFactory ..> Controller
URLParser *-- ParsedURL
AuthService ..> Response 
AuthService ..> User
AuthRepository ..> User 

``` 




### Frontend 
``` mermaid
classDiagram
    HTMLElement <|-- View
    View <|-- LoginView
    View <|-- RegisterView

    class App {
        -root : HTMLElement
        +start()
        +router(route: string)
    }

    App ..> LoginView
    App ..> RegisterView
    App ..> AuthPresenter
    App ..> HTMLElement

    class View {
        +display()
        +template() string
        #bindEvents()
    }

    class LoginView {
        -submitBtn : HTMLElement
        -registerLink : HTMLElement

        +connectedCallback()
        +display()
        +template() string
        #bindEvents()
    }

    class RegisterView {
        -submitBtn : HTMLElement
        -loginLink : HTMLElement

        +connectedCallback()
        +display()
        +template() string
        #bindEvents()
    }

    class Presenter {
        #view : View

        +init()
        +update()
        #handleViewEvents()
    }

     class AuthPresenter {
        +update()
        #handleViewEvents()
    }


    Presenter --> View
    Presenter <|-- AuthPresenter
    Presenter --> APIService

class APIService {
    <<interface>>
    +login(email: string, password: string)
    +register(email: string, name: string, password: string)
}

class Observer {
    <<interface>>
    +subscribe(event: string, callback: function)
    +unsubscribe(event: string, callback: function)
    +notify(event: string, data: any)
    +clear()
}

class DefaultObserver {
    -listeners: Map
    +subscribe(event, callback)
    +unsubscribe(event, callback)
    +notify(event, data)
    +clear()
}
DefaultObserver --|> Observer

App ..> DefaultObserver
AuthPresenter ..> DefaultObserver 
LoginView ..> DefaultObserver 
RegisterView ..> DefaultObserver

```