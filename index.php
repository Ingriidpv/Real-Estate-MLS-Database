<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Real Estate Listings</title>

<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    body 
    {
        font-family: Arial, sans-serif;
        margin: 20px;
        background: #f5f5f5;
    }
    h1 
    {
        margin-bottom: 30px;
    }
    .section 
    {
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    table 
    {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    th, td 
    {
        padding: 8px;
        border: 1px solid #000;
    }
    th 
    {
        background: #eee;
    }
    .button 
    {
        padding: 8px 16px;
        background: #a298e0;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
    }
    .button:hover 
    {
        background: #d9bfed;
    }
    input[type="number"], input[type="text"] 
    {
        padding: 6px;
        width: 120px;
        margin-bottom: 10px;
    }
    select 
    {
        padding: 6px;
        margin-bottom: 10px;
    }
    #results 
    {
        margin-top: 20px;
    }
    pre 
    {
        background: #222;
        color: rgb(142, 195, 224);
        padding: 15px;
        white-space: pre-wrap;
    }
    .tab 
    {
        overflow: hidden;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        margin-bottom: 20px;
    }
    .tab button 
    {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        font-size: 16px;
    }
    .tab button:hover 
    {
        background-color: #ddd;
    }
    .tab button.active 
    {
        background-color: #ccc;
        font-weight: bold;
    }
    .tabcontent 
    {
        display: none;
        padding: 20px;
        border: none;
        border-top: none;
    }
</style>

</head>
<body>

<h1>Real Estate Listings</h1>

<!-- Tab Navigation -->
<div class="tab">
  <button class="tablinks active" onclick="openTab(event, 'Listings')">Listings</button>
  <button class="tablinks" onclick="openTab(event, 'Houses')">Houses</button>
  <button class="tablinks" onclick="openTab(event, 'Business')">Business</button>
  <button class="tablinks" onclick="openTab(event, 'Agents')">Agents</button>
  <button class="tablinks" onclick="openTab(event, 'Buyers')">Buyers</button>
  <button class="tablinks" onclick="openTab(event, 'SQL')">SQL Query</button>
</div>

<!-- Tab contents-->

<div id="Listings" class="tabcontent" style="display:block;">
    <div class="section">
        <h2>All Listings</h2>
        <button class="button" onclick="loadListings()">Load Listings</button>
    </div>
</div>

<div id="Houses" class="tabcontent">
    <div class="section">
        <h2>Search Houses</h2>

        <label>Min Price:</label><br>
        <input type="number" id="h_minPrice" value="0"><br>

        <label>Max Price:</label><br>
        <input type="number" id="h_maxPrice" value="999999"><br>

        <label>Min Bedrooms:</label><br>
        <input type="number" id="h_bedrooms" value="1"><br>

        <label>Min Bathrooms:</label><br>
        <input type="number" id="h_bathrooms" value="1"><br>

        <button class="button" onclick="searchHouses()">Search Houses</button>
    </div>
</div>

<div id="Business" class="tabcontent">
    <div class="section">
        <h2>Search Business Properties</h2>

        <label>Min Price:</label><br>
        <input type="number" id="b_minPrice" value="0"><br>

        <label>Max Price:</label><br>
        <input type="number" id="b_maxPrice" value="999999"><br>

        <label>Min Size (sq ft):</label><br>
        <input type="number" id="b_minSize" value="0"><br>

        <label>Max Size (sq ft):</label><br>
        <input type="number" id="b_maxSize" value="999999"><br>

        <button class="button" onclick="searchBusiness()">Search Business</button>
    </div>
</div>

<div id="Agents" class="tabcontent">
    <div class="section">
        <h2>View All Agents</h2>
        <button class="button" onclick="loadAgents()">Load Agents</button>
    </div>
</div>

<div id="Buyers" class="tabcontent">
    <div class="section">
        <h2>View All Buyers</h2>
        <button class="button" onclick="loadBuyers()">Load Buyers</button>
    </div>
</div>

<div id="SQL" class="tabcontent">
    <div class="section">
        <h2>Run Custom SQL Query</h2>

        <label>Query:</label><br>
        <input type="text" id="sql_query" style="width: 70%;" placeholder="SELECT * FROM Property;"><br>

        <button class="button" onclick="runSQL()">Run Query</button>
    </div>
</div>

<!-- Results -->
<div id="results" class="section">
    <h2>Results</h2>
    <div id="output"></div>
</div>

<script>
    
function openTab(evt, tabName)
{
    $(".tabcontent").hide();
    $(".tab button").removeClass("active");
    $("#" + tabName).show();
    $(evt.currentTarget).addClass("active");
    $("#output").html("");
}

function renderTable(data) 
{
    if (!data || data.length === 0) 
    {
        return "<p>No results found.</p>";
    }

    let table = "<table><tr>";
    Object.keys(data[0]).forEach(key => 
    {
        table += "<th>" + key + "</th>";
    });
    table += "</tr>";

    data.forEach(row => 
    {
        table += "<tr>";
        Object.values(row).forEach(val => 
        {
            table += "<td>" + (val !== null ? val : "") + "</td>";
        });
        table += "</tr>";
    });

    table += "</table>";
    return table;
}

function loadListings() 
{
    $.get("database.php?action=load", function(response) 
    {
        let data = JSON.parse(response);
        $("#output").html(renderTable(data));
    });
}

function searchHouses() 
{
   const minPrice = parseFloat($("#h_minPrice").val()) || 0;
   const maxPrice = parseFloat($("#h_maxPrice").val()) || 99999999;
   const bedrooms = parseInt($("#h_bedrooms").val()) || 0;
   const bathrooms = parseInt($("#h_bathrooms").val()) || 0;

   $.get("database.php",
    {
        action: "search_houses",
        minPrice, maxPrice, bedrooms, bathrooms
    },
    function(response) 
    {
        let data = JSON.parse(response);
        $("#output").html(renderTable(data));
    });
}

function searchBusiness() 
{
    const minPrice = parseFloat($("#b_minPrice").val()) || 0;
    const maxPrice = parseFloat($("#b_maxPrice").val()) || 999999999;
    const minSize = parseFloat($("#b_minSize").val()) || 0;
    const maxSize = parseFloat($("#b_maxSize").val()) || 999999999;
    
    $.get("database.php",
    {
        action: "search_business",
        minPrice, maxPrice, minSize, maxSize
    },
    function(response) 
    {
        let data = JSON.parse(response);
        $("#output").html(renderTable(data));
    });
}

function loadAgents() 
{
    $.get("database.php?action=agents", function(response) 
    {
        let data = JSON.parse(response);
        $("#output").html(renderTable(data));
    });
}

function loadBuyers() 
{
    $.get("database.php?action=buyers", function(response) 
    {
        let data = JSON.parse(response);
        $("#output").html(renderTable(data));
    });
}

function runSQL() 
{
    let q = $("#sql_query").val();

    $.get("database.php", 
    {
        action: "sql",
        query: q
    }, 
    function(response) 
    {
        let data = JSON.parse(response);

        if (data.error) 
        {
            $("#output").html("<pre>SQL Error:\n" + data.error + "</pre>");
            return;
        }
        $("#output").html(renderTable(data));
    });
}
</script>

</body>
</html>
