# PHP API Consumption – Practical Exercises

This repository contains a collection of **hands-on exercises focused on consuming REST APIs using PHP**.  
The work is based on a guided learning process and extended with additional self-created exercises to reinforce concepts in a real-world scenario.

All examples were tested against a real API developed by me:

- **API used for practice:** `PabloGarayOk/apirest-pacientes`
- The same API is also consumed by a Vue.js dashboard project.

The goal of this repository is to demonstrate **practical backend skills**, clean structure, and defensive programming when working with external APIs.

---

## Project Overview

**Role:** Backend Developer (PHP)

This repository contains a **progressive learning** project focused on consuming REST APIs using **pure PHP**.

The project was built through a series of **23 hands-on exercises**, combining guided challenges and self-proposed tasks to reinforce concepts.
All examples were tested against a **real, production-like API** ('apirest-pacientes'), ensuring realistic constraints such as authentication, timeouts, retries, and error handling.

The goal of this project is not to provide a finished application, but to demonstrate **practical API consumption patterns**, clean request structure, and defensive programming techniques in PHP.

This repository is designed to remain **open for future extensions** as new scenarios and edge cases are explored.

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

## Lessons Learned

- Consuming real APIs exposes edge cases that are not visible in mock examples.
- Token-based authentication requires careful handling of expiration and renewal.
- Centralizing request logic (e.g. apiRequest() helper) greatly improves maintainability.
- Proper timeout and retry strategies are essential for resilient integrations.
- Supporting multiple HTTP methods (GET, POST, PUT, PATCH, DELETE) clarifies API semantics.
- DELETE requests via headers require special attention in PHP implementations.
- Clear separation between parameters, options, and headers improves readability.
- Defensive validation and structured error handling prevent silent failures.
- Repetition across multiple exercises significantly reinforces API design understanding.

---

## Related Projects

- REST API: `apirest-pacientes`
- Vue.js Dashboard consuming the same API

---

## Author

Pablo Garay  
[Personal website](https://pablogaray.com.ar)

