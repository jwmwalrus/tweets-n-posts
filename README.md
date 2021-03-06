Tweets 'n Posts
========================

A place to post your thoughts and check some tweets.

## Pre-requistes
* Composer: A `composer` package is provided by Apt.
* OpenSSL: An `openssl` package is provided by Apt.
* Node.js: The recommended instructions can be found [here](https://github.com/nodesource/distributions#debinstall).

## Installation

### Environment

Create a `.env.local` file from the provided `.env` file.

Make sure the `DATABASE_URL` variable is set to the right values.

### Setup

From a command line, go to the root of the project and run the following:

```
./setup.sh all
```

The script will take care of installing dependencies, creating and populating the database, 

### JWT
Run the following to generate appropriate keys for JWT.

```
./setup.sh genrsa
```

For demo purposes, the passphrase is **tweetsnposts**.

## Seed

The generated users have the following usernames:
* user1
* user2
* user3
* user4
* user5. I

In all the cases the password is `jobsity`.

## Test

Create a `.env.test.local` file from the provided `.env.test` file.

Start a debug/dev server with:
```
bin/console server:start localhost:8000 --env=test
```

Run the tests with:

```
bin/codecept run functional,api
```

