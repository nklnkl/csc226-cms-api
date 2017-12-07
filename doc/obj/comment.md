# Comment
The structure of a comment.

## Properties
- id
  - type: string
  - description: unique identifier for the resource
- created
  - type: integer
  - description: used to mark when this resource was created since epoch
- updated
  - type: integer
  - description: used to mark when this resource was last updated since epoch
- account_id
  - type: string
  - description: used to identify which account this resource is associated
    with
- blog_id
  - type: string
  - description: used to identify which blog this resource is associated
  with
- body
  - type: string
  - max: 5000
  - description: used as the content of the comment
