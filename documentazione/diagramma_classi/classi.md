``` mermaid 
classDiagram

%% =========================
%% FRONTEND
%% =========================
class LoginView {
    +display(message)
}

class LoginPresenter {
    -api: APIService
    +login(username, password)
}

class APIService {
    -token: string
    +login(username, password)
    +saveToken(token)
}

class BackendAPI {
    +POST /auth/login
}

LoginView --> LoginPresenter
LoginPresenter --> APIService
APIService --> BackendAPI


%% =========================
%% BACKEND – ENTRY POINT
%% =========================
class Main {
    +handleRequest()
}

class Router {
    -parser: URLParser
    -factory: ControllerFactory
    +route()
}

class URLParser {
    +parse(url): RouteInfo
}

Main --> Router
Router --> URLParser


%% =========================
%% FACTORY
%% =========================
class ControllerFactory {
    -connection: DBConnection
    +createController(name): Controller
}

Main --> ControllerFactory
Router --> ControllerFactory


%% =========================
%% CONTROLLER LAYER
%% =========================
class Controller {
    <<abstract>>
    -service: Service
    +resolve(action)
}

class AuthController {
    +login(email, password)
    +setCookie(token)
}

Controller <|-- AuthController
ControllerFactory --> Controller


%% =========================
%% SERVICE LAYER
%% =========================
class Service {
    <<abstract>>
}

class AuthService {
    -repository: AuthRepository
    -jwtEncoder: JWTEncoder
    +login(email, password)
    -verifyPassword(password, hash)
}

Service <|-- AuthService
AuthController --> AuthService


%% =========================
%% REPOSITORY LAYER
%% =========================
class Repository {
    <<abstract>>
}

class AuthRepository {
    -connection: DBConnection
    +getUserByEmail(email)
}

Repository <|-- AuthRepository
AuthService --> AuthRepository


%% =========================
%% INFRASTRUCTURE
%% =========================
class JWTEncoder {
    +generateToken(user): string
}

class Database
class DBConnection

AuthService --> JWTEncoder
AuthRepository --> DBConnection
DBConnection --> Database

``` 


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
}

class IAuthRepository {
    <<interface>>
    +findByEmail(email) User
}

class AuthRepository
AuthRepository ..|> IAuthRepository

class AuthService {
    -repository IAuthRepository
    -encoder JWTEncoder
    +login(email, password)
}

class Response

class ControllerFactory {
    +create(controllerName) Controller
}

class URLParser {
    +parse(url) ParsedURL
}

class ParsedURL {
    +controller: string
    +action: string
    +params: string
}

class Router {
    -parser: URLParser
    -factory: ControllerFactory
    +dispatch(url)
}

class JWTEncoder {
    +encode(data): token
}

AuthController --|> Controller

Router --> URLParser
Router --> ControllerFactory
URLParser ..> ParsedURL
ControllerFactory ..> Controller

AuthController --> AuthService
Controller ..> Response

AuthService --> IAuthRepository
AuthService --> JWTEncoder

``` 


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
}

class IAuthRepository {
    <<interface>>
    +findByEmail(email) User
}

class AuthRepository
AuthRepository ..|> IAuthRepository

class ILoginStrategy {
    <<interface>>
    +authenticate(email, password) User
}

class StandardLoginStrategy {
    -repository: IAuthRepository
    +authenticate(email, password) User
}

StandardLoginStrategy ..|> ILoginStrategy

class AuthService {
    -repository: IAuthRepository
    -encoder: JWTEncoder
    -loginStrategy: ILoginStrategy
    +login(email, password)
    +setLoginStrategy(strategy: ILoginStrategy)
}

class Response

class ControllerFactory {
    +create(controllerName) Controller
}

class URLParser {
    +parse(url) ParsedURL
}

class ParsedURL {
    +controller: string
    +action: string
    +params: string
}

class Router {
    -parser: URLParser
    -factory: ControllerFactory
    +dispatch(url)
}

class JWTEncoder {
    +encode(data): token
}

AuthController --|> Controller

Router --> URLParser
Router --> ControllerFactory
URLParser ..> ParsedURL
ControllerFactory ..> Controller

AuthController --> AuthService
Controller ..> Response

AuthService --> IAuthRepository
AuthService --> JWTEncoder
AuthService --> ILoginStrategy

StandardLoginStrategy --> IAuthRepository

```  



