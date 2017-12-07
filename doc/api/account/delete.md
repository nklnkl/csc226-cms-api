# Deactivate Account
Deactivates an account from the database.

## Request
- url
  - api/account
- method
  - DELETE
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'user-id' (string, required)
- url parameters
  - none
- url queries
  - none
- body (json string)
  - none

## Response
- code: 200
  - description: account was deactivated
  - body (json string)
    - none
- code: 401
  - description: user was not authorized
  - body (json string)
    - none
- code: 404
  - description: the account could not be found
  - body (json string)
    - none
- code: 409
  - description: the account was already deactivated
  - body (json string)
    - error (array, required)
      - 'email is already in use' (string, optional)
      - 'username is already in use' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
