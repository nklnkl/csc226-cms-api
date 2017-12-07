# Update Account
Updates account from database.

## Request
- url
  - api/account
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

## Response
- code: 200
  - description: account updated
- code: 401
  - description: client not authorized
- code: 403
  - description: client forbidden to update account
- code: 404
  - description: account not found
- code: 409
  - description: email or username already in use
  - body
    - error (array, required)
      - 1 (string, optional): email already in use
      - 2 (string, optional): username already in use
- code: 410
  - description: account inactive
- code: 422
  - description: request body data invalid
  - body
    - error (array, required)
      - 1 (string, optional): email invalid
      - 2 (string, optional): password invalid
      - 3 (string, optional): username invalid
- code: 500
  - description: server error
