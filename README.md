
---

# Test Task for CC

## Overview

This project is deployed using Docker and all data is stored in a SQLite file. For ease of use, the main commands are included in a Makefile.

## Instructions

1. **Project Deployment**
   - The project is deployed using Docker and all source data is stored in a SQLite file.

2. **Main Commands** (included in the Makefile)
   - **To start the project:**
     ```sh
     make up
     ```
   - **To stop the project:**
     ```sh
     make down
     ```
   - **To run tests:**
     ```sh
     make exec php composer app:test-unit
     ```

3. **API Access**
   - To access the API, add a new host:
     ```sh
     echo '127.0.0.1 cc-test.local' >> /etc/hosts
     ```
   - The main URL for working with the API is:
     ```sh
     http://cc-test.local/products?category=boots&limit=2&priceLessThan=710.01&page=1
     ```
     The result will be the following response:
     ```json
     [
         {
             "sku": "000003",
             "name": "Ashlington leather ankle boots",
             "category": "boots",
             "price": {
                 "origin": 71000,
                 "final": 49699,
                 "discount_percentage": "30%",
                 "currency": "EUR"
             }
         }
     ]
     ```

---
