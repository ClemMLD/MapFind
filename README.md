<p align="center"><a href="https://mapfind.app" target="_blank"><img width="322" height="87" alt="output-onlinepngtools-2" src="https://github.com/user-attachments/assets/6b656158-3f62-400c-82aa-dc1e783e19d6" /></a></p>

**MapFind** est une application web de cartographie d'annonces locales entre particuliers. Elle permet aux utilisateurs de publier, consulter et échanger des annonces autour d'eux, avec une carte interactive, une messagerie temps réel, et des fonctionnalités avancées comme le prêt d'objets ou les enchères.

---

## 🧾 À propos

### 🎯 Objectif
Proposer une alternative moderne, locale et plus humaine aux plateformes de petites annonces traditionnelles (comme Leboncoin), en misant sur la **proximité géographique**, la **simplicité d’usage** et la **visualisation cartographique**.

### ⚙️ Fonctionnalités principales
- Carte interactive (OpenStreetMap via Leaflet)
- Publication et gestion d’annonces
- Filtres avancés (catégories)
- Messagerie temps réel (Laravel Reverb)
- Interface d’administration (Filament)
- API publique (à venir)

### 👤 Utilisateurs ciblés
Particuliers souhaitant vendre, prêter ou donner des objets dans un périmètre local (ville/quartier), sans passer par des envois ou plateformes impersonnelles.

---

## 🚀 Technologies utilisées

| Composant         | Stack                       |
|-------------------|-----------------------------|
| Back-end          | PHP 8.4 · Laravel 11        |
| Front-end         | Blade · Tailwind CSS        |
| Temps réel        | Laravel Reverb (WebSockets) |
| Carte             | Leaflet · OpenStreetMap     |
| Base de données   | MySQL                       |
| Interface admin   | Filament                    |
| Tests             | PHPUnit                     |
| CI/CD             | CircleCI                    |

---

## 🔧 Installation

### Prérequis
- Docker Desktop installé sur votre machine

### 1. Cloner le dépôt
```bash
git clone git@github.com:ClemMLD/MapFind.git
cd MapFind
```

### 2. Lancer l'application avec Docker
```bash
docker compose up -d
```

### 3. Populer la base de données
```bash
docker compose exec app php artisan migrate --seed
```

### 4. Accéder à l'application
Ouvrez votre navigateur et allez sur [http://localhost:8080](http://localhost:8080).

Les identifiants par défaut sont :
- **Email** : `clement.maldonado@icloud.com`
- **Mot de passe** : `30072002`


### 5. Réinstallation des paquets avce Composer
Si des changements ont été apportés dans les paquets, utilisez cette commande afin de les réinstaller :
```bash
docker compose exec app composer install
```
