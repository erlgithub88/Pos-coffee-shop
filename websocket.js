import { Server } from "socket.io";

export function initializeWebSocket(server) {
    const io = new Server(server, {
        cors: {
            origin: true,
            methods: ["*"],
        },
    });

    const clientConnections = {};

    io.on("connection", (socket) => {
        console.log("A client connected:", socket.id);

        // Simpan koneksi client
        socket.on("userConnected", (userId) => {
            clientConnections[userId] = socket.id;
            console.log(`User ${userId} connected with socket ID: ${socket.id}`);
        });

        // Kirim data ke client
        socket.on("updateChart", (data) => {
            const targetSocketId = clientConnections[data.userId];
            if (targetSocketId) {
                io.to(targetSocketId).emit("chartData", data);
            } else {
                console.log(`User with ID ${data.userId} not found`);
            }
        });

        // Handle disconnect
        socket.on("disconnect", () => {
            console.log("A client disconnected:", socket.id);
            for (const [userId, socketId] of Object.entries(clientConnections)) {
                if (socketId === socket.id) {
                    delete clientConnections[userId];
                    console.log(`User ${userId} disconnected`);
                    break;
                }
            }
        });
    });

    return io;
}