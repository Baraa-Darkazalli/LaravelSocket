import * as config from './config.js';
import express from 'express';
import { v4 as uuidv4 } from 'uuid';
import mysql from 'mysql';

const port = config.SOCKET_PORT;

// Your database credentials here, same as with the Laravel app.
const connection = mysql.createConnection({
    host: config.SOCKET_HOST,
    user: config.DB_USERNAME,
    password: config.DB_PASSWORD,
    database: config.DB_DATABASE,
});

// Now let's create a server that will listen to our port.
const app = express();
const server = app.listen(port, () => {
    console.log(`Server started on port ${port}`);
    // Connect to our database.
    connection.connect();
});

const handleSocketEvents = require('./events.js');


// Intialize Socket
const io = require("socket.io")(server, {
    cors : { origin : "*" }
});

// Setup Socket IO.
io.on('connection', (socket) => {
    console.log('Client connected!');

    handleSocketEvents(socket, connection, io);

    // // Listener when user emits the 'fetch/students/index' event
    // socket.on('fetch/students/index', (data) => {
    //     console.log('fetch');

    //     // Index students from the database
    //     connection.query('SELECT * FROM students', (error, results) => {
    //         if (error) throw error;
    //         // Success
    //         // Emit the students to the client who requested the data
    //         io.emit('load/students/index', results);
    //     });
    // });

    socket.on('disconnect', () => {
        console.log('Client disconnected!');
    });
});
