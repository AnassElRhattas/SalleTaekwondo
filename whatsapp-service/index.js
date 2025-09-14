const express = require('express');
const makeWASocket = require('@whiskeysockets/baileys').default;
const { useMultiFileAuthState, fetchLatestBaileysVersion, DisconnectReason } = require('@whiskeysockets/baileys');
const { Boom } = require('@hapi/boom');
const P = require('pino');
const qrcode = require('qrcode-terminal');
const fs = require('fs');
const path = require('path');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

let sock = null;
let isConnected = false;
let qrCodeGenerated = false;

// Fonction pour démarrer la connexion WhatsApp
async function startWhatsApp() {
    try {
        const { state, saveCreds } = await useMultiFileAuthState('./auth');
        const { version } = await fetchLatestBaileysVersion();
        
        sock = makeWASocket({
            version,
            auth: state,
            logger: P({ level: 'silent' }),
            printQRInTerminal: false,
            browser: ['Taekwondo Club', 'Desktop', '1.0.0']
        });

        sock.ev.on('creds.update', saveCreds);
        
        sock.ev.on('connection.update', ({ connection, lastDisconnect, qr }) => {
            if (qr && !qrCodeGenerated) {
                console.log('\n=== QR CODE POUR CONNEXION WHATSAPP ===');
                qrcode.generate(qr, { small: true });
                console.log('Scannez ce QR code avec votre téléphone WhatsApp');
                console.log('Allez dans WhatsApp > Appareils liés > Lier un appareil');
                qrCodeGenerated = true;
            }
            
            if (connection === 'close') {
                const shouldReconnect = lastDisconnect?.error?.output?.statusCode !== DisconnectReason.loggedOut;
                console.log('Connexion fermée. Reconnexion:', shouldReconnect);
                isConnected = false;
                qrCodeGenerated = false;
                
                if (shouldReconnect) {
                    setTimeout(() => startWhatsApp(), 5000);
                }
            } else if (connection === 'open') {
                console.log('✅ Connecté à WhatsApp!');
                isConnected = true;
                qrCodeGenerated = false;
            }
        });
        
        sock.ev.on('messages.upsert', async ({ messages }) => {
            const msg = messages[0];
            if (!msg.key.fromMe && msg.message?.conversation) {
                console.log(`📩 Message reçu de ${msg.key.remoteJid}: ${msg.message.conversation}`);
            }
        });
        
    } catch (error) {
        console.error('Erreur lors du démarrage WhatsApp:', error);
        setTimeout(() => startWhatsApp(), 10000);
    }
}

// Fonction pour envoyer un message
async function sendMessage(number, message) {
    if (!sock || !isConnected) {
        throw new Error('WhatsApp n\'est pas connecté');
    }
    
    try {
        // Formatage du numéro (ajouter @s.whatsapp.net si nécessaire)
        const formattedNumber = number.includes('@') ? number : `${number}@s.whatsapp.net`;
        
        await sock.sendMessage(formattedNumber, { text: message });
        console.log(`✅ Message envoyé à ${formattedNumber}`);
        return { success: true, message: 'Message envoyé avec succès' };
    } catch (error) {
        console.error('Erreur lors de l\'envoi du message:', error);
        throw error;
    }
}

// Routes API
app.get('/status', (req, res) => {
    res.json({
        connected: isConnected,
        qrRequired: !isConnected && !qrCodeGenerated
    });
});

app.post('/send-message', async (req, res) => {
    try {
        const { number, message } = req.body;
        
        if (!number || !message) {
            return res.status(400).json({
                success: false,
                error: 'Numéro et message requis'
            });
        }
        
        const result = await sendMessage(number, message);
        res.json(result);
    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

app.post('/send-bulk', async (req, res) => {
    try {
        const { contacts } = req.body;
        
        if (!contacts || !Array.isArray(contacts)) {
            return res.status(400).json({
                success: false,
                error: 'Liste de contacts requise'
            });
        }
        
        const results = [];
        
        for (const contact of contacts) {
            try {
                await sendMessage(contact.number, contact.message);
                results.push({
                    number: contact.number,
                    success: true
                });
                
                // Délai entre les messages pour éviter le spam
                await new Promise(resolve => setTimeout(resolve, 2000));
            } catch (error) {
                results.push({
                    number: contact.number,
                    success: false,
                    error: error.message
                });
            }
        }
        
        res.json({
            success: true,
            results
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

// Démarrage du serveur
app.listen(PORT, () => {
    console.log(`🚀 Service WhatsApp démarré sur le port ${PORT}`);
    console.log('📱 Démarrage de la connexion WhatsApp...');
    startWhatsApp();
});

// Gestion propre de l'arrêt
process.on('SIGINT', () => {
    console.log('\n🛑 Arrêt du service WhatsApp...');
    if (sock) {
        sock.end();
    }
    process.exit(0);
});