Use `HasMovie` trait for
- Model will observer for creating Movie item
- Must be `implements MovieInterface`

HasMovie will process
- `boot` for observer registering
- `initialize` for merging fillable & casts
  - `dvd_id` is **uniqued** 
  - `content_id` came from R18

`Movie` created will trigger `MovieCreated`
- Than create `MovieIndex` item
