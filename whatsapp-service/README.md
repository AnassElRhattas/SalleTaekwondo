# Service WhatsApp Automatique

Ce service utilise la bibliothèque Baileys pour envoyer des messages WhatsApp automatiquement.

## Installation

1. Naviguez vers le dossier du service :
```bash
cd whatsapp-service
```

2. Installez les dépendances :
```bash
npm install
```

## Utilisation

### Démarrage du service
```bash
npm start
```

### Première connexion
1. Lancez le service
2. Un QR code s'affichera dans le terminal
3. Ouvrez WhatsApp sur votre téléphone
4. Allez dans **Paramètres > Appareils liés > Lier un appareil**
5. Scannez le QR code

### API Endpoints

#### Vérifier le statut
```
GET http://localhost:3000/status
```

#### Envoyer un message unique
```
POST http://localhost:3000/send-message
Content-Type: application/json

{
  "number": "212600000000",
  "message": "Bonjour, votre abonnement expire bientôt!"
}
```

#### Envoyer des messages en masse
```
POST http://localhost:3000/send-bulk
Content-Type: application/json

{
  "contacts": [
    {
      "number": "212600000000",
      "message": "Bonjour Client 1, votre abonnement expire le 15/01/2025"
    },
    {
      "number": "212700000000",
      "message": "Bonjour Client 2, votre abonnement expire le 20/01/2025"
    }
  ]
}
```

## Format des numéros

- Format international sans le + : `212600000000`
- Le service ajoute automatiquement `@s.whatsapp.net`

## Sécurité

- Les informations d'authentification sont stockées dans le dossier `auth/`
- Ne partagez jamais ce dossier
- Utilisez ce service de manière responsable
- Respectez les conditions d'utilisation de WhatsApp

## Limitations

- Service non officiel (utilise WhatsApp Web)
- Délai de 2 secondes entre chaque message pour éviter le spam
- Peut être interrompu si WhatsApp met à jour son protocole

## Dépannage

### Le service ne se connecte pas
1. Vérifiez que votre téléphone est connecté à Internet
2. Assurez-vous que WhatsApp est ouvert sur votre téléphone
3. Supprimez le dossier `auth/` et reconnectez-vous

### Messages non envoyés
1. Vérifiez que le numéro est correct
2. Assurez-vous que le contact existe dans WhatsApp
3. Vérifiez les logs du service pour plus de détails