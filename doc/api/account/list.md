# List Accounts
Lists accounts from database.

## Request
- url
  - api/account/list
- method
  - GET
- headers
  - 'Content-Type' : 'application/json'
- url queries
  - username (string, optional)

## Response
- code: 200
  - description: accounts found
  - body (json array of accounts)
    - account (object, required)
      - username (string, required)
      - id (string, required)
- code: 404
  - description: no accounts found
- code: 500
  - description: server error
