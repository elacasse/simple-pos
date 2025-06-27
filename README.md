# Mini Système de Facturation – Latactik Exercise

Prototype d’un micro-service de facturation respectant le format JSON:API, développé avec Laravel 11 et Vue 3.

---

## 🚀 Démarrage rapide

```bash
git clone git@github.com:elacasse/simple-pos.git
cd simple-pos

docker-compose up --build -d
```

---

## ⚙️ Architecture & Choix techniques

### Backend – Laravel 11
- JSON:API via `laravel-json-api/laravel`
- Authentification simple via Bearer token dans `.env`
- Modèles : `Bill`, `Article`
  - Relations : `Bill hasMany Articles`, `Article belongsTo Bill`
  - Enum de statut (`draft`, `sent`, `paid`) avec valeur par défaut
- Calculs (`subtotal`, `discount_total`, `total_due`) effectués côté serveur
- Endpoints conformes JSON:API (création, relation, filtrage, etc.)

### Frontend – Vue 3 + Vite + TypeScript + Tailwind
- Table de factures avec actions "Envoyer" / "Payer"
- Formulaire dans une modal pour créer une nouvelle facture
- Requêtes via `fetch`/`axios` sur endpoints JSON:API
- Affichage des erreurs (422) en cas de validation échouée

---

## 🧮 Calculs serveur

Lors de la création ou de la mise à jour d'une facture, les champs suivants sont générés automatiquement :

- `subtotal` = somme des `(quantity × unit_price)` de chaque article
- `discount_total` = `subtotal × (discount / 100)`
- `total_due` = `subtotal − discount_total`

Ces champs sont **en lecture seule** pour garantir l'intégrité des données.

---

## ⛔ Fonctionnalités omises (volontairement)

- Aucun système de rôles, permissions ou authentification complexe
- Pas de gestion multi-devise ni de taxes
- Pas de génération PDF, pas de jobs, ni de passerelles de paiement
- Pas de système temps réel (Pusher, Echo)

---

## 🔮 Pistes d’évolution

- Ajout de filtres avancés (par date, statut, client)
- Edition des articles d'une facture existante
- Export PDF ou envoi par courriel
- Authentification JWT complète
- Intégration d’un dashboard de paiements

---

## 📁 Collection API

Une collection Postman/Insomnia est disponible dans le dossier `/docs` *(optionnel)*.

---

## 🎥 Démo

Une vidéo de démonstration est disponible ici :  
[📹 Lien vers la vidéo](https://votre-lien-demo.com)

---

Développé dans le cadre du devoir technique Latactik.  
⏱ Temps passé : ~5h
