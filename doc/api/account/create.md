# Create Account
Submits account to database.

## Request
- url
  - api/account
- method
  - POST
- headers
  - 'Content-Type' : 'application/json'
- body
  - email (string, required)
  - password (string, required)
  - username (string, required)

## Response
- code: 200
  - description: account registered
- code: 409
  - description: email or username already in use
  - body
    - error (integer array, required)
      - 1 (optional): email already in use
      - 2 (optional): username already in use
- code: 422
  - description: request body data invalid
  - body
    - error (integer array, required)
      - 1 (optional): email invalid
      - 2 (optional): password invalid
      - 3 (optional): username invalid
- code: 500
  - description: server error
