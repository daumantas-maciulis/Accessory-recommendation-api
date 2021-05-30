# Accessory recommendation API

This accessory recommendation api was build as practical task for Backend PHP developer position at Adeo Web.

---

## Description:
Create a service, which returns product recommendations depending on the weather forecast.
Application has to request weather information from an official LHMT API. Filter and process received data and 
recommend two accessories per day which are suited for weather. Accessories have to be saved in database. 
Response data has to be provided in JSON format and cached for 5 minutes. 

---
## Used technology stack:
- PHP 8 version
- MySQL database using TablePlus client
- Symfony 5.2 framework
- Postman for sending requests

### Libraries used for this app:
- Doctrine ORM for creating database and SQL queries
- Guzzle for making request to an external API


## Application installation

To install and start using this application in local environment firstly you need to configure database information in .env file
secondly change directory to product folder and  run: </br>

````
bash install.sh
````

After this command you will have database created, table created and fixtures loaded.

## API endpoint

### Request

To fetch data from API you need to send GET request to:
```puml
api/v1/products/recommended/{city}
```
In the city wildcard you can provide name of any Lithuanian city.

### Response
To your request API will answer with JSON response which is shown bellow:
```json
{
    "city": "Vilnius",
    "recommendations": [
        {
            "Weather_forecast": "clouds",
            "date": "2021-05-30",
            "products": [
                {
                    "sku": "SCR-F",
                    "name": "Scarf from cold wind",
                    "price": 22
                },
                {
                    "sku": "CLD-22",
                    "name": "Jacket with SUN",
                    "price": 100
                }
            ]
        },
        {
            "Weather_forecast": "clouds",
            "date": "2021-05-31",
            "products": [
                {
                    "sku": "SCR-F",
                    "name": "Scarf from cold wind",
                    "price": 22
                },
                {
                    "sku": "SCR-F",
                    "name": "Scarf from cold wind",
                    "price": 22
                }
            ]
        },
        {
            "Weather_forecast": "clouds",
            "date": "2021-06-01",
            "products": [
                {
                    "sku": "CLD-22",
                    "name": "Jacket with SUN",
                    "price": 100
                },
                {
                    "sku": "SCR-F",
                    "name": "Scarf from cold wind",
                    "price": 22
                }
            ]
        }
    ],
    "additional information": "Weather for this information was taken from LHMT API. For more information: https://api.meteo.lt/"
}
```

