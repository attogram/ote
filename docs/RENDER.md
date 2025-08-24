# Deploying to Render

This project is configured for automatic deployment to the [Render](https://render.com/) hosting platform.

## How It Works

This repository contains a `render.yaml` file that uses Render's "Blueprint" feature. This file tells Render everything it needs to know to deploy the application, including:

-   A **web service** running the Laravel application.
-   A **PostgreSQL database** for storing data.

When you push changes to the `main` branch on GitHub, Render will automatically:

1.  Detect the changes.
2.  Build the application using the `buildCommand` defined in `render.yaml`. This includes installing dependencies and running database migrations.
3.  Deploy the new version of the application.

## Initial Setup on Render

To get your own version of this application running on Render, follow these steps:

1.  **Fork this Repository**: Create your own copy of this repository on GitHub.

2.  **Sign up for Render**: If you don't have one already, create an account on [Render](https://render.com/).

3.  **Create a New Blueprint Service**:
    *   From your Render Dashboard, click **New +** and select **Blueprint**.
    *   Connect your GitHub account and select the repository you forked.
    *   Render will automatically find and parse the `render.yaml` file.
    *   Give your services unique names (e.g., `my-ote-app-web` and `my-ote-app-db`).
    *   Click **Apply**.

Render will now build and deploy your application. You can monitor the progress in the Render dashboard. Once complete, your application will be live at a URL like `https://your-app-name.onrender.com`.

## Free Tier Limitations

This setup uses Render's free tier, which has the following limitations:

-   **Web Service**: The service "spins down" after 15 minutes of inactivity. The next request will cause a delay of 20-30 seconds while it restarts.
-   **Database**: The free PostgreSQL database is automatically **deleted after 30 days**. This makes it suitable for demos and testing, but not for production use. To retain your data, you must upgrade to a paid database plan on Render.
