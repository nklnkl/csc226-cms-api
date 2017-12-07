# Example API doc
Example of an api doc.

## Request
- url
  - api/:category/article
- method
  - POST
- headers (multiple)
  - 'Content-Type' : 'application/json'
  - 'session-id' (string, required)
  - 'account-id' (string, required)
- url parameters (multiple)
  - category (string, required)
- url queries
  - none
- body (json string)
  - body (string, required)
  - title (string, required)

## Response
- code: 200
  - description: article was created
  - body (json string)
    - none
- code: 401
  - description: client was not authorized
  - body (json string)
    - none
- code: 409
  - description: account already has a article with the same title
  - body (json string)
    - error (array, required)
      - 1 (string, optional): duplicate title
- code: 422
  - description: the data given by the client did not pass validation
  - body (json string)
    - error (array, required)
      - 'article body too long' (string, optional)
      - 'article title too long' (string, optional)
- code: 500
  - description: an unexpected server error has occurred and has been reported
  - body (json string)
    - error (array, required)
      - 'server error, please try again later' (string, required)
