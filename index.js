import express from "express";
import cors from "cors";
import http from "http";
import { initializeWebSocket } from "./websocket.js";

const port = 9000;
const app = express();
const server = http.createServer(app);

app.use(cors({ credentials: true, origin: true }), express.json());

initializeWebSocket(server);

server.listen(port, () => {
    console.log(`Server started at: http://0.0.0.0:${port}`);
});