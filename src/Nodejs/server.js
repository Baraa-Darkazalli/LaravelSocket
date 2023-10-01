require('dotenv').config({ path: '../../.env' });
const express = require('express');
const app = express();
const { v4 : uuidv4 } = require('uuid');
const port = process.env.SOCKET_PORT || 3030;
let mysql = require('mysql');

// Your database credentials here same with the laravel app.
let connection = mysql.createConnection({
    host : process.env.SOCKET_HOST || 'localhost',
    user : process.env.DB_USERNAME || 'root',
    password : process.env.DB_PASSWORD || '',
    database : process.env.DB_DATABASE || 'laravel',
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
