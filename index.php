<?php
// composer autoload
require __DIR__ . '/vendor/autoload.php';

// if you aren't using composer
// require_once 'path/to/algolia/folder/autoload.php';

use Algolia\AlgoliaSearch\SearchClient;

$client = SearchClient::create('379ALRH0L0', '13863c463e3745e3a2536441e0fdcb8a');

$index = $client->initIndex('contacts');

$batch = json_decode(file_get_contents('contacts.json'), true);
//$index->saveObjects($batch, ['autoGenerateObjectIDIfNotExist' => true]);

$index->setSettings(['customRanking' => ['desc(followers)']]);


?>

<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/instantsearch.css@8.0.0/themes/satellite-min.css"
    integrity="sha256-p/rGN4RGy6EDumyxF9t7LKxWGg6/MZfGhJM/asKkqvA="
    crossorigin="anonymous"
  />
</head>
<body>
  <header>
    <div id="search-box"></div>
  </header>

  <main>
    <div id="hits"></div>
    <div id="pagination"></div>
  </main>

  <script type="text/html" id="hit-template">
    <div class="hit">
      <p class="hit-name">
        {{#helpers.highlight}}{ "attribute": "firstname" }{{/helpers.highlight}}
        {{#helpers.highlight}}{ "attribute": "lastname" }{{/helpers.highlight}}
      </p>
    </div>
  </script>

  <script
    src="https://cdn.jsdelivr.net/npm/algoliasearch@4.19.1/dist/algoliasearch-lite.umd.js"
    integrity="sha256-qzlNbRtZWHoUV5I2mI2t9QR7oYXlS9oNctX+0pECXI0="
    crossorigin="anonymous"
  ></script>
  <script
    src="https://cdn.jsdelivr.net/npm/instantsearch.js@4.56.9/dist/instantsearch.production.min.js"
    integrity="sha256-8AA0iLtMtPZvYXCp1M0yOWKK/PkffhvDt+1yl7bNtCw="
    crossorigin="anonymous"
  ></script>
  <script>
        // Replace with your own values
        const searchClient = algoliasearch(
        '379ALRH0L0',
        '87ce96419f767074a2db13ae40728de2' // search only API key, not admin API key
        )

        const search = instantsearch({
          indexName: 'contacts',
          searchClient,
          routing: true,
        })

        search.addWidgets([
            instantsearch.widgets.configure({
                hitsPerPage: 10,
            }),
        ])

        search.addWidgets([
            instantsearch.widgets.searchBox({
                container: '#search-box',
                placeholder: 'Search for contacts',
            }),
        ])

        search.addWidgets([
            instantsearch.widgets.hits({
                container: '#hits',
                templates: {
                item: document.getElementById('hit-template').innerHTML,
                empty: `We didn't find any results for the search <em>"{{query}}"</em>`,
                },
            }),
        ])

        search.start()

  </script>
</body>
