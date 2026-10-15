<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Gradient Color Generator</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

    <header class="navbar">
        <div class="logo">
            Color<span>Flow</span>
        </div>

        <nav>
            <a href="#generator">Generator</a>
            <a href="#palette">Palette</a>
            <a href="fitur_warna/color_picker.php">Color Picker &amp; History</a>
            <a href="#about">About</a>
            <?php if ($isLoggedIn): ?>
                <a href="color_history/color_palette/dasboard.php">Dashboard</a>
                <a href="color_history/color_palette/logout.php">Logout</a>
            <?php else: ?>
                <a href="color_history/color_palette/login.php">Login</a>
                <a href="color_history/color_palette/register.php">Daftar</a>
            <?php endif; ?>
        </nav>
    </header>


    <main>

        <!-- HERO -->
        <section class="hero">

            <div class="hero-text">
                <p class="label">COLOR TOOL</p>

                <h1>
                    Create beautiful
                    <span>gradients.</span>
                </h1>

                <p class="description">
                    Generate random gradients and create your own
                    color palette easily.
                </p>

                <a href="#generator" class="start-btn">
                    Start Creating →
                </a>
                <a href="fitur_warna/color_picker.php" class="start-btn">
                    Open Color Picker &amp; History
                </a>
            </div>

        </section>


        <!-- GENERATOR -->
        <section id="generator" class="generator-section">

            <div class="section-title">
                <p class="label">GRADIENT GENERATOR</p>
                <h2>Build your gradient</h2>
            </div>


            <!-- Gradient Preview -->
            <div class="gradient-preview" id="gradientPreview">

                <div class="preview-content">
                    <p>YOUR GRADIENT</p>
                    <h3 id="gradientText">
                        linear-gradient(...)
                    </h3>
                </div>

            </div>


            <!-- Controls -->
            <div class="controls">

                <div class="control-box">

                    <h3>Gradient Type</h3>

                    <select id="gradientType">
                        <option value="linear">Linear</option>
                        <option value="radial">Radial</option>
                    </select>

                </div>


                <div class="control-box">

                    <h3>Angle</h3>

                    <input
                        type="range"
                        id="angle"
                        min="0"
                        max="360"
                        value="135"
                    >

                    <span id="angleValue">135°</span>

                </div>


                <div class="control-box">

                    <h3>Colors</h3>

                    <div id="colorInputs"></div>

                    <button
                        class="small-btn"
                        onclick="addColor()"
                    >
                        + Add Color
                    </button>

                </div>

            </div>


            <div class="main-buttons">

                <button
                    class="random-btn"
                    onclick="randomGradient()"
                >
                    🎲 Random Gradient
                </button>

                <button
                    class="copy-btn"
                    onclick="copyGradient()"
                >
                    Copy CSS
                </button>

            </div>

        </section>


        <!-- PALETTE -->
        <section id="palette" class="palette-section">

            <div class="section-title">

                <p class="label">COLOR PALETTE</p>

                <h2>Your color palette</h2>

                <p>
                    Click a color to copy its HEX code.
                </p>

            </div>


            <div
                class="palette"
                id="paletteContainer"
            ></div>


            <button
                class="random-btn"
                onclick="generatePalette()"
            >
                🎨 Generate Palette
            </button>

        </section>


        <!-- ABOUT -->
        <section id="about" class="about">

            <p class="label">ABOUT</p>

            <h2>
                A simple tool for
                <span>designers.</span>
            </h2>

            <p>
                ColorFlow helps you generate gradients and color
                palettes quickly for websites, posters, UI designs,
                illustrations and other creative projects.
            </p>

        </section>

    </main>


    <footer>

        <p>
            © 2026 ColorFlow — Gradient Color Generator
        </p>

    </footer>


    <script src="script.js"></script>

</body>
</html>