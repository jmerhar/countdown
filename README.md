# Countdown Solver

This web application is a solver for the popular British gameshow *Countdown*. It can find solutions for both the **Letters game** (finding the longest possible word from a selection of 9 letters) and the **Numbers game** (reaching a target number using 6 chosen numbers and basic arithmetic).

This project was built as a proof-of-concept to handle CPU-intensive tasks initiated from a web browser without blocking the user interface, providing real-time results.

## How It Works

The application uses a client-server architecture with WebSockets for real-time, bidirectional communication.

1.  **Frontend**: The user interacts with a web page built with HTML, CSS, and jQuery. When the user selects their letters or numbers, the browser sends a request to the backend via a WebSocket connection.
2.  **WebSocket Server**: A PHP server built with the [Ratchet](http://socketo.me/) library listens for incoming WebSocket connections. When it receives a request to solve a game, it doesn't perform the calculation itself. Instead, it delegates the task to a background process.
3.  **Background Worker**: The server spawns a new PHP worker process (`worker.php`) for each calculation. This prevents the main server from getting blocked by the CPU-intensive anagramming or arithmetic calculations.
4.  **Real-time Results**: The worker process opens its own WebSocket connection back to the server to stream results and progress updates as they are found. The server then relays these messages to the correct user's web browser, which dynamically updates the page to show the answers in real time.

This architecture ensures the UI remains responsive and provides immediate feedback to the user.

## Features

* **Numbers Game Solver**: Finds exact solutions to the numbers game, or the closest possible answers if an exact solution isn't possible.
* **Letters Game Solver**: Finds all possible words of 5 letters or more from the given selection, sorted by length and quality.
* **Real-time Updates**: Solutions are displayed as soon as they are found by the background worker.
* **Progress Bar**: A progress bar shows the status of the ongoing calculation.
* **Cancellable Tasks**: Users can stop a running calculation at any time.

## Tech Stack

* **Backend**: PHP, [Ratchet](http://socketo.me/) (WebSocket library), SQLite
* **Frontend**: HTML, CSS, JavaScript, jQuery, Foundation Framework

## Installation and Setup

To run this project locally, you will need PHP. All dependencies are included in the repository.

> **Important Compatibility Note:** This project was originally written for PHP 5 and relies on outdated libraries. It has been tested and works correctly with PHP versions up to **7.4**. It will **not** run on PHP 8.0 or newer due to breaking changes in the language.

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/jmerhar/countdown.git
    cd countdown
    ```

2.  **Run the WebSocket Server:**
    Open a terminal and run the following command. The server will start listening for connections on port 8142.
    ```bash
    php bin/server.php
    ```

3.  **Serve the Web Files:**
    You need to serve the `web` directory using a local web server (like Apache, Nginx, or the built-in PHP server). For example, using the PHP server:
    ```bash
    php -S localhost:8000 -t web
    ```

4.  **Open the Application:**
    Navigate to `http://localhost:8000` in your web browser.
