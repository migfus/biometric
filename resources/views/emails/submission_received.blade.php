<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Received</title>
</head>
<body>
    <p>Hello,</p>
    <p>We have received your submission for the check record. Here are the details we recorded:</p>
    <ul>
        <li><strong>Employee No:</strong> {{ $submission['employee_no'] }}</li>
        <li><strong>Full Name:</strong> {{ $submission['full_name'] }}</li>
        <li><strong>College:</strong> {{ $submission['college'] ?? 'N/A' }}</li>
        <li><strong>Office:</strong> {{ $submission['office'] }}</li>
        <li><strong>Action:</strong> {{ $submission['check'] }}</li>
        <li><strong>Work Description:</strong> {{ $submission['work_description'] }}</li>
        <li><strong>Rephrase Count:</strong> {{ $submission['rephrase_count'] }}</li>
    </ul>
    <p>If you did not submit this request, please contact support.</p>
    <p>Thank you.</p>
</body>
</html>
