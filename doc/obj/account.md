# Account
The structure of a user account.

## Properties
- id
  - type: string
  - description: unique identifier for the resource
- created
  - type: integer
  - description: when this resource was created since epoch
- updated
  - type: integer
  - description: when this resource was last updated since epoch
- status
  - type: integer
  - values
    - 0: active
    - 1: deactivated
  - description: the current status of the account
- email
  - type: string
  - description: authenticates a user for a session
- password
  - type: string
  - description: authenticates a user for a session, password is encrypted prior to database submission
- username
  - type: string
  - description: used as a public handler for a user's account
