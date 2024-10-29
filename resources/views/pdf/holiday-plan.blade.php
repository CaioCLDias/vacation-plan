<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Holiday Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            margin-bottom: 10px;
        }
        .details {
            margin-bottom: 15px;
        }
        .participants {
            margin-top: 20px;
        }
        .participants li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<div class="header">
    <img src="{{ asset('path/to/logo.png') }}" alt="Company Logo">
    <h1>{{ $holidayPlan->title }}</h1>
</div>

<div class="details">
    <p><strong>Description:</strong> {{ $holidayPlan->description }}</p>
    <p><strong>Date:</strong> {{ $holidayPlan->date }}</p>
    <p><strong>Location:</strong> {{ $holidayPlan->location }}</p>
</div>

@if (!empty($holidayPlan->participants))
    <div class="participants">
        <h2>Participants</h2>
        <ul>
            @foreach($holidayPlan->participants as $participant)
                <li>{{ $participant }}</li>
            @endforeach
        </ul>
    </div>
@endif

</body>
</html>
