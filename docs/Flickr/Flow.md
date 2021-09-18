# Flick flow
## Contacts
- Will be created via `flickr:contacts`
  - `FlickrContactCreated` event will create 3 processes
    - STEP_PEOPLE_INFO // Get people information
    - STEP_PEOPLE_PHOTOS // Get people photos
    - STEP_PHOTOSETS_LIST // Get photosets
## People
Process model is FlickrContact
- `flickr:process-people info`
  - Process STEP_PEOPLE_INFO for fetching people information
- `flickr:process-people photos`
  - Process STEP_PEOPLE_PHOTOS for fetching all photos
## Photosets
- `flickr:photosets list`
  - Process STEP_PHOTOSETS_LIST for fetching all photosets
    - After completed will create STEP_PHOTOSETS_PHOTOS
- `flickr:photosets photos`
  - Process STEP_PHOTOSETS_PHOTOS for getting all photos of a photoset

Until now we have done all basically process
