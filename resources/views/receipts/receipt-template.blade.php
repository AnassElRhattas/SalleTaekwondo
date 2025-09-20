<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
            color: #333;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header .subtitle {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .receipt-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .label {
            font-weight: bold;
            color: #495057;
        }
        .value {
            color: #212529;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
        }
        .payment-status {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .footer .thank-you {
            font-size: 16px;
            color: #495057;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .qr-placeholder {
            width: 80px;
            height: 80px;
            background: #e9ecef;
            border-radius: 8px;
            margin: 0 auto 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>🥋 CLUB DE TAEKWONDO</h1>
            <div class="subtitle">REÇU DE PAIEMENT OFFICIEL</div>
        </div>
        
        <div class="content">
            <div class="receipt-info">
                <div class="info-row">
                    <span class="label">Numéro de reçu:</span>
                    <span class="value">{{ $receiptNumber }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date d'émission:</span>
                    <span class="value">{{ $currentDate }}</span>
                </div>
            </div>

            <div class="section-title">👤 Informations Client</div>
            <div class="receipt-info">
                <div class="info-row">
                    <span class="label">Nom complet:</span>
                    <span class="value">{{ $client->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Téléphone:</span>
                    <span class="value">{{ $client->phone }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Groupe:</span>
                    <span class="value">{{ $client->group }}</span>
                </div>
            </div>

            <div class="section-title">💰 Détails du Paiement</div>
            <div class="receipt-info">
                <div class="info-row">
                    <span class="label">Date de paiement:</span>
                    <span class="value">{{ $paymentDate }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Type de paiement:</span>
                    <span class="value">Abonnement mensuel</span>
                </div>
                <div class="info-row">
                    <span class="label">Prochaine échéance:</span>
                    <span class="value">{{ $nextPaymentDate }}</span>
                </div>
            </div>

            <div class="payment-status">
                ✅ PAIEMENT CONFIRMÉ ET VALIDÉ
            </div>
        </div>

        <div class="footer">
            <div class="thank-you">Merci pour votre confiance !</div>
            <div>Continuez à vous entraîner dur ! 💪</div>
            <div style="margin-top: 15px;">
                Ce reçu confirme le paiement de votre abonnement mensuel.<br>
                Conservez-le comme preuve de paiement.
            </div>
            <div style="margin-top: 15px; font-size: 12px;">
                📞 Contact : Club de Taekwondo<br>
                📧 Pour toute question, contactez-nous.
            </div>
        </div>
    </div>
</body>
</html>