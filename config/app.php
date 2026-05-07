<?php
/**
 * G-Pharm - Configuration Globale
 *
 * BASE_URL : chemin de base de l'application depuis la racine web.
 * - Si DocumentRoot = /srv/http/ProjetPharma  →  BASE_URL = ''
 * - Si le projet est dans un sous-dossier      →  BASE_URL = '/ProjetPharma'
 */
define("BASE_URL", "/ProjetPharma");

/** Version de l'application */
define("APP_VERSION", "2.0");

/** Nom affiché dans l'interface */
define("APP_NAME", "G-Pharm");
