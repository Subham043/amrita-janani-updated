require('dotenv').config()
const express = require('express');
const helmet = require("helmet");
const hpp = require('hpp');
const cors = require('cors');
global.crypto = require('crypto')

const app = express();
app.use(express.json()); //Used to parse JSON bodies
app.use(express.urlencoded({ extended: false })); //Parse URL-encoded bodies
app.use(helmet({
  crossOriginResourcePolicy: false,
}));
app.use(hpp());
const corsOptions = {
    origin: function (origin, callback) {
        callback(null, true)
    }
}
app.use(cors(corsOptions))

const createSignature = (req) => {
    const url = req.protocol + '://' + req.get('host') + req.originalUrl;
    const payload = req.method.toUpperCase() + '&' + url + '&' + req.buf;
    const hmac = crypto.createHmac('sha1', process.env.WEBHOOK_SECRET);
    hmac.update(Buffer.from(payload), 'utf-8');
    return hmac.digest('hex');
};
const verifyWebhookSignature = (req, res, next) => {
    const signature = createSignature(req);
    if (!req.headers["x-artibot-signature"] || signature !== req.headers["x-artibot-signature"].toLowerCase()) {
        return res.status(403).json({
            message: "invalid signature"
        });
    }
    next();
};

app.get('/artibot/test', async function (req, res) {
    console.log(req);
    return res.status(200).json({
        message: "working"
    });
});

app.post('/artibot/webhook', verifyWebhookSignature, async function (req, res) {
    console.log(req);
    return res.status(200).json({
        message: "working"
    });
});

module.exports = app;