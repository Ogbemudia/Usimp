// Function to handle file input change
$('#csvFileInput').on('change', function(event) {
    var file = event.target.files[0]; // Get the uploaded file
    var reader = new FileReader();

    reader.onload = function(event) {
        var csvData = event.target.result; // Get the CSV data

        // Convert CSV data to JSON
        var jsonData = csvJSON(csvData);

        // Display JSON data
        console.log(jsonData);
    };

    // Read the uploaded file as text
    reader.readAsText(file);
});

// Function to convert CSV data to JSON
function csvJSON(csvData) {
    var lines = csvData.split('\n');
    var result = [];
    var headers = lines[0].split(',');

    for (var i = 1; i < lines.length; i++) {
        var obj = {};
        var currentLine = lines[i].split(',');

        for (var j = 0; j < headers.length; j++) {
            obj[headers[j]] = currentLine[j];
        }
        result.push(obj);
    }

    return JSON.stringify(result); // Convert array of objects to JSON string
}