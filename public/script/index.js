document.addEventListener("DOMContentLoaded", function () {
    const apiUrlBinance = "https://api.binance.com/api/v3/ticker/price";
    const chartDataUrl = 'chart_data.php';

    const currencySelect = document.getElementById("currencySelect");
    const chartSelect = document.getElementById("chartSelect");
    const cryptoCurContainer = document.getElementById("cryptoCurContainer");
    const searchBtn = document.getElementById("searchBtn");
    const searchInput = document.getElementById("search");
    const searchResults = document.getElementById("searchResults");
    const portfolioDiv = document.querySelector(".portfolio-container");
    const clearBtn = document.getElementById("clearBtn");
    const clearPortfolio = document.getElementById("clearPortfolioBtn");
    const myChartCanvas = document.getElementById("myChart");

    const ctx = myChartCanvas.getContext('2d');
    const myChart = createChart(ctx);

    let portfolio = loadPortfolioFromLocalStorage();
    let chartData = [];
    let selectedCryptocurrency = 'bitcoin';

    fetchChartData();
    fetchCryptocurrencyData();
    showPortfolioData(portfolio);
    

    setInterval(fetchChartData, 360000);

    currencySelect.addEventListener("change", fetchCryptocurrencyData);
    searchBtn.addEventListener("click", searchCryptocurrency);
    clearBtn.addEventListener("click", clearSearchResults);
    clearPortfolio.addEventListener("click", clearPortfolioData);

    chartSelect.addEventListener("change", function () {
        selectedCryptocurrency = chartSelect.value.toLowerCase();
        fetchChartData();
    });

    function createChart(context) {
        return new Chart(context, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Cryptocurrency Prices',
                    data: [],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Cryptocurrency Name'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Price'
                        }
                    }
                }
            }
        });
    }

    function fetchChartData() {
        fetch(chartDataUrl + `?crypto=${selectedCryptocurrency}`)
            .then(handleErrors)
            .then(response => response.json())
            .then(data => {
                chartData = data.map(item => [item[0], parseFloat(item[1]), item[2]]);
                updateChart();
            })
            .catch(error => console.error('Error fetching chart data:', error));
    }

    function updateChart() {
        const filteredChartData = chartData.filter(item => item[0] === selectedCryptocurrency);

        if (filteredChartData.length > 0) {
            const labels = filteredChartData.map(item => item[0]);
            const prices = filteredChartData.map(item => parseFloat(item[1]));

            myChart.data.labels = labels;
            myChart.data.datasets[0].data = prices;

            myChart.update();
        } else {
            console.log(`No data found for ${selectedCryptocurrency}`);
        }
    }

    function fetchCryptocurrencyData() {
        const selectedCurrency = currencySelect.value;

        fetch(apiUrlBinance)
            .then(handleErrors)
            .then(response => response.json())
            .then(data => {
                cryptoCurContainer.innerHTML = '';
                const filteredData = data.filter(crypto => crypto.symbol.endsWith(selectedCurrency));
                filteredData.sort((a, b) => parseFloat(b.price) - parseFloat(a.price));

                filteredData.forEach(crypto => {
                    const symbol = crypto.symbol;
                    const price = crypto.price;
                    const cryptoInfo = document.createElement("p");
                    cryptoInfo.textContent = `${symbol}: ${price} ${selectedCurrency}`;
                    cryptoCurContainer.appendChild(cryptoInfo);
                });

                updateChart();
            })
            .catch(error => console.error(error));
    }

    function searchCryptocurrency() {
        const searchTerm = searchInput.value.toUpperCase();

        fetch(apiUrlBinance)
            .then(handleErrors)
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = '';
                data.forEach(crypto => {
                    const symbol = crypto.symbol;
                    if (symbol.startsWith(searchTerm)) {
                        const resultBtn = document.createElement("button");
                        resultBtn.textContent = symbol;
                        resultBtn.addEventListener("click", () => {
                            togglePortfolio(symbol, crypto.price);
                        });
                        searchResults.appendChild(resultBtn);
                    }
                });
            })
            .catch(error => console.error(error));
    }

    function showPortfolioData(portfolio) {
        for (const [symbol, price] of Object.entries(portfolio)) {
            const cryptoDiv = document.createElement("div");
            cryptoDiv.id = symbol;
            const currencyCode = symbol.slice(-3);
            const selectedCurrencyPriceText = `${price} ${currencyCode}`;
            cryptoDiv.textContent = `${symbol}: ${selectedCurrencyPriceText}`;
            document.querySelector(".portfolio-container").appendChild(cryptoDiv);
        }
    }

    function togglePortfolio(symbol, price) {
        if (portfolio[symbol]) {
            delete portfolio[symbol];
            const cryptoDiv = document.getElementById(symbol);
            cryptoDiv.remove();
        } else {
            portfolio[symbol] = price;
            const cryptoDiv = document.createElement("div");
            cryptoDiv.id = symbol;
            const currencyCode = symbol.slice(-3);
            const selectedCurrencyPriceText = `${price} ${currencyCode}`;
            cryptoDiv.textContent = `${symbol}: ${selectedCurrencyPriceText}`;
            portfolioDiv.appendChild(cryptoDiv);
        }
        savePortfolioToLocalStorage();
    }

    function clearSearchResults() {
        searchResults.innerHTML = '';
    }

    function clearPortfolioData() {
        portfolio = {};
        portfolioDiv.innerHTML = '';
        savePortfolioToLocalStorage();
    }

    function savePortfolioToLocalStorage() {
        localStorage.setItem('portfolio', JSON.stringify(portfolio));
    }
    
    function loadPortfolioFromLocalStorage() {
        const localStorageValue = localStorage.getItem('portfolio');
        const portfolioData = localStorageValue ? JSON.parse(localStorageValue) : {};
        return portfolioData;
    }

    function setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }

    function getCookie(name) {
        const cookieValue = document.cookie.match(`(^|;)\\s*${name}\\s*=\\s*([^;]+)`);
        return cookieValue ? cookieValue.pop() : null;
    }

    function handleErrors(response) {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response;
    }
});
