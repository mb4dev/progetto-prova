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



