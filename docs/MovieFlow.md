## Movie flow
Movie is core Model used for storing all Movies
- With Genres
- With Performers

### Flow
- Implement interface `MovieInterface`
- Use trait `HasMovieObserver`
- Whenever model is created it'll be observer `created` in `app/Core/Services/Movie/Observers/MovieObserver.php`
- `Movie` will be created with `Genres` & `Performers`
- Finally, will trigger event `MovieCreated`
### Wordpress Post
- After `Movie` is created will create new `WordPressPost` with `pending` state
- Another cron job will get pending `WordPressPost` and send email
- After email sent succeed will update `WordPressPost` with `completed` state

### MovieService
Used to create new Movie
- Receive MovieInterface implementation for creating
