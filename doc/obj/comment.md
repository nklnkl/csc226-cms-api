# Comment
Structure of comment.

## Properties
- id
  - type: string
  - description: unique identifier for comment
- created
  - type: integer
  - description: when comment was created since epoch
- updated
  - type: integer
  - description: when comment was last updated since epoch
- account_id
  - type: string
  - description: identify account comment belongs to
- blog_id
  - type: string
  - description: identify blog comment belongs to
- body
  - type: string
  - max: 5000
  - description: body content of comment
