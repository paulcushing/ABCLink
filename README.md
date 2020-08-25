# ABCL.ink

## Description

This is a simple service to create a/b testing links, and provide the link as a shortlink. I've
combined a PHP API and React front end into one repo, which has it benefits, but also makes local
development a little more cumbersome. I'm working on making the local development easier, but it
works enough to get started.

## Local Setup

For local development, I'm using MAMP to run a local Apache for serving the PHP files, and the React
front end. I started with create-react-app, which includes the `npm run start` for development, but
that doesn't allow PHP execution.

1. Clone the repo.

2. Run `npm install` in the base folder.

3. Run `php composer.phar` in the `/php-api/` folder.

4. Create a MySQL db and provide the credentials in a `.env.local` file within `/php-api/`.
   (instructions to come...)

5. Run a server that serves PHP files locally (MAMP, Apache, or Nginx) and make the `/build` folder
   the home document folder.

6. Run `npm run build` to create the `/build` folder, then run `./copyapi.sh` to put the api in
   place.

## Use

The user can enter URLs that they want to test against. The page default allows 2 URLs, and clicking
the "Add More Destinations" button creates more fields for URLs. Once the URLs are there, clicking
"Create Short Link" stores the destinations and creates a corresponding shortlink, and presents that
to the user for use.

When a visitor hits one of the shortened links, the db is checked for that link. If it exists, the
page is forwarded to a random one of the options.

It's currently hosted at [https://abcl.ink](https://abcl.ink) if you'd like to demo what's there so
far.

## To-Do

-   Streamline local development.
-   Add better field sanitization and error checking.
-   Add React Router for...
-   Add user registration and login.
-   Add tracking and stats reports.
-   Add more control over how destinations are chosen. ( percentage based, based on action
    completions )

### Thanks

This was a 2 day personal sprint to brush up on React and PHP since I've been doing a lot of Java at
work lately and needed to do something different for a bit. Any feedback is welcome. _Thanks_
