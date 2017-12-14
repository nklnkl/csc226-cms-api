# Deactivate Account
Deactivates account from database.

## Request
- url
  - api/account/:id
- method
  - DELETE
- headers
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters
  - id (string, required)

## Response
- code: 200
  - description: account deactivated
- code: 401
  - description: client not authorized
  - conditions
    - session-id account-id combo invalid
- code: 403
  - description: client forbidden to deactivate account
  - conditions:
    - target account not owned
- code: 404
  - description: account not found
- code: 409
  - description: account already deactivated
- code: 500
  - description: server error
