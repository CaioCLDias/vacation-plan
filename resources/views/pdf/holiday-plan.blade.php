<!DOCTYPE html>
<html>
<head>
    <title>Holiday Plan PDF</title>
</head>
<body>
<h1>{{ $holidayPlan->title }}</h1>
<p><strong>Description:</strong> {{ $holidayPlan->description }}</p>
<p><strong>Date:</strong> {{ $holidayPlan->date }}</p>
<p><strong>Location:</strong> {{ $holidayPlan->location }}</p>
@if($holidayPlan->participants)
    <p><strong>Participants:</strong> {{ implode(', ', $holidayPlan->participants) }}</p>
@endif
</body>
</html>
