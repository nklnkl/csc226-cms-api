# Update Account
Updates account from database.

## Request
- url
  - api/account/:id
- method
  - PATCH
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- body
  - email (string, optional)
  - password (string, optional)
  - username (string, optional)
  - bio (string, optional)
  - location (string, optional)
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: account updated
- code: 401
  - description: client not authorized
  - conditions
    - session-id account-id combo invalid
- code: 403
  - description: client forbidden to update account
  - conditions:
    - target account not owned
    - client account not admin
- code: 404
  - description: account not found
- code: 409
  - description: email or username already in use
  - body
    - error (array, required)
      - 1 (string, optional): email already in use
      - 2 (string, optional): username already in use
- code: 422
  - CURRENTLY NOT IMPLEMENTED!
  - description: request body data invalid
  - body
    - error (array, required)
      - 1 (string, optional): email invalid
      - 2 (string, optional): password invalid
      - 3 (string, optional): username invalid
      - 4 (string, optional): bio invalid
      - 5 (string, optional): location invalid
- code: 500
  - description: server error
