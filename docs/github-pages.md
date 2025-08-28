# Deploying Documentation to GitHub Pages

This guide explains how to set up a GitHub Actions workflow to automatically deploy the contents of the `/docs` directory to a GitHub Pages website.

## How it Works

GitHub Pages can be configured to build and deploy from a branch or from a custom GitHub Actions workflow. We will use a workflow to gain more control over the process. The workflow will be triggered every time there is a push to the `main` branch.

The key steps in the workflow are:
1.  Check out the repository's code.
2.  Upload the entire `/docs` directory as an artifact.
3.  Deploy this artifact to the `gh-pages` branch, which is the standard source for GitHub Pages.

## Step-by-Step Setup

### 1. Create the Workflow File

Create a new file in your repository at the following path: `.github/workflows/deploy-docs.yml`.

Copy and paste the following content into that file:

```yaml
name: Deploy Docs to GitHub Pages

on:
  # Runs on pushes to the main branch
  push:
    branches:
      - main

  # Allows you to run this workflow manually from the Actions tab
  workflow_dispatch:

# Sets permissions of the GITHUB_TOKEN to allow deployment to GitHub Pages
permissions:
  contents: read
  pages: write
  id-token: write

# Allow only one concurrent deployment, skipping runs queued between the run in-progress and the latest queued.
# However, do NOT cancel in-progress runs as we want to allow these production deployments to complete.
concurrency:
  group: "pages"
  cancel-in-progress: false

jobs:
  # Build job
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout
        uses: actions/checkout@v4
      - name: Setup Pages
        uses: actions/configure-pages@v5
      - name: Upload artifact
        uses: actions/upload-pages-artifact@v3
        with:
          # Upload the /docs directory
          path: './docs'

  # Deployment job
  deploy:
    environment:
      name: github-pages
      url: ${{ steps.deployment.outputs.page_url }}
    runs-on: ubuntu-latest
    needs: build
    steps:
      - name: Deploy to GitHub Pages
        id: deployment
        uses: actions/deploy-pages@v4
```

### 2. Configure Your Repository Settings

After committing the workflow file, you need to configure your repository to use it for GitHub Pages.

1.  Go to your repository on GitHub.
2.  Click on the **Settings** tab.
3.  In the left sidebar, click on **Pages**.
4.  Under "Build and deployment", change the **Source** from "Deploy from a branch" to **"GitHub Actions"**.

### 3. Push to `main`

Commit the new `.github/workflows/deploy-docs.yml` file and push it to the `main` branch.

```bash
git add .github/workflows/deploy-docs.yml
git commit -m "docs: Add workflow for GitHub Pages deployment"
git push origin main
```

### 4. Verify the Deployment

-   Go to the **Actions** tab in your repository. You should see the "Deploy Docs to GitHub Pages" workflow running.
-   Once the workflow completes successfully, your documentation will be live.
-   You can find the URL for your GitHub Pages site in the **Settings > Pages** tab of your repository. It will typically be something like `https://<your-username>.github.io/<your-repository-name>/`.
