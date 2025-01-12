<?php
require_once '../../app/controller/articles.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $articleId = $_GET['id'];
    $article = Article::getById($articleId);
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title><?= htmlspecialchars($article['titre']) ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/inter/3.19.3/inter.css">
    <style>
        :root {
            /* Enterprise Color System */
            --primary-blue: #0050ff;
            --secondary-blue: #0039b5;
            --accent-blue: #00d1ff;
            --deep-blue: #001b57;
            --navy: #000c24;
            --frost: rgba(255, 255, 255, 0.05);
            --glass: rgba(255, 255, 255, 0.1);

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--primary-blue), #0095ff);
            --gradient-dark: linear-gradient(135deg, var(--deep-blue), var(--navy));
            --gradient-frost: linear-gradient(135deg, var(--frost), transparent);

            /* Elevations */
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-glow: 0 0 20px rgba(0, 81, 255, 0.1);

            /* Typography Scale */
            --font-xs: 0.75rem;
            --font-sm: 0.875rem;
            --font-base: 1rem;
            --font-lg: 1.125rem;
            --font-xl: 1.25rem;
            --font-2xl: 1.5rem;
            --font-3xl: 1.875rem;
            --font-4xl: 2.25rem;
            --font-5xl: 3rem;
        }

        @keyframes gradientShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--navy);
            color: #fff;
            min-height: 100vh;
            line-height: 1.7;
            background-image:
                radial-gradient(circle at 100% 0%, rgba(0, 81, 255, 0.12) 0%, transparent 25%),
                radial-gradient(circle at 0% 100%, rgba(0, 209, 255, 0.12) 0%, transparent 25%),
                linear-gradient(rgba(0, 12, 36, 0.97), rgba(0, 12, 36, 0.97)),
                repeating-linear-gradient(45deg, rgba(255, 255, 255, 0.02) 0px, rgba(255, 255, 255, 0.02) 1px, transparent 1px, transparent 10px);
            background-attachment: fixed;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--deep-blue);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg), var(--shadow-glow);
            border: 1px solid var(--glass);
            animation: fadeIn 0.6s ease-out;
        }

        .article-header {
            padding: 4rem;
            background: var(--gradient-dark);
            position: relative;
            overflow: hidden;
        }

        .article-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--gradient-frost);
            opacity: 0.5;
            pointer-events: none;
        }

        .title-wrapper {
            position: relative;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: var(--font-5xl);
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.2;
            background: linear-gradient(45deg, #fff, var(--accent-blue));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .article-meta {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            font-size: var(--font-sm);
            color: rgba(255, 255, 255, 0.7);
            padding: 0.5rem 1rem;
            background: var(--frost);
            border-radius: 6px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .article-meta::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--accent-blue);
            border-radius: 50%;
        }

        .article-description {
            position: relative;
            font-size: var(--font-lg);
            color: rgba(255, 255, 255, 0.9);
            padding: 2rem;
            background: var(--frost);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid var(--glass);
            margin-top: 2rem;
        }

        .article-content {
            padding: 4rem;
            font-size: var(--font-lg);
        }

        /* Rich Text Styling */
        .article-content p {
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .article-content h2 {
            font-size: var(--font-3xl);
            color: #fff;
            margin: 3rem 0 1.5rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .article-content h3 {
            font-size: var(--font-2xl);
            color: var(--accent-blue);
            margin: 2rem 0 1rem;
            font-weight: 600;
        }

        .article-content img {
            width: 100%;
            border-radius: 12px;
            margin: 2rem 0;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--glass);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .article-content img:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-lg), 0 0 30px rgba(0, 209, 255, 0.2);
        }

        .ql-syntax {
            background: rgba(0, 0, 0, 0.3);
            padding: 1.5rem;
            border-radius: 12px;
            font-family: 'Fira Code', monospace;
            font-size: var(--font-sm);
            line-height: 1.7;
            margin: 2rem 0;
            overflow-x: auto;
            border: 1px solid var(--glass);
            position: relative;
        }

        .ql-syntax::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, var(--primary-blue), transparent);
            opacity: 0.05;
            pointer-events: none;
        }

        blockquote,
        .ql-quote {
            margin: 2rem 0;
            padding: 2rem;
            background: var(--frost);
            border-radius: 12px;
            border-left: 4px solid var(--accent-blue);
            font-style: italic;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        a {
            color: var(--accent-blue);
            text-decoration: none;
            background: linear-gradient(to right, var(--accent-blue), var(--accent-blue));
            background-size: 0% 1px;
            background-position: 0 100%;
            background-repeat: no-repeat;
            transition: background-size 0.3s ease;
        }

        a:hover {
            background-size: 100% 1px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 2rem 0;
            background: var(--frost);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--glass);
        }

        th {
            background: rgba(0, 81, 255, 0.1);
            font-weight: 600;
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid var(--glass);
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--glass);
        }

        tr:last-child td {
            border-bottom: none;
        }

        /* Lists */
        ul,
        ol {
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }

        li {
            margin-bottom: 0.5rem;
        }

        code {
            background: rgba(0, 81, 255, 0.1);
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Fira Code', monospace;
            font-size: 0.9em;
            color: var(--accent-blue);
        }

        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .container {
                border-radius: 12px;
            }

            .article-header,
            .article-content {
                padding: 2rem;
            }

            h1 {
                font-size: var(--font-3xl);
            }

            .article-description {
                padding: 1.5rem;
            }
        }

        /* Dark mode optimizations */
        @media (prefers-color-scheme: dark) {
            :root {
                --shadow-glow: 0 0 30px rgba(0, 81, 255, 0.15);
            }
        }

        /* Print optimizations */
        @media print {
            body {
                background: none;
                color: #000;
            }

            .container {
                box-shadow: none;
                border: none;
            }

            .article-header::before {
                display: none;
            }

            h1 {
                color: #000;
                -webkit-text-fill-color: initial;
            }

            .article-description {
                background: none;
                border: 1px solid #eee;
            }
        }

        .featured-image-container {
            margin: -2rem -4rem 2rem;
            position: relative;
            background: var(--frost);
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .featured-image {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .featured-image:hover {
            transform: scale(1.02);
        }

        @media (max-width: 768px) {
            .featured-image-container {
                margin: -1rem -2rem 1rem;
                min-height: 150px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <article>
            <header class="article-header">
                <div class="title-wrapper">
                    <h1><?= $article['titre'] ?></h1>
                    <div class="article-meta">
                        Publié le: <?= $article['created_at'] ?>
                    </div>
                </div>
                <?php if (!empty($article['image_url']) && file_exists($article['image_url'])): ?>
                    <div class="featured-image-container">
                        <img class="featured-image" src="<?= htmlspecialchars($article['image_url']) ?>"
                            alt="<?= htmlspecialchars($article['titre']) ?>" loading="lazy"
                            onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
                <div class="article-description">
                    <?= $article['description'] ?>
                </div>
            </header>

            <div class="article-content">
                <?= $article['contenu'] ?>
            </div>
        </article>
    </div>
</body>

</html>