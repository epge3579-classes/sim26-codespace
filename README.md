# PHP + MariaDB + Nginx — Codespaces Compose Override Demo

This repository demonstrates one common Docker Compose stack with two GitHub Codespaces configurations:

- **Teaching**: Nginx + PHP-FPM + MariaDB + phpMyAdmin. Xdebug is off.
- **Development**: Nginx + PHP-FPM + MariaDB. Xdebug is enabled.

The important part is that both configurations start with `compose.yaml` and then apply a small override.

## Files

```text
.
├── .devcontainer/
│   ├── devcontainer.json                  # Teaching (default)
│   └── development/
│       └── devcontainer.json              # Development
├── .vscode/
│   └── launch.json
├── docker/
│   ├── db/init/001-demo.sql
│   ├── nginx/
│   │   ├── app.conf
│   │   └── teaching.conf
│   └── php/
│       ├── Dockerfile
│       └── xdebug.ini
├── src/public/index.php
├── compose.yaml
├── compose.teaching.yaml
├── compose.development.yaml
├── .env.example
└── .gitignore
```

## How the override works

Teaching uses:

```bash
docker compose -f compose.yaml -f compose.teaching.yaml config
```

Development uses:

```bash
docker compose -f compose.yaml -f compose.development.yaml config
```

The second file extends or replaces settings from the first one.

## Create a Codespace

From the repository on GitHub:

1. **Code** → **Codespaces** → create a new codespace.
2. Choose the dev container configuration:
   - `PHP Stack - Teaching`, or
   - `PHP Stack - Development`.
3. Wait for the containers to start.

The application is forwarded on port **8080**.

In Teaching mode, phpMyAdmin is also forwarded on **8081**.

## Database connection from PHP

PHP uses Docker's internal DNS name for the database service:

```text
DB_HOST=db
DB_PORT=3306
```

Do not use `localhost` for MariaDB from PHP. `localhost` would mean the PHP/Nginx network namespace, not the database container.

Default disposable credentials are:

```text
database: codespaces_demo
user:     appuser
password: appsecret
root:     rootsecret
```

For anything beyond a disposable sandbox, replace these with Codespaces secrets or another proper secret mechanism.

## SQLTools connection inside VS Code

Use:

```text
Server:   db
Port:     3306
Database: codespaces_demo
Username: appuser
Password: appsecret
```

No database port needs to be exposed to the internet.

## Xdebug — Development only

1. Create the **Development** Codespace.
2. Open `src/public/index.php` and set a breakpoint.
3. Run **Listen for Xdebug** from VS Code's Run and Debug view.
4. Reload the application in the browser.

The PHP container is also the VS Code dev container, so Xdebug connects to `127.0.0.1:9003` inside that container.

## Local Docker Compose test

You can inspect the fully merged configuration without starting anything:

```bash
docker compose -f compose.yaml -f compose.teaching.yaml config
```

or:

```bash
docker compose -f compose.yaml -f compose.development.yaml config
```

To start locally outside a Dev Container, use:

```bash
docker compose -f compose.yaml -f compose.teaching.yaml up --build
```

or:

```bash
docker compose -f compose.yaml -f compose.development.yaml up --build
```

When running this way, `compose.yaml` intentionally does not publish host ports because Codespaces uses forwarding. If you want normal `localhost:8080` access outside Codespaces, add a small local override containing `ports: ["8080:8080"]` on `web`.

## Reset the database

The MariaDB data is stored in the named volume `dbdata`. The initialization SQL runs only when the database volume is first created.

To destroy the local sandbox database and recreate it:

```bash
docker compose -f compose.yaml -f compose.teaching.yaml down -v
```

Then start it again.
