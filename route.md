# Routes

## Anonymous

URL | HTTP Method | Controller | Methode | Title | Content | Comment |
-|-|-|-|-|-|-|
/| `GET` | `MainController` | `#home` | O'Secours | Homepage | - |
`/brand`| `GET` | `MainController` | `#brand` | O'Secours for brands | Homepage Brands| - |
`/influencer`| `GET` | `MainController` | `#influencer` | O'Secours for influencers | Homepage influencers| - |

## Route logged

URL | HTTP Method | Controller | Methode | Title | Content | Comment |
-|-|-|-|-|-|-|
`/login`| `GET|POST` | `UserController` | `#login` | login | Login form | - |
`/logout`| `GET` | `UserController` | `#logout` | logout | - | - |
`/{role}`| `GET` | `UserController` | `#list` | user list  | {role} user's role: brand, influencer, moderator or admin  |
`/{role}/{id}/detail`| `GET` | `UserController` | `#read` | user | {id}  user's id, {role} user's role: brand, influencer, moderator or admin |
`/user/{id}/edit`| `GET|POST` | `UserController` | `#edit` | form to edit a user | {id} - is the user to edit |
`/user/{id}/delete`| `GET|POST` | `UserController` | `#delete` | form to delete a user | {id} - is the user to delete |
`/user/new` | `POST` | `UserController` | `new` | user new | create new user | - |

URL | HTTP Method | Controller | Methode | Title | Content | Comment |
-|-|-|-|-|-|-|
`/announcements`| `GET` | `AnnouncementController` | `#list` | Liste des annonces | List of all announcements| - |
`/announcement/{id}/detail`| `GET` | `AnnouncementController` | `#read` | announcement  | {id} is the annoucement to read |
`/announcement/{id}/edit`| `GET|POST` | `AnnouncementController` | `#edit` | form to edit an announcement  | {id} - is the announcement to edit |
`/announcement/{id}/delete`| `GET|POST` | `AnnouncementController` | `#delete` | form to delete a announcement  | {id} - is the announcement to delete |

URL | HTTP Method | Controller | Methode | Title | Content | Comment |
-|-|-|-|-|-|-|
`/categories`| `GET` | `CategoryController` | `#list` | Liste des catégories | List of all categories| - |
`/category/{id}/detail`| `GET` | `CategoryController` | `#read` | category  | {id} is the category to read |
`/category/{id}/edit`| `GET|POST` | `CategoryController` | `#edit` | form to edit an category  | {id} - is the category to edit |
`/category/{id}/delete`| `GET|POST` | `CategoryController` | `#delete` | form to delete a category  | {id} - is the category to delete |