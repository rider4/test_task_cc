### Introduction

This project is a test assignment and demonstrates some of the skills I possess. It fully covers the business task, including tests (although, as the task mentioned not to perform functional tests, the Controller is not covered; in a real-world scenario, it should be covered as well). The code is not perfect and has room for improvement, but I suggest discussing these in person to sensibly economize the time spent on the test assignment. Some points I would further improve or replace include:

- **Validation of incoming data in the controller**: This is usually done with separate validators, not a private method, but it can depend on the project.
- **Infrastructure**: It should be deployed using Docker. I used Symfony's built-in server with `symfony local:server:start`. In real-world conditions, Docker should be used, but it requires a bit more time, and I didn't have a simple setup handy without lots of auxiliary logic.

### Requirements and Installation

- PHP 8.2 must be installed.
- Symfony CLI must be installed on the host machine (instructions here: [Symfony CLI Installation](https://symfony.com/download#step-1-install-symfony-cli)).
- Unpack the archive.
- Check the environment using the `make check` command. If you see the following output, you are ready to run the application:
    ```
    [OK]
    Your system is ready to run Symfony projects
    ```

### Usage

The project includes a Makefile with three recipes:

- `make check` - Checks the environment.
- `make up` - Installs and runs the application (the server runs without detaching, all logs will be visible in the console, and the server will stop when the session is closed).
- `make down` - Shuts down the running server and deletes the database.
- `make run-test` - Runs all tests.

### Note

I hope you enjoy it, and we can discuss all the nuances during the tech review.
