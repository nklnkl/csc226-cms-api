# Create Account
Submits data to register an account to the database.

## Request
- url
  - api/account
- method
  - PATCH
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'user-id' (string, required)
- url parameters
  - none
- url queries
  - none
- body (json string)
  - email (string, optional)
  - password (string, optional)
  - username (string, optional)

## Response
- code: 200
  - description: account was updated
  - body (json string)
    - none
- code: 401
  - description: user was not authorized
  - body (json string)
    - none
- code: 409
  - description: the email or username was already in use
  - body (json string)
    - error (array, required)
      - 'email is already in use' (string, optional)
      - 'username is already in use' (string, optional)
- code: 410
  - description: account could not be updated due to status
  - body (json string)
    - none
- code: 422
  - description: the data given by the client did not pass validation
  - body (json string)
    - error (array, required)
      - 'email invalid' (string, optional)
      - 'password invalid' (string, optional)
      - 'username invalid' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
