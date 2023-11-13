<!DOCTYPE html>
<html>

<head>
    <title>Réinitialisation de mot de passe</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css">
        body {
            background-color: #f6f6f6;
            font-family: sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
            width: 100%;
        }

        .header {
            background-color: #2251a0;
            color: #222222;
            padding: 20px 0;
            text-align: center;
        }

        .header img {
            height: 20%;
            width: 20%;
        }

        .content {
            background-color: #ffffff;
            padding: 20px;
        }

        .content p {
            margin: 0 0 20px;
            padding: 0;
        }

        .content a {
            color: #4169e1;
            text-decoration: none;
        }

        .content a:hover {
            text-decoration: none;
        }

        .footer {
            background-color: #f6f6f6;
            color: #222222;
            padding: 20px 0;
            text-align: center;
        }

        .footer p {
            margin: 0;
            padding: 0;
        }

        span {
            font-size: 40px;
        }


        /* Style du bouton personnalisé */
        .custom-button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            color: #222222;
            border: solid 2px #4169e1;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-bottom: 10px;
            text-decoration: none;
        }

        /* Style du bouton personnalisé au survol */
        .custom-button:hover {
            background-color: #0056b3;
            color: #f6f6f6;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 0
        }

        strong {
            color: #2251a0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://centre.academy-tutoriels.com/pluginfile.php/1/theme_moove/logo/1692166718/logo-font-transparant-les-tutoriels.png" alt="Logo" width="200px">
        </div>
        <div class="content" style="text-align: center">
            <h1>Nouvel administrateur enregistré</h1>
            <p>Votre compte a été créé avec succès sur Les Tutoriels Pointage.</p>
            <p>Voici vos informations de connexion :</p>
            <ul>
                <li><strong>Nom et Prénoms :</strong> {{ $admin->name }}</li>
                <li><strong>Adresse e-mail :</strong> {{ $admin->email }}</li>
                <li><strong>Téléphone :</strong> {{ $admin->phone }}</li>
                <li><strong>Mot de passe temporaire :</strong> LesTutorielsAdmin</li>
            </ul>
            <p>Vous pouvez utiliser ces informations pour vous connecter à votre compte.</p>
            <p>Si vous souhaitez changer votre mot de passe, vous pouvez le faire après vous être connecté.</p>
        </div>
        <div class="footer">
            <p style="font-size: 10px; font-style: inherit">Cet e-mail a été envoyé automatiquement. Merci de ne pas y
                répondre.</p>
        </div>
    </div>

</body>

</html>
