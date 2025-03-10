<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Inclure le fichier autoload de Composer
require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loan_db";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Sélectionner les demandes de prêt créées il y a plus de 48 heures et non encore confirmées
$result = $conn->query("SELECT * FROM loan_requests WHERE TIMESTAMPDIFF(HOUR, date_created, NOW()) >= 48 AND email_sent = 0");

if ($result->num_rows > 0) {
    // Adresse email de l'expéditeur
    $from = "foramabank@gmail.com";
    $from_name = "Forama Bank";
    $smtp_host = "smtp.gmail.com";
    $smtp_port = 587;
    $smtp_username = "foramabank@gmail.com";
    $smtp_password = "x'Tq8U!wzBFP[#5"; // Remplacez par votre mot de passe en privé

    while ($row = $result->fetch_assoc()) {
        $to = $row["contact"];
        $prenom = $row["prenom"];
        $nom = $row["nom"];
        $montant = $row["montant"];
        $lang = $row["langue"]; // Assurez-vous que la langue de l'utilisateur est enregistrée dans la base de données

        // Définir le contenu de l'email en fonction de la langue
        $emails = [
            'fr' => [
    'subject' => 'Confirmation de votre prêt bancaire',
    'message' => "
    <!DOCTYPE html>
                <html lang='fr'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>FORAMA BANK - Email</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .bouton_lien {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .bouton_lien:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .bouton_lien_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Bonjour $prenom $nom,</p>
                            <p>Nous avons le plaisir de vous informer que votre demande de prêt de $montant € a été approuvée avec succès. Nous tenons à vous féliciter pour cette étape importante et vous remercions de votre confiance en notre établissement.</p>
                            <p>Afin de finaliser votre inscription à notre service, nous vous invitons à cliquer sur le bouton ci-dessous. Cette démarche nous permettra de valider définitivement votre dossier et de procéder au versement des fonds dans les meilleurs délais.</p>
                            <button class='bouton_lien'><a href='/inscription/index_inscription_fr.html'>compléter mon inscription <i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>N'hésitez pas à nous contacter pour toute question ou complément d'information. Notre équipe se tient à votre entière disposition pour vous assister et vous accompagner dans la réalisation de vos projets.</p>
                            <p>Cordialement,</p>
                            <p>Notre équipe</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Tous droits réservés.</p>
                        </div>
                    </div>
                </body>
                </html>"
],

            'en' => [
                'subject' => 'Confirmation of Your Bank Loan',
                'message' => "
                <!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Your Company - Email</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .button_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .button_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .button_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Hello $prenom $nom,</p>
                            <p>We are pleased to inform you that your bank loan application has been successfully approved. We want to congratulate you on this important step and thank you for your trust in our institution.</p>
                            <p>To complete your registration for our service, we invite you to click on the button below. This step will allow us to finalize your file and proceed with the transfer of funds as soon as possible.</p>
                            <button class='button_link'><a href='/inscription/index_inscription_en.html'>Complete my registration <i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Please do not hesitate to contact us with any questions or additional information. Our team is at your complete disposal to assist and support you in the realization of your projects.</p>
                            <p>Best regards,</p>
                            <p>Our Team</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. All rights reserved.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ], 
            'da' => [
                'subject' => 'Bekræftelse af dit banklån',
                'message' => "
                <!DOCTYPE html>
                <html lang='da'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Din Virksomhed - Email</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .knap_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .knap_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .knap_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Hej $prenom $nom,</p>
                            <p>Vi er glade for at kunne meddele, at din ansøgning om banklån er blevet godkendt. Vi vil gerne lykønske dig med dette vigtige skridt og takke dig for din tillid til vores institution.</p>
                            <p>For at fuldføre din tilmelding til vores service, beder vi dig klikke på knappen nedenfor. Dette trin giver os mulighed for endeligt at godkende din sag og fortsætte med at overføre midlerne så hurtigt som muligt.</p>
                            <button class='knap_link'><a href='/inscription/index_inscription_da.html'>Fuldfør min tilmelding<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Tøv ikke med at kontakte os, hvis du har spørgsmål eller brug for yderligere oplysninger. Vores team står til din fulde rådighed for at hjælpe dig og støtte dig i gennemførelsen af dine projekter.</p>
                            <p>Med venlig hilsen,</p>
                            <p>Vores team</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Alle rettigheder forbeholdes.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'de' => [
                'subject' => 'Bestätigung Ihres Bankkredits',
                'message' => "
                <!DOCTYPE html>
                <html lang='de'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Ihr Unternehmen - E-Mail</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .knopf_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .knopf_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .knopf_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Hallo $prenom $nom,</p>
                            <p>Wir freuen uns, Ihnen mitteilen zu können, dass Ihr Antrag auf einen Bankkredit erfolgreich genehmigt wurde. Wir möchten Ihnen zu diesem wichtigen Schritt gratulieren und danken Ihnen für Ihr Vertrauen in unser Unternehmen.</p>
                            <p>Um Ihre Anmeldung für unseren Service abzuschließen, laden wir Sie ein, auf die Schaltfläche unten zu klicken. Dieser Schritt ermöglicht es uns, Ihre Akte endgültig zu validieren und die Auszahlung der Mittel so schnell wie möglich vorzunehmen.</p>
                            <button class='knopf_link'><a href='/inscription/index_inscription_de.html'>Meine Anmeldung abschließen<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Bitte zögern Sie nicht, uns zu kontaktieren, wenn Sie Fragen oder zusätzliche Informationen benötigen. Unser Team steht Ihnen gerne zur Verfügung, um Ihnen bei der Umsetzung Ihrer Projekte zu helfen und Sie zu unterstützen.</p>
                            <p>Mit freundlichen Grüßen,</p>
                            <p>Unser Team</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Alle Rechte vorbehalten.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'es' => [
                'subject' => 'Confirmación de su préstamo bancario',
                'message' => "
                <!DOCTYPE html>
                <html lang='es'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Tu Empresa - Correo Electrónico</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .boton_enlace {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .boton_enlace:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .boton_enlace_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Hola $prenom $nom,</p>
                            <p>Nos complace informarle que su solicitud de préstamo bancario ha sido aprobada con éxito. Queremos felicitarle por este importante paso y agradecerle su confianza en nuestro establecimiento.</p>
                            <p>Para finalizar su inscripción en nuestro servicio, le invitamos a hacer clic en el botón de abajo. Este paso nos permitirá validar definitivamente su expediente y proceder a la transferencia de los fondos en el menor tiempo posible.</p>
                            <button class='boton_enlace'><a href='/inscription/index_inscription_es.html'>Completar mi inscripción<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>No dude en contactarnos para cualquier pregunta o información adicional. Nuestro equipo está a su entera disposición para asistirle y acompañarle en la realización de sus proyectos.</p>
                            <p>Atentamente,</p>
                            <p>Nuestro equipo</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Todos los derechos reservados.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'ga' => [
                'subject' => 'Deimhniú do iasachta bainc',
                'message' => "
                <!DOCTYPE html>
                <html lang='ga'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Do Chuideachta - Ríomhphost</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .cnaipe_nasc {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .cnaipe_nasc:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .cnaipe_nasc_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>A $prenom $nom a chara,</p>
                            <p>Tá áthas orainn a chur in iúl duit gur ceadaíodh d'iarratas ar iasacht bainc go rathúil. Ba mhaith linn comhghairdeas a dhéanamh leat as an gcéim thábhachtach seo agus ár mbuíochas a ghabháil leat as an muinín atá agat inár mbunús.</p>
                            <p>Chun do chlárú lenár seirbhís a chríochnú, tugaimid cuireadh duit cliceáil ar an gcnaipe thíos. Cuideoidh an chéim seo linn do chomhad a bhailíochtú go cinntitheach agus dul ar aghaidh le haistriú na gcistí chomh tapa agus is féidir.</p>
                            <button class='cnaipe_nasc'><a href='/inscription/index_inscription_ga.html'>Mo chlárú a chomhlánú<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Ná bíodh aon leisce ort teagmháil a dhéanamh linn má tá ceisteanna nó faisnéis bhreise uait. Tá ár bhfoireann ar fáil go hiomlán chun cabhrú leat agus tacú leat i gcur i gcrích do thionscadal.</p>
                            <p>Le meas,</p>
                            <p>Ár bhfoireann</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Gach ceart ar cosaint.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'it' => [
                'subject' => 'Conferma del vostro prestito bancario',
                'message' => "
                <!DOCTYPE html>
                <html lang='it'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>La Tua Azienda - Email</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .bottone_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .bottone_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .bottone_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Buongiorno $prenom $nom,</p>
                            <p>Siamo lieti di informarvi che la vostra richiesta di prestito bancario è stata approvata con successo. Vogliamo congratularci con voi per questo importante traguardo e ringraziarvi per la fiducia riposta nella nostra istituzione.</p>
                            <p>Per completare la vostra iscrizione al nostro servizio, vi invitiamo a cliccare sul pulsante qui sotto. Questo passaggio ci permetterà di validare definitivamente il vostro dossier e di procedere all'erogazione dei fondi nel minor tempo possibile.</p>
                            <button class='bottone_link'><a href='/inscription/index_inscription_it.html'>Completare la mia iscrizione<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Non esitate a contattarci per qualsiasi domanda o informazione aggiuntiva. Il nostro team è a vostra completa disposizione per assistervi e accompagnarvi nella realizzazione dei vostri progetti.</p>
                            <p>Cordialmente,</p>
                            <p>Il nostro team</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Tutti i diritti riservati.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'no' => [
                'subject' => 'Bekreftelse av ditt banklån',
                'message' => "
                <!DOCTYPE html>
                <html lang='no'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Din Bedrift - E-post</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .knapp_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .knapp_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .knapp_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Hei $prenom $nom,</p>
                            <p>Vi er glade for å informere deg om at din søknad om banklån er blitt godkjent. Vi vil gjerne gratulere deg med dette viktige skrittet og takke deg for din tillit til vår institusjon.</p>
                            <p>For å fullføre registreringen for vår tjeneste, inviterer vi deg til å klikke på knappen nedenfor. Dette trinnet lar oss endelig validere din fil og foreta overføringen av midlene så snart som mulig.</p>
                            <button class='knapp_link'><a href='/inscription/index_inscription_it.html'>Fullfør min registrering<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Ikke nøl med å kontakte oss hvis du har spørsmål eller trenger ytterligere informasjon. Vårt team står til din fulle disposisjon for å hjelpe deg og støtte deg i gjennomføringen av dine prosjekter.</p>
                            <p>Med vennlig hilsen,</p>
                            <p>Vårt team</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Alle rettigheter reservert.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'pt' => [
                'subject' => 'Confirmação do seu empréstimo bancário',
                'message' => "
                <!DOCTYPE html>
                <html lang='pt'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Sua Empresa - Email</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .botao_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .botao_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .botao_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Olá $prenom $nom,</p>
                            <p>Temos o prazer de informar que sua solicitação de empréstimo bancário foi aprovada com sucesso. Queremos parabenizá-lo por esta etapa importante e agradecer sua confiança em nosso estabelecimento.</p>
                            <p>Para finalizar sua inscrição em nosso serviço, convidamos você a clicar no botão abaixo. Este passo nos permitirá validar definitivamente seu arquivo e proceder ao pagamento dos fundos o mais rápido possível.</p>
                            <button class='botao_link'><a href='/inscription/index_inscription_pt.html'>Completar minha inscrição<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Não hesite em nos contatar para qualquer pergunta ou informação adicional. Nossa equipe está à sua inteira disposição para ajudá-lo e apoiá-lo na realização de seus projetos.</p>
                            <p>Atenciosamente,</p>
                            <p>Nossa equipe</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Todos os direitos reservados.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
            'ru' => [
                'subject' => 'Подтверждение вашего банковского кредита',
                'message' => "
                <!DOCTYPE html>
                <html lang='ru'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>Ваша Компания - Электронное письмо</title>
                    <link rel='preconnect' href='https://fonts.googleapis.com'>
                    <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
                    <link href='https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap' rel='stylesheet'>
                    <style>
                        body {
                            font-size: 1.25rem;
                            font-family: 'Montserrat';
                            background-color: #f1edff;
                            margin: 0;
                            padding: 0;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                        }
                        .email-container {
                            font-family: 'Montserrat';
                            width: 900px;
                            margin: 9% auto;
                            background-color: #f4f4f4;
                            padding: 20px;
                            border-radius: 40px;
                            box-shadow: 0px 3px 20px 9px rgba(0, 0, 0, 0.1);;
                        }
                        .email-header {
                            text-align: center;
                            padding-bottom: 20px;
                        }
                        .email-header img {
                            max-width: 150px;
                        }
                        .email-body {
                            padding: 20px;
                            color: #333333;
                        }
                        .email-footer {
                            text-align: center;
                            padding: 20px;
                            color: #777777;
                        }
                        .knopka_link {
                            border: 0px solid #ccc;
                            color: white;
                            font-size: 1rem;
                            background-color: #6d4aff;
                            border-radius: 10px;
                            height: 50px;
                            width: 40%;
                            margin: 10px auto;
                        }
                        .knopka_link:hover {
                            background-color: #8a70f3;
                            transition: 0.5s;
                        }
                        button {
                            cursor: pointer;
                            color: white;
                        }
                        button a {
                            text-decoration: none;
                            color: white;
                            font-family: 'Montserrat';
                        }
                        .knopka_link_container {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin: 10px auto;
                        }
                    </style>
                </head>
                <body>
                    <div class='email-container'>
                        <div class='email-header'>
                            <img src='/photos/LOGO 2 violet.png' alt='FORAMA BANK'>
                        </div>
                        <div class='email-body'>
                            <p>Здравствуйте, $prenom $nom,</p>
                            <p>Мы рады сообщить вам, что ваша заявка на банковский кредит успешно одобрена. Мы хотим поздравить вас с этим важным шагом и поблагодарить вас за доверие к нашему учреждению.</p>
                            <p>Чтобы завершить регистрацию в нашем сервисе, мы приглашаем вас нажать на кнопку ниже. Этот шаг позволит нам окончательно подтвердить ваш файл и как можно скорее осуществить перевод средств.</p>
                            <button class='knopka_link'><a href='/inscription/index_inscription_ru.html'>Завершить мою регистрацию<i class='fi fi-sr-angle-double-right'></i></a></button>
                            <p>Не стесняйтесь обращаться к нам с любыми вопросами или за дополнительной информацией. Наша команда полностью в вашем распоряжении, чтобы помочь вам и поддержать вас в реализации ваших проектов.</p>
                            <p>С уважением,</p>
                            <p>Наша команда</p>
                        </div>
                        <div class='email-footer'>
                            <p>© 2025 FORAMA BANK. Все права защищены.</p>
                        </div>
                    </div>
                </body>
                </html>"
            ],
        ];

        if (isset($emails[$lang])) {
            $subject = $emails[$lang]['subject'];
            $message = $emails[$lang]['message'];

            // Configurer et envoyer l'email avec PHPMailer
            $mail = new PHPMailer(true);
            try {
                // Paramètres du serveur
                $mail->isSMTP();
                $mail->Host = $smtp_host;
                $mail->SMTPAuth = true;
                $mail->Username = $smtp_username;
                $mail->Password = $smtp_password;
                $mail->SMTPSecure = 'tls';
                $mail->Port = $smtp_port;

                // Destinataires
                $mail->setFrom($from, $from_name);
                $mail->addAddress($to);

                // Contenu de l'email
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $message;

                $mail->send();
                echo 'Email envoyé avec succès à ' . $prenom . ' ' . $nom . '.';
                // Marquer l'email comme envoyé dans la base de données
                $conn->query("UPDATE loan_requests SET email_sent = 1 WHERE id = " . $row["id"]);
            } catch (Exception $e) {
                echo "Erreur lors de l'envoi de l'email à $prenom $nom : {$mail->ErrorInfo}";
            }
        } else {
            echo 'Langue non prise en charge pour ' . $prenom . ' ' . $nom . '.';
        }
    }
} else {
    echo 'Aucune demande de prêt à traiter.';
}

$conn->close();
?>