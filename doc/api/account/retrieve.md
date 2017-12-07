# Retrieve Account
Retrieves data of an account from the database. Uses the 'id' parameter in
the url to query the account from the database.

## Request
- url
  - api/account/:id
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
- url parameters
  - id (string, required)
- url queries
  - none
- body (json string)
  - none

## Response
- code: 200
  - description: an account was found
  - body (json string)
    - account (key value object, required)
      - email (string, required)
      - username (string, required)
      - id (string, required)
      - created (integer, required)
      - updated (integer, required)
- code: 404
  - description: an account was not found
  - body (json string)
    - none
- code: 410
  - description: an account was found, but can not be given due to status
  - body (json string)
    - none
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
