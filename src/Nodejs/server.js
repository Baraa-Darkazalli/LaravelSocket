// require('dotenv').config({ path: '../../.env' });
const config = require('/config.js');
const express = require('express');
const app = express();
const { v4 : uuidv4 } = require('uuid');
// const port = process.env.SOCKET_PORT || 3030;
let mysql = require('mysql');

// Access configuration settings
const port = config.SOCKET_PORT | 3030;
const host = config.SOCKET_HOST | 'localhost';
const database = config.DB_DATABASE | 'laravel';
const user = config.DB_USERNAME | 'root';
const password = config.DB_PASSWORD | '';

// Your database credentials here same with the laravel app.
let connection = mysql.createConnection({
    host : host,
    user : user,
    password : password,
    database : database
});

// Now let's create a server that will listen to our port.
const server = app.listen(`${port}`, () => {
    console.log(`Server started on port ${port}`);
    // Connect to our database.
    connection.connect();
});

// Intialize Socket
const io = require("socket.io")(server, {
    cors : { origin : "*" }
});

// Setup Socket IO.
io.on('connection', (socket) => {
    console.log('Client connected!');

    // Listener when user emits the 'fetch/students/index' event
    socket.on('fetch/students/index', (data) => {
        console.log('fetch');

        // Index students from the database
        connection.query('SELECT * FROM students', (error, results) => {
            if (error) throw error;
            // Success
            // Emit the students to the client who requested the data
            io.emit('load/students/index', results);
        });
    });

    socket.on('disconnect', () => {
        console.log('Client disconnected!');
    });
});
