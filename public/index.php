<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$userName = explode(":", $_SESSION['user']["name"])[0];

$portfolio = [];

if (isset($_COOKIE['portfolio'])) {
    $portfolioData = json_decode($_COOKIE['portfolio'], true);
    if (is_array($portfolioData)) {
        $portfolio = $portfolioData;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/index.css">
    <title>Cryptocurrency prices</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="script/index.js"></script>
</head>

<body style="background-image: url('media/backgroundImage.jpg'); background-size: contain;">
    <header>
        <input type="text" id="search" placeholder="Search">
        <button id="searchBtn">Search</button>
        <button id="clearBtn">Clear</button>
        <div id="searchResults" class="search-results"></div>
    </header>
    <main>
        <div class="name-continer">
            <h3>Welcome
                <?php echo $userName; ?>!
            </h3>
        </div>
        <div class="portfolio-container">
            <h3>Your Portfolio</h3>
        </div>
        <button id="clearPortfolioBtn">Clear Portfolio</button>
        <div class="portfolioDiv"></div>
        </div>
        <div class="chart-container">
            <h3>Cryptocurrency Price History</h3>
            <div class="currency-dropdown">
                <select id="chartSelect" style="width: 100%;">
                    <option value="bitcoin">Bitcoin</option>
                    <option value="ethereum">Ethereum</option>
                </select>
            </div>
            <canvas id="myChart" style="width:100%;max-width:700px;height:82%"></canvas>
        </div>
    </main>
    <list>
        <div class="currency-dropdown">
            <select id="currencySelect">
                <option value="BTC">BTC</option>
                <option value="ETH">ETH</option>
                <option value="EUR">EUR</option>
                <option value="USD">USD</option>
                <option value="USDT">USDT</option>
                <option value="BUSD">BUSD</option>
            </select>
        </div>
        <div class="crypto-cur" id="cryptoCurContainer">
        </div>
    </list>
</body>

</html>