# Blog Post
Structure of blog post.

## Properties
- id
  - type: string
  - description: unique identifier for blog post
- created
  - type: integer
  - description: when blog post was created since epoch
- updated
  - type: integer
  - description: when blog post was last updated since epoch
- account_id
  - type: string
  - description: identify account blog post belongs to
- title
  - type: string
  - max: 255
  - description: blog post title
- body
  - type: string
  - max: 5000
  - description: blog post body content
- privacy
  - type: integer
    - values
      - 0: public
      - 1: private
  - description: retrieval state of blog post
