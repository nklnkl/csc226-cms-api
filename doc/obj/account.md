# Account
Structure of account.

## Properties
- id
  - type: string
  - description: unique identifier for account
- created
  - type: integer
  - description: when account was created since epoch
- updated
  - type: integer
  - description: when account was last updated since epoch
- status
  - type: integer
  - values
    - 0: active
    - 1: inactive
  - description: status of account
- email
  - type: string
  - description: authenticates user for session
- password
  - type: string
  - description: authenticates user for session, password encrypted prior database submission
- username
  - type: string
  - description: public handle for account
- bio
  - type: string
  - description: bio of account
- location
  - type: string
  - description: location of account
