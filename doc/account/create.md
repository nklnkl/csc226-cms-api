# Create Account
Submits data to register an account to the database.

## Request
- url
  - api/account
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
- url parameters
  - none
- url queries
  - none
- body (json string)
  - email (string, required)
  - password (string, required)
  - username (string, required)

## Response
- code: 200
  - body (json string)
    - none
- code: 409
  - body (json string)
    - error (array, required)
      - 'email is already in use' (string, optional)
      - 'username is already in use' (string, optional)
- code: 422
  - body (json string)
    - error (array, required)
      - 'email invalid' (string, optional)
      - 'password invalid' (string, optional)
      - 'username invalid' (string, optional)
- code: 500
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
