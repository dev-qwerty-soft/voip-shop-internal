# CustomBaseTheme

This is a WordPress theme called CustomBaseTheme, developed by Oleksandr Vasylchuk.

## Installation

Make sure you have Node.js and npm installed on your computer.

```bash
# Setting project dependencies
npm install
```

## Development

```bash
# Starting development mode (watch mode)
npm start

# Format code
npm run format

# Check code formatting (used in CI)
npm run format:check

# Build for production
npm run build

# Optimize images
npm run images
```

## Node.js Version

This project uses Node.js version specified in [.nvmrc](.nvmrc). To use the correct version:

```bash
# Using nvm (Node Version Manager)
nvm use

# Or install the version if not available
nvm install
```

## CI/CD Pipeline

### Auto-deployment to Staging

Push to `staging` branch triggers automatic deployment:

```
git push origin staging → Server builds & deploys (10-15s)
```

**Troubleshooting:** Check the deployment log on the server (path depends on hosting/webhook configuration).

## Naming standards

We use JIRA ticket IDs in branch names, PR titles, and commit messages.

- Branch: `type/QI-123` (example: `chore/QI-1`)
- PR title: `QI-123: TYPE: Summary` (example: `QI-1: CHORE: Set up CI build and deploy`)
- Commit message: `QI-123: short summary`
