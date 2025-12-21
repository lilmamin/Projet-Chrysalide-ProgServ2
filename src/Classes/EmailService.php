<?php
/**
 * Utilise PHPMailer pour envoyer des emails via SMTP
 */


// Charger PHPMailer manuellement
require_once __DIR__ . '/PHPMailer/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private string $host;
    private int $port;
    private bool $authentication;
    private string $username;
    private string $password;
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->loadConfiguration();
    }

    /**
     * Charge la configuration SMTP depuis mail.ini
     */
    private function loadConfiguration(): void
    {
        $configFile = __DIR__ . '/../config/mail.ini';

        if (!file_exists($configFile)) {
            throw new Exception("Fichier de configuration mail.ini introuvable");
        }

        $config = parse_ini_file($configFile, true);

        if (!$config) {
            throw new Exception("Erreur lors de la lecture du fichier mail.ini");
        }

        $this->host = $config['host'];
        $this->port = (int) $config['port'];
        $this->authentication = filter_var($config['authentication'], FILTER_VALIDATE_BOOLEAN);
        $this->username = $config['username'] ?? '';
        $this->password = $config['password'] ?? '';
        $this->fromEmail = $config['from_email'];
        $this->fromName = $config['from_name'];
    }

    /**
     * Envoie un email de confirmation d'inscription
     */
    public function sendConfirmationEmail(string $toEmail, string $toName, string $confirmationToken, string $lang = 'fr'): bool
    {
        $subject = $lang === 'fr' ?
            "Bienvenue sur Chrysalide - Confirmez votre compte" :
            "Welcome to Chrysalide - Confirm your account";

        $confirmUrl = "https://heig-chrysalide.ch/confirm.php?token=" . urlencode($confirmationToken);

        if ($lang === 'fr') {
            $htmlBody = "
            <html>
            <head>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
                    .header h1 { margin: 0; font-size: 28px; }
                    .content { padding: 40px 30px; line-height: 1.6; color: #333; }
                    .button { display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; margin: 20px 0; }
                    .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🦋 Bienvenue sur Chrysalide !</h1>
                    </div>
                    <div class='content'>
                        <h2>Bonjour $toName,</h2>
                        <p>Merci de vous être inscrit sur <strong>Chrysalide</strong>, la plateforme de lecture et d'écriture d'histoires.</p>
                        <p>Pour activer votre compte et commencer votre aventure, veuillez confirmer votre adresse email en cliquant sur le bouton ci-dessous :</p>
                        <p style='text-align: center;'>
                            <a href='$confirmUrl' class='button'>Confirmer mon compte</a>
                        </p>
                        <p style='font-size: 14px; color: #666;'>Si le bouton ne fonctionne pas, copiez-collez ce lien dans votre navigateur :<br>
                        <a href='$confirmUrl' style='color: #667eea;'>$confirmUrl</a></p>
                        <p>Si vous n'avez pas créé ce compte, ignorez cet email.</p>
                        <p>À bientôt sur Chrysalide ! 📖</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " Chrysalide - Tous droits réservés</p>
                        <p>Projet réalisé dans le cadre du cours ProgServ2 - HEIG-VD</p>
                    </div>
                </div>
            </body>
            </html>";

            $textBody = "Bonjour $toName,\n\nMerci de vous être inscrit sur Chrysalide.\n\nPour confirmer votre compte, cliquez sur ce lien :\n$confirmUrl\n\nÀ bientôt !";
        } else {
            $htmlBody = "
            <html>
            <head>
                <style>
                    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 20px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
                    .header h1 { margin: 0; font-size: 28px; }
                    .content { padding: 40px 30px; line-height: 1.6; color: #333; }
                    .button { display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; margin: 20px 0; }
                    .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 14px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>🦋 Welcome to Chrysalide!</h1>
                    </div>
                    <div class='content'>
                        <h2>Hello $toName,</h2>
                        <p>Thank you for signing up on <strong>Chrysalide</strong>, the reading and writing platform for stories.</p>
                        <p>To activate your account and start your adventure, please confirm your email address by clicking the button below:</p>
                        <p style='text-align: center;'>
                            <a href='$confirmUrl' class='button'>Confirm my account</a>
                        </p>
                        <p style='font-size: 14px; color: #666;'>If the button doesn't work, copy and paste this link into your browser:<br>
                        <a href='$confirmUrl' style='color: #667eea;'>$confirmUrl</a></p>
                        <p>If you didn't create this account, please ignore this email.</p>
                        <p>See you soon on Chrysalide! 📖</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " Chrysalide - All rights reserved</p>
                        <p>Project developed as part of ProgServ2 course - HEIG-VD</p>
                    </div>
                </div>
            </body>
            </html>";

            $textBody = "Hello $toName,\n\nThank you for signing up on Chrysalide.\n\nTo confirm your account, click this link:\n$confirmUrl\n\nSee you soon!";
        }

        return $this->sendEmail($toEmail, $toName, $subject, $htmlBody, $textBody);
    }

    /**
     * Méthode générique pour envoyer un email
     */
    private function sendEmail(string $toEmail, string $toName, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = $this->host;
            $mail->Port = $this->port;
            $mail->SMTPAuth = $this->authentication;

            if ($this->authentication) {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Username = $this->username;
                $mail->Password = $this->password;
            }

            $mail->CharSet = "UTF-8";
            $mail->Encoding = "base64";

            // Expéditeur et destinataire
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->addAddress($toEmail, $toName);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody ?: strip_tags($htmlBody);

            $mail->send();
            return true;

        } catch (Exception $e) {
            //error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
            return false;
        }
    }
}