# Account
The structure of a user's account.

## Properties
- id
  - type: string
  - description: unique identifier for the account
- created
  - type: integer
  - description: used to mark when this resource was created since epoch
- updated
  - type: integer
  - description: used to mark when this resource was last updated since epoch
- email
  - type: string
  - description: used to authenticate a user for a session
- password
  - type: string
  - description: password used to authenticate a user for a session, password
    is encrypted prior to database submission
- username
  - type: string
  - description: used as a public handler for a user's account
