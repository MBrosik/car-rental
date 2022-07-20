# Car rental

## About
Simple car rental website. Like Bolt. 



## Interface

### Offers
![Untitled-1](https://user-images.githubusercontent.com/63966121/173819932-754b3a86-a0a0-4ef3-a001-2b801e19d86c.png)

### Users
![Untitled-2](https://user-images.githubusercontent.com/63966121/173820604-c027bc42-8809-45ab-9b26-d22a1bd5322f.png)

### Your reservations
![image](https://user-images.githubusercontent.com/63966121/173820931-8b0ceb8f-f066-49ac-acee-54c59ca9c108.png)

## Installation
 - Download repo
 - In terminal run: `npm i` and `npm run build`
 - Install [XAMP](https://www.apachefriends.org/pl/index.html)
 - Files from [public](https://github.com/MBrosik/car-rental/tree/master/public) copy to XAMP "htdocs" folder
 - Import [databes](https://github.com/MBrosik/car-rental/tree/master/db/car_rental.sql) and name that: "car_rental"
- Activate sql events
- Open project in browser


## Features
- Register account
- User management
- Car reservation
- Time manipulation


## User privileges
|Privileges                                   | Client        | Moder        | Admin |
|---------------------------------------------|:-------------:|:------------:| -----:|
|Car reservation                              | ✔             | ✔           | ✔     |
|watch available cars                         | ✔             | ✔           | ✔     |
|Managing own reservations                    | ✔             | ✔           | ✔     |
|Access to QR Code                            | ✔             | ✔           | ✔     |
|Return car                                   | ✔             | ✔           | ✔     |
|Time manipulation                            |               | ✔           | ✔     |
|Reservations management                      |               | ✔           | ✔     |
|User management (activating/blocking account)|               |             | ✔     |

## Technologies
### Frontend
![Svelte](https://img.shields.io/badge/Svelte-FF3E00?logo=Svelte&logoColor=white&style=for-the-badge)
![Javascript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=JavaScript&logoColor=black&style=for-the-badge)
![Html](https://img.shields.io/badge/HTML5-E34F26?logo=HTML5&logoColor=white&style=for-the-badge)
![Css](https://img.shields.io/badge/CSS3-1572B6?logo=CSS3&logoColor=white&style=for-the-badge)
![TailwindCSS](https://img.shields.io/badge/Tailwind%20CSS-06B6D4?logo=Tailwind%20CSS&logoColor=white&style=for-the-badge)

### Backend
![PHP](https://img.shields.io/badge/PHP-777BB4?logo=PHP&logoColor=white&style=for-the-badge)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=MySQL&logoColor=white&style=for-the-badge)
