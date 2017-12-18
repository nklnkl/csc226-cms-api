# Example API doc
Example of an api doc.

## Request
- url
  - api/:category/article
- method
  - POST
- headers (multiple)
  - 'Content-Type' : 'application/json'
  - 'session_id' (string, required)
  - 'account_id' (string, required)
- url parameters (multiple)
  - category (string, required)
- url queries
  - none
- body
  - body (string, required)
  - title (string, required)

## Response
- code: 200
  - description: article was created
  - body
    - none
- code: 401
  - description: client was not authorized
  - body
    - none
- code: 409
  - description: account already has a article with the same title
  - body
    - error (array, required)
      - 1 (string, optional): duplicate title
- code: 422
  - description: the data given by the client did not pass validation
  - body
    - error (array, required)
      - 'article body too long' (string, optional)
      - 'article title too long' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body
    - error (array, required)
      - 'server error, please try again later' (string, required)
