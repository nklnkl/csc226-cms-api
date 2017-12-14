# Retrieve Account
Retrieves account from database.

## Request
- url
  - api/account/:id
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, optional)
  - 'account-id' (string, optional)
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: account found
  - body
    - account (object, required)
      - username (string, required)
      - bio (string, required)
      - location (string, required)
      - id (string, required)
      - created (integer, required)
      - updated (integer, required)
      - email (string, only if account owned)
- code: 404
  - description: account not found
- code: 410
  - description: target account inactive
  - conditions
    - target account inactive
- code: 500
  - description: server error
