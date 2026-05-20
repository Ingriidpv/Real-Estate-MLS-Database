<?php
$host = "localhost";
$user = "root";
$password = "root";
$db = "real_estate_listings";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Table Rendering 

function renderTable($result)
{
    if (!$result || $result->num_rows == 0)
    {
        echo "<p>No results found.</p>";
        return;
    }

    echo "<table border='1' cellpadding='5' cellspacing='0'><tr>";

    // Header rom
    while ($field = $result->fetch_field()) 
    {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
    }
    echo "</tr>";

    // Data rows
    while ($row = $result->fetch_assoc()) 
    {
        echo "<tr>";
        foreach ($row as $val) 
        {
            echo "<td>" . htmlspecialchars($val) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}

// Handling Query
function getListings ($conn)
{
    $sql = "SELECT * FROM Listings";
    $result = $conn->query($sql);
    renderTable($result);
}

function searchHouses($conn)
{
    $minPrice = $_GET['minPrice'] ?? 0;
    $maxPrice = $_GET['maxPrice'] ?? 999999999;
    $bedrooms = $_GET['bedrooms'] ?? 0;
    $bathrooms = $_GET['bathrooms'] ?? 0;

    $sql = "SELECT P.address, P.price, H.bedrooms, H.bathrooms, H.size
        FROM House H
        JOIN Property P ON H.address = P.address
        WHERE price BETWEEN ? AND ?
          AND H.bedrooms >= ?
          AND H.bathrooms >= ?
        ORDER BY price DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $minPrice, $maxPrice, $bedrooms, $bathrooms);
    $stmt->execute();
    $result = $stmt->get_result();

    renderTable($result);
}

function searchBusiness($conn)
{
    $minPrice = $_GET['minPrice'] ?? 0;
    $maxPrice = $_GET['maxPrice'] ?? 999999999;
    $minSize = $_GET['minSize'] ?? 0;
    $maxSize = $_GET['maxSize'] ?? 999999999;

    $sql = "SELECT B.address, B.type, B.size, P.price
        FROM BusinessProperty B
        JOIN Property P ON B.address = P.address
        WHERE price BETWEEN ? AND ?
        AND size BETWEEN ? AND ?
        ORDER BY price DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $minPrice, $maxPrice, $minSize, $maxSize);
    $stmt->execute();
    $result = $stmt->get_result();

    renderTable($result);
}

function getAgents($conn)
{
    $sql = "SELECT * FROM Agent";
    $result = $conn->query($sql);
    renderTable($result);
}
function getBuyers($conn)
{
    $sql = "SELECT * FROM Buyer";
    $result = $conn->query($sql);
    renderTable($result);
}

function runUserQuery($conn)
{
    if (!isset($_POST['userQuery'])) {
        echo "<p>No SQL query provided.</p>";
        return;
    }

    $query = $_POST['userQuery'];

    // running raw query
    $result = $conn->query($query);

    if ($result == TRUE)
    {
        echo "<p>Query executed successfully.</p>";
        renderTable($result);
    }
    
    if ($result instanceof mysqli_result)
    {
        renderTable($result);
        return;
    }

    echo "<p style='color:red;'>Error executing query: " . $conn->error . "</p>";
}

// Router 
$type = $_GET['type'] ?? $_POST['type'] ?? '';

switch ($type) 
{
    case 'listings':
        getListings($conn);
        break;

    case 'search_houses':
        searchHouses($conn);
        break;

    case 'search_business':
        searchBusiness($conn);
        break;

    case 'agents':
        getAgents($conn);
        break;

    case 'buyers':
        getBuyers($conn);
        break;

    case 'user_query':
        runUserQuery($conn);
        break;

    default:
        echo "<p>Invalid request type.</p>";
        break;
}
$conn->close();
?>