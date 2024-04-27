const express = require("express");
const http = require("http");
const app = express();
const server = http.createServer(app);
const socket = require("socket.io");
const io = socket(server);
const path = require("path");

// Serve static files
app.use(express.static(path.join(__dirname, 'call')));

// Handle GET request for the call page
app.get('/call', (req, res) => {
    res.sendFile(path.join(__dirname, 'call', 'index.html'));
});

// Handle socket.io events
io.on('connection', (socket) => {
    console.log('A user connected');

    // Handle 'join room' event
    socket.on('join room', ({ room, username }) => {
        console.log(`User ${username} joined room ${room}`);
        socket.join(room);

        // Broadcast to all participants in the room that a new user has joined
        socket.to(room).emit('user joined', socket.id, username);

        // Handle 'disconnect' event
        socket.on('disconnect', () => {
            console.log(`User ${username} left room ${room}`);
            socket.to(room).emit('user left', socket.id);
        });
    });

    // Handle 'offer' event
    socket.on('offer', (offer, targetId) => {
        console.log(`Received offer from ${socket.id} to ${targetId}`);
        socket.to(targetId).emit('offer', offer, socket.id);
    });

    // Handle 'answer' event
    socket.on('answer', (answer, targetId) => {
        console.log(`Received answer from ${socket.id} to ${targetId}`);
        socket.to(targetId).emit('answer', answer, socket.id);
    });

    // Handle 'ice-candidate' event
    socket.on('ice-candidate', (candidate, targetId) => {
        console.log(`Received ICE candidate from ${socket.id} to ${targetId}`);
        socket.to(targetId).emit('ice-candidate', candidate, socket.id);
    });
});

const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});