<?php
include_once '../config/koneksi.php';
include_once '../config/fungsi_indotgl.php';
include "../config/library.php";

// Check if the request is an AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    $api = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM api WHERE id='1' LIMIT 1"));

    $apikey = $api['apikey'];
    $merchant = $api['merchant'];

    // Fetch data from your API endpoint
    $offset = 7260327; // Set your desired offset value
    $typeToFilter = 'K';
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://bukubank.com/api/v1/mutasi/recent/?offset=' . $offset,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Authorization: Bearer ' . $apikey
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if decoding was successful
    if ($data === null) {
        die('Error decoding JSON response');
    }

    // Check if the response is an array
    if (!is_array($data)) {
        die('Invalid JSON response format');
    }

    // Initialize total amount
    $totalAmount = 0;

    // Sort the data array by the 'date' field
    usort($data, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    // Filter data based on type
    $filteredData = array_filter($data, function ($entry) use ($typeToFilter) {
        return $entry['type'] === $typeToFilter;
    });

    // Filter data based on search query
    $searchQuery = $_POST['search']['value'];
    if (!empty($searchQuery)) {
        $filteredData = array_filter($filteredData, function ($entry) use ($searchQuery) {
            // Adjust this condition based on your actual data structure
            return stripos($entry['description'], $searchQuery) !== false;
        });
    }

    // Slice data based on start and length parameters
    $slicedData = array_slice($filteredData, $_POST['start'], $_POST['length']);

    // Format the data for DataTables
    $formattedData = array();
        $totalAmount = 0;
        
        foreach ($slicedData as $entry) {
            $formattedData[] = array(
                'date' => tgl_indo_jam($entry['date']),
                'type' => $entry['type'],
                'module' => $entry['module'],
                'accountnumber' => $entry['accountnumber'],
                'description' => $entry['description'],
                'amount' => buatRp($entry['amount'])
            );
        
            // Convert the formatted amount back to numeric before adding to the total
            $totalAmount += $entry['amount'];
        }
        
        // Format the totalAmount here if needed (e.g., adding "Rp" prefix)
        $formattedTotalAmount = buatRp($totalAmount);
        
        // Prepare the response for DataTables
        $responseArray = array(
            'draw' => $_POST['draw'],
            'recordsTotal' => count($filteredData),
            'recordsFiltered' => count($filteredData),
            'data' => $formattedData,
            'totalAmount' => $formattedTotalAmount // Additional data for the total amount
        );

// Output the JSON response



    // Output the JSON response
    echo json_encode($responseArray);
}
?>
