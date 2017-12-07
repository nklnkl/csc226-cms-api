# Retrieve Account
Retrieves account from database.

## Request
- url
  - api/account/:id
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: account found
  - body
    - account (object, required)
      - email (string, required)
      - username (string, required)
      - id (string, required)
      - created (integer, required)
      - updated (integer, required)
- code: 404
  - description: account not found
- code: 410
  - description: account found, but account inactive
- code: 500
  - description: server error
