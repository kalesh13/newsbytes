### URL Shortner

Since the JD asked for good understanding of Laravel, this test is built using Laravel.

There was no sophisticated architecture involved in the making of this application. Backend uses simple Laravel MVC application and frontend uses VueJS.

In order to avoid code repertition, I usually avoid fat controller functions and add a separate business layer. Controller functions access the business layer and performs tasks. So controller functions are always very thin. This additional business layer allows me to do the same task that a controller does internally, if the need arises.

In this application `Services\UrlShortnerService` does the job of a creation and retrieval of UrlMapping - which is the core Model.

The `UrlShortnerService` creates a Base62 encoded key for the `id` of each mapping entry and stores it in the database. The tiny url looks like this `127.0.0.1:8000/marketing/aQbt9T` where `aQbt9T` is the Base62 encoded key in this example.

When a request is made to the tiny-url, application decodes the `id` and retrieves the original url. We update the open count and simply perform a `302` redirect to the original url.

The application uses `https://github.com/tuupola/base62` for encoding and decoding. If decoding becomes a heavy operation and blocking the scalability, we could add an index on the `key` column in the database and query the database directly for the mapping.

The service-interface mapping is done on the `Providers\AppServiceProvider`.

In order to make this application easy to run and test, I've used SQLite as the database and added the file to the git.