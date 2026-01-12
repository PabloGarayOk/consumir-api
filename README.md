# PHP API Consumption – Practical Exercises

This repository contains a collection of **hands-on exercises focused on consuming REST APIs using PHP**.  
The work is based on a guided learning process and extended with additional self-created exercises to reinforce concepts in a real-world scenario.

All examples were tested against a real API developed by me:

- **API used for practice:** `PabloGarayOk/apirest-pacientes`
- The same API is also consumed by a Vue.js dashboard project.

The goal of this repository is to demonstrate **practical backend skills**, clean structure, and defensive programming when working with external APIs.

---

## Topics Covered

Throughout the exercises, the following concepts were implemented and practiced:

- API consumption using `file_get_contents`
- HTTP methods:
  - `GET`
  - `POST`
  - `PUT`
  - `PATCH`
  - `DELETE`
  - `DELETE` via request headers
- Bearer token authentication
- Dynamic token handling
- Logical request structure (parameters, headers, options)
- Robust input and response validation
- Error handling and response verification
- Timeouts and retry logic
- HTTP status code handling
- Real API interaction (not mock data)

---

## Advanced Concepts

As part of the later exercises, the request logic was refactored into a reusable function to centralize behavior:

- `apiRequest()` function
- Centralized handling of:
  - Timeouts
  - Retries
  - HTTP errors
  - API response validation

This approach reflects a **more senior-style backend pattern**, focusing on maintainability, clarity, and reuse.

---

## Project Purpose

This repository was created to:

- Practice API consumption in a realistic environment
- Reinforce backend fundamentals using PHP
- Apply defensive programming techniques
- Demonstrate understanding of REST principles
- Serve as a reference for future backend projects

---

## Technologies Used

- PHP
- REST APIs
- HTTP protocol
- JSON
- Bearer Token authentication

---

## Related Projects

- REST API: `apirest-pacientes`
- Vue.js Dashboard consuming the same API

---

## Author

Pablo Garay  
[Personal website](https://pablogaray.com.ar)

